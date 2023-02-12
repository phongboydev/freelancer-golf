@extends('admin.layouts.app')
<?php
if(isset($post_category)){
    $title = $post_category->categoryName;
    $post_title = $post_category->categoryName;
    $post_title_en = $post_category->categoryName_en;
    $post_slug = $post_category->categorySlug;
    $post_description = $post_category->categoryDescription;
    $post_description_en = $post_category->categoryDescription_en;
    $post_short = $post_category->categoryShort;
    $categoryIndex = (isset($post_category->categoryIndex) || $post_category->categoryIndex != "") ? $post_category->categoryIndex : '0';
    $parent_id = $post_category->categoryParent;
    $status = $post_category->status;
    $thumbnail = $post_category->thumbnail;
    $thumbnail_alt = $post_category->thumbnail_alt;
    $date_update = $post_category->updated;
    //khai báo biến SEO
    $seo_title = $post_category->seo_title;
    $seo_keyword = $post_category->seo_keyword;
    $seo_description = $post_category->seo_description;
    $sid = $post_category->categoryID;
} else{
    $title = 'Thêm thể loại tin';
    $post_title = '';
    $post_title_en = '';
    $post_slug = '';
    $post_description = '';
    $post_description_en = '';
    $categoryIndex = 0;
    $post_short = 0;
    $parent_id = 0;
    $status = \App\Constants\BaseConstants::ACTIVE;
    $thumbnail = "";
    $thumbnail_alt = "";
    $date_update = date('Y-m-d h:i:s');
    $seo_title = "";
    $seo_keyword = "";
    $seo_description = "";
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
        <form action="{{route('admin.storePostCategoryDetail')}}" method="POST" id="frm-create-category" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="sid" value="{{$sid}}">
    	    <div class="row">
    	      	<div class="col-9">
    	        	<div class="card">
    		          	<div class="card-header">
    		            	<h3 class="card-title">{{$title}}</h3>
    		          	</div> <!-- /.card-header -->
    		          	<div class="card-body">
                            <!-- show error form -->
                            <div class="errorTxt"></div>
                            <ul class="nav nav-tabs" id="tabLang" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="vi-tab" data-toggle="tab" href="#vi" role="tab" aria-controls="vi" aria-selected="true">Tiếng việt</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">Tiếng Anh</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="vi" role="tabpanel" aria-labelledby="vi-tab">
                                    <div class="form-group">
                                        <label for="post_title">Tiêu đề thể loại tin</label>
                                        <input type="text" class="form-control title_slugify" id="post_title" name="post_title" placeholder="Tiêu đề" value="{{$post_title}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="post_slug">Slug thể loại tin</label>
                                        <input type="text" class="form-control slug_slugify" id="post_slug" name="post_slug" placeholder="Slug" value="{{$post_slug}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="post_description">Trích dẫn</label>
                                        <textarea id="post_description" name="post_description">{!!$post_description!!}</textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                    <div class="form-group">
                                        <label for="post_title_en">Title category</label>
                                        <input type="text" class="form-control" id="post_title_en" name="post_title_en" placeholder="Title" value="{{$post_title_en}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="post_description_en">Description category</label>
                                        <textarea id="post_description_en" name="post_description_en">{!!$post_description_en!!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="template_checkID" class="title_txt">Chọn thể loại Cha</label>
                                <?php
                                    $list_cate = App\Models\Category::where('categoryParent', 0)->get();
                                ?>
                                <select class="custom-select mr-2" name="category_parent">
                                    <option value="0" <?php if( $parent_id == 0): ?> selected <?php endif; ?> >== Không có ==</option>
                                    @foreach($list_cate as $cate)
                                        <option value="{{$cate->categoryID}}"
                                            <?php
                                                if($parent_id > 0){
                                                    if($parent_id == $cate->categoryID){
                                                        echo "selected";
                                                    }
                                                }
                                            ?>
                                        >{{$cate->categoryName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="post_title">Sắp xếp(Càng lớn càng nằm trên cùng)</label>
                                <input type="text" class="form-control" id="post_short" name="post_short" value="{{$post_short}}">
                            </div>
                            <div class="form-group">
                                <label for="categoryIndex" style="color: #FF0000;">Hiển thị lên Menu</label>
                                <input id="categoryIndex" type="checkbox" name="categoryIndex" value="1" @if($categoryIndex == 1) checked @endif>
                            </div>
                            @include('admin.form-seo.seo')
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Image Thumbnail</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="post_title">Thumbnail Alt</label>
                                <input type="text" class="form-control" id="post_thumb_alt" name="post_thumb_alt" value="{{$thumbnail_alt}}" placeholder="Thumbnail Alt">
                            </div>
                            <div class="form-group">
                                <label for="thumbnail_file">File input</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="thumbnail_file" class="custom-file-input" id="thumbnail_file" style="display: none;">
                                        <input type="text" name="thumbnail_file_link" class="custom-file-link form-control" id="thumbnail_file_link" value="{{$thumbnail}}">
                                        <label class="custom-file-label custom-file-label-thumb" for="thumbnail_file"></label>
                                    </div>
                                </div>
                                @if($thumbnail != "")
                                <div class="demo-img" style="padding-top: 15px;">
                                    <img src="{{asset('images/category/'.$thumbnail)}}">
                                </div>
                                @endif
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

        $('#post_description').summernote({
            placeholder: 'Nhập trích dẫn',
            tabsize: 2,
            focus: true,
            height: 200,
            codemirror: { // codemirror options
                theme: 'monokai'
            }
        });

        $('#post_description_en').summernote({
            placeholder: 'Enter your description',
            tabsize: 2,
            focus: true,
            height: 200,
            codemirror: { // codemirror options
                theme: 'monokai'
            }
        });

        $('#thumbnail_file').change(function(evt) {
            $("#thumbnail_file_link").val($(this).val());
            $("#thumbnail_file_link").attr("value",$(this).val());
        });

        //xử lý validate
        $("#frm-create-category").validate({
            rules: {
                post_title: "required",
            },
            messages: {
                post_title: "Nhập tiêu đề thể loại tin",
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
<script type="text/javascript">
	CKEDITOR.replace('post_content',{
		width: '100%',
		resize_maxWidth: '100%',
		resize_minWidth: '100%',
		height:'300',
		filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
	});
	CKEDITOR.instances['post_content'];

    CKEDITOR.replace('post_content_en',{
        width: '100%',
        resize_maxWidth: '100%',
        resize_minWidth: '100%',
        height:'300',
        filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
    });
    CKEDITOR.instances['post_content_en'];
</script>
@endsection
