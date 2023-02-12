@extends('user.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'Orders Details - ' . Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Orders Details - ' . Helpers::get_setting('seo_title'),
        'og_description' => Helpers::get_setting('seo_description'),
        'og_url' => Request::url(),
        'og_img' => url('/images/') . '/logo_1397577072.png',
    );
    $seo = WebService::getSEO($data_seo);
    ?>
    @include('partials.seo')
@endsection
@section('content')
    <?php
    $currency = \App\Libraries\Helpers::get_option_minhnn('currency');
    $total_price = $detail->total;

    $country_name = "";
    $state_name = "";
    $city_name = "";
    $ward_name = "";
    $country = App\Models\Country::where('id', $detail->country)->select('name')->first();
    if ($detail->country == \App\Constants\BaseConstants::VIETNAM_COUNTRY_ID) {
        $state = App\Models\ViettelPostProvince::where('id', $detail->province)->select('name')->first();
        $city = App\Models\ViettelPostDistrict::where('id', $detail->district)->select('name')->first();
        $ward = App\Models\ViettelPostWard::where('id', $detail->ward)->select('name')->first();
        if ($ward) {
            $ward_name = $ward->name;
        }
    } else {
        $state = App\Models\State::where('id', $detail->state)->select('name')->first();
        $city = App\Models\City::where('id', $detail->city)->select('name')->first();
    }

    if ($country) {
        $country_name = $country->name;
    }
    if ($state) {
        $state_name = $state->name;
    }
    if ($city) {
        $city_name = $city->name;
    }
    $time_order = $detail->created_at;
    ?>
    <div class="page-title">
        <h1>Orders Details {{ $detail->code }}</h1>
    </div>
    <h3 class="title-order-details">Purchase Details</h3>
    <div class="infor-shipping box-infor-order">
        <div class="row clear">
            <div class="col-md-4">
                <p><b>Order Number:</b> {{ $detail->code }}</p>
                <p><b>Order Date:</b> {{ date('M d, Y', strtotime($time_order)) }}</p>
                <p><b>Email:</b> {{ $detail->email }}</p>
                <p><b>Phone:</b> {{ $detail->phone }}</p>
            </div>
            <div class="col-md-4">
                <p>
                    <b>Shipping Address:</b><br>
                    {{ $detail->first_name }} {{ $detail->first_name }}<br>
                    {{ $detail->address}}<br>
                    {{ $country_name }}, {{ $state_name }}, {{ $city_name }}@if($ward_name != ''), {{ $ward_name }} @endif
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <b>Payment:</b><br>
                    <?php
                    $payment_method = isset($detail->payment_method) ? $detail->payment_method : '';
                    switch ($payment_method):
                        case "sacombank":
                            echo "Bank Transfer";
                            break;
                        default:
                            echo "Unknown";
                    endswitch;
                    ?> <br>
                    {{ $detail->payment_type }}
                </p>
            </div>
        </div>
    </div>
    <h3 class="title-order-details" style="padding-top: 30px;">Return & Exchange</h3>
    <div class="box-return-order">
        <!-- Button return modal -->
        @if(session()->has('request_return_send'))
            <div class="alert alert-success request_return_send">
                {{ session()->get('request_return_send') }}
            </div>
        @endif
        @if(session()->has('return_error'))
            <div class="alert alert-danger return_error">
                {{ session()->get('return_error') }}
            </div>
        @endif
        <button type="button" class="btn-return-order"
            @if(empty($detail->order_return_history) || $detail->order_return_history->process == 0)
                data-toggle="modal" data-target="#return_order_modal"
            @else
                disabled="true"
            @endif
        >
            Request Return
        </button>
    </div>
    @if($detail->status == 4)
        <?php
        $shipping_order = \App\Models\ShippingOrder::where('order_id', $detail->cart_id)->first();
        if($shipping_order):
        $message = json_decode($shipping_order->message);
        ?>
        <h3 class="title-order-details" style="padding-top: 30px;">Tracking Order</h3>
        <div class="box-return-order">
            <a class="btn-return-order" target="_blank" style="display: inline-block;"
               href="https://www.dhl.com/vn-en/home/tracking/tracking-express.html?submit=1&tracking-id={{ $message->ShipmentIdentificationNumber }}">
                View Tracking
            </a>
        </div>
        <?php endif; ?>
    @endif
    <h3 class="title-order-details" style="padding-top: 30px;">Product Details</h3>
    <div class="table-responsive table-product-detail">
        <table class="table tbl-my-reviews">
            <thead>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Quantily</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $j = 0;
            $count = 0;
            $bg_child_tb = "";
            ?>

            @foreach($detail->order_details as $orderDetail)
                <?php
                $j++;
                $count++;
                $product_id = $orderDetail->product_id;
                $product = \App\Libraries\Helpers::getProductStockById($product_id);
                $product_thumbnail = '';
                $product_link = '';
                if ($product) {
                    $product_thumbnail = asset('images/product/' . $product->thumbnail);
                    $product_link = \App\Libraries\Helpers::get_permalink_by_id($product_id);
                }
                if ($count % 2 == 0) {
                    $bg_child_tb = "bg_chan";
                } else {
                    $bg_child_tb = "bg_le";
                }

                $variable = '';
                $variable_data = (!empty($product->variable_data)) ? json_decode($product->variable_data, true) : '';
                if ($variable_data != '') {
                    unset($variable_data['product_name']);
                    unset($variable_data['product_slug']);
                    unset($variable_data['product_sku']);
                    unset($variable_data['product_stock']);
                    unset($variable_data['price_origin']);
                    unset($variable_data['price_promotion']);
                    unset($variable_data['thumbnail']);
                    foreach ($variable_data as $key => $item_variable) {
                        if ($variable == '') {
                            $variable = ucfirst($key) . ': ' . $item_variable;
                        } else {
                            $variable .= ' | ' . ucfirst($key) . ': ' . $item_variable;
                        }
                    }
                }
                ?>
                <tr class="{{ $bg_child_tb }}">
                    <td style="border-left-color: rgb(203, 203, 203);">
                        <a href="{{ $product_link }}" target="_blank">{{ $orderDetail->product_name }}</a><br/>
                        <span class="small-p small-p--black small-p--uppercase d-block"
                              style="visibility: visible;">
                            {!! $variable !!}
                        </span>
                    </td>
                    <td>
                        <a href="{{ $product_link }}" target="_blank">
                            <img src="{{ $product_thumbnail }}" style="max-height: 250px;" alt="{{ $orderDetail->product_name }}"/>
                        </a>
                    </td>
                    <td align="center">
                        <b>{{ $orderDetail->quantity }}</b>
                    </td>
                    <td align="center">
                        <span class="red">{!! \App\WebService\WebService::formatMoney12($orderDetail->total) !!}</span> {{ $currency }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <p><b>Subtotal:</b></p>
                    <p><b>Tax:</b></p>
                    <p><b>Shipping:</b></p>
                    <p><b>Total:</b></p>
                </td>
                <td style="text-align: right;">
                    <p>{!! number_format($detail->subtotal) !!} {{ $currency }}</p>
                    <p>{!! number_format($detail->tax) !!} {{ $currency }}</p>
                    <p>{!! number_format($detail->shipping_fee) !!} {{ $currency }}</p>
                    <p>{!! number_format($detail->total) !!} {{ $currency }}</p>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <!-- Modal Return-->
    <div class="modal fade" id="return_order_modal" tabindex="-1" role="dialog"
         aria-labelledby="return_order_modal_Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="return_order_modal_Label">HOW TO REQUEST A RETURN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frm_return_order" method="POST" action="{{ route('user.returnOrder') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="order_id" value="{{ $detail->id }}">
                        <div style="margin-bottom: 15px">
                            To request a return, click the “Return” button next to the items you’d like to send back and select the reason for return from the dropdown menu. When you’ve selected the items you'd like to return, click the “Submit Request” button at the bottom of this page. If you have any questions please see our Return Policy.
                        </div>
                        <div class="form-group">
                            <label for="reason_for_return">REASON FOR RETURN (REQUIRED)</label>
                            <select name="reason_for_return" id="reason_for_return" required class="form-control">
                                <option value="FIT - TOO LARGE">FIT - TOO LARGE</option>
                                <option value="FIT - TOO SMALL">FIT - TOO SMALL</option>
                                <option value="FIT - TOO LONG">FIT - TOO LONG</option>
                                <option value="FIT - TOO SHORT">FIT - TOO SHORT</option>
                                <option value="ORDERED MULTIPLE SIZES TO COMPARE">ORDERED MULTIPLE SIZES TO COMPARE</option>
                                <option value="DID NOT LIKE FABRIC">DID NOT LIKE FABRIC</option>
                                <option value="CHANGED MY MIND">CHANGED MY MIND</option>
                                <option value="PRODUCT DID NOT MATCH THE WEBSITE">PRODUCT DID NOT MATCH THE WEBSITE</option>
                                <option value="RECEIVED WRONG ITEM">RECEIVED WRONG ITEM</option>
                                <option value="RECEIVED AS A GIFT">RECEIVED AS A GIFT</option>
                                <option value="DAMAGED">DAMAGED</option>
                                <option value="NOT WHAT I EXPECTED">NOT WHAT I EXPECTED</option>
                                <option value="OTHER">OTHER</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="return_email">Email</label>
                            <?php
                            if (Auth::check()) {
                                $order_email = Auth::user()->email;
                            } else {
                                $order_email = $detail->email;
                            }
                            ?>
                            <input type="email" name="return_email" id="return_email" value="{{ $order_email }}"
                                   class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="return_email">Reason</label>
                            <textarea class="form-control" name="return_reason"></textarea>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit"
                                    class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
