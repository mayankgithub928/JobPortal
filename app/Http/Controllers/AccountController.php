<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    // This function shows registraion page
    public function registration(){
        return view("front.account.registration");
    }
    public function processRegistration(Request $request){
        $validator=Validator::make($request->all(),[
            "name"=>"required",            
            "email"=> "required|email|unique:users,email",
            "password"=> "required|min:5|same:confirm_password",
            "confirm_password"=> "required",
            ]);

            if($validator->passes()){
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->save();
                session()->flash("success","You have registered successfully");
                return response()->json([
                    'status'=>true, 
                    'errors'=>[]
                  ]);

            }else{
                return response()->json([
                  'status'=>false, 
                  'errors'=>$validator->errors()
                ]);
            }



    }
    public function login(){
        return view("front.account.login");
    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            "email"=> "required|email",
            "password"=> "required",
        ]);

        if($validator->passes()){
            if(Auth::attempt(["email"=>$request->email,"password"=>$request->password])){
                return redirect()->route("account.profile");
            }else{
                return redirect()->route("account.login")->with("error","Invalid Credentials");
            }
        }else{
           return redirect()->route("account.login")
           ->withErrors($validator)
           ->withInput()->onlyInput("email");
        }

    }
    function profile(){
        $id=Auth::User()->id;
        $user= User::find($id);
       
        return view("front.account.profile",['user'=>$user]);
    }
    public function updateProfile(Request $request){
        $id= Auth::user()->id;
        $validator= Validator::make($request->all(),[
            'name'=>'required|min:5|max:15',
            'email'=>'required|email|unique:users,email,'.$id.',id'
        ]);

        if($validator->passes()){
            $user = User::find($id);
            $user->name=$request->name;
            $user->email=$request->email;
            $user->designation=$request->designation;
            $user->mobile=$request->mobile;
            $user->save();

            session()->flash('success','user updated successfully');
            return response()->json([
                'status'=>true,
                'errors'=>[]
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }

    }
    public function updateProfilePic(Request $request){
        //dd($request->all());
        $id = Auth::user()->id;
        $validator=Validator::make($request->all(),
        [
            'profile_pic'=>'required|image'
        ]
        );
        if($validator->passes()){
            $profile_pic= $request->profile_pic;
            $ext=$profile_pic->getClientOriginalExtension();
            $image_name=$id.'-'.time().'.'.$ext;
            $profile_pic->move(public_path('/profile_pic/'),$image_name);

            // create image manager with desired driver
            $manager = new ImageManager(new Driver());

            // read image from file system
            $image = $manager->read(public_path('/profile_pic/').$image_name);
           // $image->cover(15,150);
            $image->scale(width: 300);
            $image->toPng()->save(public_path('/profile_pic/thumb/').$image_name);
            File::delete(public_path('/profile_pic/thumb/'.Auth::user()->image));
            File::delete(public_path('/profile_pic/'.Auth::user()->image));


            User::where('id',$id)->updajob_type_idte(['image'=>$image_name]);
            session()->flash('success','Profile pic updated');

            return response()->json([
                    'status'=>true,
                    'errors'=>[]
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
        ]);
        }

    }
    function createJob(){
        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes=JobType::orderBy('name','ASC')->where('status',1)->get();
        return view('front.account.job.create',
                    [
                        'categories'=>$categories,
                        'jobTypes'=>$jobTypes,

                    ]
                    
                    );
    }
    function saveJob(Request $request){
        $rules=array(
            'title'=>'required',
            'category'=>'required',
            'jobType'=>'required',
            'vacancy'=>'required',
            'location'=>'required',
            'description'=>'required',
            'company_name'=>'required',
        );
        $validator=Validator::make($request->all(), $rules);
        if($validator->passes()){
          
            $job= new Job();
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id=$request->jobType;
            $job->user_id=Auth::user()->id;
            $job->salary =$request->salary;
            $job->vacancy =$request->vacancy;
            $job->location=$request->location;
            $job->description=$request->description;
            $job->benefits=$request->benefits;
            $job->responsibility=$request->responsibility;
            $job->qualifications=$request->qualifications;
            $job->keywords=$request->keywords;
            $job->experience=$request->experience;
            $job->company_name=$request->company_name;
            $job->company_location=$request->company_location;
            $job->company_website=$request->company_website;
            $job->save();

            session()->flash('success','Job created susccesfully');
            return response()->json(
                [
                    'status'=>true,
                    'errors'=>[]
                ]

            );


        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    function myJobs(){

        $jobs = Job::where('user_id',Auth::user()->id)->with('jobType')->orderBy('created_at','desc')->paginate(10);
        //dd($jobs);

        return view('front.account.job.my-jobs',['jobs'=>$jobs]);
    }
    function editJob(Request $request, $id){
        //echo Auth::user()->id; die();
      
        $job = Job::where(['id'=>$id,
        'user_id'=>Auth::user()->id
        ])->first();
        if($job == null){
            abort(404);
        }
        //dd($job);
        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes=JobType::orderBy('name','ASC')->where('status',1)->get();

        return view('front.account.job.edit',[
                'categories'=>$categories,
                'jobTypes'=>$jobTypes,
                'job' =>$job
        ]);
    }
    function updateJob(Request $request,$id){
        
        $rules=array(
            'title'=>'required',
            'category'=>'required',
            'jobType'=>'required',
            'vacancy'=>'required',
            'location'=>'required',
            'description'=>'required',
            'company_name'=>'required',
        );
        $validator=Validator::make($request->all(), $rules);
        if($validator->passes()){
            $job= Job::find($id);
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id=$request->jobType;
            $job->user_id=Auth::user()->id;
            $job->salary =$request->salary;
            $job->vacancy =$request->vacancy;
            $job->location=$request->location;
            $job->description=$request->description;
            $job->benefits=$request->benefits;
            $job->responsibility=$request->responsibility;
            $job->qualifications=$request->qualifications;
            $job->keywords=$request->keywords;
            $job->experience=$request->experience;
            $job->company_name=$request->company_name;
            $job->company_location=$request->company_location;
            $job->company_website=$request->company_website;
            $job->save();

            session()->flash('success','Job updated susccesfully');
            return response()->json(
                [
                    'status'=>true,
                    'errors'=>[]
                ]

            );


        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    function deleteJob(Request $request){
     
        $job= Job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$request->jobId
        ])->first();
        if($job == null){
            session()->flash('success','Job not found');
            return response()->json(
                [
                    'status'=>false,
                    'errors'=>[]
                ]

            );
        }
      
        Job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$request->jobId
        ])->delete();

        session()->flash('success','Job deleted successfully');
            return response()->json(
                [
                    'status'=>true,
                    'errors'=>[],
                    'message'=>'deleted'
                ]

            );
       

    }
    function logout(){
        Auth::logout();
        return redirect()->route("account.login");
    }
    function demoJob(){
       return 'test';
    }
}
