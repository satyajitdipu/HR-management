
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
                    <div class="col-sm-12">
                        <h3 class="page-title">Payments</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Payments</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">	
                        <table class="table table-striped custom-table datatable mb-0">
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
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
 
    @section('script')
    @endsection
@endsection
