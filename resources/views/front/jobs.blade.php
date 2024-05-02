@extends('front.layout.app')
@section('main')
<section class="section-3 py-5 bg-2 ">
    <div class="container">     
        <div class="row">
            <div class="col-6 col-md-10 ">
                <h2>Find Jobs</h2>  
            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    <select name="sort" id="sort" class="form-control">
                        <option value="1" {{ Request::get('sort')=='1'?'selected':'' }}>Latest</option>
                        <option value="0" {{ Request::get('sort')=='0'?'selected':'' }}>Oldest</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-4 col-lg-3 sidebar mb-4">
                <form name="jobSearch" id="jobSearch">
                    <div class="card border-0 shadow p-4">
                        <div class="mb-4">
                            <h2>Keywords</h2>
                            <input type="text" placeholder="keywords" class="form-control" name="keywords" id="keywords" value="{{ request()->keywords }}">
                        </div>

                        <div class="mb-4">
                            <h2>Location</h2>
                            <input type="text" placeholder="Location" class="form-control" name="location" id="location" value="{{ request()->location }}">
                        </div>

                        <div class="mb-4">
                            <h2>Category</h2>
                            <select name="category" id="category" class="form-control">
                                <option value="">Select a Category</option>
                                @if($categories->isNotEmpty())
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}"  {{ $category->id == Request::get('category') ? 'selected':'' }} >{{ $category->name }} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>                   

                        <div class="mb-4">
                            <h2>Job Type</h2>
                            
                            @if($jobTypes->isNotEmpty())
                                @foreach($jobTypes as $jobType)
                                    <div class="form-check mb-2"> 
                                        <input class="form-check-input " name="job_type" type="checkbox" value="{{ $jobType->id }}" id="job_type_{{ $jobType->id }}"
                                        {{ in_array($jobType->id, $jobTypeArr)? 'checked':'' }}>    
                                        <label class="form-check-label " for="job_type_{{ $jobType->id }}">{{ $jobType->name }}</label>
                                    </div>
                                @endforeach
                                    
                            @endif

                            
                        </div>

                        <div class="mb-4">
                            <h2>Experience</h2>
                            <select name="experience" id="experience" class="form-control">
                                <option value="">Select Experience</option>
                                <option value="1" {{ Request::get('experience')== 1? 'selected':'' }} >1 Year</option>
                                <option value="2" {{ Request::get('experience')== 2? 'selected':'' }}>2 Years</option>
                                <option value="3" {{ Request::get('experience')== 3? 'selected':'' }}>3 Years</option>
                                <option value="4" {{ Request::get('experience')== 4? 'selected':'' }}>4 Years</option>
                                <option value="5" {{ Request::get('experience')== 5? 'selected':'' }}>5 Years</option>
                                <option value="6" {{ Request::get('experience')== 6? 'selected':'' }}>6 Years</option>
                                <option value="7" {{ Request::get('experience')== 7? 'selected':'' }}>7 Years</option>
                                <option value="8" {{ Request::get('experience')== 8? 'selected':'' }}>8 Years</option>
                                <option value="9" {{ Request::get('experience')== 9? 'selected':'' }}>9 Years</option>
                                <option value="10" {{ Request::get('experience')== 10? 'selected':'' }}>10 Years</option>
                                <option value="10_plus" {{ Request::get('experience')== '10_plus'? 'selected':'' }}>10+ Years</option>
                            </select>
                        </div>   
                        <button type="submit" name="submit">Search</button>
                       <a href="{{ route('jobs') }}" class="btn bt-secondary mt-3">Reset</a>                          
                    </div>
                   
                </form>
            </div>
            <div class="col-md-8 col-lg-9 ">
                <div class="job_listing_area">                    
                    <div class="job_lists">
                    <div class="row">
                        @if($jobs->isNotEmpty())
                            @foreach($jobs as $job)
                            <div class="col-md-4">
                                <div class="card border-0 p-3 shadow mb-4">
                                    <div class="card-body">
                                        <h3 class="border-0 fs-5 pb-2 mb-0">{{ $job->title }}</h3>
                                        <p>{{ Str::words($job->description,10) }}</p>
                                        <p>Keywords : {{ $job->keywords }}</p>
                                        <p>Location: {{ $job->location }}</p>
                                        <p>Category: {{ $job->category->name }}</p>
                                        <p>JobType: {{ $job->jobType->name }} - {{ $job->job_type_id }}<br>
                                        Exp: {{ $job->experience }}
                                        </p>
                                        
                                        <div class="bg-light p-3 border">
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                <span class="ps-1">{{ $job->location }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                <span class="ps-1">{{ $job->jobType->name }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                <span class="ps-1">{{ $job->salary }}</span>
                                            </p>
                                        </div>

                                        <div class="d-grid mt-3">
                                            <a href="{{ route('jobDetail',$job->id) }}" class="btn btn-primary btn-lg">Details</a>
                                        </div>
                                     
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div>  {{ $jobs->links() }} </div>
                        @endif
                        
                                           
                    </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $('#jobSearch').submit(function(e){
        e.preventDefault();
        let url = "{{ route('jobs') }}?";
        
        let keywords = $('#keywords').val();
        let category = $('#category').val();
        let job_type = $('#job_type').val();
        let location = $('#location').val();
        let experience = $('#experience').val();
      
        if(keywords!=''){
            url += '&keywords='+keywords;
        }
        if(category!=''){
            url += '&category='+category;
        }
        
        if(location!=''){
            url += '&location='+location;
        }
        if(experience!=''){
            url += '&experience='+experience;
        }

        let jobTypesArr= $("input:checkbox[name='job_type']:checked").map(function(){
                return $(this).val();
        }).get();
        if(jobTypesArr.length){
            url += '&job_type='+jobTypesArr;
        }
        let sort = $('#sort').val();
        if(sort!=''){
            url +='&sort='+sort;
        }
        //console.log(jobTypesArr);
      

        window.location.href=url;
    });

    $('#sort').change(function(){
        $('#jobSearch').submit();
    });
</script>
   
@endsection
