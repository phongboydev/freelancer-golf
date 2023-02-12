@extends('admin.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'Danh sách tài khoản | ' . Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Danh sách tài khoản | ' . Helpers::get_setting('seo_title'),
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
                    <h1 class="m-0 text-dark">Danh sách tài khoản</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Danh sách tài khoản</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div id="delete_success"></div>
            <div id="delete_error"></div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh sách tài khoản</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="clear">
                                <ul class="nav fl">
                                    <li class="nav-item">
                                        <a class="btn btn-danger" onclick="delete_id('manager')"
                                           href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-primary" href="{{route('admin.createManager')}}"
                                           style="margin-left: 6px;"><i class="fas fa-plus"></i> Create</a>
                                    </li>
                                </ul>
                            </div>
                            <br/>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_index"></table>
                            </div>
                            <script>
                                $(function () {
                                    let data ={!! $list_managers !!};
                                    $('#table_index').DataTable({
                                        data: data,
                                        columns: [
                                            {
                                                title: '<input type="checkbox" id="selectall" onclick="select_all()">',
                                                data: 'id'
                                            },
                                            {title: 'Họ và tên', data: 'name'},
                                            {title: 'Liên hệ', data: 'phone'},
                                            {title: 'Ảnh đại diện', data: 'avatar'},
                                            {title: 'Vai trò', data: 'role.name'}
                                        ],
                                        order: [[1, "desc"]],
                                        columnDefs: [
                                            {//ID
                                                visible: true,
                                                targets: 0,
                                                className: 'text-center',
                                                orderable: false,
                                                render: function (data, type, full, meta) {
                                                    return '<input type="checkbox" id="' + data + '" name="seq_list[]" value="' + data + '">';
                                                }
                                            },
                                            {// Họ và tên
                                                visible: true,
                                                targets: 1,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    return '<a href="{{route("admin.dashboard")}}/manager/' +
                                                        full.id + '"><b>' + data + '</b></a>';
                                                }
                                            },
                                            {// Liên hệ
                                                visible: true,
                                                targets: 2,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    return data + '<br/>' + full.email;
                                                }
                                            },
                                            {// Ảnh đại diện
                                                visible: true,
                                                targets: 3,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    return '<img src="/images/avatar/' + data + '" style="max-width: 200px">';
                                                }
                                            },
                                            {// Vai trò
                                                visible: true,
                                                targets: 4,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    return data;
                                                }
                                            }
                                        ],
                                    });
                                });
                            </script>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </section>
@endsection
