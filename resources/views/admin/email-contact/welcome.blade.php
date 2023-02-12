@extends('admin.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Register Welcome Code | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Register Welcome Code | '.Helpers::get_setting('seo_title'),
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
        <h1 class="m-0 text-dark">Register Welcome Code</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Register Welcome Code</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
  	<div class="container-fluid">
	    <div class="row">
	      	<div class="col-12">
	        	<div class="card">
		          	<div class="card-header">
		            	<h3 class="card-title">Register Welcome Code</h3>
		          	</div> <!-- /.card-header -->
		          	<div class="card-body">
                        <table class="table table-bordered" id="table_index"></table>
                        <script>
                            $(function() {
                                let data2 ={!! $list !!};
                                $('#table_index').DataTable({
                                    data: data2,
                                    columns: [
                                      {title: 'email', data: 'email'},
                                      {title: 'Created', data: 'created_at'},
                                    ],
                                    order: [[ 1, "desc" ]],
                                    columnDefs: [
                                    {// Email
                                        visible: true,
                                        targets: 0,
                                        className: 'text-center',
                                        render: function (data, type, full, meta) {
                                            return data;
                                        }
                                    },
                                    {// Created
                                        visible: true,
                                        targets: 1,
                                        className: 'text-center',
                                        render: function (data, type, full, meta) {
                                            return getFormattedDate(data);
                                        }
                                    }
                                ],
                                });
                            });
                        </script>
		        	</div> <!-- /.card-body -->
	      		</div><!-- /.card -->
	    	</div> <!-- /.col -->
	  	</div> <!-- /.row -->
  	</div> <!-- /.container-fluid -->
</section>
@endsection
