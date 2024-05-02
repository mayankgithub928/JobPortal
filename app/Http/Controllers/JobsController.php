<?php

namespace App\Http\Controllers;

use App\Mail\JobNotification;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Exception;

class JobsController extends Controller
{
    public function index(Request $request){
        $categories = Category::where('status',1)->orderBy('created_at','desc')->get();
        $jobTypes = JobType::where('status',1)->orderBy('created_at','desc')->get();
        $jobs = Job::where('status',1);


        //dd($request); die();
        // Search using keyword
        if (!empty($request->keywords)) {
            $jobs = $jobs->where(function($query) use ($request) {
              
                $query->orWhere('title','like','%'.$request->keywords.'%');
                $query->orWhere('keywords','like','%'.$request->keywords.'%');
            });
        }
        if (!empty($request->category)) {
           $jobs->where('category_id',$request->category);
        }
        if (!empty($request->location)) {
            $jobs->where('location',$request->location);
        }
        $jobTypeArr=[];
        if (!empty($request->job_type)) {
            $jobTypeArr=explode(',',$request->job_type);
            $jobs->whereIn('job_type_id',$jobTypeArr);
        }
        if (!empty($request->experience)) {
            $jobs->where('experience',$request->experience);
        }
        $jobs->with(['jobType','category']);
        if($request->sort=='0'){
            $jobs->orderBy('created_at','asc');
        }else{
            $jobs->orderBy('created_at','desc');
        }
      
        $jobs = $jobs->paginate(5);

        

        $viewsVars=array('categories'=>$categories,'jobTypes'=>$jobTypes,'jobs'=>$jobs,'jobTypeArr'=> $jobTypeArr);
        return view('front.jobs',$viewsVars);
    }
    function jobDetail($id){
        $job= Job::with(['jobType','category'])->find($id);
        if($job == null){
            abort(404);
        }
        return view('front.job-detail',['job'=>$job]);
    }
    public function applyJob(Request $request){
        $jobId=$request->id;
        $job=Job::where(['id'=>$jobId])->first();
        $message='Job Does\'t exist';
        if($job==null){
            session()->flash('error',$message);
            return response()->json(
                [
                    'status'=>false,
                    'message'=>$message
                ]
            );
        }
        $employer_id=$job->user_id;
        if($employer_id == Auth::user()->id){
            $message='You can\'t apply on your own job';
            session()->flash('error',$message);
            return response()->json(
                [
                    'status'=>false,
                    'message'=>$message
                ]
            );
        }
        $jobApplicationCount = JobApplication::where([
            'user_id'=>Auth::user()->id,
            'job_id' => $jobId
        ])->count();
        if($jobApplicationCount>0){
            $message='You can\'t apply on a job more than one';
            session()->flash('error',$message);
            return response()->json(
                [
                    'status'=>false,
                    'message'=> $message
                ]
            );
        }
        $jobApplication = new JobApplication();
        $jobApplication->job_id = $request->id;
        $jobApplication->user_id = Auth::user()->id;
        $jobApplication->employer_id = $job->user_id;
        $jobApplication->applied_date =now();
        $jobApplication->save();

        $employer= User::where(['id'=>$employer_id])->first();
        $mailData= [
            'employer'=>$employer,
            'user'=>Auth::user(),
            'job'=>$job
        ];
        //dd($employer->email);
        try{
            Mail::to($employer->email)->send(new JobNotification($mailData));
        }catch(Exception $e){
            dd($e);
        }
       
  
        $message='You applied successfully';
        session()->flash('success',$message);
        return response()->json(
            [
                'status'=>true,
                'message'=>$message
            ]
        );

        
       
        

    }
}
