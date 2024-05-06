@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Job Title</th>
                                    <th>Email</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($department as $key => $applicant)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="{{ url('resume/details/'.$applicant->id) }}" class="avatar"><img alt="" src="assets/img/profiles/avatar-{{ $applicant->id }}.jpg"></a>
                                                <a href="{{ url('resume/details/'.$applicant->id) }}">{{ $applicant->name }}</a>
                                            </h2>
                                        </td>
                                        <td><a href="{{ url('job/details/resume/'.$applicant->id) }}">{{ $applicant->job_title }}</a></td>
                                        <td>{{ $applicant->email }}</td>
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="#">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> {{ $applicant->status_selection }}
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
        </div>
    </div>
@endsection
