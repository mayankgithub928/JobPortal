@extends('front.layout.app')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
             @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
               @include('front.message')
                <form name="editJobForm" id="editJobForm" method="post">
                    <div class="card-body card-form p-4">
                        <h3 class="fs-4 mb-1">Job Details</h3>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="" class="mb-2">Title<span class="req">*</span></label>
                                <input type="text" placeholder="Job Title" id="title" name="title" value="{{  $job->title }}" class="form-control">
                                <p></p>
                            </div>
                            <div class="col-md-6  mb-4">
                                <label for="" class="mb-2">Category<span class="req">*</span></label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @if($categories->isNotEmpty())
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $job->category_id == $category->id ?'selected':'' }}>{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p></p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="" class="mb-2">Job Nature<span class="req">*</span></label>
                                <select id="jobType" name="jobType" class="form-select">
                                <option value="">Select Job Type</option>
                                     @if($jobTypes->isNotEmpty())
                                        @foreach($jobTypes as $jobType)
                                        <option value="{{ $jobType->id }}" {{ $job->job_type_id == $jobType->id ?'selected':'' }}>{{ $jobType->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p></p>
                            </div>
                            <div class="col-md-6  mb-4">
                                <label for="" class="mb-2">Vacancy <span class="req">*</span></label>
                                <input type="number" min="1" placeholder="Vacancy" id="vacancy" name="vacancy"  value="{{  $job->vacancy }}" class="form-control">
                                <p></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="" class="mb-2">Salary</label>
                                <input type="text" placeholder="Salary" id="salary" name="salary"  value="{{  $job->salary }}" class="form-control">
                            </div>

                            <div class="mb-4 col-md-6">
                                <label for="" class="mb-2">Location<span class="req">*</span></label>
                                <input type="text" placeholder="location" id="salary" name="location"  value="{{  $job->location }}" class="form-control">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="" class="mb-2">Description<span class="req">*</span></label>
                            <textarea class="form-control" name="description" id="description" cols="5" rows="5" placeholder="Description">{{  $job->description }}</textarea>
                            <p></p>
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">Benefits</label>
                            <textarea class="form-control" name="benefits" id="benefits" cols="5" rows="5"  placeholder="Benefits">{{  $job->benefits }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">Responsibility</label>
                            <textarea class="form-control" name="responsibility" id="responsibility" cols="5" rows="5"  placeholder="Responsibility">{{  $job->responsibility }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="" class="mb-2">Qualifications</label>
                            <textarea class="form-control" name="qualifications" id="qualifications" cols="5" rows="5"  placeholder="Qualifications">{{  $job->qualifications }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="" class="mb-2">Experince<span class="req">*</span></label>
                            <select name="experience" id="experience">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="10_plus">10+</option>
                            </select>
                            <p></p>
                        </div>
                        
                        

                        <div class="mb-4">
                            <label for="" class="mb-2">Keywords</label>
                            <input type="text" placeholder="keywords" id="keywords" name="keywords"  value="{{  $job->keywords }}" class="form-control">
                        </div>

                        <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="" class="mb-2">Name<span class="req">*</span></label>
                                <input type="text" placeholder="Company Name" id="company_name" name="company_name"  value="{{  $job->company_name }}" class="form-control">
                                <p></p>
                            </div>

                            <div class="mb-4 col-md-6">
                                <label for="" class="mb-2">Location</label>
                                <input type="text" placeholder="Location" id="company_location" name="company_location"  value="{{  $job->company_location }}" class="form-control">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="" class="mb-2">Website</label>
                            <input type="text" placeholder="Website" id="company_website" name="company_website"  value="{{  $job->company_website }}" class="form-control">
                        </div>
                        <div class="card-footer  p-4">
                        <button type="submit" class="btn btn-primary">Update Job</button>
                    </div> 
                    </div> 
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
    <script>
        $('#editJobForm').submit(function(e){
            e.preventDefault();
          
            $.ajax({
                url:'{{ route('account.updateJob' , $job->id)}}',
                type:'post',
                dataType:'json',
                data:$('#editJobForm').serializeArray(),
                success:function(response){
                    if(response.status){
                        $('#title').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $('#catgory').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $('#company_name').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $('#description').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $('#jobType').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        $('#vacancy').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');

                            window.location.href="{{route('account.myJobs')}}"; 

                    }else{
                        var errors= response.errors;
                        if(errors.title){
                            $('#title').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.title);
                        }else{
                            $('#title').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        }
                        if(errors.catgory){
                            $('#catgory').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.catgory);
                        }else{
                            $('#catgory').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        }
                        if(errors.company_name){
                            $('#company_name').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.company_name);
                        }else{
                            $('#company_name').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        }
                        if(errors.description){
                            $('#description').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.description);
                        }else{
                            $('#description').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        }
                        if(errors.jobType){
                            $('#jobType').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.jobType);
                        }else{
                            $('#jobType').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        }
                        if(errors.vacancy){
                            $('#vacancy').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors.vacancy);
                        }else{
                            $('#vacancy').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('');
                        }
                       
                    }

                }
                
            });

        });
    
    </script>

@endsection
