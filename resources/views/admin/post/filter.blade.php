@extends('admin.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Tin tức | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Tin tức | '.Helpers::get_setting('seo_title'),
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
        <h1 class="m-0 text-dark">Lọc tin tức</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Lọc tin tức</li>
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
		            	<h3 class="card-title">Lọc tin tức</h3>
		          	</div> <!-- /.card-header -->
		          	<div class="card-body">
                        <div class="clear">
                            <ul class="nav fl">
                                <li class="nav-item">
                                    <a class="btn btn-danger" onclick="delete_id('post')" href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-primary" href="{{route('admin.createPost')}}" style="margin-left: 6px;"><i class="fas fa-plus"></i> Add New</a>
                                </li>
                            </ul>
                            <div class="fr">
                                <form method="GET" action="{{route('admin.searchPost')}}" id="frm-filter-post" class="form-inline">
                                    <?php
                                        $list_cate = App\Models\Category::orderBy('categoryName', 'ASC')->select('categoryID', 'categoryName')->get();
                                    ?>
                                    <select class="custom-select mr-2" name="category">
                                        <option value="">Thể loại tin tức</option>
                                        @foreach($list_cate as $cate)
                                            <option value="{{$cate->categoryID}}" @if(isset($_GET['category']) && $_GET['category'] == $cate->categoryID) selected @endif>{{$cate->categoryName}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" class="form-control" name="search_title" value="<?php if(isset($_GET['search_title'])){ echo $_GET['search_title']; } ?>" id="search_title" placeholder="Từ khoá">
                                    <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
                                </form>
                            </div>
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table_index">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center"><input type="checkbox" id="selectall" onclick="select_all()"></th>
                                        <th scope="col" class="text-center">Title</th>
                                        <th scope="col" class="text-center">Category</th>
                                        <th scope="col" class="text-center">Thumbnail</th>
                                        <th scope="col" class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_post as $data)
                                    <tr>
                                        <td class="text-center"><input type="checkbox" id="{{$data->id}}" name="seq_list[]" value="{{$data->id}}"></td>
                                        <td class="text-center">
                                            <a class="row-title" href="{{route('admin.postDetail', array($data->id))}}">
                                                <b>{{$data->title}}</b>
                                                <br>
                                                <b style="color:#c76805;">{{$data->slug}}</b>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                $categories = App\Models\Post::where('post.id','=', $data->id)
                                                    ->join('join_category_post','post.id','=','join_category_post.id_post')
                                                    ->join('categories','join_category_post.id_category','=','categories.categoryID')
                                                    ->select('categories.categoryName')
                                                    ->first();
                                            ?>
                                            <b style="color:#FF0000;">{{$categories->categoryName}}</b>
                                        </td>
                                        <td class="text-center">
                                            @if($data->thumbnail != '')
                                                <img src="{{asset('images/article/'.$data->thumbnail)}}" style="height: 70px">
                                            @else
                                                <img src="{{asset('img/default-150x150.png')}}">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{$data->created}}
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
                            {!! $data_post->links() !!}
                        </div>
		        	</div> <!-- /.card-body -->
	      		</div><!-- /.card -->
	    	</div> <!-- /.col -->
	  	</div> <!-- /.row -->
  	</div> <!-- /.container-fluid -->
</section>
@endsection
