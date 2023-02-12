@extends('admin.layouts.app')
<?php
    use App\Constants\BaseConstants;

    $user_role_id = Request()->user_role['role_id'];
    $is_super_admin = ($user_role_id == BaseConstants::SUPER_ADMIN_ROLE_ID) ? true : false;
    if(isset($post_detail)){
        $title = $post_detail->title;
        //khai báo biến có trong post

        $post_title = $post_detail->title;
        $post_title_en = $post_detail->title_en;
        $post_slug = $post_detail->slug;
        $post_description = $post_detail->description;
        $post_description_en = $post_detail->description_en;
        $post_content = $post_detail->content;
        $post_content_en = $post_detail->content_en;
        $post_order = $post_detail->order_short;
        $gallery_checked = $post_detail->gallery_checked;
        $store_gallery = (isset($post_detail->gallery_images) || $post_detail->gallery_images != "")?unserialize($post_detail->gallery_images) :'';
        $status = $post_detail->status;
        $thumbnail = $post_detail->thumbnail;
        $thumbnail_alt = $post_detail->thumbnail_alt;
        $date_update = $post_detail->updated;

        //khai báo biến SEO
        $seo_title = $post_detail->seo_title;
        $seo_keyword = $post_detail->seo_keyword;
        $seo_description = $post_detail->seo_description;
        $sid = $post_detail->id;

        $data_cats=\Illuminate\Support\Facades\DB::table('categories')
                  ->join('join_category_post','categories.categoryID','=','join_category_post.id_category')
                  ->where('join_category_post.id_post', $sid)
                  ->select('categories.categoryName','categories.categorySlug','categories.categoryID')
                  ->first();
        $link_url_check="";
        $link_url_demo = '';
        if($data_cats):
            $slug_cat = $data_cats->categorySlug;
            $link_url_check = route('single.detail', array($slug_cat,$post_detail->slug));
            $link_url_demo = route('admin.draftDetails', array($slug_cat,$post_detail->slug));
        endif;
    } else{
        $title = 'Viết bài mới';
        $post_title = "";
        $post_title_en = "";
        $post_slug = "";
        $post_description = "";
        $post_description_en = "";
        $post_content = "";
        $post_content_en = "";
        $post_order = 0;
        $gallery_checked = 0;
        $store_gallery = "";
        $status = BaseConstants::ACTIVE;
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
        <form action="{{route('admin.storePostDetail')}}" method="POST" id="frm-create-post" enctype="multipart/form-data">
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

                            @if ($errors->any())
                                <div class="mgt-10 alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

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
                                        <label for="post_title">Tiêu đề</label>
                                        <input type="text" class="form-control title_slugify" id="post_title" name="post_title" placeholder="Tiêu đề" value="{{$post_title}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="post_slug">Slug</label>
                                        <input type="text" class="form-control slug_slugify" id="post_slug" name="post_slug" placeholder="Slug" value="{{$post_slug}}">
                                        @if ($sid > 0)
                                            <b style="color: #0000cc;">Demo Link:</b> <u><i><a  style="color: #F00;" href="{{ $link_url_check }}" target="_blank">{{ $link_url_check }}</a></i></u>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="post_description">Trích dẫn</label>
                                        <textarea id="post_description" name="post_description">{!!$post_description!!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="post_content">Nội dung</label>
                                        <textarea id="post_content" name="post_content">{!!$post_content!!}</textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                    <div class="form-group">
                                        <label for="post_title_en">Title</label>
                                        <input type="text" class="form-control" id="post_title_en" name="post_title_en" placeholder="Title" value="{{$post_title_en}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="post_description_en">Description</label>
                                        <textarea id="post_description_en" name="post_description_en">{!!$post_description_en!!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="post_content_en">Content</label>
                                        <textarea id="post_content_en" name="post_content_en">{!!$post_content_en!!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="post_order" class="title_txt">Sắp xếp (Số càng lớn thứ tự càng cao)</label>
                                <input type="text" name="post_order" id="post_order" value="{{$post_order}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="gallery_checked" style="color: #FF0000;">Gallery Checked</label>
                                <input id="gallery_checked" type="checkbox" name="gallery_checked" value="1" @if($gallery_checked == 1) checked @endif>
                            </div>
                            <!--Post Gallery-->
                            <div class="form-group">
                                <label>Post Gallery</label>
                                @if(empty($store_gallery))
                                <div class="content_gallery_list_images">
                                    <input class="gallery_item_txt" type="hidden" value="1" name="gallery_item_count" autocomplete="off"/>
                                    <div class="clear content_add_item_images">
                                        <div class="group_item_images clear">
                                            <div class="inside clear">
                                                <div class="icon_change_postion"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                <div class="text_input_file_image">
                                                    <input class="myfile_gallery_store form-control" type="text" value="" size="50" name="upload_gallery1" />
                                                </div>
                                                <div class="mybutton_upload_img">
                                                    <input class="upload_gallery_file" type="file" onchange="set_link_gallery(this);" name="upload_gallery_file1">Upload
                                                </div>
                                            </div>
                                        </div><!--group_item_images-->
                                    </div><!--content_add_item_images-->
                                    <div class="clear add_link add_part_images">
                                        <a class="add r">+ Add Image</a>
                                    </div>
                                </div>
                                @else
                                <div class="content_gallery_list_images">
                                    <input class="gallery_item_txt" type="hidden" value="<?php echo count($store_gallery); ?>" name="gallery_item_count" autocomplete="off"/>
                                    <div class="clear content_add_item_images">
                                        <?php for($j=0;$j<count($store_gallery);$j++): $n=$j+1; ?>
                                            <div class="group_item_images clear">
                                                <div class="inside clear">
                                                    <div class="icon_change_postion"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                    <div class="text_input_file_image">
                                                        <input class="myfile_gallery_store form-control" type="text" value="<?php echo $store_gallery[$j]; ?>" size="50" name="upload_gallery<?php echo $n; ?>" />
                                                    </div>
                                                    <div class="mybutton_upload_img">
                                                        <input class="upload_gallery_file" type="file" onchange="set_link_gallery(this);" name="upload_gallery_file<?php echo $n; ?>">Upload
                                                    </div>
                                                </div>
                                            </div><!--group_item_images-->
                                        <?php endfor; ?>
                                    </div><!--content_add_item_images-->
                                    <div class="clear add_link add_part_images">
                                        <a class="add r">+ Add Image</a>
                                    </div>
                                </div>
                                @endif
                                <script type="text/javascript">
                                    jQuery(document).ready(function($){
                                        $('.add_part_images').delegate('a.add', 'click', function() {
                                            var count_images_gallery=$(this).parent().parent().find('.content_add_item_images').find('.group_item_images').length+1;
                                            var html_add_item_gallery='<div class="group_item_images clear">'+
                                                '<div class="inside clear">'+
                                                '<div class="icon_change_postion"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>'+
                                                '<div class="text_input_file_image">'+
                                                '<input class="myfile_gallery_store form-control" type="text" value="" size="50" name="upload_gallery'+count_images_gallery+'" />'+
                                                '</div>'+
                                                '<div class="mybutton_upload_img">'+
                                                '<input class="upload_gallery_file" type="file" onchange="set_link_gallery(this);" name="upload_gallery_file'+count_images_gallery+'">Upload'+
                                                '</div>'+
                                                '</div>'+
                                                '</div><!--group_item_images-->';
                                            $('.content_gallery_list_images .content_add_item_images').append(html_add_item_gallery);
                                            $(this).parent().parent().parent().find(".gallery_item_txt").val(count_images_gallery);

                                        });

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
                                    function set_link_gallery(elm){
                                          var fn = $(elm).val();
                                          $(elm).parent().parent().find('.myfile_gallery_store').val(fn);
                                          $(elm).parent().parent().find('.myfile_gallery_store').attr("value",fn);
                                    }
                                </script>
                            </div>
                            <!--End Post Gallery-->
                            <!--SEO-->
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
                                    <input type="radio" id="radioPublic" name="status" value="1" @if($status == 1) checked @endif >
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
                            <h3 class="card-title">Thể loại tin</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <?php
                                $list_cate = App\Models\Category::orderBy('categories.categoryName', 'ASC')->select('categories.categoryName', 'categories.categoryID')->get();
                                if($sid != 0){
                                    $data_checks = App\Models\Join_Category_Post::where('id_post', $post_detail->id)->get();
                                }
                            ?>
                            <div class="list_category">
                                @foreach($list_cate as $cate)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input category_item_input" value="{{$cate->categoryID}}" name="category_item[]" id="category_item_{{$cate->categoryID}}"
                                    <?php
                                    if($sid != 0):
                                        foreach($data_checks as $data_check):
                                            $seq_check = $data_check->id_category;
                                            if($seq_check == $cate->categoryID):
                                               echo "checked='checked'";
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                    >
                                    <label class="custom-control-label" for="category_item_{{$cate->categoryID}}">{{$cate->categoryName}}</label>
                                </div>
                                @endforeach
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
                                <input type="text" class="form-control" id="post_thumb_alt" value="{{$thumbnail_alt}}" name="post_thumb_alt" placeholder="Thumbnail Alt">
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
                                    <img src="{{asset('images/article/'.$thumbnail)}}">
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

        $('#thumbnail_file').change(function(evt) {
            $("#thumbnail_file_link").val($(this).val());
            $("#thumbnail_file_link").attr("value",$(this).val());
        });

        //xử lý validate
        $("#frm-create-post").validate({
            rules: {
                post_title: "required",
                'category_item[]': { required: true, minlength: 1 }
            },
            messages: {
                post_title: "Nhập tiêu đề tin",
                'category_item[]': "Chọn thể loại tin",
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

    CKEDITOR.replace('post_description',{
        width: '100%',
        resize_maxWidth: '100%',
        resize_minWidth: '100%',
        height:'300',
        filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
    });
    CKEDITOR.instances['post_description'];

    CKEDITOR.replace('post_description_en',{
        width: '100%',
        resize_maxWidth: '100%',
        resize_minWidth: '100%',
        height:'300',
        filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
    });
    CKEDITOR.instances['post_description_en'];
</script>
@endsection
