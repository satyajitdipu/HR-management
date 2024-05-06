@extends('layouts.master')
@section('content')
@php
use Carbon\Carbon;

use App\Models\Attendance; // Adjust the model path as per your application

$today = Carbon::today()->format('Y-m-d');

// Assuming $attendances is a query builder instance


$todayHours = number_format($attendances->where('date', $today)->sum('production_hours') - $attendances->where('date', $today)->sum('break_hours'),6);
$thisWeekHours = number_format($attendances->filter(function($attendance) {
    return Carbon::parse($attendance->date)->between(now()->startOfWeek(), now()->endOfWeek());
})->sum('production_hours') - $attendances->filter(function($attendance) {
    return Carbon::parse($attendance->date)->between(now()->startOfWeek(), now()->endOfWeek());
})->sum('break_hours'),6);
$thisMonthHours = number_format($attendances->filter(function($attendance) {
    return Carbon::parse($attendance->date)->month == now()->month;
})->sum('production_hours') - $attendances->filter(function($attendance) {
    return Carbon::parse($attendance->date)->month == now()->month;
})->sum('break_hours'),6);


$remainingHours = 160 - $thisMonthHours; // Assuming 160 is the total monthly hours
@endphp

<!-- Page Wrapper -->
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
    {!! Toastr::message() !!}
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

        <div class="row">
            <div class="col-md-4">
                <div class="card punch-status">
                    <div class="card-body">
                        <h5 class="card-title">Timesheet <small class="text-muted">{{ date('d M Y') }}</small></h5>
                        <div class="punch-det">
                            <h6>Punch In at</h6>
                            <p>
                                @if($todayAttendance = $attendances->where('date', $today)->first())
                                {{$todayAttendance->punch_in}}
                                @else
                                Absent
                                @endif
                            </p>
                        </div>
                        <div class="punch-info">
                            <div class="punch-hours">
                                @php
                                

                                $todayAttendance = $attendances->where('date', $today)->first();

                                if ($todayAttendance) {
                                $totalProductionHours = $todayAttendance->sum('production_hours');
                                $totalBreakHours = $todayAttendance->sum('break_hours');
                                } else {
                                $totalProductionHours = 0;
                                $totalBreakHours = 0;
                                }

                                @endphp
                                <span>{{8-$totalProductionHours}}</span>
                            </div>
                        </div>
                        <div class="punch-btn-section">
                        <form action="{{ route('/updatePunchStatus') }}" method="POST">
    @csrf
    <input type="hidden" name="status" value="{{ $todayAttendance && $todayAttendance->punch ? 'out' : 'in' }}">
    <button type="submit" class="btn btn-primary punch-btn">
        @if($latestAttendance)
            Punch Out
        @else
            Punch In
        @endif
    </button>
</form>


</div>

                        <div class="statistics">
                            <div class="row">
                                <div class="col-md-6 col-6 text-center">
                                    <div class="stats-box">
                                        <p>Break</p>
                                        <h6>{{ isset($todayAttendance->break_hours) ? $todayAttendance->break_hours . '
                                            hrs' : 'null' }}</h6>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6 text-center">
                                    <div class="stats-box">
                                        <p>Overtime</p>
                                        <h6>{{ isset($todayAttendance->overtime_hours) ?
                                            $todayAttendance->overtime_hours . ' hrs' : 'null' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                    <div class="card att-statistics">
                        <div class="card-body">
                            <h5 class="card-title">Statistics</h5>
                            <div class="stats-list">
                            <div class="stats-info">
    <p>Today <strong>{{ $todayHours }} <small>/ 8 hrs</small></strong></p>
    <div class="progress">
        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($todayHours / 8) * 100 }}%" aria-valuenow="{{ ($todayHours / 8) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>
<div class="stats-info">
    <p>This Week <strong>{{ $thisWeekHours }} <small>/ 40 hrs</small></strong></p>
    <div class="progress">
        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($thisWeekHours / 40) * 100 }}%" aria-valuenow="{{ ($thisWeekHours / 40) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>
<div class="stats-info">
    <p>This Month <strong>{{ $thisMonthHours }} <small>/ 160 hrs</small></strong></p>
    <div class="progress">
        <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($thisMonthHours / 160) * 100 }}%" aria-valuenow="{{ ($thisMonthHours / 160) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>
<div class="stats-info">
    <p>Remaining <strong>{{ $remainingHours }} <small>/ 160 hrs</small></strong></p>
    <div class="progress">
        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($remainingHours / 160) * 100 }}%" aria-valuenow="{{ ($remainingHours / 160) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card recent-activity">
                        <div class="card-body">
                            <h5 class="card-title">Today Activity</h5>
                            <ul class="res-activity-list">
                            @foreach ($todayActivities as $activity)
    @php
        [$punchIn, $punchOut] = explode(', ', $activity);
    @endphp
    <li>
        <p class="mb-0">Punch In at</p>
        <p class="res-activity-time">
            <i class="fa fa-clock-o"></i>
            {{ $punchIn }}
        </p>
    </li>
    <li>
        <p class="mb-0">Punch Out at</p>
        <p class="res-activity-time">
            <i class="fa fa-clock-o"></i>
            {{ $punchOut }}
        </p>
    </li>
@endforeach

</ul>

                        </div>
                    </div>
                </div>
            </div>
        <!-- Search Filter -->
        <!-- /Search Filter -->

        <!-- Table with attendance records -->
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Punch In</th>
                                <th>Punch Out</th>
                                <th>Production</th>
                                <th>Break</th>
                                <th>Overtime</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $key => $attendance)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $attendance->date }}</td>
                                <td>{{ $attendance->punch_in }}</td>
                                <td>{{ $attendance->punch_out }}</td>
                                <td>{{ $attendance->production_hours }} hrs</td>
                                <td>{{ $attendance->break_hours }} hrs</td>
                                <td>{{ $attendance->overtime_hours }} hrs</td>
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
<script>
    function togglePunch() {
        var button = document.getElementById('punch-btn');
        var isPunchedIn = button.innerText === 'Punch Out';
        var newStatus = isPunchedIn ? 'Punch In' : 'Punch Out';

        // Send AJAX request to update punch status
       
    }
</script>

<!-- /Page Wrapper -->
@endsection