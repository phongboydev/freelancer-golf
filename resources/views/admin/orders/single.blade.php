@extends('admin.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'Chi tiết đơn hàng: '.$order_detail->code,
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Chi tiết đơn hàng: '.$order_detail->code,
        'og_description' => Helpers::get_setting('seo_description'),
        'og_url' => Request::url(),
        'og_img' => asset('images/logo_seo.png'),
        'current_url' =>Request::url(),
        'current_url_amp' => ''
    );
    $seo = WebService::getSEO($data_seo);
    $currency = Helpers::get_option_minhnn('currency');
    $total_price = isset($order_detail->total) ? $order_detail->total : '';
    $details= $order_detail->order_details;
    ?>
    @include('admin.partials.seo')
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Chi tiết đơn hàng: {{ $order_detail->code }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Chi tiết đơn hàng: {{$order_detail->code}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('admin.storeOrderDetail', $order_detail->id) }}" method="POST" id="frm-order-detail">
                @csrf
                <div class="row">
                    <input type="hidden" name="id" value="{{ $order_detail->id }}">
                    <input type="hidden" name="order_id" value="{{ $order_detail->code }}">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Information</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <td style="width: 200px;">Order Number:</td>
                                        <td>{{ $order_detail->code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Full name:</td>
                                        <td>{{ $order_detail->first_name }} {{ $order_detail->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone:</td>
                                        <td>{{ $order_detail->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td>{{ $order_detail->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address:</td>
                                        <td>{{$order_detail->address}}</td>
                                    </tr>
                                    <tr>
                                        <td>Note:</td>
                                        <td>{{ $order_detail->note }}</td>
                                    </tr>
                                    <?php
                                    if($order_detail->discount_data != ''):
                                        $discountData = json_decode($order_detail->discount_data, true);
                                        $discountCode = $discountData['data'];
                                    ?>
                                    <tr>
                                        <td>Discount Code:</td>
                                        <td>
                                            {{ $discountCode['code'] }}<br>
                                            <?php
                                                if ($discountCode['percent'] == 0) {
                                                    echo 'Discount ' . WebService::formatMoney12($discountCode['fixed_price']);
                                                } else{
                                                    echo 'Discount ' . $discountCode['percent'] .'%';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Detail</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                @if ($details)
                                <?php
                                $count = 0;
                                ?>
                                <table class="table table-striped" id="tbl-order-detail">
                                    <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Product</th>
                                        <th>Image</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="3" class="col_tb_mb">
                                            <div class="tt_col mb-1">
                                                <span class="title_tt">Subtotal:</span>
                                                <span class="sum_price_default">
                                                    {{ WebService::formatMoney12($order_detail->subtotal) }}
                                                </span>
                                                {{ $currency }}
                                            </div>
                                            <div class="fee_ship mb-1">
                                                <span class="title_tt">Shipping:</span>
                                                <span class="sum_price_default">
                                                    {{ WebService::formatMoney12($order_detail->shipping_fee) }}
                                                </span>
                                                {{ $currency }}
                                            </div>
                                            <div class="totl_col mb-1">
                                                <span class="title_tt">Total:</span>
                                                <span class="sum_price">{{ WebService::formatMoney12($total_price) }}</span>
                                                {{ $currency }}
                                            </div>
                                        </td>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    foreach($order_detail->order_details as $detail):
                                    $count++;
                                    $variable = '';
                                    $variable_data = (!empty($detail->product_variants)) ? json_decode($detail->product_variants, true) : '';
                                    if ($variable_data != '') {
                                        unset($variable_data['product_name']);
                                        unset($variable_data['product_slug']);
                                        unset($variable_data['product_sku']);
                                        unset($variable_data['product_stock']);
                                        unset($variable_data['price_origin']);
                                        unset($variable_data['price_promotion']);
                                        unset($variable_data['thumbnail']);
                                        unset($variable_data['sort']);
                                        foreach ($variable_data as $key => $item_variable) {
                                            if ($variable == '') {
                                                $variable = ucfirst($key) . ': ' . $item_variable;
                                            } else {
                                                $variable .= ' | ' . ucfirst($key) . ': ' . $item_variable;
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td>{{ $count }}</td>
                                        <td style="border-left-color: rgb(203, 203, 203);">
                                            <a href="{{ Helpers::get_permalink_by_id($detail->product_id) }}">
                                                {{ $detail->product_name }} - {{ $detail->product_sku }} <br>
                                                <b>{!! $variable !!}</b>
                                            </a>
                                        </td>
                                        <td><img src="{{ asset('images/product/' . $detail->product_thumbnail) }}" height="50"/></td>
                                        <td align="center"><span style="color:#F00;">{{ WebService::formatMoney12($detail->price) }}</span> {{ $currency }}</td>
                                        <td align="center">
                                            <b>{{ $detail->quantity }}</b>
                                        </td>
                                        <td align="center"><span class="red">{{ WebService::formatMoney12($detail->total) }}</span> {{ $currency }}</td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                @endif
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">For Admin</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <tbody>
                                    <tr>
                                        <td>Note:</td>
                                        <td>
                                            <textarea id="admin_note" name="admin_note">
                                                {!! htmlspecialchars_decode($order_detail->admin_note) !!}
                                            </textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            <select name="order_status" class="form-control">
                                                @foreach(\App\Models\Order::Status as $key => $value)
                                                    <option value="{{ $value }}" @if($order_detail->status == $value) selected @endif>
                                                        {{ $key }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: right;">
                                            <input type="submit" name="btn_submit_order" class="btn btn-success" value="Save">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div> <!-- /.col -->
                </div> <!-- /.row -->
            </form>
        </div> <!-- /.container-fluid -->
    </section>

    <script>
        $(function () {
            // Summernote
            $('#admin_note').summernote({
                placeholder: 'Enter your note',
                tabsize: 2,
                focus: true,
                height: 200,
                codemirror: { // codemirror options
                    theme: 'monokai'
                }
            });
        })
    </script>
@endsection
