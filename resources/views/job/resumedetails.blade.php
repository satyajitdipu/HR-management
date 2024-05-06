@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Resume Details</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" class="form-control" value="{{ $resume_view_detail[0]->name }}"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="text" class="form-control" value="{{ $resume_view_detail[0]->email }}"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label>Phone:</label>
                                    <input type="text" class="form-control" value="{{ $resume_view_detail[0]->phone }}"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label>Job Title:</label>
                                    <input type="text" class="form-control"
                                        value="{{ $resume_view_detail[0]->job_title }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Message:</label>
                                    <textarea class="form-control" rows="5"
                                        readonly>{{ $resume_view_detail[0]->message }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>CV :</label>
                                    <a href="{{ asset('public/assets/img/'.$resume_view_detail[0]->cv_upload) }}"
                                        download class="btn btn-primary">Download CV</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Status:</label>
                                <input type="text" class="form-control" value="{{ $resume_view_detail[0]->status }}"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label>Offer Status:</label>
                                <input type="text" class="form-control"
                                    value="{{ $resume_view_detail[0]->offer_status }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Status Selection:</label>
                                <input type="text" class="form-control"
                                    value="{{ $resume_view_detail[0]->status_selection }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection