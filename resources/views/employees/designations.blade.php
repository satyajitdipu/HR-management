@extends('layouts.master')
@section('content')
  
    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Designations</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Designations</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_designation"><i class="fa fa-plus"></i> Add Designation</a>
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
                                    <th style="width: 30px;">#</th>
                                    <th>Designation </th>
                                    <th>Department </th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($designations as $designation)
                                <tr>
                                    <td>{{ $designation->id }}</td>
                                    <td>{{ $designation->designation }}</td>
                                    <td id="{{$designation->department_id}}" class="department-name">{{ $department->find($designation->department_id)->department }}</td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item editDesignation" href="#" data-toggle="modal" data-target="#edit_designation" data-id="{{ $designation->id }}">
    <i class="fa fa-pencil m-r-5"></i> Edit
</a>

                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_designation" data-id="{{ $designation->id }}">
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

        <!-- Add Designation Modal -->
        {!! Toastr::message() !!}
        <div id="add_designation" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Designation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('/form/designations/save') }}" >
                        @csrf

                        <div class="form-group">
                            <label>Designation Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="designation_name" required>
                        </div>
                        <div class="form-group">
                            <label>Department <span class="text-danger">*</span></label>
                            <select class="form-control" name="department">
    <option value="">Select Department</option>
    @foreach($option as $name => $id)
        <option value="{{ $id }}">{{ $name }}</option>
    @endforeach
</select>

                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Add Designation Modal -->

<!-- Edit Designation Modal -->
<div id="edit_designation" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Designation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('/form/designations/update') }}">
                    @csrf
                    <input type="hidden" id="edit_designation_id" name="id">
                    <div class="form-group">
                        <label>Designation Name <span class="text-danger">*</span></label>
                        <input class="form-control" id="edit_designation_name" name="degination" type="text">
                    </div>
                    <div class="form-group">
                        <label>Department <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_department" value="edit_department" name="department">
        <option value="">Select Department</option>
        @foreach($option as $name => $id)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>

                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- /Edit Designation Modal -->

<!-- Delete Designation Modal -->
<div class="modal custom-modal fade" id="delete_designation" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Designation</h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <form id="delete_designation_form" method="POST" action="{{ route('form/designations/delete') }}">
                    @csrf
                    <input type="hidden" name="designation_id" id="designation_id">
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-primary cancel-btn" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Designation Modal -->
    
</div>
<!-- /Page Wrapper -->

<script>
    $(document).ready(function () {
        $('#delete_designation').on('show.bs.modal', function (e) {
            var designationId = $(e.relatedTarget).data('id');
            $('#designation_id').val(designationId);
        });
    });
    $(document).ready(function() {
    $('.editDesignation').click(function() {
        var designationId = $(this).data('id');
        var designationName = $(this).closest('tr').find('td:eq(1)').text(); // Assuming the designation name is in the second column
        var departmentId = $(this).closest('tr').find('.department-name').attr('id'); // Assuming the department ID is in the third column
console.log(departmentId);
        $('#edit_designation_id').val(designationId);
        $('#edit_designation_name').val(designationName);
        $('#edit_department').val(departmentId).change(); // Select the department in the dropdown
    });
});

</script>




@section('script')
    
@endsection
@endsection
