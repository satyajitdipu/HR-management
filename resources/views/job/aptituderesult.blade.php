@extends('layouts.master')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <!-- Page Content -->
    {!! Toastr::message() !!}
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="page-title">Aptitude Result</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item">Jobs</li>
                        <li class="breadcrumb-item active">Aptitude Result</li>
                    </ul>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                        Add New Result
                    </button>

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
                                <th>Department</th>
                                <th>Category Wise Mark</th>
                                <th>Total Mark</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result as $result)
                            <tr>
                                <td>{{ $result->id }}</td>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="{{ url('resume/details/'.$result->apply_for_job_id) }}" class="avatar"><img alt=""
                                                src="assets/img/profiles/avatar-02.jpg"></a>
                                        <a href="{{ url('resume/details/'.$result->apply_for_job_id) }}">{{ $result->applyForJob->name }} <span>{{
                                                $result->addJob->job_title }}</span></a>
                                    </h2>
                                </td>
                                <td><a href="{{ url('job/applicants/'.$result->addJob->job_title) }}">{{ $result->addJob->job_title }}</a></td>
                                <td>{{ $result->addJob->department }}</td>
                                <td>
                                    @foreach(json_decode($result->catagory_wise_mark, true) as $key => $value)
                                    {{ $key }} - <b>{{ $value }}</b><br>
                                    @endforeach
                                </td>
                                <td class="text-center">{{ $result->total_mark }}</td>
                                <td class="text-center">
                                    <div class="dropdown action-label">
                                        <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                            data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-dot-circle-o text-danger"></i> {{ $result->status }}
                                        </a>
                                        <form id="statusForm_{{ $result->id }}" action="{{ route('page/aptitude/result/status') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="statusValue_{{ $result->id }}" name="status" value="">
                                            <input type="hidden" id="id_{{ $result->id }}" name="id" value="{{ $result->id }}">
                                        </form>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" onclick="setStatus('Resume selected', '{{ $result->id }}')">Resume selected</a>
                                            <a class="dropdown-item" href="#" onclick="setStatus('Resume Rejected', '{{ $result->id }}')">Resume Rejected</a>
                                            <a class="dropdown-item" href="#" onclick="setStatus('Aptitude Selected', '{{ $result->id }}')">Aptitude Selected</a>
                                            <a class="dropdown-item" href="#" onclick="setStatus('Aptitude rejected', '{{ $result->id }}')">Aptitude rejected</a>
                                            <a class="dropdown-item" href="#" onclick="setStatus('video call selected', '{{ $result->id }}')">video call selected</a>
                                            <a class="dropdown-item" href="#" onclick="setStatus('Video call rejected', '{{ $result->id }}')">Video call rejected</a>
                                            <a class="dropdown-item" href="#" onclick="setStatus('Offered', '{{ $result->id }}')">Offered</a>
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

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('page/aptitude/result/update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <select class="form-control" id="name" name="name" required>
                            <option value="">Select Name</option>
                            @foreach($applyjob as $job)
                            <option value="{{ $job->name }}" data-job-title="{{ $job->job_title }}"
                                data-id="{{ $job->id }}">{{ $job->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="job_title">Job Title</label>
                        <input type="text" class="form-control" id="job_title" name="job_title" readonly>
                    </div>
                    <div class="form-group">
                        <label for="catagory_wise_mark">Category Wise Mark</label>
                        <div id="categoryInputs">
                            <div class="category-input">
                                <input type="text" class="form-control" name="categories[]" placeholder="Category"
                                    required>
                                <input type="number" class="form-control" name="marks[]" placeholder="Mark" required>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary mt-2" id="addCategoryInput">Add
                            Category</button>
                    </div>
                    <div class="form-group">
                        <label for="total_mark">Total Mark</label>
                        <input type="number" class="form-control" id="total_mark" name="total_mark"
                            placeholder="Enter Total mark" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="Resume selected">Resume selected</option>
                            <option value="Resume Rejected">Resume Rejected</option>
                            <option value="Aptitude Selected">Aptitude Selected</option>
                            <option value="Aptitude rejected">Aptitude rejected</option>
                            <option value="video call selected">video call selected</option>
                            <option value="Video call rejected">Video call rejected</option>
                            <option value="Offered">Offered</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addCategoryInputButton = document.getElementById('addCategoryInput');
            const categoryInputsContainer = document.getElementById('categoryInputs');

            addCategoryInputButton.addEventListener('click', function () {
                const newCategoryInput = document.createElement('div');
                newCategoryInput.classList.add('category-input');
                newCategoryInput.innerHTML = `
                <input type="text" class="form-control" name="categories[]" placeholder="Category" required>
                <input type="text" class="form-control" name="marks[]" placeholder="Mark" required>
            `;
                categoryInputsContainer.appendChild(newCategoryInput);
            });
        });
        document.getElementById('name').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var jobTitle = selectedOption.getAttribute('data-job-title');
            var id = selectedOption.getAttribute('data-id'); // Corrected
            document.getElementById('job_title').value = id;
        });
        
    </script>
<script>
  function setStatus(status, id) {
        document.getElementById('statusValue_' + id).value = status;
        document.getElementById('statusForm_' + id).submit();
    }
</script>
@if ($errors->any())
    <script>
        $(document).ready(function() {
            $('#addModal').modal('show');
        });
    </script>
@endif

    <!-- /Page Wrapper -->
    @endsection