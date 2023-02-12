@extends('admin.layouts.app')
<?php
use App\Constants\BaseConstants;
if(isset($detail)){
    $title = $detail->name;
    $name = $detail->name;
    $name_en = $detail->name_en;
    $slug = $detail->slug;
    $description = $detail->description;
    $description_en = $detail->description_en;
    $content = $detail->content;
    $content_en = $detail->content_en;
    $sort = $detail->sort;
    $show_in_home = $detail->show_in_home;
    $galleries = ($detail->galleries != '') ? json_decode($detail->galleries, true) : [];
    $parent_id = $detail->categoryParent;
    $status = $detail->status;
    $thumbnail = $detail->thumbnail;
    $thumbnail_alt = $detail->thumbnail_alt;
    $date_update = $detail->updated_at;
    //khai báo biến SEO
    $seo_title = $detail->seo_title;
    $seo_keyword = $detail->seo_keyword;
    $seo_description = $detail->seo_description;
    $id = $detail->id;
} else{
    $title = 'Thêm thể loại sản phẩm';
    $name = '';
    $name_en = '';
    $slug = '';
    $description = '';
    $description_en = '';
    $content = '';
    $content_en = '';
    $sort = 0;
    $show_in_home = 0;
    $galleries = [];
    $parent_id = 0;
    $status = BaseConstants::ACTIVE;
    $thumbnail = "";
    $thumbnail_alt = "";
    $date_update = date('Y-m-d h:i:s');
    $seo_title = "";
    $seo_keyword = "";
    $seo_description = "";
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
        <form action="{{route('admin.storeProductCategory')}}" method="POST" id="frm-create-category" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$id}}">
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
                                        <label for="name">Tên danh mục</label>
                                        <input type="text" class="form-control title_slugify" id="name" name="name" placeholder="Tên danh mục" value="{{ $name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="post_slug">Slug danh mục</label>
                                        <input type="text" class="form-control slug_slugify" id="slug" name="slug" placeholder="Slug" value="{{ $slug }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Trích dẫn</label>
                                        <textarea id="description" name="description">{!! $description !!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="content">Nội dung</label>
                                        <textarea id="content" name="content">{!! $content !!}</textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                    <div class="form-group">
                                        <label for="name_en">Category Name</label>
                                        <input type="text" class="form-control" id="name_en" name="name_en" placeholder="Category Name" value="{{ $name_en }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="description_en">Description</label>
                                        <textarea id="description_en" name="description_en">{!! $description_en !!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="content_en">Content</label>
                                        <textarea id="content_en" name="content_en">{!! $content_en !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="template_checkID" class="title_txt">Chọn thể loại Cha</label>
                                <select class="custom-select mr-2" name="category_parent">
                                    <option value="0" @if($parent_id == 0) selected @endif >== Không có ==</option>
                                    @if(count($listCategories)>0)
                                        {!! WebService::showOptionCategory($listCategories, $parent_id, 0); !!}
                                    @endif
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="show_in_home" style="color: #bd3bff;">Hiện thị lên Trang chủ</label>
                                        <input id="show_in_home" type="checkbox" name="show_in_home" value="1"
                                               @if($show_in_home == 1) checked @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sort">Sắp xếp(Càng lớn càng nằm trên cùng)</label>
                                <input type="text" class="form-control" id="sort" name="sort" value="{{ $sort }}">
                            </div>

                            <!--********************************************Gallery**************************************************-->
                            <!--Post Gallery-->
                            <div class="form-group">
                                <label>Gallery</label>
                                @if(count($galleries) == 0)
                                    <div class="content_gallery_list_images">
                                        <input class="gallery_item_txt" type="hidden" value="1" name="gallery_item_count" autocomplete="off"/>
                                        <div class="clear content_add_item_images">
                                            <div class="group_item_images clear">
                                                <div class="inside clear">
                                                    <div class="icon_change_postion"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                    <div class="icon_change_postion image_view"><img width="20" height="20" src="https://dummyimage.com/20x20/000/fff"></div>
                                                    <div class="text_input_file_image">
                                                        <input class="myfile_gallery_store form-control myfile_gallery_default" type="text" value="" size="50" name="upload_gallery0" />
                                                    </div>
                                                    <div class="mybutton_upload_img">
                                                        <input class="upload_gallery_file" type="file" onchange="set_link_gallery(this);" multiple name="upload_gallery_file0[]">Upload
                                                    </div>
                                                </div>
                                            </div><!--group_item_images-->
                                        </div><!--content_add_item_images-->
                                        <!--<div class="clear add_link add_part_images">
                                            <a class="add r">+ Add Image</a>
                                        </div>-->
                                    </div>
                                @else
                                    <div class="content_gallery_list_images">
                                        <input class="gallery_item_txt" type="hidden" value="{!! count($galleries) !!}" name="gallery_item_count" autocomplete="off"/>
                                        <div class="clear content_add_item_images">
                                            <?php for($j=0; $j < count($galleries); $j++):
                                                $n = $j+1;
                                            ?>
                                            <div class="group_item_images clear">
                                                <div class="inside clear">
                                                    <div class="icon_change_postion"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                    <div class="icon_change_postion image_view image_show_demo">
                                                        <a target="_blank" class="html5lightbox" href="{{ asset('images/category/' . $galleries[$j]) }}">
                                                            <img width="50" height="auto" src="{{ asset('images/category/' . $galleries[$j]) }}" alt="">
                                                        </a>
                                                    </div>
                                                    <div class="space_input_demo text_input_file_image">
                                                        <input class="myfile_gallery_store form-control" type="text" value="{{ $galleries[$j] }}" size="50" name="upload_gallery{{ $n }}" />
                                                    </div>
                                                    <div class="mybutton_upload_img hidden">
                                                        <input class="upload_gallery_file" type="file" onchange="set_link_gallery(this);" multiple name="upload_gallery_file{{ $n }}">Upload
                                                    </div>
                                                </div>
                                            </div><!--group_item_images-->
                                            <?php endfor; ?>
                                            <div class="group_item_images clear">
                                                <div class="inside clear">
                                                    <div class="icon_change_postion image_view"><img width="20" height="auto" src="https://dummyimage.com/20x20/000/fff"></div>
                                                    <div class="text_input_file_image">
                                                        <input class="myfile_gallery_store form-control myfile_gallery_default" type="text" value="" size="50" name="upload_gallery0" />
                                                    </div>
                                                    <div class="mybutton_upload_img">
                                                        <input class="upload_gallery_file" type="file" onchange="set_link_gallery(this);" multiple name="upload_gallery_file0[]">Upload
                                                    </div>
                                                </div>
                                            </div><!--group_item_images-->
                                        </div><!--content_add_item_images-->
                                        <!--<div class="clear add_link add_part_images">
                                            <a class="add r">+ Add Image</a>
                                        </div>-->
                                    </div>
                                @endif
                                <script type="text/javascript">
                                    jQuery(document).ready(function($){
                                        $(".content_add_item_images").sortable({
                                            stop: function(event, ui){
                                                var cnt = 1;
                                                $(this).children('.group_item_images').each(function(){
                                                    $(this).find('input.myfile_gallery_store').attr('name','upload_gallery'+cnt);
                                                    //.val(cnt);
                                                    cnt++;
                                                });
                                            }
                                        });
                                    });

                                    var fileCollection = new Array();
                                    var count_images_gallery=$('.upload_gallery_file').length;

                                    window.set_link_gallery = function (input) {
                                        if (input.files && input.files[0]) {
                                            var add_item_gallery_select_muti="";
                                            $(input.files).each(function (i, file) {
                                                $('.create_news_item_img').remove();
                                                fileCollection.push(file);
                                                var reader = new FileReader();
                                                reader.readAsDataURL(this);
                                                reader.onload = function (e) {
                                                    add_item_gallery_select_muti='<div class="group_item_images clear create_news_item_img">'+
                                                        '<div class="inside clear">'+
                                                        '<div class="icon_change_postion"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'+
                                                        '<div class="icon_change_postion image_view image_show_demo"><a class="html5lightbox"><img width="50" height="auto" src="'+e.target.result+'"></a></div>'+
                                                        '<div class="space_input_demo text_input_file_image">'+
                                                        '<input class="myfile_gallery_store form-control" type="text" value="'+file.name+'" size="50" name="upload_gallery'+parseInt(count_images_gallery+i)+'" />'+
                                                        '</div>'+
                                                        '<div class="mybutton_upload_img hidden">'+
                                                        '<input class="upload_gallery_file" type="file" onchange="set_link_gallery(this);"  attb="'+i+'" multiple name="upload_gallery_file'+parseInt(count_images_gallery+i)+'">Upload'+
                                                        '</div>'+
                                                        '</div>'+
                                                        '</div><!--group_item_images-->';
                                                    $('.content_gallery_list_images .content_add_item_images').prepend(add_item_gallery_select_muti);
                                                    var cnt = 1;
                                                    $('.content_add_item_images').children('.group_item_images').each(function(){
                                                        $(this).find('input.myfile_gallery_store').not('.myfile_gallery_default').attr('name','upload_gallery'+cnt);
                                                        cnt++;
                                                    });
                                                    $(".gallery_item_txt").val($('.upload_gallery_file').length-1);
                                                }
                                            });
                                        }
                                    }

                                    function set_link_gallery(elm){
                                        var fileCollection = new Array();
                                        var fn = $(elm).val();
                                        $(elm).parent().parent().find('.myfile_gallery_store').val(fn);
                                        $(elm).parent().parent().find('.myfile_gallery_store').attr("value",fn);
                                    }

                                    function set_link_icon(elm){
                                        var fileCollection = new Array();
                                        var fn = $(elm).val();

                                        $(elm).parent().parent().find('.myfile_icon_store').val(fn);
                                        $(elm).parent().parent().find('.myfile_icon_store').attr("value",fn);
                                    }

                                    function prepareUpload(event){
                                        var files = event.target.files;
                                        var fileName = files[0].name;
                                        alert(fileName);
                                    }
                                </script>
                            </div>
                            <!--End Post Gallery-->
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
                                    <input type="text" name="created" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{ $date_update }}">
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

        //if(isset($detail)){
        @if(isset($detail))

        @else
            $('.slug_slugify').slugify('.title_slugify');
        @endif
        //Date range picker
        $('#reservationdate').datetimepicker({
            format: 'YYYY-MM-DD hh:mm:ss'
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
                post_title: "Nhập tiêu đề thể loại sản phẩm",
            },
            errorElement : 'div',
            errorLabelContainer: '.errorTxt',
            invalidHandler: function(event, validator) {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });

        CKEDITOR.replace('content',{
            width: '100%',
            resize_maxWidth: '100%',
            resize_minWidth: '100%',
            height:'300',
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
        CKEDITOR.instances['content'];

        CKEDITOR.replace('content_en',{
            width: '100%',
            resize_maxWidth: '100%',
            resize_minWidth: '100%',
            height:'300',
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
        CKEDITOR.instances['content_en'];

        CKEDITOR.replace('description',{
            width: '100%',
            resize_maxWidth: '100%',
            resize_minWidth: '100%',
            height:'200',
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
        CKEDITOR.instances['description'];

        CKEDITOR.replace('description_en',{
            width: '100%',
            resize_maxWidth: '100%',
            resize_minWidth: '100%',
            height:'200',
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
        CKEDITOR.instances['description_en'];
    });
</script>
@endsection
