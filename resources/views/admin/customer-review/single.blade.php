@extends('admin.layouts.app')
<?php
if(isset($detail)){
    $title = $detail->name;
    $name = $detail->name;
    $review = $detail->review;
    $avatar = $detail->avatar;
    $status = $detail->status;
    $id = $detail->id;
} else{
    $title = 'Thêm đánh giá khách hàng';
    $name = '';
    $review = '';
    $status = '';
    $avatar = '';
    $id = 0;
}
?>
@section('seo')
<?php
$data_seo = array(
    'title' => $title.' | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => $title.' | '.Helpers::get_setting('seo_title'),
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
        <form action="{{route('admin.storeCustomerReview')}}" method="POST" id="frm-create-category" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$title}}</h3>
                </div> <!-- /.card-header -->
                <div class="card-body">
                    <!-- show error form -->
                    <div class="errorTxt"></div>
                    <div class="form-group">
                        <label for="name">Tên khách hàng</label>
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="Tên khách hàng" value="{{$name}}">
                    </div>
                    <div class="form-group">
                        <label for="review">Đánh giá</label>
                        <textarea id="review" name="review" class="form-control">{!!$review!!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail_file">Avatar</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="thumbnail_file" class="custom-file-input" id="thumbnail_file" style="display: none;">
                                <input type="text" name="thumbnail_file_link" class="custom-file-link form-control"
                                       id="thumbnail_file_link" value="{{ $avatar }}">
                                <label class="custom-file-label custom-file-label-thumb" for="thumbnail_file"></label>
                            </div>
                        </div>
                        @if($avatar != "")
                            <div class="demo-img" style="padding-top: 15px;">
                                <img src="{{ $avatar }}">
                            </div>
                        @endif
                    </div>

                    <div class="form-group text-center mt-3">
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="radioDraft" name="status" value="1" @if($status == 1) checked @endif>
                            <label for="radioDraft">Draft</label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-left: 15px;">
                            <input type="radio" id="radioPublic" name="status" value="0" @if($status == 0) checked @endif>
                            <label for="radioPublic">Public</label>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div> <!-- /.card-body -->
            </div><!-- /.card -->
        </form>
  	</div> <!-- /.container-fluid -->
</section>
<script type="text/javascript">
    jQuery(document).ready(function ($){
        $('#thumbnail_file').change(function(evt) {
            $("#thumbnail_file_link").val($(this).val());
            $("#thumbnail_file_link").attr("value",$(this).val());
        });
        //xử lý validate
        $("#frm-create-category").validate({
            rules: {
                name: "required",
                review: "required"
            },
            messages: {
                name: "Nhập tên khách hàng.",
                review: "Nhập đánh giá."
            },
            errorElement : 'div',
            errorLabelContainer: '.errorTxt',
            invalidHandler: function(event, validator) {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });
    });
</script>
@endsection
