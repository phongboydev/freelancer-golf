@extends('admin.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'List Users | ' . Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'List Users | ' . Helpers::get_setting('seo_title'),
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
                    <h1 class="m-0 text-dark">List Userss</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">List Userss</li>
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
                            <h3 class="card-title">List Userss</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="users-table"></table>
                            </div>
                            <script>
                                $(function () {
                                    let data2 ={!! $list !!};
                                    $('#users-table').DataTable({
                                        data: data2,
                                        columns: [
                                            {title: 'ID', data: 'id'},
                                            {title: 'Name', data: 'name'},
                                            {title: 'Information', data: 'email'},
                                            {title: 'Address', data: 'address'},
                                            {title: 'Affiliate marketing', data: 'referral'},
                                            {title: 'Created', data: 'created_at'}
                                        ],
                                        order: [[5, "desc"]],
                                        columnDefs: [
                                            {//ID
                                                visible: true,
                                                targets: 0,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    return data;
                                                }
                                            },
                                            {// Name
                                                visible: true,
                                                targets: 1,
                                                className: 'text-center a',
                                                render: function (data, type, full, meta) {
                                                    let slug = '';
                                                    if (full.referral !== null) {
                                                        slug = full.referral.slug;
                                                    }
                                                    return '<a href="{{route("admin.dashboard")}}/user/' + full.id + '">' +
                                                        '<b>' + data + '</b></br>' +
                                                        '<b style="color:#c76805;">' + slug + '</b>' +
                                                        '</a>';
                                                }
                                            },
                                            {// Information
                                                visible: true,
                                                targets: 2,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    return '<i class="fas fa-envelope"></i> ' + data + '</br>' + '<i class="fas fa-phone"></i> ' +full.phone;
                                                }
                                            },
                                            {// Address
                                                visible: true,
                                                targets: 3,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    return data;
                                                }
                                            },
                                            {// Affiliate marketing
                                                visible: true,
                                                targets: 4,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    let activated = '<b style="color: red">Inactive</b>';
                                                    if (data !== null) {
                                                        activated = '<b style="color: green">Activated</b>';
                                                    }
                                                    return activated;
                                                }
                                            },
                                            {// Created
                                                visible: true,
                                                targets: 5,
                                                className: "text-center",
                                                render: function (data, type, full, meta) {
                                                    let status = '<b style="color: red">Inactive</b>';
                                                    if (full.status == 1) {
                                                        status = '<b style="color: green">Active</b>';
                                                    }
                                                    return getFormattedDate(data) + '</br>' +status;
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
