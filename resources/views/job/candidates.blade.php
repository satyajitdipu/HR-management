@extends('layouts.master')
@section('content')
{!! Toastr::message() !!}
    <!-- Page Wrapper -->
    <div class="page-wrapper">    
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Candidates List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item">Jobs</li>
                            <li class="breadcrumb-item active">Candidates List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" data-toggle="modal" data-target="#add_employee" class="btn add-btn"> Add Candidates</a>
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
                                    <th>Mobile Number</th>
                                    <th>Email</th>
                                    <th>status</th>
                                    <th>Created Date</th>
                                    <th>Offer Status</th>
                                    <th>Selection Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
    @forelse ($applicants as $key => $applicant)
        <tr>
            <td>{{ ++$key }}</td>
            <td>
                <h2 class="table-avatar">
                    <a href="{{ url('resume/details/'.$applicant->id) }}" class="avatar"><img alt="" src="assets/img/profiles/avatar-{{ $applicant->id }}.jpg"></a>
                    <a href="{{ url('resume/details/'.$applicant->id) }}">{{ $applicant->name }}</a>
                </h2>
            </td>
            <td>{{ $applicant->phone }}</td>
            <td>{{ $applicant->email }}</td>
            <td>{{ $applicant->status }}</td>
           
            <td>{{ date('d M Y', strtotime($applicant->created_at)) }}</td>
            <td>{{ $applicant->offer_status }}</td>
            <td>{{ $applicant->status_selection }}</td>
            <td class="text-center">
                <div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle edit_job" data-toggle="modal" data-id="{{ $applicant->id }}" data-target="#edit_job"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_job"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                    </div>
                </div>
            </td>
            

            
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
        <!-- /Page Content -->
        
        <!-- Add Employee Modal -->
        <div id="add_employee" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Candidates</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form id="apply_jobs" action="{{ route('form/apply/job/save') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Job Titel</label>
                                    <select name="job_title" class="form-control">
    @foreach($job_title as $job)
        <option value="{{ $job }}">{{ $job }}</option>
    @endforeach
</select>
</div>
                                <div class="form-group">
                                    <label>Name</label>
            


                                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input class="form-control @error('phone') is-invalid @enderror" type="tel" name="phone" value="{{ old('phone') }}">
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" value="{{ old('email') }}">
                                </div>
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" name="message">{{ old('message') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Upload your CV</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('cv_upload') is-invalid @enderror" id="cv_upload" name="cv_upload">
                                        <label class="custom-file-label" for="cv_upload">Choose file</label>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Employee Modal -->

        <!-- Edit Job Modal -->
        <div id="edit_job" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Candidates</h5>
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
                            <div class="form-group">
    <label for="edit_status">Status:</label>
    <select class="form-control" id="edit_status" name="status">
        <option value="New">New</option>
        <option value="Hired">Hired</option>
        <option value="Rejected">Rejected</option>
        <option value="Interviewed">Interviewed</option>
        <option value="Offered">Offered</option>
        <option value="Pending">Pending</option>
    </select>
</div>

<div class="form-group">
    <label for="edit_offer_status">Offer Status:</label>
    <select class="form-control" id="edit_offer_status" name="offer_status">
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
        <option value="Requested">Requested</option>
        <option value="Interviewed">Interviewed</option>
        <option value="Offered">Offered</option>
        <option value="Pending">Pending</option>
    </select>
</div>

<div class="form-group">
    <label for="edit_status_selection">Status Selection:</label>
    <select class="form-control" id="edit_status_selection" name="status_selection">
        <option value="Action pending">Action pending</option>
        <option value="Resume selected">Resume selected</option>
        <option value="Resume Rejected">Resume Rejected</option>
        <option value="Aptitude Selected">Aptitude Selected</option>
        <option value="Aptitude rejected">Aptitude rejected</option>
        <option value="video call selected">video call selected</option>
        <option value="Video call rejected">Video call rejected</option>
        <option value="Offered">Offered</option>
    </select>
</div>

                            <button type="submit" class="btn btn-primary">Update</button>
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
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
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
        $(document).ready(function() {
            $(document).on('click', '.edit_job', function() {
                var id = $(this).data('id');
                var row = $(this).closest('tr');
                var name = row.find('td:eq(1)').text();
                var email = row.find('td:eq(2)').text();
                var phone = row.find('td:eq(3)').text();
                var status = row.find('td:eq(4)').text();

                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_email').val(phone);
                $('#edit_phone').val(email);
                $('#edit_status').val(status);

                $('#edit_job').modal('show');
            });
        });
        
    </script>
    @if ($errors->any())
    <script>
        $(document).ready(function() {
            $('#add_employee').modal('show');
        });
    </script>
@endif
    <!-- /Page Wrapper -->
@endsection
