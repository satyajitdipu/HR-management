
@extends('layouts.master')
@section('content')
  
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Attendance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Search Filter -->
            <form action="{{ route('all/attentance/search') }}" method="POST">
    @csrf
    <div class="row filter-row">
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
            <div class="form-group form-focus">
                <input type="text" class="form-control floating" name="employee_name" placeholder="Employee Name">
                <label class="focus-label">Employee Name</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3"> 
            <div class="form-group form-focus select-focus">
                <select class="select floating" name="month">
                    <option value="">Select Month</option>
                    @php
                        $months = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];
                    @endphp
                    @foreach($months as $key => $month)
                        <option value="{{ $key }}">{{ $month }}</option>
                    @endforeach
                </select>
                <label class="focus-label">Select Month</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3"> 
            <div class="form-group form-focus select-focus">
                <select class="select floating" name="year">
                    <option value="">Select Year</option>
                    @php
                        $currentYear = date('Y');
                        for ($i = 0; $i < 3; $i++) {
                            $year = $currentYear - $i;
                            echo "<option>{$year}</option>";
                        }
                    @endphp
                </select>
                <label class="focus-label">Select Year</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">  
            <button type="submit" class="btn btn-success btn-block"> Search </button>  
        </div>
    </div>
</form>


            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table table-nowrap mb-0">
                           <thead>
        <tr>
            <th>Employee</th>
            @for ($i = 1; $i <= 31; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
    @foreach ($employees as $employee)
    <tr>
        <td>
            <h2 class="table-avatar">
                <a class="avatar avatar-xs" href="{{ url('employee/profile/'.$employee->employee_id) }}"><img alt="" src="{{ URL::to('assets/img/profiles/avatar-09.jpg') }}"></a>
                <a href="{{ url('employee/profile/'.$employee->employee_id) }}">{{ $employee->name }}</a>
            </h2>
        </td>
        @for ($day = 1; $day <= $daysInMonth; $day++)
            <td>
                @php
               // dd($currentYear . '-' . $currentMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT) );
                    $attendance = $attendances->where('employee_id', $employee->id)
                                              ->where('date', 'like', $currentYear . '-' . $currentMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT) )
                                              ->first();
                                             
                @endphp
                @if ($attendance!=null)
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#attendance_info"><i class="fa fa-check text-success"></i></a>
                @elseif ($attendance && $attendance->status == 'half_day')
                    <div class="half-day">
                        <span class="first-off"><a href="javascript:void(0);" data-toggle="modal" data-target="#attendance_info"><i class="fa fa-check text-success"></i></a></span> 
                        <span class="first-off"><i class="fa fa-close text-danger"></i></span>
                    </div>
                @else
                    <i class="fa fa-close text-danger"></i>
                @endif
            </td>
        @endfor
    </tr>
@endforeach

    </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
        <!-- Attendance Modal -->
        <div class="modal custom-modal fade" id="attendance_info" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attendance Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card punch-status">
                                    <div class="card-body">
                                        <h5 class="card-title">Timesheet <small class="text-muted">11 Mar 2019</small></h5>
                                        <div class="punch-det">
                                            <h6>Punch In at</h6>
                                            <p>Wed, 11th Mar 2019 10.00 AM</p>
                                        </div>
                                        <div class="punch-info">
                                            <div class="punch-hours">
                                                <span>3.45 hrs</span>
                                            </div>
                                        </div>
                                        <div class="punch-det">
                                            <h6>Punch Out at</h6>
                                            <p>Wed, 20th Feb 2019 9.00 PM</p>
                                        </div>
                                        <div class="statistics">
                                            <div class="row">
                                                <div class="col-md-6 col-6 text-center">
                                                    <div class="stats-box">
                                                        <p>Break</p>
                                                        <h6>1.21 hrs</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-6 text-center">
                                                    <div class="stats-box">
                                                        <p>Overtime</p>
                                                        <h6>3 hrs</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card recent-activity">
                                    <div class="card-body">
                                        <h5 class="card-title">Activity</h5>
                                        <ul class="res-activity-list">
                                            <li>
                                                <p class="mb-0">Punch In at</p>
                                                <p class="res-activity-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    10.00 AM.
                                                </p>
                                            </li>
                                            <li>
                                                <p class="mb-0">Punch Out at</p>
                                                <p class="res-activity-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    11.00 AM.
                                                </p>
                                            </li>
                                            <li>
                                                <p class="mb-0">Punch In at</p>
                                                <p class="res-activity-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    11.15 AM.
                                                </p>
                                            </li>
                                            <li>
                                                <p class="mb-0">Punch Out at</p>
                                                <p class="res-activity-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    1.30 PM.
                                                </p>
                                            </li>
                                            <li>
                                                <p class="mb-0">Punch In at</p>
                                                <p class="res-activity-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    2.00 PM.
                                                </p>
                                            </li>
                                            <li>
                                                <p class="mb-0">Punch Out at</p>
                                                <p class="res-activity-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    7.30 PM.
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Attendance Modal -->
        
    </div>
    <!-- Page Wrapper -->
@endsection
