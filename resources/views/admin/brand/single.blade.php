@extends('admin.layouts.app')
<?php
$title = $title_head ?? 'Module';
if(isset($post_brand)){
    extract($post_brand->toArray());
    $title = $name;
    $gallery = unserialize($gallery);
}
?>
@section('seo')
<?php
$data_seo = array(
    'title' => $title.' | '.Helpers::get_option_minhnn('seo-title-add')
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
        <form action="{{route('admin.brand.post')}}" method="POST" id="frm-create-category" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $id ?? 0 }}">
    	    <div class="row">
    	      	<div class="col-9">
    	        	<div class="card">
    		          	<div class="card-header">
    		            	<h3 class="card-title">{{$title}}</h3>
    		          	</div> <!-- /.card-header -->
    		          	<div class="card-body">
                            <!-- show error form -->
                            <div class="errorTxt"></div>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="vi" role="tabpanel" aria-labelledby="vi-tab">
                                    <div class="form-group">
                                        <label for="name">Tên</label>
                                        <input type="text" class="form-control title_slugify" id="name" name="name" placeholder="Tiêu đề" value="{{$name ?? ''}}">
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input type="text" class="form-control slug_slugify" id="slug" name="slug" placeholder="Slug" value="{{ $slug ?? '' }}">
                                    </div> -->
                                    <div class="form-group">
                                        <label for="content">Mô tả</label>
                                        <textarea id="content" name="content">{!! $content ?? '' !!}</textarea>
                                    </div>
                                </div>
                            </div>
    		        	</div> <!-- /.card-body -->
    	      		</div><!-- /.card -->

    	    	</div> <!-- /.col-9 -->
                <div class="col-3">
                    @include('admin.partials.action_button')
                    @include('admin.partials.image', ['title'=>'Hình ảnh', 'id'=>'img', 'name'=>'image', 'image'=>$image ?? ''])
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
        $("#frm-create-category").validate({
            rules: {
                post_title: "required",
            },
            messages: {
                post_title: "Nhập tên thương hiệu",
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

    // maps
    var geocoder;
    var map;
    var currentMarker;
    function initMap() {
        geocoder = new google.maps.Geocoder();
        var mapOptions = {
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: new google.maps.LatLng({{ $addr_lat??'10.787860138989465'}}, {{ $addr_long??'106.6970651693116' }})
        };

        map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);
        currentMarker = new google.maps.Marker({
            map: map,
            /*position: new google.maps.LatLng(35.1683493,136.90769160000002),*/
            position: new google.maps.LatLng({{ $addr_lat??'10.787860138989465'}}, {{ $addr_long??'106.6970651693116' }}),
            /*icon: {
             path: google.maps.SymbolPath.CIRCLE,
             scale: 10
             },
             */
            draggable: true
        });
        google.maps.event.addListener(currentMarker, 'drag', function(event) {
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('lng').value = event.latLng.lng();
        });

        google.maps.event.addListener(currentMarker, 'dragend', function(event) {
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('lng').value = event.latLng.lng();
        });
    } //end init

    function codeAddress() {
        var address = document.getElementById('storeaddress').value;
        currentMarker.setMap(null);
        //markerNewPosition.setMap(null);
        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                console.log(results);
                document.getElementById('lat').value = results[0].geometry.location.lat();
                document.getElementById('lng').value = results[0].geometry.location.lng();

                //console.log(JSON.stringify(results));
                map.setCenter(results[0].geometry.location);
                currentMarker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    //icon: {
                    //  path: google.maps.SymbolPath.CIRCLE,
                    //  scale: 10
                    //},
                    draggable: true,
                });

                google.maps.event.addListener(currentMarker, 'drag', function(event) {
                    document.getElementById('lat').value = event.latLng.lat();
                    document.getElementById('lng').value = event.latLng.lng();
                });

                google.maps.event.addListener(currentMarker, 'dragend', function(event) {
                    document.getElementById('lat').value = event.latLng.lat();
                    document.getElementById('lng').value = event.latLng.lng();
                });
            } else {
                alert('Error not found address !');
            }
        });
    }

    jQuery(document) .ready(function($) {
        if(!window.google || !window.google.maps){
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src =' https://maps.googleapis.com/maps/api/js?key=AIzaSyB-eCEI4wiuaWtUAmSDRZQKYs2roDEBirY&callback=initMap';
            document.body.appendChild(script);
        }
        else
        {
            google.maps.event.addDomListener(window, 'load', initMap);
        }

        var district = $('#district').attr('data'),
            province = $('#province').val();
        if(district!='' && province!=''){
            axios({
                method: 'post',
                url: '/admin/ajax/get-district',
                data: {province:province, district:district }
            })
            .then(function (res) {
                if(res.data!='')
                    $('#district').html(res.data);
            });
        }
        $('#province').change(function(event) {
            var province = $(this).val();
            axios({
                method: 'post',
                url: '/admin/ajax/get-district',
                data: {province:province }
            })
            .then(function (res) {
                if(res.data!='')
                    $('#district').html(res.data);
            });
        });
    });
    // initMap();
    //google.maps.event.addDomListener(window, 'load', initMap);
</script>
<script type="text/javascript">
    editorQuote('content');
</script>
@endsection