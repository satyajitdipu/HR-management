@extends('layouts.app')
@section('content')
    <div class="main-wrapper">	
        <div class="error-box">
            <h1></h1>
            <h3><i class="fa fa-warning"></i> Work on progress </h3>
            <p>The page you requested was under progress</p>
            <a href="{{ route('home') }}" class="btn btn-custom">Back to Home</a>
        </div>
    </div>
@endsection