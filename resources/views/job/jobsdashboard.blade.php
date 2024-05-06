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
                    <h3 class="page-title">Job Dashboard</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Jobs</li>
                        <li class="breadcrumb-item active">Job Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-briefcase"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{ count($job_title) }}</h3>
                            <span>Jobs</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{ count($applicants) }}</h3>
                            <span>Job Seekers</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{count($employee)}}</h3>
                            <span>Employees</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-clipboard"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{ count($applicants) }}</h3>
                            <span>Applications</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 text-center d-flex">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <h3 class="card-title">Overview</h3>
                                <!-- <canvas id="lineChart"></canvas> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <h3 class="card-title text-center">Latest Jobs</h3>
                                <ul class="list-group">
                                    @foreach ($job as $job)
                                    <li class="list-group-item list-group-item-action">{{ $job->job_title }} <span
                                            class="float-right text-sm text-muted">{{ $job->created_at->diffForHumans()
                                            }}</span></li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-table">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Applicants List</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-nowrap custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Job Title</th>
                                        <th>Departments</th>
                                        <th>Start Date</th>
                                        <th>Expire Date</th>
                                        <th class="text-center">Job Types</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($applicants as $key => $applicant)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="{{ url('resume/details/'.$applicant->id) }}" class="avatar"><img alt=""
                                                        src="assets/img/profiles/avatar-{{ $applicant->id }}.jpg"></a>
                                                <a href="{{ url('resume/details/'.$applicant->id) }}">{{ $applicant->name }}</a>
                                            </h2>
                                        </td>
                                        <td>{{ $applicant->phone }}</td>
                                        <td>{{ $applicant->email }}</td>
                                        <td>{{ $applicant->status }}</td>

                                        <td>{{ date('d M Y', strtotime($applicant->created_at)) }}</td>
                                        <td>{{ $applicant->offer_status }}</td>
                                        <td>{{ $applicant->status_selection }}</td>




                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No applicants found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-table">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Shortlist Candidates</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-nowrap custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Job Title</th>
                                        <th>Departments</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($department as $key => $applicant)
                                    <tr>
                                        
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="{{ url('resume/details/'.$applicant->id) }}" class="avatar"><img alt=""
                                                        src="assets/img/profiles/avatar-{{ $applicant->id }}.jpg"></a>
                                                <a href="{{ url('resume/details/'.$applicant->id) }}">{{ $applicant->name }}</a>
                                            </h2>
                                        </td>
                                        <td><a href="{{ url('job/details/'.$applicant->job_id) }}">{{ $applicant->job_title }}</a></td>
                                        <td>{{ $applicant->email }}</td>
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="#">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> {{
                                                    $applicant->status_selection }}
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
            </div>
        </div>

        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    @endsection