@extends('admin.layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Filter Orders | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Filter Orders | '.Helpers::get_setting('seo_title'),
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
        <h1 class="m-0 text-dark">Filter Orders</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Filter Orders</li>
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
                        <h3 class="card-title">Filter Orders</h3>
                    </div> <!-- /.card-header -->
                    <div class="card-body">
                        <div class="clear">
                            <ul class="nav fl">
                                <li class="nav-item">
                                    <a class="btn btn-danger" onclick="delete_id('order')" href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                </li>
                            </ul>
                            <div class="fr">
                                <form method="GET" action="{{route('admin.searchOrder')}}" id="frm-filter-post" class="form-inline">
                                    <select class="custom-select mr-2" name="order_status">
                                        <option value="">Tình trạng đơn hàng</option>
                                        <option value="1" @if($_GET['order_status'] == 1) selected @endif>Mới đặt</option>
                                        <option value="2" @if($_GET['order_status'] == 2) selected @endif>Đã xác nhận</option>
                                        <option value="3" @if($_GET['order_status'] == 3) selected @endif>Đang giao</option>
                                        <option value="4" @if($_GET['order_status'] == 4) selected @endif>Hoàn thành</option>
                                        <option value="5" @if($_GET['order_status'] == 5) selected @endif>Đã hủy</option>
                                    </select>
                                    <input type="text" class="form-control" value="<?php if(isset($_GET['search_title'])){ echo $_GET['search_title']; } ?>" name="search_title" id="search_title" placeholder="Mã đơn hàng">
                                    <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
                                </form>
                            </div>
                        </div>
                        <br/>
                        <div class="clear">
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
                                        <th scope="col" class="text-center">Mã đơn hàng</th>
                                        <th scope="col" class="text-center">Họ tên</th>
                                        <th scope="col" class="text-center">Thời gian đặt</th>
                                        <th scope="col" class="text-center">Tổng giá trị</th>
                                        <th scope="col" class="text-center">Tình trạng</th>
                                        <th scope="col" class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_order as $data)
                                    <tr>
                                        <td class="text-center"><input type="checkbox" id="{{$data->cart_id}}" name="seq_list[]" value="{{$data->cart_id}}"></td>
                                        <td class="text-center">
                                            <a class="row-title" href="{{route('admin.orderDetail', array($data->cart_id))}}">
                                                <b>{{$data->cart_code}}</b>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a class="row-title" href="{{route('admin.orderDetail', array($data->cart_id))}}">
                                                {{$data->cart_hoten}}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{$data->created}}
                                        </td>
                                        <td class="text-center">
                                            <span class='b' style='color: red;'>{{number_format($data->cart_total)}} VNĐ</span>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                switch ($data->cart_status) {
                                                    case 1:
                                                        echo "<span class='b' style='color: green;'>Mới đặt</span>";
                                                        break;
                                                    case 2:
                                                        echo "<span class='b' style='color: #ffa500;'>Đã xác nhận</span>";
                                                        break;
                                                    case 3:
                                                        echo "<span class='b' style='color: red;'>Đang giao</span>";
                                                        break;
                                                    case 4:
                                                        echo "<span class='b' style='color: #ffb100;'>Hoàn thành</span>";
                                                        break;
                                                    case 5:
                                                        echo "<span class='b' style='color: #0025db;'>Đã hủy</span>";
                                                        break;
                                                    default:
                                                        break;
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.orderDetail', array($data->cart_id)) }}" title="Xem chi tiết"><i class="far fa-eye"></i></a>
                                            <span class="mr-2 ml-2">|</span>
                                            <a href="{{ route('admin.printOrder', array($data->cart_id)) }}" title="Tải xuống"><i class="fas fa-download"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="fr">
                            {!! $data_order->links() !!}
                        </div>
                    </div> <!-- /.card-body -->
                </div><!-- /.card -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
</section>
@endsection
