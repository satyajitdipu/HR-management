@extends('layouts.master')
@section('content')
@php
use App\Models\AddJob
@endphp
{!! Toastr::message() !!}
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title"> Jobs Status</h3>
                       
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
        
            <!-- Content Starts -->
         
        
           

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Job Title</th>
                                    <th>Department</th>
                                    <th>Start Date</th>
                                    <th>Expire Date</th>
                                    <th class="text-center">Job Type</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Payment</th>
                                    <th class="text-center">Offer Status Or Selection Status</th>
                                    <th class="text-center">Download Document</th>
                                    
                                   
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($job_list as $key => $items)
                                    <tr>
                                    
                                        <td>{{ ++$key }}</td>
                                        
                                        <td><a href="{{ url('form/job/view/'.$items->job_id) }}">{{ $items->job_title }}</a></td>
                                        <td>{{ AddJob::where('job_title',$items->job_title)->get()[0]->department }}</td>
                                        <td>{{ date('d F, Y',strtotime($items->start_date)) }}</td>
                                        <td>{{ date('d F, Y',strtotime($items->expired_date)) }}</td>
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> {{ AddJob::where('job_title',$items->job_title)->get()[0]->job_type}}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> {{ $items->status }}
                                                </a>
                                            </div>
                                        </td>
                                       
                                        <td class="text-center">
                                        @if($items->round==null)
    <div class="card card-default">
        <div class="card-body text-center">
            
            <form id="paymentForm" action="{{ route('razorpay.payment.store') }}" method="POST">
                @csrf 
                <input type="hidden" name="id" value="{{ $items->job_id }}">
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

                                        </td>
                                      
                                        @if($items->status=="Hired")
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> {{ $items->offer_status }}
                                                </a>
                                            </div>
                                        </td>
                                        @endif
                                        @if($items->status=="Interviewed")
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> {{ $items->status_selection }}
                                                </a>
                                            </div>
                                        </td>
                                       @endif
                                       @if($items->status=="Interviewed")
                                        <td class="text-center">
                                            <div class="action-label">
                                            <a href="{{ url('interview/view/'.$items->id)  }}"  target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-download mr-1"></i> Interview letter </a>
                                            </div>
                                        </td>
                                       @endif
                                       @if($items->status=="Hired")
                                        <td class="text-center">
                                            <div class="action-label">
                                            <a href="{{ url('interview/view/'.$items->id)  }}" class="btn btn-sm btn-primary"><i class="fa fa-download mr-1"></i> Interview letter</a>
                                            <a href="{{ url('offer-letter/'.$items->id)  }}" class="btn btn-sm btn-primary"><i class="fa fa-download mr-1"></i> Offer letter</a>
                                            </div>
                                        </td>
                                       @endif
                                      
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
    
    <!-- Delete Employee Modal -->
    <div class="modal custom-modal fade" id="delete_employee" role="dialog">
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
    <!-- /Delete Employee Modal -->
@endsection