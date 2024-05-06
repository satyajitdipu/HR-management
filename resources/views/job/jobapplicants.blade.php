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
                        <h3 class="page-title">Job Applicants</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Job Applicants</li>
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
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Apply Date</th>
                                    <th class="text-center">Status</th>
                                    <th>Resume</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($apply_for_jobs as $key=>$apply )
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $apply->name }}</td>
                                    <td>{{ $apply->email }}</td>
                                    <td>{{ $apply->phone }}</td>
                                    <td class="id" hidden>{{ $apply->id }}</td>
                                    <td>{{ date('d F, Y',strtotime($apply->created_at)) }}</td>
   <td class="text-center">
    <div class="dropdown action-label">
        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-dot-circle-o text-info"></i> {{$apply->status}}
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <form id="status_form_{{ $apply->id }}" action="{{ route('jobapplicants/updateStatus') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $apply->id }}">
                <button class="dropdown-item" type="submit" name="status" value="New"><i class="fa fa-dot-circle-o text-info"></i> New</button>
                <button class="dropdown-item" type="submit" name="status" value="Hired"><i class="fa fa-dot-circle-o text-success"></i> Hired</button>
                <button class="dropdown-item" type="submit" name="status" value="Rejected"><i class="fa fa-dot-circle-o text-danger"></i> Rejected</button>
                <button class="dropdown-item" type="submit" name="status" value="Interviewed"><i class="fa fa-dot-circle-o text-danger"></i> Interviewed</button>
            </form>
        </div>
    </div>
</td>


                                    <td><a href="{{ url('cv/download/'.$apply->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download</a></td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item edit_job" data-toggle="modal" data-target="#edit_job"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                               
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
    </div>
    <!-- Add/Edit Modal -->
<!-- Add/Edit Modal -->
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
                <form id="edit_form" action="{{ route('jobapplicants/edit') }}" method="POST">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_name">Name:</label>
                        <input type="text" class="form-control" id="edit_name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email:</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Phone:</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone">
                    </div>
                    <!-- <div class="form-group">
                        <label for="edit_status">Status:</label>
                        <select class="form-control" id="edit_status" name="status">
                            <option value="New">New</option>
                            <option value="Hired">Hired</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Interviewed">Interviewed</option>
                        </select>
                    </div> -->
                 

                    <!-- Add other fields here -->
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Update Edit JavaScript -->
<script>
    $(document).on('click', '.edit_job', function() {
        // Existing edit_job click event handler
        var _this = $(this).closest('tr');
        var id = _this.find('.id').text(); // Assuming you have a hidden td with class 'id'
        var name = _this.find('td:eq(1)').text();
        var email = _this.find('td:eq(2)').text();
        var phone = _this.find('td:eq(3)').text();

        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#edit_email').val(email);
        $('#edit_phone').val(phone);
    });

    flatpickr('#edit_interview', {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
</script>




    <!-- /Page Wrapper -->
@endsection