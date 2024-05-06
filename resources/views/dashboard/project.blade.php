
@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Page Content -->
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Clients</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Clients</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="{{ route('create/estimate/page') }}" class="btn add-btn"><i class="fa fa-plus"></i> Create Client</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Search Filter -->
            <!-- <div class="row filter-row">
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">To</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option>Select Status</option>
                            <option>Accepted</option>
                            <option>Declined</option>
                            <option>Expired</option>
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <a href="#" class="btn btn-success btn-block"> Search </a>  
                </div>     
            </div> -->
            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                    <table class="table custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Project Name </th>
                                        <th>Progress</th>
                                        <!-- <th class="text-right">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
    @foreach ($project as $project)
    @php
    $taskproject = $task->where('project_id', $project->id);
    $taskcomplete = $task->where('project_id', $project->id)
        ->where('task_status', 'Completed Tasks')
        ->count();
    $totalTasks = $taskproject->count();
    $percentage = $totalTasks > 0 ? ($taskcomplete / $totalTasks) * 100 : 0;
    @endphp
    <tr>
        <td>
            <h2><a href="">{{ $project->name }}</a></h2>
            <small class="block text-ellipsis">
                <span>{{ $project->open_tasks }}</span> <span class="text-muted">open tasks, </span>
                <span>{{ $project->completed_tasks }}</span> <span class="text-muted">tasks completed</span>
            </small>
        </td>
        <td>
            <div class="progress progress-xs progress-striped">
                <div class="progress-bar" role="progressbar" data-toggle="tooltip" title="{{ $percentage }}%"
                    style="width: {{ $percentage }}%"></div>
            </div>
        </td>
    
                                        <!-- <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item edit-project" href="#" data-toggle="modal" data-target="#editProjectModal" data-projectid="{{ $project->id }}" data-projectname="{{ $project->name }}" data-projectdescription="{{ $project->description }}" data-projectstatus="{{ $project->status }}">
    <i class="fa fa-pencil m-r-5"></i> Edit
</a>
                                        <a class="dropdown-item" href=""><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                    </div>
                                </div>
                            </td> -->
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editClientModal" tabindex="-1" role="dialog"
                aria-labelledby="editClientModalLabel" aria-hidden="true">

                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editClientModalLabel">Edit Client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editClientForm" method="POST" action="{{ route('/home/client/edit') }}">
                                @csrf
                                <input type="hidden" id="clientId" name="clientId">
                                <div class="form-group">
                                    <label for="clientName">Name</label>
                                    <input type="text" class="form-control" id="clientName" name="clientName">
                                </div>
                                <div class="form-group">
                                    <label for="clientEmail">Email</label>
                                    <input type="email" class="form-control" id="clientEmail" name="clientEmail">
                                </div>
                                <div class="form-group">
                                    <label for="clientStatus">Status</label>
                                    <select class="form-control" id="clientStatus" name="clientStatus">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="saveClientChanges">Save
                                        Changes</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <!-- /Page Content -->
        
        <!-- Delete Estimate Modal -->
        <div class="modal custom-modal fade" id="delete_estimate" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Estimate</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <form action="{{ route('estimate/delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" class="e_id" value="">
                            <input type="hidden" name="estimate_number" class="estimate_number" value="">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Estimate Modal -->
    
    </div>
    <!-- /Page Wrapper -->
 
    @section('script')
         {{-- delete model --}}
         <script>
             $(document).ready(function () {
        // Edit client button click event
        $('.edit-client').click(function () {
            var clientId = $(this).data('clientid');
            var clientName = $(this).data('clientname');
            var clientEmail = $(this).data('clientemail');
            var clientStatus = $(this).data('clientstatus');

            $('#clientId').val(clientId);
            $('#clientName').val(clientName);
            $('#clientEmail').val(clientEmail);
            $('#clientStatus').val(clientStatus);
        });
    });
        </script>
    @endsection
@endsection
