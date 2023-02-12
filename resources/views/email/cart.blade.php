<?php
    $country_sql = App\Models\Country::where('id', $details['country'])->select('name')->first();
    if ($details['country'] == 240) {
        $state_sql = App\Models\ViettelPostProvince::where('id', $details['state'])->select('name')->first();
        $city_sql = App\Models\ViettelPostDistrict::where('id', $details['city'])->select('name')->first();
    } else {
        $state_sql = App\Models\State::where('id', $details['state'])->select('name')->first();
        $city_sql = App\Models\City::where('id', $details['city'])->select('name')->first();
    }

    $currency = Helpers::get_option_minhnn('currency');
    $order_details = $details['order_details'];
?>
Hi {{$details['name_email_admin']}},
<h4>THÔNG TIN KHÁCH HÀNG</h4>
<p>________________________________<i style="color: #F00;">♥♥♥</i>__________________________________</p>
<p>Họ tên: {{ $details['name'] }}</p>
<p>Số điện thoại: {{ $details['phone'] }}</p>
<p>E-mail: {{ $details['email'] }}</p>
<p>Country: @if($country_sql) {{ $country_sql->name }} @endif</p>
<p>State: @if($state_sql) {{ $state_sql->name }} @endif</p>
<p>City: @if($city_sql) {{ $city_sql->name }} @endif</p>
<p>Zipcode: {{ $details['zipcode'] }}</p>
<p>Địa chỉ: {{ $details['address'] }}</p>
<p>Mã đơn hàng: <b>{{ $details['code'] }}</b></p>
<p>Hình thức thanh toán: <b><i style="color: #F00;"><?php
   switch($details['cart_pay_method']):
      case "paypal":
         echo "Thanh toán qua Paypal";
      break;
      case "bank":
         echo "Thanh toán bằng thẻ";
      break;
      default:
         echo "Không xác định";
   endswitch;
 ?></i></b></p>
@if($details['cart_pay_method'] == 'bank')
<p>
  <b>Mã giao dịch VTCPAY:</b>
  <?php
      $vtc_trans = \App\Models\VtcPayTransaction::where('order_id', $details['order_id'])->first();
      if($vtc_trans){
          echo $vtc_trans->transaction_id;
      }
  ?>
</p>
@endif
 <p>Trạng thái đơn hàng: <?php
  if ($details['payment_status'] == \App\Models\Order::PaymentStatus['NotPaid']) {
    echo '<b>Chưa thanh toán</b>';
  } else {
    echo '<b style="color: #F00;">Đã thanh toán</b>';
  }
 ?></p>
<p>Ghi chú: {{ $details['note'] }}</p>
@if($details['discount'] > 0)
<p>Mã giảm giá: <b style="color: #F00;">{{$details['discountCode']}} (-{{$details['discount']}}<i>{!! $currency !!}</i>)</b></p>
@endif
<p>Tổng tiền: <b style="color: #F00;">{!!WebService::formatMoney12($details['total'])!!}</b> {!! $currency !!}</p>
<p>________________________________<i style="color: #F00;">♥♥♥</i>__________________________________</p>
<h4>CHI TIẾT ĐƠN HÀNG</h4>
<table width="100%" border="0" style="border: 1px solid #eae8e8;empty-cells: 0px;border-spacing: 0px;border-spacing: 0px;border-collapse: collapse;">
<tr>
    <th style="background: #adadad; text-align: center; padding:10px 15px; text-transform: uppercase; border: 1px solid #e5e5e5;">Tên SP</td>
    <th style="background: #adadad; text-align: center; padding:10px 15px; text-transform: uppercase; border: 1px solid #e5e5e5;">Giá</td>
    <th style="background: #adadad; text-align: center; padding:10px 15px; text-transform: uppercase; border: 1px solid #e5e5e5;">Số lượng</td>
    <th style="background: #adadad; text-align: center; padding:10px 15px; text-transform: uppercase; border: 1px solid #e5e5e5;">Thành tiền</td>
</tr>
@if(count($order_details) > 0)
    @foreach($order_details as $detail)
        <tr>
            <td align="center" valign="middle" style="border: 1px solid #eaeaea;">

                <?php
                  $quantity = $detail->quantity;
                  $product = Helpers::getPriceProductStock($detail->product_id);
                  $link_product = Helpers::get_permalink_by_id($detail->product_id);
               ?>
               <p><a href="{!! $link_product !!}"  target="_blank"> {{ $product->title }}</a></p>
            </td>
            <td align="center" valign="middle" style="border: 1px solid #eaeaea;"><b style="color: #F00;">
                    {!! WebService::formatMoney12($detail->price) !!}</b>  {!! $currency !!}
            </td>
            <td align="center" valign="middle" style="border: 1px solid #eaeaea;">
                <b><i style="color: #000;">{{ $quantity }}</i></b>
            </td>
            <td align="center" valign="middle" style="border: 1px solid #eaeaea;">
                <b style="color: #F00;">{!!WebService::formatMoney12($detail->total)!!}</b> <i>{!! $currency !!}</i>
            </td>
        </tr>
    @endforeach
@endif
<tr>
    <td colspan="4" align="center" valign="middle" style="border: 1px solid #eaeaea; padding:5px 10px;">
        @if($details['discount'] > 0)
        <p><b>Giảm giá:</b> <b style="color: #F00;"> -{!!WebService::formatMoney12($details['discount'])!!} </b> {!! $currency !!}</p>
        @endif
        <p><b>Tổng tiền:</b> <b style="color: #F00;">{!!WebService::formatMoney12($details['total'])!!} </b> {!! $currency !!}</p>
    </td>
</tr>
</table>
<p>--</p>
<p>Thanks and Best Regards,</p>
<h4>Olaben</h4>
