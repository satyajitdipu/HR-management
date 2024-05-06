
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
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Employee Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Employee Report</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="#" class="btn btn-primary">PDF</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <!-- Content Starts -->  
            
            <!-- Search Filter -->
            <div class="row filter-row mb-4">
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <input class="form-control floating" type="text">
                        <label class="focus-label">Employee</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option>Select Department</option>
                            <option>Designing</option>
                            <option>Development</option>
                            <option>Finance</option>
                            <option>Hr & Finance</option>
                        </select>
                        <label class="focus-label">Department</label>
                    </div>
                </div>
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
                    <a href="#" class="btn btn-success btn-block"> Search </a>  
                </div>     
            </div>
            <!-- /Search Filter -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Employee Type</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Joining Date</th>
                                    <th>DOB</th>
                                    <th>Martial Status</th>
                                    <th>Gender</th>
                                    <th>Terminated Date</th>
                                    <th>Relieving Date</th>
                                    <th>Salary</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Emercency Contact Details</th>
                                    <th>Experience</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
    @foreach($employees as $employee)
    <tr>
        <td>
            <h2 class="table-avatar">
                <a href="profile.html" class="avatar"><img alt="" src="{{ URL::to('assets/img/profiles/'.$employee['avatar']) }}"></a>
                <a href="profile.html" class="text-primary">{{ $employee['name'] }} <span>#{{ $employee['id'] }}</span></a>
            </h2>
        </td>
        <td>{{ $employee['role'] }}</td>
        <td class="text-info">{{ $employee['email'] }}</td>
        <td>{{ $employee['department'] }}</td>
        <td>{{ $employee['designation'] }}</td>
        <td>{{ $employee['join_date'] }}</td>
        <td>{{ $employee['birth_date'] }}</td>
        <td>{{ $employee['marital_status'] }}</td>
        <td>{{ $employee['gender'] }}</td>
        <td>{{ $employee['emergency_contact'] }}</td>
        <td>{{ $employee['pan'] }}</td>
        <td>{{ $employee['salary'] }}</td>
        <td>{{ $employee['address'] }}</td>
        <td>{{ $employee['phone'] }}</td>
        <td>{{ $employee['emergency_contact'] }}</td>
        <td>{{ $employee['experience'] }}</td>
        <td><button class="btn btn-outline-success btn-sm">{{ $employee['status'] }}</button></td>
    </tr>
    @endforeach
</tbody>

                        </table>
                    </div>
                </div>
            </div>
            <!-- /Content End -->
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection
