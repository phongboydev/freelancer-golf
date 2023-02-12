@extends('admin.layouts.app')
@section('seo')
<?php
    use App\Models\Variable_Product;
    use App\Constants\BaseConstants;
    $data_seo = array(
        'title' => 'Danh sách biến thể | '.Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Danh sách biến thể | '.Helpers::get_setting('seo_title'),
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
        <h1 class="m-0 text-dark">Danh sách biến thể</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Danh sách biến thể</li>
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
		            	<h3 class="card-title">Danh sách biến thể</h3>
		          	</div> <!-- /.card-header -->
		          	<div class="card-body">
                        <div class="clear">
                            <ul class="nav fl">
                                <li class="nav-item">
                                    <a class="btn btn-danger" onclick="delete_id('variable_product')" href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-primary" href="{{route('admin.createProductVariable')}}" style="margin-left: 6px;"><i class="fas fa-plus"></i> Add New</a>
                                </li>
                            </ul>
                            <div class="fr">
                                <form method="GET" action="{{route('admin.listProductVariables')}}" id="frm-filter-post" class="form-inline">
                                    <input type="text" class="form-control" name="search_title" id="search_title" placeholder="Từ khoá">
                                    <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
                                </form>
                            </div>
                        </div>
                        <br/>
                        <div class="clear">
                            <div class="fr">
                                {!! $data_variable->links() !!}
                            </div>
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table_index">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center"><input type="checkbox" id="selectall" onclick="select_all()"></th>
                                        <th scope="col" class="text-center">Title</th>
                                        <th scope="col" class="text-center">View</th>
                                        <th scope="col" class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_variable as $data)
                                    <?php
                                        $child_list_variables = Variable_Product::where('parent', $data->id)
                                            ->where('status', BaseConstants::ACTIVE)
                                            ->get();
                                        $child_html='<table width="100%" border="0" class="table_variable_child_'.$data->id.' table_child_bt">
                                                       <tr>
                                                         <th align="center">Tên biến thể</th>
                                                         <th align="center">Slug</th>
                                                       </tr>';
                                        foreach($child_list_variables as $child_list_variables):
                                            $child_html .='<tr>
                                              <td class="name_varibles" align="center"><a href="'.route('admin.productVariableDetail', array($child_list_variables->id)).'">'.$child_list_variables->name.'</a></td>
                                              <td class="slug_varibles" align="center">'.$child_list_variables->slug.'</td>
                                            </tr>';
                                        endforeach;
                                        $child_html .='</table>';
                                    ?>
                                    <tr>
                                        <td class="text-center"><input type="checkbox" id="{{$data->id}}" name="seq_list[]" value="{{$data->id}}"></td>
                                        <td class="text-center">
                                            <a href="{{route('admin.productVariableDetail', array($data->id))}}">
                                                <b>{{$data->name}}</b><br/>
                                                <b style="color:#c76805;">{{$data->slug}}</b>
                                            </a>
                                        </td>
                                        <td class="text-center column-slug">
                                            <a class="variable_readmore" sid="{{$data->id}}" id="variable_{{$data->id}}" href="javascript:void(0)"><?php echo strtoupper('Show Variable'); ?> <i class="fas fa-hand-point-right"></i></a>
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
                                    <tr class="alternate alternate_check_hide hidden" id="tr_{{$data->id}}">
                                        <td class="slug column-slug" colspan="5"><?php echo $child_html; ?></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="fr">
                            {!! $data_variable->links() !!}
                        </div>
                    </div> <!-- /.card-body -->
	      		</div><!-- /.card -->
	    	</div> <!-- /.col -->
	  	</div> <!-- /.row -->
  	</div> <!-- /.container-fluid -->
</section>
<script type="text/javascript">
    $( document ).ready(function() {
        var $id;
        $('.column-slug').delegate('a.variable_readmore', 'click', function(e) {
            e.preventDefault();
            $id=$(this).attr('sid');
            if ($('#tr_'+$id).hasClass("action")){
                $('#tr_'+$id).removeClass("action");
            }else{
                $('#tr_'+$id).addClass( "action");
            }
        });
    });
</script>
@endsection
