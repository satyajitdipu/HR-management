@extends('layouts.master')
@section('content')
	<!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">All Jobs</h3>
                        <ul class="breadcrumb">
                            
                            <li class="breadcrumb-item ">Jobs</li>
                          
                            <li class="breadcrumb-item active">All Jobs</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

           
            <!-- Search Filter -->
            <form action="{{ route('user/dashboard/all/search') }}" method="POST">
            
    @csrf
    <div class="row filter-row">
        <div class="col-sm-6 col-md-3">  
            <div class="form-group form-focus select-focus">
                <select name="department" class="select floating"> 
                    <option value="">Select</option>
                    @foreach($departments as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
                <label class="focus-label">Department</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">  
            <div class="form-group form-focus select-focus">
                <select name="job_type" class="select floating"> 
                    <option value="">Select</option>
                    @foreach($jobTypes as $jobType)
                        <option value="{{ $jobType }}">{{ $jobType }}</option>
                    @endforeach
                </select>
                <label class="focus-label">Job Type</label>
            </div>
        </div>
       
        <div class="col-sm-6 col-md-3">  
            <button type="submit" class="btn btn-success btn-block"> Search </button>  
        </div>
    </div>
</form>


            <!-- Search Filter -->

            <div class="row">
                    @foreach ($job_list as $list)
                    @php
                        $date = $list->created_at;
                        $date = Carbon\Carbon::parse($date);
                        $elapsed =  $date->diffForHumans();
                    @endphp
                    <div class="col-md-6">
                        <a class="job-list" href="{{ url('form/job/view/'.$list->id) }}">
                            <div class="job-list-det">
                                <div class="job-list-desc">
                                    <h3 class="job-list-title">{{ $list->job_title }}</h3>
                                    <h4 class="job-department">{{ $list->department }}</h4>
                                </div>
                                <div class="job-type-info">
                                    <span class="job-types">{{ $list->job_type }}</span>
                                </div>
                            </div>
                            <div class="job-list-footer">
                                <ul>
                                    <li><i class="fa fa-map-signs"></i>{{ $list->job_location }}</li>
                                    <li><i class="fa fa-money"></i>{{ $list->salary_from }}-{{ $list->salary_to }}</li>
                                    <li><i class="fa fa-clock-o"></i>{{ $elapsed }}</li>
                                </ul>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
            <!-- /Content End -->
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection