@extends('admin.layouts.app')
<?php
    use App\Constants\BaseConstants;
    use App\Models\ProductCategory;

    if(isset($detail)){
        $title = $detail->title;
        $product_title = $detail->title;
        $product_title_en = $detail->title_en;
        $slug = $detail->slug;
        $description = $detail->description;
        $description_en = $detail->description_en;
        $content = $detail->content;
        $content_en = $detail->content_en;
        $sort = $detail->sort;
        $sku = $detail->sku;
        $price_origin = $detail->price_origin;
        $price_promotion = $detail->price_promotion;
        $start_event = $detail->start_event;
        $end_event = $detail->end_event;
        $store_status = $detail->store_status;
        $galleries = ($detail->gallery_images != "") ? unserialize($detail->gallery_images) : '';
        $group_variable_product = ($detail->group_variable_product != "") ? json_decode($detail->group_variable_product) : '';
        $status = $detail->status;
        $thumbnail = $detail->thumbnail;
        $thumbnail_alt = $detail->thumbnail_alt;
        $date_update = $detail->updated_at;
        $id_category_primary= $detail->category_primary_id;
        //khai báo biến SEO
        $seo_title = $detail->seo_title;
        $seo_keyword = $detail->seo_keyword;
        $seo_description = $detail->seo_description;
        $id = $detail->id;

        $data_cats = ProductCategory::join('join_category_product','product_categories.id', 'join_category_product.category_id')
            ->where('join_category_product.product_id', $id)
            ->select('product_categories.name','product_categories.slug','product_categories.id')
            ->first();
        $link_url_check = "";
        $link_url_demo = "";
        if ($data_cats) {
            $slug_cat = $data_cats->slug;
            $link_url_check = route('single.detail', array($slug_cat, $detail->slug));
            $link_url_demo = route('admin.draftDetails', array($slug_cat, $detail->slug));
        }
    } else{
        $title = 'Sản phẩm mới';
        $product_title = "";
        $product_title_en = "";
        $slug = "";
        $description = "";
        $description_en = "";
        $content = "";
        $content_en = "";
        $sort = 0;
        $galleries = "";
        $sku = "";
        $group_variable_product = '';
        $price_origin = 0;
        $price_promotion = 0;
        $start_event = "";
        $end_event = "";
        $store_status = BaseConstants::ACTIVE;
        $status = BaseConstants::ACTIVE;
        $thumbnail = "";
        $thumbnail_alt = "";
        $date_update = date('Y-m-d H:i:s');
        $id = 0;
        $link_url_demo = "";
        $id_category_primary = 0;
        //khai báo biến SEO
        $seo_title = "";
        $seo_keyword = "";
        $seo_description = "";
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
        <form action="{{route('admin.storeProductDetail')}}" method="POST" id="frm-create-product" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$id}}">
    	    <div class="row">
    	      	<div class="col-12">
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
                                    <a class="nav-link active" id="vi-tab" data-toggle="tab" href="#vi" role="tab" aria-controls="vi" aria-selected="true">Thông tin sản phẩm</a>
                                </li>
                                <li class="nav-item hidden">
                                    <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">Tiếng Anh</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="vi" role="tabpanel" aria-labelledby="vi-tab">
                                    <div class="form-group">
                                        <label for="title">Tên sản phẩm</label>
                                        <input type="text" class="form-control title_slugify" id="title" name="title" placeholder="Tên sản phẩm" value="{{$product_title}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="slug">Slug sản phẩm(Hệ thống tự tạo hoặc Admin phải làm chuẩn)</label>
                                        <input type="text" class="form-control slug_slugify" id="slug" name="slug" placeholder="Slug" value="{{ $slug }}">
                                        {{--
                                        @if($id > 0 && $status == BaseConstants::ACTIVE)
                                            <b style="color: #0000cc;">Demo Link:</b> <u><i><a  style="color: #F00;" href="{{ $link_url_check }}" target="_blank">{{ $link_url_check }}</a></i></u>
                                        @else
                                            <b style="color: #0000cc;">Demo Link:</b> <u><i><a  style="color: #F00;" href="{{ $link_url_demo }}" target="_blank">{{ $link_url_demo }}</a></i></u>
                                        @endif
                                        --}}
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Trích dẫn</label>
                                        <textarea id="description" name="description">{!! $description !!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="content">Mô tả sản phẩm</label>
                                        <textarea id="content" name="content">{!! $content !!}</textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                    <div class="form-group">
                                        <label for="title_en">Title Product</label>
                                        <input type="text" class="form-control" id="title_en" name="title_en" placeholder="Product Name" value="{{ $product_title_en }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="description_en">Description</label>
                                        <textarea id="description_en" name="description_en">{!! $description_en !!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="content_en">Content Product</label>
                                        <textarea id="content_en" name="content_en">{!! $content_en !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sku">Mã sản phẩm</label>
                                <input type="text" name="sku" id="sku" value="{{ $sku }}" class="form-control">
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="price_origin" class="title_txt">Giá gốc</label>
                                    <input type="text" name="price_origin" id="price_origin" value="{{$price_origin}}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="price_promotion" class="title_txt">Giá khuyến mãi</label>
                                    <input type="text" name="price_promotion" id="price_promotion" value="{{$price_promotion}}" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="start_event" class="title_txt">Ngày bắt đầu</label>
                                    <div class="input-group date" id="start_event" data-target-input="nearest">
                                        <input type="text" name="start_event" class="form-control datetimepicker-input" data-target="#start_event" value="{{$start_event}}">
                                        <div class="input-group-append" data-target="#start_event" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="end_event" class="title_txt">Ngày kết thúc</label>
                                    <div class="input-group date" id="end_event" data-target-input="nearest">
                                        <input type="text" name="end_event" class="form-control datetimepicker-input" data-target="#end_event" value="{{$end_event}}">
                                        <div class="input-group-append" data-target="#end_event" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @include('admin.product.variable-input')

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="store_status" class="title_txt">Tình trạng(Còn hàng/ hết hàng)</label><br/>
                                        <input id="store_status" type="checkbox" value="1" name="store_status" <?php if($store_status == 1): ?> checked <?php endif; ?> data-toggle="toggle">
                                    </div>
                                </div>
                            </div>

                            <div id="group_filter_item"></div>
                            <div class="form-group">
                                <label for="sort" class="title_txt">Sắp xếp (Số càng lớn thứ tự càng cao)</label>
                                <input type="text" name="sort" id="sort" value="{{ $sort }}" class="form-control">
                            </div>


                            <!--********************************************Gallery**************************************************-->
                            <!--Post Gallery-->
                            <div class="form-group">
                                <label>Gallery</label>
                                @if(empty($galleries))
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
                                    <input class="gallery_item_txt" type="hidden" value="<?php echo count($galleries); ?>" name="gallery_item_count" autocomplete="off"/>
                                    <div class="clear content_add_item_images">
                                        <?php for($j=0;$j<count($galleries);$j++): $n=$j+1; ?>
                                            <div class="group_item_images clear">
                                                <div class="inside clear">
                                                    <div class="icon_change_postion"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></div>
                                                    <div class="icon_change_postion image_view image_show_demo"><a target="_blank" class="html5lightbox" href="<?php echo '/images/product/'.$galleries[$j]; ?>"><img width="50" height="auto" src="<?php echo '/images/product/'.$galleries[$j]; ?>"></a></div>
                                                    <div class="space_input_demo text_input_file_image">
                                                        <input class="myfile_gallery_store form-control" type="text" value="<?php echo $galleries[$j]; ?>" size="50" name="upload_gallery<?php echo $n; ?>" />
                                                    </div>
                                                    <div class="mybutton_upload_img hidden">
                                                        <input class="upload_gallery_file" type="file" onchange="set_link_gallery(this);" multiple name="upload_gallery_file<?php echo $n; ?>">Upload
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

                            <!--SEO-->
                            @include('admin.form-seo.seo')
    		        	</div> <!-- /.card-body -->
    	      		</div><!-- /.card -->
    	    	</div> <!-- /.col-9 -->
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="card widget-category">
                                <div class="card-header">
                                    <h3 class="card-title">Thể loại sản phẩm</h3>
                                </div> <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="inside clear">
                                        <div class="clear">
                                            <?php
                                            $data_checks = App\Models\Join_Category_Product::where('product_id', $id)->get();
                                            $array_checked = array();
                                            if ($data_checks) {
                                                foreach($data_checks as $data_check) {
                                                    array_push($array_checked, $data_check->category_id);
                                                }
                                            }
                                            $categories = App\Models\ProductCategory::orderBy('sort','DESC')
                                                ->get();
                                            echo \App\WebService\WebService::showMultipleCategory($categories,$array_checked,0);
                                            ?>
                                            <input type="hidden" id="category_primary_id" name="category_primary_id" value="{{$id_category_primary}}"/>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div> <!-- /.card-body -->
                            </div><!-- /.card -->
                        </div>

                        <div class="col-6">
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
                                            <div class="demo-img" style="padding-top: 15px; text-align: center">
                                                <img src="{{asset('images/product/'.$thumbnail)}}" style="max-width: 250px;">
                                            </div>
                                        @endif
                                    </div>
                                </div> <!-- /.card-body -->
                            </div><!-- /.card -->
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Publish</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group clearfix text-center">
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
<style type="text/css">
    ul#muti_menu_post li.category_menu_list label{
        display: block;
        position: relative;
        padding-right: 100px;
    }
    ul#muti_menu_post li.category_menu_list label input{
        position: relative;

    }
    ul#muti_menu_post li.category_menu_list label button{
        display: block;
        position: absolute;
        right: 0px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        color: #056FAD;
        font-weight: 400;
        text-decoration: underline;
        cursor: pointer;
        background: transparent;
        z-index: 10;
    }
    ul#muti_menu_post li.category_menu_list label button.default_primary_term{
        cursor: text;
        font-weight: 600;
        text-decoration: none;
        color: #000;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function ($){

        @if(isset($detail))

        @else
            $('.slug_slugify').slugify('.title_slugify');
        @endif

        $('#parent_variable').select2();
        $('#buy_with_combo').select2();
        $('#gift_products').select2();

        $("select").select2({
            tags: true
        });

        $("select").on("select2:select", function (evt) {
            var element = evt.params.data.element;
            var $element = $(element);

            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });

        //Date range picker
        $('#reservationdate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        //End event
        $('#start_event').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        //End event
        $('#end_event').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });

        $('#thumbnail_file').change(function(evt) {
            $("#thumbnail_file_link").val($(this).val());
            $("#thumbnail_file_link").attr("value",$(this).val());
        });

        //xử lý validate
        $("#frm-create-product").validate({
            rules: {
                post_title: "required",
                'category_item[]': { required: true, minlength: 1 },
                price_origin: "required",
                price_promotion: "required",
            },
            messages: {
                post_title: "Nhập tiêu đề tin",
                'category_item[]': "Chọn thể loại sản phẩm",
                price_origin: "Nhập giá gốc sản phẩm",
                price_promotion: "Nhập giá khuyến mãi"
            },
            errorElement : 'div',
            errorLabelContainer: '.errorTxt',
            invalidHandler: function(event, validator) {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });

        /*Set Default Category*/

        //load default
        var category_primary_id=$('#category_primary_id').val();
        $(".category_menu_list input.category_item_input:checked").each(function(){
            var value_cate=$(this).val();
            if(category_primary_id==0){
                $(this).parent().append('<button type="button" data-id="'+value_cate+'" class="make_primary_term primary_term_category" aria-label="Make primary Category">Make primary</button>');
            }
            else
            {
                if(value_cate==category_primary_id){
                    $(this).parent().append('<button type="button" data-id="'+$(this).val()+'" class="default_primary_term primary_term_category" aria-label="Primary Category">Primary</button>');
                }else{
                    $(this).parent().append('<button type="button" data-id="'+$(this).val()+'" class="make_primary_term primary_term_category" aria-label="Make primary Category">Make primary</button>');
                }

            }
        });
        //onclick checkbox
        var _clicked = false;
        $('.category_menu_list').on( 'change', 'input.category_item_input', function() {
            var ischecked = $(this).is(':checked');
            if (!ischecked) {
                 $(this).parent().find('button.primary_term_category').remove();
            } else {
               if($(this).parent().find('button.primary_term_category').length>0) {
                   // not working
               }else{
                    $(this).parent().append('<button type="button" data-id="' + $(this).val() + '" class="make_primary_term primary_term_category" aria-label="Make primary Category">Make primary</button>');
               }
            }

        });
        //set primary
        $(document).on("click","button.primary_term_category",function() {
            if($(this).hasClass('default_primary_term')){
                // not working
            }
            else
            {
                var id_set=$(this).attr('data-id');
                $('#category_primary_id').val(id_set);
                $('button.default_primary_term').removeClass('default_primary_term').addClass('make_primary_term').text('Make primary');
                $(this).removeClass('make_primary_term').addClass('default_primary_term');
                $(this).text('Primary');
            }
        });
        /*End Set Default Category*/

    });
</script>
<script type="text/javascript">
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
</script>
@endsection
