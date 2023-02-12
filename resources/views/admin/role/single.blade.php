@extends('admin.layouts.app')
<?php
if (isset($role)) {
    $title = $role->name;
    $role_name = $role->name;
    $date_update = $role->updated_at;
    $id = $role->id;
} else {
    $title = 'Tạo vai trò';
    $role_name = '';
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
                    <h1 class="m-0 text-dark">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{route('admin.storeRoleDetail')}}" method="POST" id="frm-create-page"
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title }}</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body">
                                <!-- show error form -->
                                <div class="errorTxt"></div>
                                <div class="form-group">
                                    <label for="title">Tên vai trò</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title" placeholder="Role Name"
                                           value="{{ $role_name }}">
                                </div>
                                <div class="form-group mt-3 box-bg">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="module">Chức năng</label>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <div class="row">
                                                <div class="col-md-3"><b>Thêm</b></div>
                                                <div class="col-md-3"><b>Xem</b></div>
                                                <div class="col-md-3"><b>Sửa</b></div>
                                                <div class="col-md-3"><b>Xoá</b></div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- List module name --}}
                                    <div id="group_modules">
                                        <?php
                                        $count_module = count($list_module);
                                        $k = 1;
                                        ?>
                                        <input type="hidden" name="count_module" value="{{ $count_module }}">
                                        @foreach($list_module as $module)
                                            <div class="modules pt-2 pb-2" style="border-bottom: 1px dashed #ccc;">
                                                <input type="hidden" value="{{ $module->id }}" name="module_id_{{ $k }}">
                                                <div class="row align-items-center">
                                                    <div class="col-md-6">
                                                        <label for="">{{ $module->title }}</label>
                                                    </div>
                                                    <?php
                                                    $permission = \App\Models\RoleDetail::where('role_id', $id)
                                                        ->where('module_id', $module->id)
                                                        ->first();
                                                    if ($permission) {
                                                        $arr_permission = WebService::convertPermission($permission->permission);
                                                    } else {
                                                        $arr_permission = [];
                                                    }
                                                    ?>
                                                    <div class="col-md-6 text-center">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <input type="checkbox" value="1"
                                                                       @if(in_array('create', $arr_permission)) checked @endif
                                                                       name="permission_create_{{ $k }}"
                                                                       id="permission_create_{{ $k }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox"
                                                                       @if(in_array('read', $arr_permission)) checked @endif
                                                                       value="2" name="permission_read_{{ $k }}"
                                                                       id="permission_read_{{ $k }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" value="4"
                                                                       @if(in_array('update', $arr_permission)) checked @endif
                                                                       name="permission_update_{{ $k }}"
                                                                       id="permission_update_{{ $k }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="checkbox" value="8"
                                                                       @if(in_array('delete', $arr_permission)) checked @endif
                                                                       name="permission_delete_{{ $k }}"
                                                                       id="permission_delete_{{ $k }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php $k++; ?>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-success">Lưu</button>
                                </div>
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div> <!-- /.col-9 -->
                </div> <!-- /.row -->
            </form>
        </div> <!-- /.container-fluid -->
    </section>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            //Date range picker
            $('#reservationdate').datetimepicker({
                format: 'YYYY-MM-DD hh:mm:ss'
            });

            $('#thumbnail_file').change(function (evt) {
                $("#thumbnail_file_link").val($(this).val());
                $("#thumbnail_file_link").attr("value", $(this).val());
            });

            //xử lý validate
            $("#frm-create-page").validate({
                rules: {
                    sub_region_name: "required",
                },
                messages: {
                    sub_region_name: "Enter sub region name",
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
