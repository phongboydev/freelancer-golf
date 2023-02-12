@extends('admin.layouts.app')
<?php
if (isset($module)) {
    $title = $module->title;
    $module_name = $module->title;
    $date_update = $module->updated_at;
    $id = $module->id;
} else {
    $title = 'Create Module';
    $module_name = '';
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
            <form action="{{route('admin.storeModuleDetail')}}" method="POST" id="frm-create-page"
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
                                    <label for="title">Module Name</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title" placeholder="Module Name"
                                           value="{{ $module_name }}">
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-success">Save</button>
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
