@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        @php
        use App\Models\AddJob;
        if(App\Models\Resultofmcq::where('user_id',Auth::user()->id)->exists()){
        $button=false;
        }
        else{
            $button=true;
        }
        @endphp
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Interviewing</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item">Jobs</li>
                            <li class="breadcrumb-item">User Dashboard</li>
                            <li class="breadcrumb-item active">Interviewing</li>
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

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-solid nav-justified flex-column">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">Apptitude</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu1">Schedule Interview</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div id="home" class="tab-pane show active">
                                    <div class="card-box">
                                    <div class="table-responsive">
                                        <table class="table table-striped custom-table mb-0 datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Job Title</th>
                                                    <th>Department</th>
                                                    <th class="text-center">Job Type</th>
                                                    <th class="text-center">Aptitude Test</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            @if ($job_list->isEmpty()==true)
    <tbody>
        <tr>
            <td colspan="6">No jobs found</td>
        </tr>
    </tbody>
    @else
    @foreach($job_list as $job)
    <tr>
        <td>{{ $job->id }}</td>
        <td><a href="#">{{ $job->job_title }}</a></td>
        <td>{{ AddJob::where('job_title',$job->job_title)->get()[0]->department }}</td>
        <td class="text-center">
            <div class="action-label">
                <a class="btn btn-white btn-sm btn-rounded" href="#">
                <i class="fa fa-dot-circle-o {{ $job->type === 'Full Time' ? 'text-danger' : 'text-warning' }}"></i> {{ AddJob::where('job_title',$job->job_title)->get()[0]->job_type }}
                </a>
            </div>
        </td>
        <td class="text-center">
    @if ($button)
        <a href="{{ 'test/' . Auth::user()->id }}" class="btn btn-primary aptitude-btn">
            <span>Click Here</span>
        </a>
    @else
        <button class="btn btn-primary aptitude-btn" >
            <a href="{{ 'result' }}">
            <span>check result</span>
            </a>
            
        </button>
    @endif
</td>
    </tr>
    @endforeach
</tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                    <div class="card-box">
                                    <div class="table-responsive">
                                        <table class="table table-striped custom-table mb-0 datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Job Title</th>
                                                    <th>Department</th>
                                                    <th class="text-center">Job Type</th>
                                                    <th class="text-center">Aptitude Test</th>
                                                    <th class="text-center">Schedule Interview</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($appitude as $job)
    @php
        $jobType = AddJob::where('job_title', $job->job_title)->first();
        $interviews = json_decode($job->interview, true);
    @endphp
    <tr>
        <td>{{ $job->id }}</td>
        <td><a href="#">{{ $job->job_title }}</a></td>
        <td>{{ $jobType->department }}</td>
        <td class="text-center">
            <div class="action-label">
                <a class="btn btn-white btn-sm btn-rounded" href="#">
                    <i class="fa fa-dot-circle-o {{ $jobType->job_type === 'Full Time' ? 'text-danger' : 'text-warning' }}"></i> {{ $job->type }}
                </a>
            </div>
        </td>
        <td class="text-center">
    @php
        $interviews = json_decode($job->interview, true);
    @endphp
    @if (empty($interviews) or $count==1)
        <a href="javascript:void(0);" class="btn btn-primary aptitude-btn" >
            <span>Selected</span>
        </a>
    @else
        <a href="javascript:void(0);" class="btn btn-primary aptitude-btn" data-toggle="modal" data-target="#selectMyTime">
            <span>Select Your Time Here</span>
        </a>
    @endif
</td>
<td class="text-center">
@php
    $interviews = json_decode($job->interview, true);
@endphp

@if (!empty($interviews) || $count == 1)
    {{ \Carbon\Carbon::parse($interviews[0]['date'])->format('d M Y') }}
@else
    Show date a
@endif

</td>
    </tr>
@endforeach
@endif
</tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Content End -->
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    </div>
    <!-- /Main Wrapper -->
        
    <!-- Modal -->
    @if (!empty($interviews))
<div id="selectMyTime" class="modal  custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Your Time</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('submit-time', ['id' => optional($job)->id]) }}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Day1 <span class="text-danger">*</span></label>
                                <select class="form-control" name="time" id="time">
                                    @foreach ($interviews as $interview)
                                    <option value="{{ $interview['time'] }}">{{ $interview['time'] }} ({{ \Carbon\Carbon::parse($interview['date'])->format('d M Y') }})</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="date" value="{{ isset($interviews[0]['date']) ? \Carbon\Carbon::parse($interviews[0]['date'])->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-btn delete-action mt-3">
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary btn-block cancel-btn">Submit</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-primary btn-block cancel-btn" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

        </div>
    </div>
</div>

@endsection