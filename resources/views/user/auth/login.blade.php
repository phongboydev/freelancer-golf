@extends('layouts.app_login')
@section('seo')
<?php
$title='Login | '.Helpers::get_setting('seo_title');
$description='Login | '.Helpers::get_setting('seo_description');
$keyword=Helpers::get_setting('seo_keyword');
$thumb_img_seo=asset(Helpers::get_setting('seo_image'));
$data_seo = array(
    'title' => $title,
    'keywords' => $keyword,
    'description' =>$description,
    'og_title' => $title,
    'og_description' => $description,
    'og_url' => Request::url(),
    'og_img' => $thumb_img_seo,
    'current_url' =>Request::url(),
    'current_url_amp' => Request::url()
    //route('amp.category.list',array($data->slug))
);
$seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')
<div class="container">
   <div class="d-flex justify-content-center h-100">
      <div class="card">
         <div class="card-header text-center text-uppercase">
            <h3>Login</h3>
         </div>
         <div class="card-body">
            @if (count($errors) >0)
               @foreach($errors->all() as $error)
                 <div class="text-danger"> {{ $error }}</div>
               @endforeach
            @endif
            @if(session('status'))
               <div class="text-danger"> {{ session('status') }}</div>
            @endif
            <form class="form-horizontal" method="POST" action="{{route('loginCustomerAction')}}">
               {{ csrf_field() }}
               <div class="input-group form-group">
                  <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fas fa-user"></i></span>
                  </div>
                  <input type="text" name="email" class="form-control" placeholder="Username or Email" value="{{ old('email') }}" required autofocus>
               </div>
               <div class="input-group form-group">
                  <div class="input-group-prepend">
                     <span class="input-group-text"><i class="fas fa-key"></i></span>
                  </div>
                  <input type="password" class="form-control" placeholder="Password" name="password" required>
               </div>
               <div class="form-group text-center">
                  <input type="submit" value="Login" class="btn login_btn">
               </div>
               <div class="d-flex justify-content-center social_icon">
                  <span>
                     <a href="{{route('facebook.login')}}" class="btn btn-primary">
                        <i class="fab fa-facebook-f"></i>&nbsp; Facebook
                     </a>
                  </span>
                  <span>
                     <a href="{{route('google.login',array('google'))}}" class="btn btn-danger">
                        <i class="fab fa-google"></i>&nbsp; Google
                     </a>
                  </span>
               </div>
            </form>
         </div>
         <div class="card-footer">
            <div class="d-flex justify-content-center links">
               Do not have an account?<a href="{{ route('registerCustomer') }}">Sign up</a>
            </div>
            <div class="d-flex justify-content-center">
               <a href="{{route('forgetPassword')}}">Forgot password?</a>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
