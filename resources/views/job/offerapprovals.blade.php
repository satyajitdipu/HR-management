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
                    <h3 class="page-title">Offer Approvals</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">Jobs</li>
                        <li class="breadcrumb-item active">Offer Approvals</li>
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
                                <th>Job Type</th>
                                <th>Pay</th>
                                <th>Annual IP</th>
                                <th>Long Term IP</th>
                                <th>Status</th>
                                <th class="text-center">Approve or Reject</th>
                            </tr>
                        </thead>
                        <tbody>
    @foreach($department as $key => $job)
    <tr>
        <td>{{ ++$key }}</td>
        <td>
            <h2 class="table-avatar">
                <a href="profile.html" class="avatar"><img alt="" src="assets/img/profiles/avatar-{{ $job->id }}.jpg"></a>
                <a href="{{ url('resume/details/'.$job->id) }}">{{ $job->name }} <span>{{ $job->job_title }}</span></a>
            </h2>
        </td>
        <td><a href="{{ url('job/details/'.$job->id) }}">{{ $job->job_title }}</a></td>
        <td>{{ $job->job_type }}</td>
        <td>{{ isset($job->salary_to) ? $job->salary_to : 'No data' }}</td>
        <td>{{ isset($job->annual_ip) ? $job->annual_ip : 'No data' }}</td>
        <td>{{ isset($job->long_term_ip) ? $job->long_term_ip : 'No data' }}</td>

        <td><label class="badge bg-inverse-warning" style="display: inline-block;min-width: 90px;">{{ $job->offer_status }}</label></td>
        
        <td class="text-right">
            <div class="dropdown dropdown-action">
                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <form id="edit_form_{{ $job->id }}" action="{{ route('jobapplicants/edit') }}" method="POST">
                        <input type="hidden" name="id" value="{{ $job->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="approval_action_{{ $job->id }}" name="offer_status" value="">
                    </form>
                    <a class="dropdown-item" href="#" onclick="setApprovalAction('approved', {{ $job->id }}); document.getElementById('edit_form_{{ $job->id }}').submit();">
                        <i class="fa fa-thumbs-o-up m-r-5"></i> Approve
                    </a>
                    <a class="dropdown-item" href="#" onclick="setApprovalAction('rejected', {{ $job->id }}); document.getElementById('edit_form_{{ $job->id }}').submit();">
                        <i class="fa fa-ban m-r-5"></i> Reject
                    </a>
                </div>
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
    <!-- /Page Content -->

    <!-- Edit Job Modal -->
    <div id="edit_job" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Job</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Title</label>
                                    <input class="form-control" type="text" value="Web Developer">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department</label>
                                    <select class="select">
                                        <option>-</option>
                                        <option selected>Web Development</option>
                                        <option>Application Development</option>
                                        <option>IT Management</option>
                                        <option>Accounts Management</option>
                                        <option>Support Management</option>
                                        <option>Marketing</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Location</label>
                                    <input class="form-control" type="text" value="California">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No of Vacancies</label>
                                    <input class="form-control" type="text" value="5">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Experience</label>
                                    <input class="form-control" type="text" value="2 Years">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Age</label>
                                    <input class="form-control" type="text" value="-">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Salary From</label>
                                    <input type="text" class="form-control" value="32k">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Salary To</label>
                                    <input type="text" class="form-control" value="38k">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Type</label>
                                    <select class="select">
                                        <option selected>Full Time</option>
                                        <option>Part Time</option>
                                        <option>Internship</option>
                                        <option>Temporary</option>
                                        <option>Remote</option>
                                        <option>Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select">
                                        <option selected>Open</option>
                                        <option>Closed</option>
                                        <option>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" class="form-control datetimepicker" value="3 Mar 2019">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Expired Date</label>
                                    <input type="text" class="form-control datetimepicker" value="31 May 2019">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Save</button>
                        </div>
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
   function setApprovalAction(action, jobId) {
        document.getElementById('approval_action_' + jobId).value = action;
    }
    </script>
<!-- /Page Wrapper -->
@endsection