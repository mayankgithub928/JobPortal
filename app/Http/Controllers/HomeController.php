<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    
    public function index(){

        $categories = Category::where('status',1)->orderBy('created_at','desc')->take(5)->get();

        $featuredJobs = Job::where(['is_featured'=>1])->with('jobType')->orderBy('created_at','desc')->take(10)->get();
        $latestJobs = Job::with('jobType')->orderBy('created_at','desc')->take(10)->get();

        $viewVars=array('categories'=>$categories,'featuredJobs'=>$featuredJobs,'latestJobs'=>$latestJobs);

        return view("front.home",$viewVars);

    }
    public function contact(){
        return view("front.contact");

    }
}
