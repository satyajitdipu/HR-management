@extends('layouts.master')
@section('content')
<!-- Page Wrapper -->
{!! Toastr::message() !!}
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="page-title">Schedule timing</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">Jobs</li>
                        <li class="breadcrumb-item active">Schedule timing</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Job Title</th>
                                <th>User Available Timings</th>
                                <th class="text-center">Schedule timing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($department as $key => $applicant)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="profile.html" class="avatar"><img alt=""
                                                src="assets/img/profiles/avatar-{{ $applicant->id }}.jpg"></a>
                                        <a href="{{ url('resume/details/'.$applicant->id) }}">{{ $applicant->name }} <span>{{ $applicant->job_title
                                                }}</span></a>
                                    </h2>
                                </td>
                                <td><a href="{{ url('job/details/'.$applicant->job_id) }}">{{ $applicant->job_title }}</a></td>
                                <td>
                                    @if($applicant->interview == null)
                                    {{ $applicant->interview }}
                                    @else
                                    @foreach(json_decode($applicant->interview) as $timing)
                                    <b>{{ $timing->date }}</b> - {{ $timing->time }}<br>
                                    @endforeach
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="action-label">
                                    <a class="btn btn-primary btn-sm edit-schedule-time" data-toggle="modal" data-target="#edit_job"
   data-applicant-id="{{ $applicant->id }}" href="#">
   Schedule Time
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


        <!-- Edit Job Modal -->
        <div id="edit_job" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form action="{{ route('update_schedule_timing') }}" method="POST">
    @csrf
    <input type="hidden" name="id" id="applicant_id" value="">
    <div class="row">
        <!-- Schedule Date 1 -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="schedule_date_1">Schedule Date 1</label>
                <input type="date" class="form-control" id="schedule_date_1" name="schedule_date_1" value="{{ $applicant->schedule_dates[0]->date ?? '' }}">
            </div>
            <div class="form-group">
                <label for="schedule_time_1">Select Time</label>
                <select class="form-control" id="schedule_time_1" name="schedule_time_1">
                    <option>Select Time</option>
                    <option selected>12:00 AM-01:00 AM</option>
                    <option>01:00 AM-02:00 AM</option>
                    <option>02:00 AM-03:00 AM</option>
                    <option>03:00 AM-04:00 AM</option>
                    <option>04:00 AM-05:00 AM</option>
                    <option>05:00 AM-06:00 AM</option>
                </select>
            </div>
        </div>
        <!-- Schedule Date 2 -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="schedule_date_2">Schedule Date 2</label>
                <input type="date" class="form-control" id="schedule_date_2" name="schedule_date_2" value="{{ $applicant->schedule_dates[1]->date ?? '' }}">
            </div>
            <div class="form-group">
                <label for="schedule_time_2">Select Time</label>
                <select class="form-control" id="schedule_time_2" name="schedule_time_2">
                    <option>Select Time</option>
                    <option selected>12:00 AM-01:00 AM</option>
                    <option>01:00 AM-02:00 AM</option>
                    <option>02:00 AM-03:00 AM</option>
                    <option>03:00 AM-04:00 AM</option>
                    <option>04:00 AM-05:00 AM</option>
                    <option>05:00 AM-06:00 AM</option>
                </select>
            </div>
        </div>
        <!-- Schedule Date 3 -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="schedule_date_3">Schedule Date 3</label>
                <input type="date" class="form-control" id="schedule_date_3" name="schedule_date_3" value="{{ $applicant->schedule_dates[2]->date ?? '' }}">
            </div>
            <div class="form-group">
                <label for="schedule_time_3">Select Time</label>
                <select class="form-control" id="schedule_time_3" name="schedule_time_3">
                    <option>Select Time</option>
                    <option selected>12:00 AM-01:00 AM</option>
                    <option>01:00 AM-02:00 AM</option>
                    <option>02:00 AM-03:00 AM</option>
                    <option>03:00 AM-04:00 AM</option>
                    <option>04:00 AM-05:00 AM</option>
                    <option>05:00 AM-06:00 AM</option>
                </select>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Update Schedule Timing</button>
</form>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Job Modal -->

        <!-- Delete Job Modal -->
        <div class="modal custom-modal fade" id="delete_job" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal"
                                        class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Job Modal -->

    </div>
    <script>
    $('.edit-schedule-time').on('click', function () {
        var applicantId = $(this).data('applicant-id');
        $('#applicant_id').val(applicantId);
    });
</script>
    <!-- /Page Wrapper -->
    @endsection