@extends('admin.layouts.app')
@section('seo')
<?php
use App\Models\Order;
$data_seo = array(
    'title' => 'Đơn hàng | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Đơn hàng | '.Helpers::get_setting('seo_title'),
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
<script type="text/javascript">
    jQuery(document).ready(function($){
        var date_picker = $('.date_picker');
        if(date_picker.length>0){

            date_picker.each(function(index, el) {
                $(this).datepicker({
                    format: 'DD-MM-YYYY',
                });

            });
        }
    });
</script>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Đơn hàng</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Đơn hàng</li>
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
		            	<h3 class="card-title">Đơn hàng</h3>
		          	</div> <!-- /.card-header -->
		          	<div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <ul class="nav fl">
                                    <li class="nav-item">
                                        <a class="btn btn-danger" onclick="delete_id('order')" href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-9">
                                <div class="fr">
                                    <form method="GET" action="{{route('admin.searchOrder')}}" id="frm-filter-post" >
                                        <div class="row">
                                            <div class="col-lg-8 px-lg-1">
                                                <div class="row">
                                                    <div class="form-group col-lg-4 px-lg-1">
                                                        <input type="text" name="from_date" class="form-control date_picker" placeholder="From Date" value="{{ request()->from_date }}">
                                                    </div>
                                                    <div class="form-group col-lg-4 px-lg-1">
                                                        <input type="text" name="to_date" class="form-control date_picker" placeholder="To Date"  value="{{ request()->to_date }}">
                                                    </div>
                                                    <div class="form-group col-lg-4 px-lg-1">
                                                        <select class="custom-select mr-2" name="status">
                                                            <option value="">Status</option>
                                                            <option value="0" {{request()->status == Order::Status['New'] ? 'selected' : ''}}>New</option>
                                                            <option value="1" {{request()->status == Order::Status['Confirm'] ? 'selected' : ''}}>Confirm</option>
                                                            <option value="2" {{request()->status == Order::Status['Completed'] ? 'selected' : ''}}>Completed</option>
                                                            <option value="3" {{request()->status == Order::Status['Cancel'] ? 'selected' : ''}}>Cancel</option>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-lg-4  form-group d-flex">
                                                <input type="text" class="form-control" name="search_title" id="search_title" placeholder="Order Number" value="{{request()->search_title}}">
                                                <button type="submit" class="btn btn-primary ml-2 text-nowrap">Filter</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="clear">
                            <div class="fl">
                                <span style="font-size:17px;display: block;float: right;margin-top: 9px;margin-left: 15px;font-weight: bold;text-decoration: underline;">
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#printMultiple" style="color: #1c7ed6;">
                                        <i class="fas fa-print"></i> Print Orders
                                    </a>
                                </span>
                                <span style="font-size:17px;display: block;float: right;margin-top: 9px;font-weight: bold;text-decoration: underline;">
                                    <a href="{{route('admin.excelOrder')}}?{{request()->getQueryString()}}" target="_blank" style="color: #1d6c41;">
                                        <i class="fas fa-file-csv"></i> Export Orders
                                    </a>
                                </span>
                            </div>
                            <div class="fr">
                                {!! $data_order->links() !!}
                            </div>
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table_index">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center"><input type="checkbox" id="selectall" onclick="select_all()"></th>
                                        <th scope="col" class="text-center">Order Number</th>
                                        <th scope="col" class="text-center">Full Name</th>
                                        <th scope="col" class="text-center">Order Date</th>
                                        <th scope="col" class="text-center">Total</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_order as $data)
                                    <tr>
                                        <td class="text-center"><input type="checkbox" id="{{$data->id}}" name="seq_list[]" value="{{$data->id}}"></td>
                                        <td class="text-center">
                                            <a class="row-title" href="{{route('admin.orderDetail', $data->id)}}">
                                                <b>{{$data->code}}</b>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a class="row-title" href="{{route('admin.orderDetail', $data->id)}}">
                                                {{ $data->first_name }} {{ $data->last_name }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{ $data->created_at }}
                                        </td>
                                        <td class="text-center">
                                            <span class='b' style='color: red;'>{{ WebService::formatMoney12($data->total) }} {{ $data->currency }}</span>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                switch ($data->status) {
                                                    case 0:
                                                        echo "<span class='b' style='color: green;'>New</span>";
                                                        break;
                                                    case 1:
                                                        echo "<span class='b' style='color: #ffa500;'>Confirm</span>";
                                                        break;
                                                    case 2:
                                                        echo "<span class='b' style='color: red;'>Completed</span>";
                                                        break;
                                                    case 3:
                                                        echo "<span class='b' style='color: #ffb100;'>Cancel</span>";
                                                        break;
                                                    default:
                                                        break;
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.orderDetail', $data->id) }}" title="View"><i class="far fa-eye"></i></a>
                                            <span class="mr-2 ml-2">|</span>
                                            <a href="{{ route('admin.printOrder', $data->id) }}" title="Download"><i class="fas fa-download"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="fr">
                            {!! $data_order->withQueryString()->links() !!}
                        </div>
		        	</div> <!-- /.card-body -->
	      		</div><!-- /.card -->
	    	</div> <!-- /.col -->
	  	</div> <!-- /.row -->
  	</div> <!-- /.container-fluid -->
</section>
<!-- Modal -->
<div class="modal fade" id="printMultiple" tabindex="-1" role="dialog" aria-labelledby="printMultipleLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.printMultipleOrders') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="printMultipleLabel">Export Orders</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="from">From</label>
                                <input type="date" name="from" id="from" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to">To</label>
                                <input type="date" name="to" id="to" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status">Order Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Tình trạng đơn hàng</option>
                                    @foreach(Order::Status as $key => $value)
                                        <option value="{{ $value }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="type">Format</label>
                                <select class="form-control" name="type">
                                    <option value="pdf">PDF</option>
                                    <option value="zip">ZIP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
