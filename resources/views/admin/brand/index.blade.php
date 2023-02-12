@extends('admin.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'List Brand | '.Helpers::get_option_minhnn('seo-title-add')
);
$seo = WebService::getSEO($data_seo);
?>
@include('admin.partials.seo')
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2 justify-content-end">
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">List Brand</li>
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
		            	<h5>List Brand</h5>
		          	</div> <!-- /.card-header -->
		          	<div class="card-body">
                        <div class="clear">
                            
                            @include('admin.partials.button_add_delete', ['type' => 'brand', 'route' => route('admin.brand.create') ])

                            <div class="fr">
                                <form method="GET" action="" id="frm-filter-post" class="form-inline">
                                    <input type="text" class="form-control" name="search_title" id="search_title" placeholder="Từ khoá">
                                    <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
                                </form>
                            </div>
                        </div>
                        <br/>
                        <div class="clear">
                            <div class="fr">
                                {!! $brands->links() !!}
                            </div>
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table_index">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center"><input type="checkbox" id="selectall" onclick="select_all()"></th>
                                        <th scope="col" class="text-center">STT</th>
                                        <th scope="col" class="text-center">Title</th>
                                        <th scope="col" class="text-center">Thumbnail</th>
                                        <th scope="col" class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($brands as $brand)
                                    <tr>
                                        <td class="text-center"><input type="checkbox" id="{{$brand->id}}" name="seq_list[]" value="{{$brand->id}}"></td>
                                        <td class="text-center">{{ $brand->preority }}</td>
                                        <td>
                                            <b><a href="{{ route('admin.brand.edit', $brand->id) }}">{{ $brand->name }}</a></b>
                                        </td>
                                        <td class="text-center">
                                            @if($brand->image)
                                                <img src="{{ asset($brand->image) }}" style="height: 70px;">
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $brand->updated_at }}</div>
                                            <div>{{ $brand->status ? 'Public' : 'Draf' }}</div>
                                        </td>
                                    </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="fr">
                            {!! $brands->links() !!}
                        </div>
		        	</div> <!-- /.card-body -->
	      		</div><!-- /.card -->
	    	</div> <!-- /.col -->
	  	</div> <!-- /.row -->
  	</div> <!-- /.container-fluid -->
</section>
@endsection