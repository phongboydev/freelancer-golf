@extends('layouts.app')
@section('seo')
<title>503 Máy chủ của trang web tạm thời ngừng hoạt động. - Thông tin doanh nghiệp</title>
@endsection
@section('content')
    <div id="wrapper_container_fix" class="clear">
    	<div class="container clear">
    	    <div class="col-xs-12 col-sm-7 col-lg-7">
    	       <!-- Info -->
    	        <div class="info">
    	           <h1>Oppps!</h1>
    	           <h2>Máy chủ của trang web tạm thời ngừng hoạt động!</h2>
    	           <p>Vui lòng chờ trong giây lát để hệ thống khắc phục sự cố</p>
                   <div class="tbl_back clear">
    	           	<a href="{{url('/')}}" class="btn btn-info">Trang chủ</a>
    	           	<a href="{{url('/')}}#" class="btn btn-warning">Liên hệ</a>
                   </div>
    	       </div>
    	       <!-- end Info -->
    	    </div>
    		<div class="col-xs-12 col-sm-5 col-lg-5 text-center">
    	        <div class="fighting">
    	             <img src="{{ asset('img/fighting.gif') }}" alt="Fighting">
    	        </div>
    	        <!-- end Fighting -->
    	   </div>
    	</div>
    </div>
@endsection
