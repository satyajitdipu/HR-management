@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
       @php
        $a=App\Models\ApplyForJob::where('job_id',$job_view[0]->id)->where('user_id',Auth::user()->id);
        $job=$a->get()->first();
       @endphp
        <!-- /Header -->

        <!-- Page Wrapper -->
        <div class="page-wrapper job-wrapper">
            <!-- Page Content -->
            <div class="content container">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Jobs</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Jobs</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @if($a->exists())
            <div class="container">
    <div class="row justify-content-left">
        <div class="col-md-8">
            <div class="mb-3">
            @if ($job->status != 'Rejected')
            <div class="progress">
            
    <div class="progress-bar {{ $job->status == 'New' ? 'bg-dark' : 'bg-secondary' }}" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">New</div>
    <div class="progress-bar {{ $job->status == 'Pending' ? 'bg-primary' : 'bg-secondary' }}" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Pending</div>
    <div class="progress-bar {{ $job->status == 'Interviewed' ? 'bg-warning' : 'bg-secondary' }}" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Interview</div>
    <div class="progress-bar {{ $job->status == 'Offered' ? 'bg-info' : 'bg-secondary' }}" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Offered</div>
    <div class="progress-bar {{ $job->status == 'Hired' ? 'bg-success' : 'bg-secondary' }}" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Hired</div>
</div>

@else
<div class="alert alert-danger" role="alert">
    Application Rejected
</div>
@endif

            </div>
        </div>
    </div>
</div>
@endif


                <!-- /Page Header -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="job-info job-widget">
                            <h3 class="job-title">{{ $job_view[0]->job_title }}</h3>
                            <span class="job-dept">{{ $job_view[0]->department }}</span>
                            <ul class="job-post-det">
                                <li><i class="fa fa-calendar"></i> Post Date: <span class="text-blue">{{ date('d F, Y',strtotime($job_view[0]->start_date)) }}</span></li>
                                <li><i class="fa fa-calendar"></i> Last Date: <span class="text-blue">{{ date('d F, Y',strtotime($job_view[0]->expired_date)) }}</span></li>
                                <li><i class="fa fa-user-o"></i> Applications: <span class="text-blue">4</span></li>
                                <li><i class="fa fa-eye"></i> Views: <span class="text-blue">
                                    @if (!empty($job_view[0]->count))
                                        {{ $job_view[0]->count }}
                                        @else
                                        0
                                    @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="job-content job-widget">
                            <div class="job-desc-title"><h4>Job Description</h4></div>
                            <div class="job-description">
                                <p>{!!nl2br ($job_view[0]->description) !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="job-det-info job-widget">
                        @if($a->exists())
    <span class="already-applied">Already Applied</span>
   @if($a->get()->first()->round==null)
    <div class="card card-default">
        <div class="card-body text-center">
            
            <form id="paymentForm" action="{{ route('razorpay.payment.store') }}" method="POST">
                @csrf 
                <input type="hidden" name="id" value="{{ $job_view[0]->id }}">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <script src="https://checkout.razorpay.com/v1/checkout.js"
                        data-key="{{ env('RAZORPAY_KEY') }}"
                        data-amount="59900"
                        data-buttontext="Pay 599 INR"
                        data-name="HRMS"
                        data-description="Razorpay payment"
                        data-image="/images/logo-icon.png"
                        data-prefill.name="{{Auth::user()->name}}"
                        data-prefill.email="{{Auth::user()->email}}"
                        data-theme.color="#8a8888">
                </script>
                <button id="rzp-button1" type="submit" class="btn btn-primary">Pay Now</button>
            </form>
        </div>
    </div>
    @else
    <div class="card card-default">
        <div class="card-body text-center">
            
        <p>Already Paid</p>
        </div>
    </div>
   
    @endif
@else
    <a class="btn job-btn" href="#" data-toggle="modal" data-target="#apply_job">Apply For This Job</a>
@endif
                            <div class="info-list">
                                <span><i class="fa fa-bar-chart"></i></span>
                                <h5>Job Type</h5>
                                <p>{{ $job_view[0]->job_type }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-money"></i></span>
                                <h5>Salary</h5>
                                <p>{{ $job_view[0]->salary_from }}$ - {{ $job_view[0]->salary_to }}$</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-suitcase"></i></span>
                                <h5>Experience</h5>
                                <p>{{ $job_view[0]->experience }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-ticket"></i></span>
                                <h5>Vacancy</h5>
                                <p>{{ $job_view[0]->no_of_vacancies }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-map-signs"></i></span>
                                <h5>Location</h5>
                                <p>{!!nl2br($job_view[0]->job_location) !!}</p>
                            </div>
                            <div class="info-list">
                                <p class="text-truncate"> 096-566-666
                                <br> <a href="https://www.nettantra.com" title="satyajit.sahoo@nettantra.net">satyajit.sahoo@nettantra.net</a>
                                <br> <a href="https://www.nettantra.com/" target="_blank" title="https://www.nettantra.com/">https://www.nettantra.com</a>
                                </p>
                            </div>
@php
    use Carbon\Carbon;

    $now = Carbon::now();
    $expiredDate = Carbon::parse($job_view[0]->expired_date);
    $diff = $expiredDate->diff($now);
@endphp

<div class="info-list text-center">
<div class="info-list text-center">
    <a id="timer" class="app-ends" href="#">
        Application ends in {{ $diff->days }}d {{ $diff->h }}h {{ $diff->i }}m {{ $diff->s }}s
    </a>
</div>


</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->

            <!-- Apply Job Modal -->
            <div class="modal custom-modal fade" id="apply_job" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Your Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="apply_jobs" action="{{ route('form/apply/job/save') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="hidden" name="job_title" value="{{ $job_view[0]->job_title }}">
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
            <!-- /Apply Job Modal -->


        </div>
        <!-- /Page Wrapper -->
    </div>
    <!-- /Main Wrapper -->
    @section('script')
    <script>
        $('#apply_jobs').validate({  
            rules: {  
                name: 'required',  
                phone: 'required',
                email: 'required',    
                message: 'required',    
                cv_upload: 'required',    
            },  
            messages: {
                name: 'Please input your name',  
                phone: 'Please input your phone number',  
                email: 'Please input your email',  
                message: 'Please input your message',  
                cv_upload: 'Please upload your cv',  
            },  
            submitHandler: function(form) {  
                form.submit();
            }  
        });  
    </script>
    <script>
    // Set the end time of the timer
    let endTime = new Date('{{  $expiredDate}}').getTime();
console.log(endTime);
    // Update the timer every second
    let timerInterval = setInterval(function() {
        let now = new Date().getTime();
        console.log(now);
        let distance = endTime - now;
        console.log(distance);
        // Calculate days, hours, minutes, and seconds
        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Update the timer display
        document.getElementById('timer').innerText = `Application ends in ${days}d ${hours}h ${minutes}m ${seconds}s`;

        // If the countdown is finished, display a message and stop the timer
        if (distance < 0) {
            clearInterval(timerInterval);
            document.getElementById('timer').innerText = 'Application has ended';
        }
    }, 1000); // Update every second
</script>
@if ($errors->any())
    <script>
        $(document).ready(function() {
            $('#add_employee').modal('show');
        });
    </script>
@endif
    @endsection
@endsection
