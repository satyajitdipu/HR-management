@extends('layouts.exportmaster')

@section('content')
    <style>
        .page-header {
            margin-left: -222px;
        }
      
    </style>

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid" id="app">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Interview Letter</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('form/salary/page') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Interview Letter</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-white" style="color: red"><i class="fa fa-file-pdf-o"></i> <a href="{{ url("/generate-pdf/$users->id") }}">PDF</a></button>
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="payslip-title">
                                @if(isset($users->interview))
                                    @php
                                        $interview = json_decode($users->interview, true);
                                    @endphp
                                    @if(is_array($interview) && count($interview) > 0)
                                        Interview Date: {{ \Carbon\Carbon::parse($interview[0]['date'])->format('d F, Y') }} <br>
                                        Interview Time: 
                                        @php
                                            $times = explode('-', $interview[0]['time']);
                                            $startTime = \Carbon\Carbon::parse(trim($times[0]))->format('h:i A');
                                        @endphp
                                        {{ $startTime }}
                                    @endif
                                @endif
                            </h4>
                            <div class="row">
                                <div class="col-sm-6 m-b-20">
                                  
                                    <ul class="list-unstyled mb-0">
                                        <li>{{ $users->user()->get()->first()->name }}</li>
                                        <li>{{ $users->user()->get()->first()->position }}</li>
                                        <li>User ID: {{ $users->user()->get()->first()->user_id }}</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6 m-b-20">
                                    <div class="invoice-details">
                                        <h3 class="text-uppercase">Interview Letter #49029</h3>
                                        <ul class="list-unstyled">
                                            @if(isset($user->interview) && is_array($user->interview) && count($user->interview) > 0)
                                                <div>
                                                    Interview Date: {{ \Carbon\Carbon::parse($user->interview[0]['date'])->format('d F, Y') }} <br>
                                                    Interview Time: {{ \Carbon\Carbon::parse($user->interview[0]['time'])->format('h:i A') }}
                                                </div>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 m-b-20">
                                    <p><strong>Dear {{ $users->user()->get()->first()->name }},</strong></p>
                                    <p>We are pleased to invite you for an interview for the position of {{ $users->user()->get()->first()->position }}. The interview is scheduled to be held on {{ \Carbon\Carbon::now()->format('d F, Y') }} at our office premises. Please arrive 15 minutes before the scheduled time.</p>
                                    <p>Kindly bring the following documents with you:</p>
                                    <ul>
                                        <li>Resume/CV</li>
                                        <li>Identity proof (such as Aadhar Card, Passport, etc.)</li>
                                        <li>Photographs</li>
                                        <li>Any other relevant documents</li>
                                    </ul>
                                    <p>If you have any questions or need further information, please feel free to contact us. We look forward to meeting you.</p>
                                    <p>Best regards,</p>
                                    <p>Your Name</p>
                                    <p>HR Manager</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection
