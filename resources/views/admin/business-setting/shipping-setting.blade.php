@extends('admin.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'Cài đặt vận chuyển | '.Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Cài đặt vận chuyển | '.Helpers::get_setting('seo_title'),
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
                    <h1 class="m-0 text-dark">Cài đặt vận chuyển</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('index')}}">Home</a></li>
                        <li class="breadcrumb-item active">Cài đặt vận chuyển</li>
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
                            <h3 class="card-title">Cài đặt vận chuyển</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            @if(Session::has('success_msg'))
                                <div class="mgt-10  alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    {{ Session::get('success_msg') }}
                                </div>
                            @endif
                            <form action="{{route('admin.storeShippingSetting')}}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div style="border: 1px solid #ccc; padding: 10px; border-radius: 6px">
                                            <h4 class="mb-2 text-center"><b>Cài đặt chung</b></h4>
                                            <div class="form-group">
                                                <label for="shipping_fee_for_hn_hcm">Phí giao hàng cho HN - HCM</label>
                                                <input type="number" name="shipping_fee_for_hn_hcm"
                                                       id="shipping_fee_for_hn_hcm" class="form-control"
                                                       value="{{ Helpers::get_setting('shipping_fee_for_hn_hcm') }}"
                                                       placeholder="VNĐ">
                                            </div>
                                            <div class="form-group">
                                                <label for="shipping_fee">Phí giao hàng cho các tỉnh khác</label>
                                                <input type="number" name="shipping_fee"
                                                       id="shipping_fee" class="form-control"
                                                       value="{{ Helpers::get_setting('shipping_fee') }}"
                                                       placeholder="VNĐ">
                                            </div>
                                            <div class="form-group">
                                                <label for="free_ship_for_total_bill">Miễn phí vận chuyển cho hoá đơn: <b>(Mặc định)</b></label>
                                                <input type="number" name="free_ship_for_total_bill"
                                                       id="free_ship_for_total_bill" class="form-control"
                                                       value="{{ Helpers::get_setting('free_ship_for_total_bill') }}"
                                                       placeholder="VNĐ">
                                            </div>
                                            <div class="bg-box">
                                                <div class="form-group">
                                                    <label for="free_ship_start_date">Thời gian bắt đầu</label>
                                                    <input type="date" class="form-control" name="free_ship_start_date" id="free_ship_start_date"
                                                           value="{{ Helpers::get_setting('free_ship_start_date') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="free_ship_end_date">Thời gian kết thúc</label>
                                                    <input type="date" class="form-control" name="free_ship_end_date" id="free_ship_end_date"
                                                           value="{{ Helpers::get_setting('free_ship_end_date') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="free_ship_apply_for_bill">Áp dụng cho hoá đơn</label>
                                                    <input type="number" name="free_ship_apply_for_bill"
                                                           id="free_ship_apply_for_bill" class="form-control"
                                                           value="{{ Helpers::get_setting('free_ship_apply_for_bill') }}"
                                                           placeholder="VNĐ">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <input type="submit" class="btn btn-primary" value="Lưu">
                                </div>
                            </form>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </section>
@endsection
