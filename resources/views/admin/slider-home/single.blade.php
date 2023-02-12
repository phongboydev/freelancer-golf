@extends('admin.layouts.app')
<?php
if(isset($data_slider)){
    $title = $data_slider->name;
    $post_title = $data_slider->name;
    $link = $data_slider->link;
    $src = $data_slider->src;
    $src_mobile = $data_slider->src_mobile;
    $order = $data_slider->order;
    $status = $data_slider->status;
    $target = $data_slider->target;
    $video_link_slider = $data_slider->video_link_slider;
    $video_link_slider_mobile = $data_slider->video_link_slider_mobile;
    $description = htmlspecialchars_decode($data_slider->description);
    $date_update = $data_slider->updated;
    $sid = $data_slider->id;
} else{
    $title = 'Create Slider';
    $post_title = "";
    $link = '';
    $src = "";
    $src_mobile = "";
    $order = 0;
    $status = \App\Constants\BaseConstants::ACTIVE;
    $target = "_top";
    $video_link_slider = '';
    $video_link_slider_mobile = '';
    $description = '';
    $date_update = date('Y-m-d h:i:s');
    $sid = 0;
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
        <form action="{{route('admin.storeSliderDetail')}}" method="POST" id="frm-create-page" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="sid" value="{{$sid}}">
    	    <div class="row">
    	      	<div class="col-9">
    	        	<div class="card">
    		          	<div class="card-header">
    		            	<h3 class="card-title">Post Page</h3>
    		          	</div> <!-- /.card-header -->
    		          	<div class="card-body">
                            <!-- show error form -->
                            <div class="errorTxt"></div>
                            <div class="form-group">
                                <label for="post_title">Tiêu đề</label>
                                <input type="text" class="form-control title_slugify" id="post_title" name="post_title" placeholder="Tiêu đề" value="{{$post_title}}">
                            </div>
                            <div class="form-group">
                                <label>Upload Images</label>
                                <div class="clear">
                                    <div class="file_csv_up" id="text_input_file">
                                        <input type="text" name="slishow_upload" class="form-control" id="csv_upload_slishow" value="<?php echo $src; ?>" autocomplete="off" />
                                    </div>
                                    <div id="csv_upload_pc" class="csv_tbl_submit_body">
                                        <input type="file" id="csv_slishow" name="csv_slishow" onchange="loadFileSlishow_pc(event)"/>Choose File
                                    </div>
                                </div>
                                <div class="clear mt-1">
                                    <img id="output_slishow_pc" src="<?php echo $src; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Upload Images Mobile</label>
                                <div class="clear">
                                    <div class="file_csv_up" id="text_input_file_mobile">
                                        <input type="text" name="slishow_upload_mobile" class="form-control" id="csv_upload_slishow_mobile" value="<?php echo $src_mobile; ?>" autocomplete="off" />
                                    </div>
                                    <div id="csv_upload_mobile" class="csv_tbl_submit_body">
                                        <input type="file" id="csv_slishow_mobile" name="csv_slishow_mobile" onchange="loadFileSlishow_mobile(event)"/>Choose File
                                    </div>
                                </div>
                                <div class="clear mt-1">
                                    <img id="output_slishow_mobile" src="<?php echo $src_mobile; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order">Order(Sắp xếp)</label>
                                <input id="order" type="text" name="order" class="form-control" value="{{$order}}">
                            </div>
                            <div class="form-group">
                                <label for="link">Link</label>
                                <input id="link" type="text" name="link" class="form-control" value="{{$link}}">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="video_link_slider">Video Link (Youtube, Vimeo)</label>
                                        <input id="video_link_slider" type="text" name="video_link_slider"
                                               class="form-control" value="{{ $video_link_slider }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="video_link_slider_mobile">Video Link Mobile (Youtube, Vimeo)</label>
                                        <input id="video_link_slider_mobile" type="text" name="video_link_slider_mobile"
                                               class="form-control" value="{{ $video_link_slider_mobile }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" cols="30" rows="5">{!! $description !!}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="target">Target</label>
                                <select name="target" id="target" class="selectbox form-control">
                                    <option value="_top" @if($target == "_top") selected @endif>_top</option>
                                    <option value="_blank" @if($target == "_blank") selected @endif>_blank</option>
                                </select>
                            </div>
    		        	</div> <!-- /.card-body -->
    	      		</div><!-- /.card -->
    	    	</div> <!-- /.col-9 -->
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Publish</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioDraft" name="status" value="0" @if($status == 0) checked @endif>
                                    <label for="radioDraft">Draft</label>
                                </div>
                                <div class="icheck-primary d-inline" style="margin-left: 15px;">
                                    <input type="radio" id="radioPublic" name="status" value="1" @if($status == 1) checked @endif>
                                    <label for="radioPublic">Public</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Date:</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" name="created" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{$date_update}}">
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
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
    jQuery(document).ready(function ($){
        $('.slug_slugify').slugify('.title_slugify');

        //Date range picker
        $('#reservationdate').datetimepicker({
            format: 'YYYY-MM-DD hh:mm:ss'
        });

        $('#csv_slishow').change(function(evt) {
            $("#csv_upload_slishow").val($(this).val());
            $("#csv_upload_slishow").attr("value",$(this).val());
        });
        $('#csv_slishow_mobile').change(function(evt) {
           $("#csv_upload_slishow_mobile").val($(this).val());
           $("#csv_upload_slishow_mobile").attr("value",$(this).val());
        });

        CKEDITOR.replace('description',{
            width: '100%',
            resize_maxWidth: '100%',
            resize_minWidth: '100%',
            height:'300',
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
        CKEDITOR.instances['description '];
    });
</script>
@endsection
