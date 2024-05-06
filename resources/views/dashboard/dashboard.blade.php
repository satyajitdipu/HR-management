@php
use Carbon\Carbon;
use App\Models\Task;
@endphp
@extends('layouts.master')
@section('content')
<?php  
        $hour   = date ("G");
        $minute = date ("i");
        $second = date ("s");
        $msg = " Today is " . date ("l, M. d, Y.");

        if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59) {
            $greet = "Good Morning,";
        } else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59) {
            $greet = "Good Day,";
        } else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59) {
            $greet = "Good Afternoon,";
        } else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59) {
            $greet = "Good Evening,";
        } else {
            $greet = "Welcome,";
        }
    ?>
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">{{ $greet }} Welcome, {{ Session::get('name') }}!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body"> <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{count($project)}}</h3> <span>Projects</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body"> <span class="dash-widget-icon"><i class="fa fa-usd"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{count($client)}}</h3> <span>Clients</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body"> <span class="dash-widget-icon"><i class="fa fa-diamond"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{count($task)}}</h3> <span>Tasks</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body"> <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{count($employee)}}</h3> <span>Employees</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-md-6 text-center">
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Total Revenue</h3>
            <canvas id="barChart"></canvas> <!-- Correct ID for the canvas -->
        </div>
    </div>
</div>
    <div class="col-md-6 text-center">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Sales Overview</h3>
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
</div>
        <div class="row">
            <div class="col-md-12">
                <div class="card-group m-b-30">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <span class="d-block">New Employees</span>
                                </div>
                                <div>
                                    @php
                                    $newEmployeesCount = $employee->where('created_at', '>=',
                                    now()->startOfDay())->count();
                                    @endphp
                                    <div>
                                        <span class="text-success"> {{ $newEmployeesCount }}</span>
                                    </div>

                                </div>
                            </div>
                            <h3 class="mb-3">{{ $newEmployeesCount }}</h3>
                            <div class="progress mb-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="width: {{ ($newEmployeesCount / ($employee->count() ?: 1)) * 100 }}%;"
                                    aria-valuenow="{{ $newEmployeesCount }}" aria-valuemin="0"
                                    aria-valuemax="{{ $employee->count() }}"></div>
                            </div>
                            <p class="mb-0">Overall Employees: {{ $employee->count() }}</p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div> <span class="d-block">Earnings</span> </div>
                                @php
                                $currentMonthEarnings = $project->sum('price');
                                $previousMonthEarnings = $project->whereBetween('created_at', [
                                Carbon::now()->subMonth()->startOfMonth(),
                                Carbon::now()->subMonth()->endOfMonth()
                                ])->sum('price');

                                $denominator = $previousMonthEarnings + $currentMonthEarnings;
                                $percentageChange = $denominator !== 0 ? ($currentMonthEarnings / $denominator) * 100 :
                                0;

                                @endphp
                                <div> <span class="text-success">{{ number_format($percentageChange, 2) }}%</span>
                                </div>
                            </div>
                            <h3 class="mb-3">{{ $currentMonthEarnings }}</h3>
                            <div class="progress mb-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="width: {{ $percentageChange > 0 ? $percentageChange : 0 }}%;"
                                    aria-valuenow="{{ $percentageChange }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="mb-0">Previous Month <span class="text-muted">{{ $previousMonthEarnings }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="card">
    @php
    $currentMonthEarnings =$expance->sum('amount');
    $previousMonthEarnings = $expance->whereBetween('created_at',
    [Carbon::now()->subMonth()->startOfMonth(),
    Carbon::now()->subMonth()->endOfMonth()])->sum('price');

    $denominator = $previousMonthEarnings + $currentMonthEarnings;
    $percentageChange = $denominator !== 0 ? ($currentMonthEarnings / $denominator) * 100 : 0;
    @endphp
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <div> <span class="d-block">Expenses</span> </div>
            <div> <span class="text-danger">{{ number_format($percentageChange, 2) }}%</span> </div>
        </div>
        <h3 class="mb-3">{{ $expance->sum('amount') }}</h3>
        <div class="progress mb-2" style="height: 5px;">
            <div class="progress-bar bg-primary" role="progressbar"
                style="width: {{ $percentageChange }}%;" aria-valuenow="{{ $percentageChange }}" aria-valuemin="0"
                aria-valuemax="100"></div>
        </div>
        <p class="mb-0">Previous Month <span class="text-muted">{{ $previousMonthEarnings }}</span> </p>
    </div>
</div>
<div class="card">
    <div class="card-body">
        @php
        $currentMonthEarnings = $project->sum('price') - $expance->sum('amount');
        $previousMonthEarnings = $project->whereBetween('created_at',
        [Carbon::now()->subMonth()->startOfMonth(),
        Carbon::now()->subMonth()->endOfMonth()])->sum('price') - $expance->whereBetween('created_at',
        [Carbon::now()->subMonth()->startOfMonth(),
        Carbon::now()->subMonth()->endOfMonth()])->sum('price');

        $denominator = $previousMonthEarnings + $currentMonthEarnings;
        $percentageChange = $denominator !== 0 ? ($currentMonthEarnings / $denominator) * 100 : 0;
        @endphp
        <div class="d-flex justify-content-between mb-3">
            <div> <span class="d-block">Profit</span> </div>
            <div> <span class="text-danger">{{ number_format($percentageChange, 2) }}%</span> </div>
        </div>
        <h3 class="mb-3">{{ $currentMonthEarnings }}</h3>
        <div class="progress mb-2" style="height: 5px;">
            <div class="progress-bar bg-primary" role="progressbar"
                style="width: {{ $percentageChange }}%;" aria-valuenow="{{ $percentageChange }}" aria-valuemin="0"
                aria-valuemax="100"></div>
        </div>
        <p class="mb-0">Previous Month <span class="text-muted">{{ $previousMonthEarnings }}</span> </p>
    </div>
</div>
</div>
</div>
</div>

        {{-- message --}}
        {!! Toastr::message() !!}
        <!-- Statistics Widget -->
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-4 d-flex">
                <div class="card flex-fill dash-statistics">
                    <div class="card-body">
                        <h5 class="card-title">Statistics</h5>
                        <div class="stats-list">
                            <div class="stats-info">
                                <p>Today Leave <strong>{{count($count)}} <small>/ {{count($leave)}}</small></strong></p>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{count($leave)}}%" aria-valuenow="31" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="stats-info">
                                <p>Pending Invoice <strong>15 <small>/ 92</small></strong></p>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 31%"
                                        aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="stats-info">
                                <p>Completed Projects <strong>85 <small>/ 112</small></strong></p>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 62%"
                                        aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="stats-info">
                                <p>Open Tickets <strong>190 <small>/ 212</small></strong></p>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 62%"
                                        aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="stats-info">
                                <p>Closed Tickets <strong>22 <small>/ 212</small></strong></p>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 22%"
                                        aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
    <div class="card flex-fill">
        <div class="card-body">
            <h4 class="card-title">Task Statistics</h4>
            <div class="statistics">
                <div class="row">
                    <div class="col-md-6 col-6 text-center">
                        <div class="stats-box mb-4">
                            <p>Total Tasks</p>
                            <h3>{{ count($task) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-6 col-6 text-center">
                        <div class="stats-box mb-4">
                            <p>Overdue Tasks</p>
                            <h3>{{ $task->where('task_status', 'Overdue Tasks')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            @php
            $totalTasks = count($task);
            $completedTasks = $task->where('task_status', 'Completed Tasks')->count();
            $inprogressTasks = $task->where('task_status', 'Inprogress Tasks')->count();
            $onHoldTasks = $task->where('task_status', 'On Hold Tasks')->count();
            $pendingTasks = $task->where('task_status', 'Pending Tasks')->count();
            $reviewTasks = $task->where('task_status', 'Review Tasks')->count();

            $totalPercentage = $totalTasks !== 0 ? 100 : 0;
            $completedPercentage = $totalTasks !== 0 ? ($completedTasks / $totalTasks) * 100 : 0;
            $inprogressPercentage = $totalTasks !== 0 ? ($inprogressTasks / $totalTasks) * 100 : 0;
            $onHoldPercentage = $totalTasks !== 0 ? ($onHoldTasks / $totalTasks) * 100 : 0;
            $pendingPercentage = $totalTasks !== 0 ? ($pendingTasks / $totalTasks) * 100 : 0;
            $reviewPercentage = $totalTasks !== 0 ? ($reviewTasks / $totalTasks) * 100 : 0;
            @endphp
            <div class="progress mb-4">
                <div class="progress-bar bg-purple" role="progressbar"
                    style="width: {{ $completedPercentage }}%" aria-valuenow="{{ $completedPercentage }}"
                    aria-valuemin="0" aria-valuemax="100">{{ $completedPercentage }}%</div>
                <div class="progress-bar bg-warning" role="progressbar"
                    style="width: {{ $inprogressPercentage }}%" aria-valuenow="{{ $inprogressPercentage }}"
                    aria-valuemin="0" aria-valuemax="100">{{ $inprogressPercentage }}%</div>
                <div class="progress-bar bg-success" role="progressbar"
                    style="width: {{ $onHoldPercentage }}%" aria-valuenow="{{ $onHoldPercentage }}"
                    aria-valuemin="0" aria-valuemax="100">{{ $onHoldPercentage }}%</div>
                <div class="progress-bar bg-danger" role="progressbar"
                    style="width: {{ $pendingPercentage }}%" aria-valuenow="{{ $pendingPercentage }}"
                    aria-valuemin="0" aria-valuemax="100">{{ $pendingPercentage }}%</div>
                <div class="progress-bar bg-info" role="progressbar"
                    style="width: {{ $reviewPercentage }}%" aria-valuenow="{{ $reviewPercentage }}"
                    aria-valuemin="0" aria-valuemax="100">{{ $reviewPercentage }}%</div>
            </div>
            <div>
                <p><i class="fa fa-dot-circle-o text-purple mr-2"></i>Completed Tasks <span
                        class="float-right">{{ $completedTasks }}</span></p>
                <p><i class="fa fa-dot-circle-o text-warning mr-2"></i>Inprogress Tasks <span
                        class="float-right">{{ $inprogressTasks }}</span></p>
                <p><i class="fa fa-dot-circle-o text-success mr-2"></i>On Hold Tasks <span
                        class="float-right">{{ $onHoldTasks }}</span></p>
                <p><i class="fa fa-dot-circle-o text-danger mr-2"></i>Pending Tasks <span
                        class="float-right">{{ $pendingTasks }}</span></p>
                <p class="mb-0"><i class="fa fa-dot-circle-o text-info mr-2"></i>Review Tasks <span
                        class="float-right">{{ $reviewTasks }}</span></p>
            </div>
        </div>
    </div>
</div>

            <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <h4 class="card-title">Today Absent <span class="badge bg-inverse-danger ml-2">{{ count($count)
                                }}</span></h4>
                        @foreach ($count as $employees)
                        <div class="leave-info-box">
                            <div class="media align-items-center">
                                <a href="profile.html" class="avatar"><img alt="" src="assets/img/user.jpg"></a>
                                <div class="media-body">

                            
                                    <div class="text-sm my-0">{{$employee->where('employee_id',$employees->employee_id)->first()->name}}</div>
                                </div>
                            </div>
                            <div class="row align-items-center mt-3">
                                <div class="col-6">
                                <h6 class="mb-0">{{ \Carbon\Carbon::parse($employees->from_date)->format('Y-m-d') }}</h6>
<h6 class="mb-0">{{ \Carbon\Carbon::parse($employees->to_date)->format('Y-m-d') }}</h6>

<span
                                        class="text-sm text-muted">Leave Date</span>
                                </div>
                                <div class="col-6 text-right"> <span
                                        class="badge bg-inverse-{{ $employees->status == 'Pending' ? 'danger' : 'success' }}">{{
                                        $employees->status }}</span> </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="load-more text-center"> <a class="text-dark" href="javascript:void(0);">Load
                                More</a> </div>
                    </div>
                </div>
            </div>

            <!-- /Statistics Widget -->
            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="card card-table flex-fill">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Invoices</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-nowrap custom-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Client</th>
                                            <th>Due Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="invoice-view.html">#INV-0001</a></td>
                                            <td>
                                                <h2><a href="#">Global Technologies</a></h2>
                                            </td>
                                            <td>11 Mar 2019</td>
                                            <td>$380</td>
                                            <td> <span class="badge bg-inverse-warning">Partially Paid</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><a href="invoice-view.html">#INV-0002</a></td>
                                            <td>
                                                <h2><a href="#">Delta Infotech</a></h2>
                                            </td>
                                            <td>8 Feb 2019</td>
                                            <td>$500</td>
                                            <td>
                                                <span class="badge bg-inverse-success">Paid</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><a href="invoice-view.html">#INV-0003</a></td>
                                            <td>
                                                <h2><a href="#">Cream Inc</a></h2>
                                            </td>
                                            <td>23 Jan 2019</td>
                                            <td>$60</td>
                                            <td>
                                                <span class="badge bg-inverse-danger">Unpaid</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('form/invoice/reports/page')}}">View all invoices</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex">
                    <div class="card card-table flex-fill">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Payments</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table table-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Client</th>
                                            <th>Payment Type</th>
                                            <th>Paid Date</th>
                                            <th>Paid Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices as $invoice)
                                        <tr>
                                            <td><a href="">{{ $invoice->r_payment_id }}</a></td>
                                            <td>
                                                <h2><a href="#">{{ $invoice->user()->first()->name }}</a></h2>
        
                                            </td>
                                            @php
                                            $data = json_decode($invoice->json_response, true);
                                            // dd($data["\x00*\x00attributes"]['method']);
                                            @endphp
                                            <td>{{ $data["\x00*\x00attributes"]['method']}}</td>
                                            <td>{{ $invoice->created_at->format('d M Y') }}</td>
                                            <td>${{ $invoice->amount }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{route('payments')}}">View all payments</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <div class="card card-table flex-fill">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Clients</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($client as $client)
<tr>
    <td>
        <h2 class="table-avatar">
            <a href="#" class="avatar"><img alt="" src="{{ $client->avatar }}"></a>
            <a href="">{{ $client->name }} <span>{{ $client->role }}</span></a>
        </h2>
    </td>
    <td>{{ $client->email }}</td>
    <td>
    <div class="dropdown action-label">
    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-dot-circle-o {{ $client->status === 'active' ? 'text-success' : 'text-danger' }}"></i>
    {{ $client->status }}
</a>

        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('status_form_active_{{ $client->id }}').submit();">
                <i class="fa fa-dot-circle-o text-success"></i> Active
            </a>
            <form id="status_form_active_{{ $client->id }}" action="{{ route('update_status', ['status' => 'active']) }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="id" value="{{ $client->id }}">
                <!-- Add any other hidden inputs you need -->
            </form>
            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('status_form_inactive_{{ $client->id }}').submit();">
                <i class="fa fa-dot-circle-o text-danger"></i> Inactive
            </a>
            <form id="status_form_inactive_{{ $client->id }}" action="{{ route('update_status', ['status' => 'inactive']) }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="id" value="{{ $client->id }}">
                    <!-- Add any other hidden inputs you need -->
                </form>
            </div>
        </div>
    </td>
    <td class="text-right">
        <div class="dropdown dropdown-action">
            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <i class="material-icons">more_vert</i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item edit-client" href="#" data-toggle="modal" data-target="#editClientModal" data-clientid="{{ $client->id }}" data-clientname="{{ $client->name }}" data-clientemail="{{ $client->email }}" data-clientstatus="{{ $client->status }}">
                    <i class="fa fa-pencil m-r-5"></i> Edit
                </a>
                <a class="dropdown-item edit-client" href="#" data-toggle="modal" data-target="#deleteClientModal" data-clientid="{{ $client->id }}">
    <i class="fa fa-pencil m-r-5"></i> Delete
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
                    <div class="card-footer"> <a href="{{route('/home/client')}}">View all clients</a> </div>
                </div>
            </div>
<!-- Delete Modal -->
<div class="modal fade" id="deleteClientModal" tabindex="-1" role="dialog"
    aria-labelledby="editClientModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteClientModalLabel">Edit Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editClientForm" method="POST" action="{{ route('/home/client/delete') }}">
                    @csrf
                    <input type="hidden" id="clientId" name="clientId">
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveClientChanges">Delete
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
            <!-- Edit Modal -->
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
            <div class="col-md-6 d-flex">
                <div class="card card-table flex-fill">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Recent Projects</h3>
                    </div>
                    <div class="card-body">
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
                                                <span>{{ $project->open_tasks }}</span> <span class="text-muted">open
                                                    tasks, </span>
                                                <span>{{ $project->completed_tasks }}</span> <span
                                                    class="text-muted">tasks completed</span>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="progress progress-xs progress-striped">
                                                <div class="progress-bar" role="progressbar" data-toggle="tooltip"
                                                    title="{{ $percentage }}%" style="width: {{ $percentage }}%"></div>
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
                    <div class="card-footer">
                        <a href="{{route('/home/project')}}">View all projects</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="editProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="editProjectForm" method="POST" action="{{route('/home/projects/edit')}}">
                    <div class="modal-body">
                        <input type="hidden" name="project_id" id="edit_project_id">
                        <div class="form-group">
                            <label for="edit_project_name">Project Name</label>
                            <input type="text" class="form-control" id="edit_project_name" name="project_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_project_description">Description</label>
                            <textarea class="form-control" id="edit_project_description" name="project_description"
                                rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_project_status">Status</label>
                            <select class="form-control" id="edit_project_status" name="project_status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveClientChanges">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>

    <!-- /Page Content -->
</div>
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

        // Edit project button click event
        $('.edit-project').click(function () {
            var projectId = $(this).data('projectid');
            var projectName = $(this).data('projectname');
            var projectDescription = $(this).data('projectdescription');
            var projectStatus = $(this).data('projectstatus');

            $('#edit_project_id').val(projectId);
            $('#edit_project_name').val(projectName);
            $('#edit_project_description').val(projectDescription);
            $('#edit_project_status').val(projectStatus);
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Mock data for demonstration
    const totalRevenueData = [@foreach($data as $value){{ json_encode($value) }},@endforeach];
    const labels = [@foreach($labels as $label)"{{$label}}",@endforeach];
    const salesOverviewData = [10, 20, 30, 40, 50];

    // Bar chart
    var ctxBar = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Revenue',
                data: totalRevenueData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Line chart
    var ctxLine = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Sales Overview',
                data: salesOverviewData,
                fill: false,
                borderColor: 'rgba(255, 99, 132, 1)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection