@extends('layouts.master')
@section('content')
@php
use App\Models\AddJob
@endphp
    <!-- Page Wrapper -->
    <div class="page-wrapper">
			
        <!-- Page Content -->
        <div class="content container-fluid">
            
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Offered Jobs</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item ">Jobs</li>
                            <li class="breadcrumb-item ">User Dashboard</li>
                            <li class="breadcrumb-item active">Offered Jobs</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Content Starts -->
            <div class="card">
                <div class="card-body">
                    @include('sidebar.sidebarjob')
                </div>
            </div>	
            
            <!-- Search Filter -->
            <form action="{{ route('user/dashboard/offer/search') }}" method="POST">
            
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
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Job Title</th>
                                    <th>Department</th>
                                    <th class="text-center">Job Type</th>
                                    <th>Start date</th>
                                    <th>Expire</th>
                                    <th>Status</th>
                                  
                                    
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($offered as $key => $items)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td><a href="{{ url('job/details/'.$items->id) }}">{{ $items->job_title }}</a></td>
                                        <td>{{ AddJob::where('job_title',$items->job_title)->get()[0]->department }}</td>
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> {{ AddJob::where('job_title',$items->job_title)->get()[0]->job_type}}
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ date('d F, Y',strtotime($items->start_date)) }}</td>
                                        <td>{{ date('d F, Y',strtotime($items->expired_date)) }}</td>
                                       
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> {{ $items->status }}
                                                </a>
                                            </div>
                                        </td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Content End -->
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection