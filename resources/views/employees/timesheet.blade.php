@extends('layouts.master')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
{!! Toastr::message() !!}
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Timesheet</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Timesheet</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_todaywork"><i
                            class="fa fa-plus"></i> Add Today Work</a>
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
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Projects</th>
                                <th class="text-center">Assigned Hours</th>
                                <th class="text-center">Hours</th>
                                <th class="d-none d-sm-table-cell">Description</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr>
                                <td>
                                <h2 class="table-avatar">
    <a href="{{ url('employee/profile/'.$task->employee->employee_id) }}" class="avatar">
        <img alt="" src="{{ asset('assets/img/profiles/' . $task->avatar) }}">
    </a>
    <a href="{{ url('employee/profile/'.$task->employee->employee_id) }}">
        {{ $task->employee->name }}
        <span>{{ $task->role }}</span>
    </a>
</h2>

                                </td>
                                <td>{{ $task->created_at }}</td>
                                <td>
                                    <h2>{{ $task->project->name }}</h2>
                                </td>
                                <td class="text-center">{{ $task->time_assign }}</td>
                                <td class="text-center">{{ $task->task_status }}</td>
                                <td class="d-none d-sm-table-cell col-md-4">{{ $task->name }}</td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item edit-link" href="#" data-toggle="modal"
   data-target="#edit_todaywork" data-id="{{ $task->id }}"
   data-task-id="{{ $task->id }}"
   data-project-id="{{ $task->project_id }}"
   data-employee-id="{{ $task->employee_id }}"
   data-status="{{ $task->task_status }}"
   data-hours="{{ $task->time_assign }}"
   data-description="{{ $task->name }}">
   <i class="fa fa-pencil m-r-5"></i>Edit
</a>

                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                data-target="#delete_workdetail"><i
                                                    class="fa fa-trash-o m-r-5"></i>Delete</a>
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

    <!-- Add Today Work Modal -->
   @livewire('add-today-work')
    <!-- /Add Today Work Modal -->

    <!-- Edit Today Work Modal -->
    <div id="edit_todaywork" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Work Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('form/timesheet/update') }}" method="POST" id="edit_timesheet_form">
                    @csrf
                    <input type="hidden" name="timesheet_id" value="" id="edit_timesheet_id">

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Project <span class="text-danger">*</span></label>
                            <select class="select" id="edit_project" name="project_id">
                                @foreach($project as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Employee <span class="text-danger">*</span></label>
                            <select class="select" id="edit_employee" name="employee_id">
                                @foreach($employee as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="select" id="select_status" name="status">
                                <option value="Completed Tasks">Completed Tasks</option>
                                <option value="Inprogress Tasks">Inprogress Tasks</option>
                                <option value="On Hold Tasks">On Hold Tasks</option>
                                <option value="Pending Tasks">Pending Tasks</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>Hours <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="edit_hours" name="hours">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description <span class="text-danger">*</span></label>
                        <textarea rows="4" class="form-control" id="edit_description" name="description"></textarea>
                    </div>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary submit-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- /Edit Today Work Modal -->

    <!-- Delete Today Work Modal -->
    <div class="modal custom-modal fade" id="delete_workdetail" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Work Details</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <form action="{{ route('form/timesheet/delete') }}" method="POST" id="delete_form">
                    @csrf
                    <input type="hidden" name="timesheet_id" value="{{ $task->id ?? '' }}" id="edit_timesheet_id">

                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Delete Today Work Modal -->
</div>
<!-- /Page Wrapper -->
<script>
  $(document).ready(function() {
    $('.edit-link').click(function() {
        var id = $(this).data('id');
        console.log(id);

     
    });
});

    
</script>
<script>
   $(document).ready(function() {
    $('.edit-link').click(function() {
        var projectId = $(this).data('project-id');
        var taskId = $(this).data('task-id');
        var employeeId = $(this).data('employee-id');
        var status = $(this).data('status');
        var hours = $(this).data('hours');
        var description = $(this).data('description');
console.log(taskId);   

        $('#edit_project').val(projectId);
        $('#edit_timesheet_id').val(taskId);
        $('#edit_employee').val(employeeId);
        $('#select_status').val(status);
        $('#edit_hours').val(hours);
        $('#edit_description').val(description);

        // Move the each loop here
        $('#edit_employee option').each(function() {
            if ($(this).val() == employeeId) {
                $(this).prop('selected', true);
              
            }
        });
    });
});

</script>
@section('script')

@endsection
@endsection