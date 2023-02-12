<?php
use App\Libraries\Helpers;
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <style media="all">
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 0.875rem;
            font-family: 'Roboto', 'sans-serif';
            font-weight: normal;
            direction: ltr;
            text-align: left;
            padding: 0;
            margin: 0;
        }

        .gry-color *,
        .gry-color {
            color: #878f9c;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .25rem .7rem;
        }

        table.padding td {
            padding: .25rem .7rem;
        }

        table.sm-padding td {
            padding: .1rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .logo img {
            background: rgb(24, 72, 160);
            padding: 4px;
            border-radius: 5px;
            margin: 15px;
            width: 100px;
        }
    </style>
</head>
<body>
@foreach($orders as $order)
    <div style="margin-bottom: 40px">
        <div style="background: #eceff4;padding: 1rem;">
            <table>
                <tr>
                    <td class="logo">
                        <img src="{{ asset(Helpers::get_setting('logo')) }}" style="display:inline-block;">
                    </td>
                    <td style="font-size: 1.8rem;" class="text-right font-weight-bold">HOÁ ĐƠN BÁN HÀNG</td>
                </tr>
            </table>
            <table style="width: 100%">
                <tr>
                    <td style="font-size: 1rem; width: 50%" class="strong">{{ Helpers::get_setting('company_name') }}</td>
                    <td style="width: 50%" class="text-right"></td>
                </tr>
                <tr>
                    <td style="width: 50%" class="gry-color small"></td>
                    <td style="width: 50%" class="text-right"></td>
                </tr>
                <tr>
                    <td style="width: 50%" class="gry-color small"></td>
                    <td style="width: 50%" class="text-right small">
                        <span class="gry-color small">Mã hoá đơn:</span> <span class="strong">{{ $order->cart_code }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%" class="gry-color small">Điện thoại: {{ Helpers::get_setting('hotline') }}</td>
                    <td style="width: 50%" class="text-right small">
                        <span class="gry-color small">Ngày lập hoá đơn:</span> <span class=" strong">{{ date('d-m-Y', strtotime($order->created)) }}</span>
                    </td>
                </tr>
            </table>
        </div>
        <div style="padding: 1rem 1rem 0 1rem;">
            <table>
                <tr><td class="strong">Người mua hàng: {{ $order->cart_hoten }}</td></tr>
                <tr><td class="gry-color small">Địa chỉ: {{ $order->cart_address }}, {{ $order->cart_ward }}, {{ $order->cart_district }}, {{ $order->cart_province }}</td></tr>
                <tr><td class="gry-color small">Email: {{ $order->cart_email }}</td></tr>
                <tr><td class="gry-color small">Số điện thoại: {{ $order->cart_phone }}</td></tr>
            </table>
        </div>
        <div style="padding: 1rem;">
            <table class="padding text-left small border-bottom">
                <thead>
                <tr class="gry-color" style="background: #eceff4;">
                    <th width="30%" class="text-left">Tên sản phẩm</th>
                    <th width="10%" class="text-left">Số lượng</th>
                    <th width="15%" class="text-left">Giá gốc (VNĐ)</th>
                    <th width="15%" class="text-left">Giá KM (VNĐ)</th>
                    <th width="15%" class="text-left">Quà tặng</th>
                    <th width="15%" class="text-right">Thành tiền</th>
                </tr>
                </thead>
                <tbody class="strong">
                <?php $subtotal = 0; ?>
                @foreach ($order->order_details as $key => $orderDetail)
                    <?php
                    $subtotal += $orderDetail->total;
                    $gift = ($orderDetail->gift != '') ? json_decode($orderDetail->gift, true) : '';
                    $product_gift_name = '';
                    if ($gift != '') {
                        foreach($gift['gift_products'] as $gift_item) {
                            $product_gift = \App\Models\ProductStock::where('id', $gift_item)
                                ->first();
                            if ($product_gift) {
                                if ($product_gift_name == '') {
                                    $product_gift_name = $product_gift->title;
                                } else {
                                    $product_gift_name .= ', ' . $product_gift->title;
                                }
                            }
                        }
                    }
                    ?>
                    <tr class="">
                        <td>{{ $orderDetail->product_name }} @if($orderDetail->product_sku != '') ({{ $orderDetail->product_sku }}) @endif</td>
                        <td>{{ $orderDetail->quantity }}</td>
                        <td class="currency">{{ number_format($orderDetail->price_origin) }}₫</td>
                        <td class="currency">{{ number_format($orderDetail->price_promotion) }}₫</td>
                        <td>{{ $product_gift_name }}</td>
                        <td class="text-right currency">{{ number_format($orderDetail->total) }}₫</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:0 1.5rem;">
            <table class="text-right sm-padding small strong">
                <thead>
                <tr>
                    <th width="60%"></th>
                    <th width="40%"></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                    </td>
                    <td>
                        <table class="text-right sm-padding small strong">
                            <tbody>
                            <tr>
                                <th class="gry-color text-left">Thành tiền</th>
                                <td class="currency">{{ number_format($subtotal) }}₫</td>
                            </tr>
                            <tr>
                                <th class="gry-color text-left">Phí giao hàng</th>
                                <td class="currency">{{ number_format($order->shipping_fee) }}₫</td>
                            </tr>
                            <?php
                            $total_discount = $order->cart_total - $order->order_total_not_discount;
                            ?>
                            @if($total_discount > 0)
                                <tr class="border-bottom">
                                    <th class="gry-color text-left">Giảm giá</th>
                                    <td class="currency">{{ number_format($total_discount) }}₫</td>
                                </tr>
                            @endif
                            <tr>
                                <th class="text-left strong">Tổng cộng</th>
                                <td class="currency">{{ number_format($order->cart_total + $order->shipping_fee) }}₫</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endforeach
</body>
</html>
