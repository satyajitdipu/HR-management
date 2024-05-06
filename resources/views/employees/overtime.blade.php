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
                    <h3 class="page-title">Overtime</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Overtime</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_overtime"><i
                            class="fa fa-plus"></i> Add Overtime</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Overtime Statistics -->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="stats-info">
                    <h6>Overtime Employee</h6>
                    <h4>{{$overtime->whereBetween('date', [now()->startOfMonth(),
                        now()->endOfMonth()])->pluck('employee_id')->unique()->count()}} <span>this month</span></h4>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="stats-info">
                    <h6>Overtime Hours</h6>
                    <h4>{{$overtime->whereBetween('date', [now()->startOfMonth(),
                        now()->endOfMonth()])->sum('overtime_hours')}} <span>this month</span></h4>
                </div>
            </div>

        </div>
        <!-- /Overtime Statistics -->

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>OT Date</th>
                                <th class="text-center">OT Hours</th>

                                <th>Description</th>
                                <th class="text-center">Remark</th>

                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overtime as $overtime)
                            <tr>
                                <td>{{ $overtime->id }}</td>
                                <td>
                                    <h2 class="table-avatar blue-link">
                                        <a href="profile.html" class="avatar"><img alt="" src=""></a>
                                        <a href="profile.html">{{ $overtime->employee->name }}</a>
                                    </h2>
                                </td>
                                <td>{{ $overtime->date }}</td>
                                <td class="text-center">{{ $overtime->overtime_hours }}</td>
                                <td>{{ $overtime->description }}</td>
                                <td>{{ $overtime->remarks }}</td>
                                <td class="text-center">
                                    <div class="action-label">
                                        <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                            <i class="fa fa-dot-circle-o text-purple"></i> New
                                        </a>
                                    </div>
                                </td>

                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                           

                                                <a class="edit_overtime" href="#" data-toggle="modal"
   data-target="#edit_overtime" data-id="{{ $overtime->id}}"
   data-overtime-id="{{ $overtime->id }}"
   data-overtime-date="{{ $overtime->date }}"
   data-employee-id="{{ $overtime->employee }}"
   data-overtime-hours="{{ $overtime->overtime_hours}}"
   data-overtime-description="{{ $overtime->description }}"
   data-overtime-remarks="{{ $overtime->remarks }}">
   <i class="fa fa-pencil m-r-5"></i>Edit
</a>

                                                <a class="dropdown-item delete-overtime-btn" href="#" data-toggle="modal" data-target="#delete_overtime"
    data-id="{{ $overtime->id }}">
    <i class="fa fa-trash-o m-r-5"></i> Delete
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

    <!-- Add Overtime Modal -->
    @livewire('add-over-time')
    <!-- /Add Overtime Modal -->

    <!-- Edit Overtime Modal -->
    <div id="edit_overtime" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Overtime</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('form/overtime/update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                        <label>Employee <span class="text-danger">*</span></label>
                            <select class="select" name="employee_id">
                                @foreach($employee as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Overtime Date <span class="text-danger">*</span></label>
                            <div class="cal-icon">
                            <input type="hidden" name="id" value="{{ $overtime->id }}">
                            @if(isset($overtime->id))
 
@endif


                                <input class="form-control datetimepicker" type="text" name="overtime_date">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Overtime Hours <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="overtime_hours">
                        </div>
                        <div class="form-group">
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea rows="4" class="form-control" name="description"></textarea>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Overtime Modal -->

    <!-- Delete Overtime Modal -->
   <!-- Delete Overtime Modal -->
   <div class="modal custom-modal fade" id="delete_overtime" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Overtime</h3>
                    <p>Are you sure you want to delete this overtime record?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <form id="deleteOvertimeForm" action="{{ route('form/overtime/delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="deleteOvertimeId">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /Delete Overtime Modal -->

    <!-- /Delete Overtime Modal -->

</div>
<!-- /Page Wrapper -->
<script>
$('.delete-overtime-btn').on('click', function () {
    var overtimeId = $(this).data('id');
    $('#deleteOvertimeId').val(overtimeId);
});


    </script>
<script>
    $('.edit_overtime').click(function() {
    var overtimeId = $(this).data('id');
    var overtimeDate = $(this).data('overtime-date');
    var employeeId = $(this).data('employee-id');
    var overtimeHours = $(this).data('overtime-hours');
    var description = $(this).data('overtime-description');
    var remarks = $(this).data('overtime-remarks');

    $('#edit_overtime').find('[name="id"]').val(overtimeId);
    $('#edit_overtime').find('[name="employee_id"]').val(employeeId);
    $('#edit_overtime').find('[name="overtime_date"]').val(overtimeDate);
    $('#edit_overtime').find('[name="overtime_hours"]').val(overtimeHours);
    $('#edit_overtime').find('[name="description"]').val(description);
    $('#edit_overtime').find('[name="remarks"]').val(remarks);
});

    </script>
@section('script')

@endsection
@endsection