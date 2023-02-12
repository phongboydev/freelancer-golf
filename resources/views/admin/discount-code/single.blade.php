@extends('admin.layouts.app')
<?php
if(isset($detail)){
    $title = $detail->code;
    $code = $detail->code;
    $start_date = $detail->start_date;
    $expired = $detail->expired;
    $type = $detail->type;
    $percent = $detail->percent;
    $discount_money = $detail->discount_money;
    $apply_for_order = $detail->apply_for_order;
    $group_code = json_decode($detail->group_code, true);
    $status = $detail->status;
    $date_update = $detail->updated_at;
    $id = $detail->id;
} else{
    $title = 'Create Discount Code';
    $code = Helpers::auto_code_discount();
    $start_date  = '';
    $expired = "";
    $type = "";
    $group_code = [];
    $percent = 0;
    $discount_money = 0;
    $apply_for_order = 0;
    $status = \App\Constants\BaseConstants::ACTIVE;
    $date_update = date('Y-m-d h:i:s');
    $id = 0;
}
$count_group_code = 1;
?>
@section('seo')
<?php
$data_seo = array(
    'title' => $title.' | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => $title.' | '.Helpers::get_setting('seo_title'),
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
        <h1 class="m-0 text-dark">{{$title}}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">{{$title}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
  	<div class="container-fluid">
        <form action="{{route('admin.storeDiscountCodeDetail')}}" method="POST" id="frm-create-page" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$id}}">
    	    <div class="row">
    	      	<div class="col-9">
    	        	<div class="card">
    		          	<div class="card-header">
    		            	<h3 class="card-title">{{ $title }}</h3>
    		          	</div> <!-- /.card-header -->
    		          	<div class="card-body">
                            <!-- show error form -->
                            <div class="errorTxt"></div>
                            <div class="form-group">
                                <label for="code">Mã giảm giá</label>
                                <input type="text" class="form-control" id="code" name="code" placeholder="Mã giảm giá" value="{{ $code }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Ngày bắt đầu:</label>
                                        <div class="input-group date" id="start_date" data-target-input="nearest">
                                            <input type="text" name="start_date" class="form-control datetimepicker-input" data-target="#start_date" value="{{ $start_date }}">
                                            <div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày hết hạn:</label>
                                        <div class="input-group date" id="expired" data-target-input="nearest">
                                            <input type="text" name="expired" class="form-control datetimepicker-input" data-target="#expired" value="{{$expired}}">
                                            <div class="input-group-append" data-target="#expired" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="apply_for_order">Áp dụng cho đơn hàng có tổng giá trị:</label>
                                <input id="apply_for_order" type="text" name="apply_for_order" class="form-control" value="{{ $apply_for_order }}">
                            </div>

                            <div class="form-group">
                                <label for="order">Loại giảm giá</label>
                                <select name="type_discount" class="form-control">
                                    <option value="">Chọn loại giảm giá </option>
                                    <option value="onetime" @if($type == "onetime") selected @endif>Một lần</option>
                                    <option value="date" @if($type == "date") selected @endif>Theo thời gian</option>
                                </select>
                            </div>

                            <div class="hidden">
                                <select id="js-get-option-product">
                                    <option value="">Chọn sản phẩm</option>
                                    @foreach($listProducts as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->title }} ({{ $product->slug }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="repeat_group_code" class="mt-3">
                                @if(count($group_code) > 0)
                                    <?php
                                    $cd = 0;
                                    $count_group_code = count($group_code);
                                    ?>
                                    <input type="hidden" name="count_group_discount" id="count_group_discount"
                                           value="{{ $count_group_code }}">
                                    @for($i = 0; $i < $count_group_code; $i++)
                                        <?php
                                        $cd++;
                                        $group_item = $group_code[$i];
                                        $except_products = $group_item['except_products'];
                                        $apply_products = $group_item['apply_products'];
                                        ?>
                                            <div class="box-discount">
                                                <a href="javascript:void(0)" class="delete-box" onclick="removeGroupCode(this)">x</a>
                                                <div class="font-weight-bold text-center title_group_box_discount">Option Discount {{ $cd }}</div>
                                                <p class="font-weight-bold my-2">Nhập <span style="color:red">MỘT trong HAI</span> loại giảm giá sau:</p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Giảm theo phần trăm</label>
                                                            <input id="percent_{{ $cd }}" type="text" name="percent_{{ $cd }}"
                                                                   value="{{ $group_item['percent'] }}" class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="discount_money_{{ $cd }}">Giảm theo giá trị cụ thể (USD)</label>
                                                            <input id="discount_money_{{ $cd }}" type="text" name="discount_money_{{ $cd }}"
                                                                   value="{{ $group_item['discount_money'] }}" class="form-control"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h4 class="font-weight-bold">Chỉ chọn <span style="color: red">1 trong 2</span> loại áp dụng giảm giá sản phẩm. Nếu không nhập mã giảm giá sẽ áp dụng cho tất cả sản phẩm.</h4>
                                                <div class="form-group">
                                                    <label for="except_products">Áp dụng cho tất cả sản phẩm ngoại trừ:</label>
                                                    <select class="form-control" id="except_products_{{ $cd }}"
                                                            name="except_products_{{ $cd }}[]" multiple="multiple">
                                                        <option value="">Chọn sản phẩm</option>
                                                        @foreach($listProducts as $product)
                                                            <option value="{{ $product->id }}"
                                                                    @if(in_array($product->id, $except_products)) selected @endif>
                                                                {{ $product->title }} ({{ $product->slug }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="apply_products">Áp dụng cho các sản phẩm có trong list:</label>
                                                    <select class="form-control" id="apply_products_{{ $cd }}" name="apply_products_{{ $cd }}[]" multiple="multiple">
                                                        <option value="">Chọn sản phẩm</option>
                                                        @foreach($listProducts as $product)
                                                            <option value="{{ $product->id }}"
                                                                    @if(in_array($product->id, $apply_products)) selected @endif>
                                                                {{ $product->title }} ({{ $product->slug }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                jQuery(document).ready(function($) {
                                                    $('#apply_products_{{ $cd }}').select2();
                                                    $('#except_products_{{ $cd }}').select2();
                                                });
                                            </script>
                                    @endfor
                                @else
                                    <input type="hidden" name="count_group_discount" id="count_group_discount" value="1">
                                    <div class="box-discount">
                                        <a href="javascript:void(0)" class="delete-box" onclick="removeGroupCode(this)">x</a>
                                        <div class="font-weight-bold text-center title_group_box_discount">Option Discount 1</div>
                                        <p class="font-weight-bold my-2">Nhập <span style="color:red">MỘT trong HAI</span> loại giảm giá sau:</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="percent_1">Giảm theo phần trăm</label>
                                                    <input id="percent_1" type="text" name="percent_1" class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="discount_money_1">Giảm theo giá trị cụ thể (USD)</label>
                                                    <input id="discount_money_1" type="text" name="discount_money_1" class="form-control"/>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="font-weight-bold">Chỉ chọn <span style="color: red">1 trong 2</span> loại áp dụng giảm giá sản phẩm. Nếu không nhập mã giảm giá sẽ áp dụng cho tất cả sản phẩm.</h4>
                                        <div class="form-group">
                                            <label for="except_products_1">Áp dụng cho tất cả sản phẩm ngoại trừ:</label>
                                            <select class="form-control" id="except_products_1"
                                                    name="except_products_1[]" multiple="multiple">
                                                <option value="">Chọn sản phẩm</option>
                                                @foreach($listProducts as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->title }} ({{ $product->slug }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="apply_products_1">Áp dụng cho các sản phẩm có trong list:</label>
                                            <select class="form-control" id="apply_products_1" name="apply_products_1[]" multiple="multiple">
                                                <option value="">Chọn sản phẩm</option>
                                                @foreach($listProducts as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->title }} ({{ $product->slug }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        jQuery(document).ready(function($) {
                                            $('#apply_products_1').select2();
                                            $('#except_products_1').select2();
                                        });
                                    </script>
                                @endif
                            </div>
                            <div class="clear my-3">
                                <div class="fr">
                                    <a href="javascript:void(0)" class="btn btn-primary" onclick="addGroupCode()">Add Group</a>
                                </div>
                            </div>
    		        	</div> <!-- /.card-body -->
    	      		</div><!-- /.card -->
    	    	</div> <!-- /.col-9 -->
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Publish</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radioDraft" name="status" value="0" @if($status == 0) checked @endif>
                                    <label for="radioDraft">Inactive</label>
                                </div>
                                <div class="icheck-primary d-inline" style="margin-left: 15px;">
                                    <input type="radio" id="radioPublic" name="status" value="1" @if($status == 1) checked @endif>
                                    <label for="radioPublic">Active</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Date:</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" name="created" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{$date_update}}">
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div> <!-- /.col-9 -->
    	  	</div> <!-- /.row -->
        </form>
  	</div> <!-- /.container-fluid -->
</section>
<script type="text/javascript">
    jQuery(document).ready(function ($){
        //Date range picker
        $('#reservationdate').datetimepicker({
            format: 'YYYY-MM-DD hh:mm:ss'
        });

        $('#expired').datetimepicker({
            format: 'YYYY-MM-DD hh:mm:ss'
        });

        $('#start_date').datetimepicker({
            format: 'YYYY-MM-DD hh:mm:ss'
        });

        //xử lý validate
        $("#frm-create-page").validate({
            rules: {
                post_title: "required",
            },
            messages: {
                post_title: "Nhập mã giảm giá",
            },
            errorElement : 'div',
            errorLabelContainer: '.errorTxt',
            invalidHandler: function(event, validator) {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });
    });
</script>
@endsection
