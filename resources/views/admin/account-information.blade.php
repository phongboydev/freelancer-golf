@extends('admin.layouts.app')
@section('seo')
    <?php
        use App\Libraries\Helpers;
    $data_seo = array(
        'title' => 'Thông tin tài khoản | ' . Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Thông tin tài khoản | ' . Helpers::get_setting('seo_title'),
        'og_description' => Helpers::get_setting('seo_description'),
        'og_url' => Request::url(),
        'og_img' => asset('images/logo_seo.png'),
        'current_url' => Request::url(),
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
                    <h1 class="m-0 text-dark">Thông tin tài khoản</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('index')}}">Home</a></li>
                        <li class="breadcrumb-item active">Thông tin tài khoản</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if(Session::has('success_msg'))
                <div class="mgt-10  alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ Session::get('success_msg') }}
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin tài khoản</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{route('admin.storeAccountInformation')}}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @foreach ($errors->all() as $error)
                                    <div class="error-msg">{{ $error }}</div>
                                @endforeach
                                <div class="form-group text-center">
                                    <div class="avatar-wrapper mb-3">
                                        <img class="profile-pic"
                                             src="{{ asset('images/avatar/' . Request()->admin_info->avatar) }}"/>
                                        <div class="upload-button">
                                            <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                                        </div>
                                        <input class="file-upload" type="file" name="avatar" accept="image/*"/>
                                        <input type="hidden" name="avatar_file_link"
                                               value="{{ Request()->admin_info->avatar }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name">Họ và Tên</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           placeholder="Họ và Tên" value="{{ Request()->admin_info->name }}">
                                </div>

                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                           placeholder="Số điện thoại" value="{{ Request()->admin_info->phone }}">
                                </div>

                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" name="address" id="address" class="form-control"
                                           placeholder="Địa chỉ" value="{{ Request()->admin_info->address }}">
                                </div>

                                <div class="form-group text-center">
                                    <input type="submit" class="btn btn-primary" value="Lưu">
                                </div>
                            </form>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </section>
@endsection
