@extends('layouts.app')
@section('seo')
<?php
$title='Liên hệ | '.Helpers::get_setting('seo_title');
$description='Liên hệ - '.Helpers::get_setting('seo_description');
$keyword='Liên hệ,'.Helpers::get_setting('seo_keyword');
$thumb_img_seo=asset(Helpers::get_setting('seo_image'));
$data_seo = array(
    'title' => $title,
    'keywords' => $keyword,
    'description' =>$description,
    'og_title' => $title,
    'og_description' => $description,
    'og_url' => Request::url(),
    'og_img' => $thumb_img_seo,
    'current_url' =>Request::url(),
    'current_url_amp' => ''
);
$seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJzht6hik8TH8cRa2itU3-n_hGO4Hv604"></script>
    <script type="text/javascript">
        var myCenter=new google.maps.LatLng({!!Helpers::get_setting('latitude')!!},{!!Helpers::get_setting('longitude')!!});
        var myMarker=new google.maps.LatLng({!!Helpers::get_setting('latitude')!!},{!!Helpers::get_setting('longitude')!!});
        var marker;
        function initialize()
        {
            var mapProp = {center:myCenter,
                zoom:16,
                mapTypeId:google.maps.MapTypeId.ROADMAP
            };
            var map=new google.maps.Map(document.getElementById("map_canvas"),mapProp);
            marker=new google.maps.Marker({
                position:myMarker,animation:google.maps.Animation.BOUNCE
            });
            marker.setMap(map);
            var infowindow = new google.maps.InfoWindow({
                content:'<h3><img class="aligncenter" alt="<?php echo Helpers::get_setting('name-company');?>" width="150" src="{{asset('img/logo-golf-japan.png')}}"/></h3><br/><h5 style="font-weight:500; text-align:center;"><?php echo Helpers::get_setting('name-company');?></h5><p class="aligncenter"><b> Địa chỉ: </b><?php echo Helpers::get_setting('address');?><b></p><br/><p>Email:</b> <a href="mailto:<?php echo Helpers::get_setting('email');?>" rel="nofollow"><?php echo Helpers::get_setting('email');?></a></p><br/><p><b>Điện thoại: </b><?php echo Helpers::get_setting('hotline');?></p>'
            });
            infowindow.open(map,marker);
        }
        google.maps.event.addDomListener(window, "load", initialize);
    </script>
<div class="main_content clear">
    <div class="container_view_map clear">
            <div class="project_index clear">
                <div class="container_contact clear">
                    <div class="map-wrapper clear">
                        <div id="map_canvas"></div>
                     </div><!--map-wrapper-->
                     <div class="wrapper-contact-form clear">
                         <div class="container clear">
                             <div class="lien-he-container clear">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-xs-12 pull-left">
                                            <p class="sort_name">
                                                <span class="title">{!! Helpers::get_setting('comapny_name') !!}</span>
                                            </p>
                                            <div class="view_order clear">
                                                {!! Helpers::get_setting('address') !!}
                                            </div><!--view_order-->
                                            <div class="view_order clear">
                                                 {!! Helpers::get_setting('hotline') !!}
                                            </div><!--view_order-->
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-xs-12 pull-right map_create">

                                            @if ($errors->any())
                                                <div class="mgt-10 alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if(Session::has('success_msg'))
                                            <div class="mgt-10  alert alert-success alert-dismissible fade in" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    {{ Session::get('success_msg') }}
                                            </div>
                                            @endif
                                            <div id="frm_contact" class="frm_contact clear">
                                                <form id="contactForm" action="{{route('storeContact')}}" method="post">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="your_name" name="your_name" value="" placeholder="Your Name (*)">
                                                        <p id="erroryour_name"></p>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="email" class="form-control" id="your_email" name="your_email"  placeholder="Email(*)">
                                                        <p id="erroryour_email"></p>
                                                    </div>
                                                    <div class="form-group">
                                                    <input type="tel" class="form-control" id="your_mobile" name="your_mobile" placeholder="Phone Number(*)" >
                                                        <p id="erroryour_mobile"></p>
                                                    </div>
                                                    <div class="form-group">
                                                   <textarea class="form-control" type="textarea" id="your_message" name="your_message" placeholder="Content (*)" maxlength="1000" rows="4"></textarea>
                                                    <p id="erroryour_message"></p>
                                                     </div>
                                                     <div class="form-group content_tbl_contact clear">
                                                        <button type="submit" id="submit" name="tbl_submit" class="btn btn-danger"><span class="dslc-icon-ext-paperplane2"></span> Send</button>
                                                     </div>
                                                </form>
                                            </div><!--frm_contact-->
                                        </div><!--map_create-->
                                    </div><!--row-->
                             </div>
                         </div>
                     </div><!--wrapper-contact-form-->
                </div><!--container_contact-->
        </div>

    </div>
</div>
@endsection
