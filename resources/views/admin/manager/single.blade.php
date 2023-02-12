@extends('admin.layouts.app')
<?php
if (isset($manager_detail)) {
    $title = $manager_detail->name;
    $name = $manager_detail->name;
    $email = $manager_detail->email;
    $phone = $manager_detail->phone;
    $address = $manager_detail->address;
    $role_id = $manager_detail->role_id;
    $id = $manager_detail->id;
} else {
    $title = 'Tạo tài khoản quản lý';
    $name = "";
    $email = "";
    $phone = "";
    $address = "";
    $role_id = "";
    $restaurant_id = '';
    $date_update = date('Y-m-d h:i:s');
    $id = 0;
}
?>
@section('seo')
    <?php
    $data_seo = array(
        'title' => $title . ' | ' . Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => $title . ' | ' . Helpers::get_setting('seo_title'),
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
                    <h1 class="m-0 text-dark">{{$title}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{route('admin.postManagerDetail')}}" method="POST" id="frm-create-page"
                  enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$id}}">
                @if(Session::has('success_msg'))
                    <div class="mgt-10  alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{ Session::get('success_msg') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $name  }}</h3>
                    </div> <!-- /.card-header -->
                    <div class="card-body">
                        <!-- show error form -->
                        <div class="errorTxt"></div>
                        <div class="form-group">
                            <label for="name">Họ và tên</label>
                            <input type="text" class="form-control" id="name"
                                   name="name" placeholder="Họ và tên"
                                   value="{{ $name }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email/Username</label>
                            <input type="text" class="form-control" id="email"
                                   name="email" placeholder="Email or Username"
                                   value="{{ $email }}">
                        </div>
                        @if($id == 0)
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password"
                                   name="password" placeholder="Password"
                                   value="">
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại"
                                   value="{{ $phone }}">
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Địa chỉ"
                                   value="{{ $address }}">
                        </div>
                        <div class="form-group">
                            <label for="role_id">Vai trò</label>
                            <select name="role_id" id="role_id" class="form-control">
                                @foreach($list_roles as $role)
                                    <option value="{{ $role->id }}" @if($role_id == $role->id) selected @endif>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-success">Lưu</button>
                        </div>
                    </div> <!-- /.card-body -->
                </div><!-- /.card -->
            </form>
        </div> <!-- /.container-fluid -->
    </section>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            //xử lý validate
            $("#frm-create-page").validate({
                rules: {
                    name: "required",
                },
                messages: {
                    name: "Nhập tên tài khoản",
                },
                errorElement: 'div',
                errorLabelContainer: '.errorTxt',
                invalidHandler: function (event, validator) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                }
            });
        });
    </script>
@endsection
