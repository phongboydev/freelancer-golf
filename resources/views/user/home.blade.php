@extends('user.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Dashboard - '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Dashboard - '.Helpers::get_setting('seo_title'),
    'og_description' => Helpers::get_setting('seo_description'),
    'og_url' => Request::url(),
    'og_img' => Helpers::get_setting('seo_image')
);
$seo = WebService::getSEO($data_seo);
$user = Auth::user();
?>
@include('partials.seo')
@endsection
@section('content')
<div class="my-dashboard">
	<div class="row-title">My Dashboard</div>
	<div class="hello">Hello, <strong>{{ $user->first_name }} {{ $user->last_name }}</strong></div>
</div>
<div class="account_infomation">
	<div class="contact_title padding-tit row-title title_primary_profile">
		<strong>PROFILE</strong>
		<a class="action edit" href="{{ route('user.profile') }}" data-role="change-email-link">Edit</a>
	</div>
	<div class="container_profile_show clear">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="contact_infomation">
					<div class="profile_view_usr clear">
						<div class="item">
							<span class="label">First Name:</span>
							<span class="value">{{ $user->first_name }}</span>
						</div>
						<div class="item">
							<span class="label">Last Name:</span>
							<span class="value">{{ $user->last_name }}</span>
						</div>
						<div class="item">
							<span class="label">Email:</span>
							<span class="value">{{ $user->email }}</span>
						</div>
						<div class="item">
							<span class="label">Password:</span>
							<span class="value">*********</span>
							<span>
                				<a href="{{route('user.changePasswordForm')}}" data-role="change-password" class="action change-password">Reset Password</a>
            				</span>
						</div>
						<div class="item">
							<span class="label">INTRODUCE</span>
							<span class="value">
								{{ $user->about_me }}
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="address_book">
	<div class="row-title title_primary_profile"><strong>ADDRESS</strong></div>
	<div class="container_profile_show clear">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="contact_infomation clear">
					<div class="contact_title padding-tit">DELIVERY ADDRESS</div>
					<div class="address-cnt">{{ $user->address }}</div>
				</div>
			</div>

			<?php /* <div class="col-lg-6 col-md-6 col-sm-12">
				<div class="contact_infomation">
					<div class="contact_title padding-tit">DEFAULT SHIPPING ADDRESS</div>
					<div class="address-cnt">abcd</div>
				</div>
			</div> */?>
		</div>
	</div>
</div>
<div class="address_book">
	<div class="row-title title_primary_profile"><strong>CREDIT CARDS</strong></div>
	<div class="container_profile_show clear">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card_infomation">
					Currently, we will not save your payment card
				</div>
			</div>

			<?php /* <div class="col-lg-6 col-md-6 col-sm-12">
				<div class="contact_infomation">
					<div class="contact_title padding-tit">DEFAULT SHIPPING ADDRESS</div>
					<div class="address-cnt">abcd</div>
				</div>
			</div> */?>
		</div>
	</div>
</div>
@endsection
