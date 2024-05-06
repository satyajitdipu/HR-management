@php
use App\Models\LeavesAdmin;
@endphp
@extends('layouts.master')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
{!! Toastr::message() !!}
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Leaves <span id="year"></span></h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leaves</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_leave"><i
                            class="fa fa-plus"></i> Add Leave</a>
                </div>
            </div>
        </div>

        <!-- Leave Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-info">
                    <h6>Annual Leave</h6>
                    <h4>{{count($leaves)}}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-info">
                    <h6>Medical Leave</h6>
                    <h4>{{$leaves->filter(function ($leave) {
    return $leave->leave_type == 'Medical Leave';
})->count()}}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-info">
                    <h6>Other Leave</h6>
                    <h4>{{$leaves->filter(function ($leave) {
    return $leave->leave_type !== 'Medical Leave';
})->count()}}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-info">
                    <h6>Remaining Leave</h6>
                    <h4>{{12-count($leaves)}}</h4>
                </div>
            </div>
        </div>

        <!-- /Leave Statistics -->

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>No of Days</th>
                                <th>Reason</th>
                                <th class="text-center">Status</th>
                                <th>Approved by</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <!-- Example of dynamically populating the table -->
                        <tbody>
                            @foreach ($leaves as $leave)
                           

                            <tr id="row-{{ $leave->id }}">
                                <td>{{ $leave-> leave_type}}</td>
                                <td>{{ $leave->from_date }}</td>
                                <td>{{ $leave->to_date }}</td>
                                <td>{{ $leave->day }}</td>
                                <td>{{ $leave->leave_reason }}</td>
                                <td>{{ $leave->status }}</td>
                                <td>{{$leave->Approved_by}}</td>

                                <!-- Add more columns as needed -->
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                        <button class="btn btn-primary edit-leave" data-id="{{ $leave->id }}">Edit</button>

                                                <a class="dropdown-item leaveDelete" href="#" data-toggle="modal" data-id="{{ $leave->id }}" data-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>

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

    <!-- Add Leave Modal -->
    <!-- Add Leave Modal -->
  @livewire('add-leave')
    <!-- /Add Leave Modal -->

    <!-- /Add Leave Modal -->

    <!-- Edit Leave Modal -->
    <div id="edit_leave" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="editLeaveForm" action="{{ route('form/leaves/edit') }}" method="POST">
                @csrf
    <div class="form-group">
        <label>Leave Type <span class="text-danger">*</span></label>
        <select class="select" name="leave_type" id="editLeaveType">
            <option>Select Leave Type</option>
            <option>Casual Leave</option>
            <option>Medical Leave</option>
            <option>Loss of Pay</option>
        </select>
    </div>
    <div class="form-group">
        <label>From <span class="text-danger">*</span></label>
        <div class="cal-icon">
            <input name="from_date" class="form-control datetimepicker" id="editFromDate" type="text">
            <input type="hidden" name="id"  id="editLeaveId">
        </div>
    </div>
    <div class="form-group">
        <label>To <span class="text-danger">*</span></label>
        <div class="cal-icon">
            <input name="to_date" class="form-control datetimepicker" id="editToDate" type="text">
        </div>
    </div>
    <input type="hidden" name="leave_employee" value="yes" id="">

    <div class="form-group">
        <label>Leave Reason <span class="text-danger">*</span></label>
        <textarea rows="4" name="leave_reason" class="form-control" id="editLeaveReason"></textarea>
    </div>
    <div class="submit-section">
        <button class="btn btn-primary submit-btn">Save</button>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Leave Modal -->

    <!-- Delete Leave Modal -->
    <div class="modal custom-modal fade" id="delete_approve" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Leave</h3>
                    <p>Are you sure want to Cancel this leave?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <form id="delete_form" method="POST" action="">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- /Delete Leave Modal -->

</div>
<script>
    $(document).ready(function() {
    $('.leaveDelete').on('click', function() {
        var id = $(this).data('id');
        var url = "{{ route('leaves.destroy', ':id') }}";
        url = url.replace(':id', id);
        $('#delete_form').attr('action', url);
    });
    $('.edit-leave').click(function () {
    var leaveType = $(this).data('leave-type');
    var fromDate = $(this).data('from-date');
    var toDate = $(this).data('to-date');
    var leaveReason = $(this).data('leave-reason');

    $('#editLeaveType').val(leaveType);
    $('#editFromDate').val(fromDate);
    $('#editToDate').val(toDate);
    $('#editLeaveReason').val(leaveReason);
});
});

$(document).ready(function() {
    $('.edit-leave').on('click', function() {
        var id = $(this).data('id');
        var row = $('#row-' + id);
        var leaveType = row.find('td:eq(0)').text(); // Leave Type
        var fromDate = row.find('td:eq(1)').text(); // From Date
        var toDate = row.find('td:eq(2)').text(); // To Date
        var leaveReason = row.find('td:eq(4)').text(); // Leave Reason

        $('#editLeaveType').val(leaveType);
        $('#editFromDate').val(fromDate);
        $('#editToDate').val(toDate);
        $('#editLeaveReason').val(leaveReason);
        $('#editLeaveId').val(id);

        $('#edit_leave').modal('show');
    });
});

    </script>
<!-- /Page Wrapper -->
@endsection