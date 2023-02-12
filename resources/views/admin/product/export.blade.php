@extends('admin.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Xuất Excel sản phẩm | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Xuất Excel sản phẩm | '.Helpers::get_setting('seo_title'),
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
        <h1 class="m-0 text-dark">Xuất Excel sản phẩm</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Xuất Excel sản phẩm</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
  	<div class="container-fluid">
        <form action="{{route('admin.exportProductAction')}}" method="GET">
            @csrf
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="checkbox" name="export_all" id="export-all" value="1">
                        <label for="export-all">Xuất toàn bộ sản phẩm</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="search_string">Từ khoá sản phẩm</label>
                        <input type="text" name="search_string" id="search_string" placeholder="Từ khoá sản phẩm"
                               class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category">Chọn danh mục</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->categoryID }}">{{ $category->categoryName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="origin">Xuất xứ</label>
                        <select name="origin" id="origin" class="form-control">
                            <option value="">Chọn xuất xứ</option>
                            @foreach($filter_origin as $origin)
                                <option value="{{ $origin->id }}">{{ $origin->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Xuất Excel</button>
            </div>
        </form>
  	</div> <!-- /.container-fluid -->
</section>
@endsection
