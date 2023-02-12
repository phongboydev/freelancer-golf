@extends('admin.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Danh mục sản phẩm | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Danh mục sản phẩm | '.Helpers::get_setting('seo_title'),
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
        <h1 class="m-0 text-dark">Danh mục sản phẩm</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Danh mục sản phẩm</li>
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
		            	<h3 class="card-title">Danh mục sản phẩm</h3>
		          	</div> <!-- /.card-header -->
		          	<div class="card-body">
                        <div class="clear">
                            <ul class="nav fl">
                                <li class="nav-item">
                                    <a class="btn btn-danger" onclick="delete_id('product_category')" href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-primary" href="{{route('admin.createProductCategory')}}" style="margin-left: 6px;"><i class="fas fa-plus"></i> Add New</a>
                                </li>
                            </ul>
                            <div class="fr">
                                <form method="GET" action="{{route('admin.listProductCategories')}}" id="frm-filter-post" class="form-inline">
                                    <input type="text" class="form-control" name="search_title" id="search_title"
                                           placeholder="Từ khoá" value="{{ Request()->get('search_title') }}">
                                    <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
                                </form>
                            </div>
                        </div>
                        <br/>
                        <div class="clear">
                            <div class="fr">
                                {!! $data_category->links() !!}
                            </div>
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table_index">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center"><input type="checkbox" id="selectall" onclick="select_all()"></th>
                                        <th scope="col" class="text-center">Title</th>
                                        <th scope="col" class="text-center">Thumbnail</th>
                                        <th scope="col" class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_category as $data)
                                    <tr>
                                        <td class="text-center"><input type="checkbox" id="{{$data->id}}" name="seq_list[]" value="{{$data->id}}"></td>
                                        <td class="text-center">
                                            <?php
                                            $seq = $data->id;
                                            $title = $data->name;
                                            $title_en = $data->name_en;
                                            $parent_cat = $data->parent;
                                            $categories_views = \App\Models\ProductCategory::get()->toArray();
                                            $breacurm = "__None__";
                                            if($categories_views):
                                                $breacurm = \App\WebService\WebService::getParentCategory($categories_views,$parent_cat);
                                            endif;
                                            $menu_show = "";
                                            if($data->categoryIndex == 1){
                                                 $menu_show = "<p style='color: #FF6600;'>Hiện thị lên menu</p>";
                                            }else{
                                                $menu_show = "";
                                            }
                                            $home_show = "";
                                            if($data->showhome == 1){
                                                 $home_show = "<p style='color: #2EAF1F;'>Hiện thị lên trang chủ</p>";
                                            }else{
                                                $home_show = "";
                                            }
                                            $title_content = "
                                            <a class='row-title' href='".route('admin.productCategoryDetail', array($seq))."''>
                                                <p><b style='color: #056FAD;'>".$title."</b></p>
                                                <p><b style='color:#777;'>Slug:</b> <span style='color:#c76805;'>".$data->slug."</span></p>
                                            </a>
                                            <p>
                                                <b style='color:#777;'>URL: </b>
                                                <a style='color:#00C600; word-break:break-all;' target='_blank' href='".route('category.list',$data->slug)."'>
                                                    ".route('category.list',$data->slug)."
                                                </a>
                                            </p>
                                            <p>
                                                <b style='color:#777;'>Parent: </b>
                                                <span class='category_theme_breacum' style='font-style: italic; color: #e818a8;'>".$breacurm."</span>
                                            </p>
                                            <p>
                                                <b style='color:#777;'>Vị Trí: </b>
                                                <font style='color:#F00;'>".$data->sort."</font>
                                            </p>" . $menu_show . $home_show;
                                            echo $title_content;
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            @if($data->thumbnail != '')
                                                <img src="{{asset('images/category/'.$data->thumbnail)}}" style="height: 90px">
                                            @else
                                                <img src="{{asset('img/default-150x150.png')}}">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{$data->created_at}}
                                            <br>
                                            @if($data->status == 1)
                                                Public
                                            @else
                                                Draft
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="fr">
                            {!! $data_category->links() !!}
                        </div>
                    </div> <!-- /.card-body -->
	      		</div><!-- /.card -->
	    	</div> <!-- /.col -->
	  	</div> <!-- /.row -->
  	</div> <!-- /.container-fluid -->
</section>
@endsection
