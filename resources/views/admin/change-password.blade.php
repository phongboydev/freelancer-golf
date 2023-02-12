@extends('admin.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Đổi mật khẩu | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Đổi mật khẩu | '.Helpers::get_setting('seo_title'),
    'og_description' => Helpers::get_setting('seo_description'),
    'og_url' => Request::url(),
    'og_img' => asset('images/logo_seo.png'),
    'current_url' =>Request::url(),
    'current_url_amp' => ''
);
$seo = WebService::getSEO($data_seo);
?>
@include('admin.partials.seo')
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Đổi mật khẩu</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('index')}}">Home</a></li>
          <li class="breadcrumb-item active">Đổi mật khẩu</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Đổi mật khẩu</h3>
                </div> <!-- /.card-header -->
                <div class="card-body">
                  <form action="{{route('admin.storeChangePassword')}}" method="POST">
                    @csrf
                    @foreach ($errors->all() as $error)
                      <div class="error-msg">{{ $error }}</div>
                    @endforeach
                    <div class="form-group">
                      <label for="current_password">Mật khẩu hiện tại</label>
                      <input type="password" name="current_password" id="current_password" class="form-control">
                    </div>
                    <div class="form-group">
                      <label for="new_password">Mật khẩu mới</label>
                      <input type="password" name="new_password" id="new_password" class="form-control">
                    </div>
                    <div class="form-group">
                      <label for="confirm_password">Xác nhận lại mật khẩu</label>
                      <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                    </div>
                    <div class="form-group">
                      <input type="submit" class="btn btn-primary" value="Đổi mật khẩu">
                    </div>
                  </form>
              </div> <!-- /.card-body -->
            </div><!-- /.card -->
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
</section>
@endsection
