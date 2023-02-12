@extends('user.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Change Password - '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Change Password - '.Helpers::get_setting('seo_title'),
    'og_description' => Helpers::get_setting('seo_description'),
    'og_url' => Request::url(),
    'og_img' => asset(Helpers::get_setting('seo_image')),
);
$seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')
<div class="page-title">
    <h1>Change Password</h1>
</div>
<form action="{{route('user.changePassword')}}" method="POST" class="update_password">
   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    @foreach ($errors->all() as $error)
        <div class="error-msg">{{ $error }}</div>
    @endforeach
   <div class="row">
      <div class="col-md-12 pr-1">
         <div class="form-group">
            <label for="current_password">Current password</label>
            <input type="password" class="form-control" required name="current_password" id="current_password"
                   placeholder="Current password">
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12 pr-1">
         <div class="form-group">
            <label for="new_password">New password</label>
            <input type="password" class="form-control" name="new_password" id="new_password" required
                   placeholder="New password">
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12 pr-1">
         <div class="form-group">
            <label for="confirm_password">Confirm password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required
                   placeholder="Confirm password">
         </div>
      </div>
   </div>
   <div class="row">
         <div class="col-md-12 pr-1 btn_edit_form">
            <div class="form-group">
               <input type="submit" class="form-control btn_submit" name="btn_submit" value="Change Password">
            </div>
         </div>
      </div>
</form>
@endsection
