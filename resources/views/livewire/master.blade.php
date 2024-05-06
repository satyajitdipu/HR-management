<div>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<meta name="description" content="SoengSouy Admin Template">
	<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
	<meta name="author" content="SoengSouy Admin Template">
	<meta name="robots" content="noindex, nofollow">
	<title>Dashboard - HRMS</title>
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="{{ URL::to('assets/img/favicon.png') }}">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap.min.css') }}">
	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/font-awesome.min.css') }}">
	<!-- Lineawesome CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/line-awesome.min.css') }}">
	<!-- Datatable CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/dataTables.bootstrap4.min.css') }}">
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/select2.min.css') }}">
	<!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap-datetimepicker.min.css') }}">
	<!-- Chart CSS -->
	<link rel="stylesheet" href="{{ URL::to('ssets/plugins/morris/morris.css') }}">
	<!-- Main CSS -->
	<link rel="stylesheet" href="{{ URL::to('assets/css/style.css') }}">

	{{-- message toastr --}}
	<link rel="stylesheet" href="{{ URL::to('assets/css/toastr.min.css') }}">
	<script src="{{ URL::to('assets/js/toastr_jquery.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/toastr.min.js') }}"></script>
</head>

<body>
	@yield('style')
	<style>    
		.invalid-feedback{
			font-size: 14px;
		}
		.error{
			color: red;
		}
	</style>
	@php
	$notifications=\App\Models\activityLog::where('name',Auth::user()->name)->get();
	
	$messages=\App\Models\Message::where('receiver_id',Auth::id())->whereNull('read_at')->get();
	
	
	@endphp
	<!-- Main Wrapper -->
	<div class="main-wrapper">
		<!-- Loader -->
		<div id="loader-wrapper">
			<div id="loader">
				<div class="loader-ellips">
				  <span class="loader-ellips__dot"></span>
				  <span class="loader-ellips__dot"></span>
				  <span class="loader-ellips__dot"></span>
				  <span class="loader-ellips__dot"></span>
				</div>
			</div>
		</div>
		<!-- /Loader -->

		<!-- Header -->
		<div class="header">
			<!-- Logo -->
			<div class="header-left">
				<a href="{{ route('home') }}" class="logo">
					<img src="{{ URL::to('/assets/images/'. Auth::user()->avatar) }}" width="40" height="40" alt="">
				</a>
			</div>
			<!-- /Logo -->
			<a id="toggle_btn" href="javascript:void(0);">
				<span class="bar-icon">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</a>
			<!-- Header Title -->
			<div class="page-title-box">
				<h3>Hi, {{ Session::get('name') }}</h3>
			</div>
			<!-- /Header Title -->
			<!-- <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a> -->
			<!-- Header Menu -->
			<ul class="nav user-menu">
				<!-- Search -->
				@php
				use App\Models\User;
				@endphp
				<li class="nav-item dropdown">
    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
	<i class="fa fa-comment-o"></i> <span class="badge badge-pill">{{ count($messages) }}</span>
    </a>
    <div class="dropdown-menu notifications">
        <div class="topnav-dropdown-header">
            <span class="notification-title">Messages</span> 
            <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
        </div>
        <div class="noti-content">
            <ul class="notification-list">
                @foreach($messages as $message)
                <li class="notification-message">
				<a href="{{ route('chat', ['query' => $message->conversation_id]) }}">

                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">
                                    <img alt="" src="{{ URL::to('/assets/images/'.Auth::user()->avatar) }}">
                                </span>
                            </div>
                            <div class="list-body">
                                <span class="message-author">{{ User::find($message->sender_id)->name }}</span> 
                                <span class="message-time">{{ $message->created_at->format('d M') }}</span>
                                <div class="clearfix"></div>
                                <span class="message-content">{{ $message->body }}</span> 
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="topnav-dropdown-footer"> <a href="{{ route('index') }}">View all Messages</a> </div>
    </div>
</li>
<li class="nav-item dropdown">
					<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
						<i class="fa fa-bell-o"></i>
						<span class="badge badge-pill">{{count($notifications)}}</span> 
					</a>
					<div class="dropdown-menu notifications">
					<div class="topnav-dropdown-header">
    <span class="notification-title">Notifications</span> 
    <a href="{{ route('clearAllNotifications') }}" class="clear-noti"> Clear All </a> 
</div>

						<div class="noti-content">
				<ul class="notification-list">
    @foreach($notifications as $notification)
    <li class="notification-message">
        <a href="{{ $notification->url }}">
            <div class="media">
                <span class="avatar">
				<img src="{{ URL::to('/assets/images/'. Auth::user()->avatar) }}" width="40" height="40" alt="">
                </span>
                <div class="media-body">
                    <p class="noti-details">
                        <span class="noti-title">{{ $notification->name }}</span>
                        {{ $notification->description }}
						<p class="noti-time">
    <span class="notification-time">{{ \Carbon\Carbon::parse($notification['date_time'])->diffForHumans() }}</span>
</p>

                </div>
            </div>
        </a>
    </li>
    @endforeach
</ul>

</div>
						<!-- <div class="topnav-dropdown-footer"> <a href="activities.html">View all Notifications</a> </div> -->
					</div>
				</li>
				<!-- /Message Notifications -->
				<li class="nav-item dropdown has-arrow main-drop">
					<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
						<span class="user-img">
						<img src="{{ URL::to('/assets/images/'. Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
						<span class="status online"></span></span>
						<span>{{ Session::get('name') }}</span>
					</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="{{ route('profile_user') }}">My Profile</a>
						<a class="dropdown-item" href="{{ route('company/settings/page') }}">Settings</a>
						<a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
					</div>
				</li>
			</ul>
			

				<!-- /Message Notifications -->
				
			<!-- /Header Menu -->

			<!-- Mobile Menu -->
			<div class="dropdown mobile-user-menu">
				<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<i class="fa fa-ellipsis-v"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item" href="{{ route('profile_user') }}">My Profile</a>
					<a class="dropdown-item" href="{{ route('company/settings/page') }}">Settings</a>
					<a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
				</div>
			</div>
			<!-- /Mobile Menu -->

		</div>
		<!-- /Header -->
		<!-- Sidebar -->
		
		<!-- /Sidebar -->
		<!-- Page Wrapper -->

