@extends('layouts.app')
@section('seo')
    <?php
    if (LaravelLocalization::getCurrentLocale() == 'en') {
        $title = 'Checkout | ' . Helpers::get_setting('seo_title');
    } else {
        $title = 'Thanh toán | ' . Helpers::get_setting('seo_title');
    }

    $description = $title . Helpers::get_setting('seo_description');
    $keyword = 'gio hang, add to cart, cart,' . Helpers::get_setting('seo_keyword');
    $thumb_img_seo = asset(Helpers::get_setting('seo_image'));
    $data_seo = array(
        'title' => $title,
        'keywords' => $keyword,
        'description' => $description,
        'og_title' => $title,
        'og_description' => $description,
        'og_url' => Request::url(),
        'og_img' => $thumb_img_seo,
        'current_url' => Request::url(),
        'current_url_amp' => ''
    );
    $seo = WebService::getSEO($data_seo);
    $request = new Request();

    $agent = new  Jenssegers\Agent\Agent();

    if (LaravelLocalization::getCurrentLocale() == 'en') {
        $currency = Helpers::get_option_minhnn('currency');
    } else {
        $currency = Helpers::get_option_minhnn('currency-vi');
    }
    ?>
    @include('partials.seo')
@endsection
@section('content')
    <div class="breadcrumbs-group-container clear">
        <div class="container clear">
            <div class="breadcrumbs_top_page clear">
                <div class="breadcrumbs-item fl">
                    {!! Breadcrumbs::render('checkout') !!}
                </div>
            </div>
        </div>
    </div>
    <!--home-index-->
    <div class="main_content clear">
        <div class="body-container none_padding border-group clear">
            <section id="section" class="section clear">
                <div class="group-section-wrap clear ">
                    <div class="container">
                        <div class="checkoutWrapper">
                            <div class="boxWrapper">
                                <div class="row clear sectionheader" id="productsHeader">
                                    <div class="col-xs-12 col-sm-6 col-md-6 generalhead">Order Summary</div>
                                    <div class="col-xs-2 hidden-xs col-sm-2 col-md-2 header-qty">Quantity</div>
                                    <div class="col-xs-2 hidden-xs  col-sm-2 col-md-2">Price</div>
                                    <div class="col-xs-6 col-md-2 col-sm-2 subtotal hidden-xs">Total</div>
                                </div>
                                <div id="productContainer">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @if (session('payment-error'))
                                        <div class="alert alert-danger">
                                            {!! session()->get('payment-error') !!}
                                        </div>
                                    @endif
                                    <?php
                                    $url_img = 'images/product/';
                                    ?>
                                    @if(Cart::content()->count()>0)
                                        @foreach(Cart::content() as $cart)
                                            <?php
                                                $product_id = $cart->id;
                                                $product = Helpers::get_product_by_id($product_id);
                                            ?>
                                            @if($product)
                                                <?php
                                                    $thumbnail = asset($url_img . $product->thubnail);
                                                    $name = $product->title;
                                                    $code = $product->theme_code;
                                                    $date_now = date("Y-m-d h:i:s");
                                                    $price = $cart->price;
                                                    $quantity = $cart->qty;
                                                    $money = $quantity * $price;
                                                    $variable = $cart->options;
                                                    $json_variable = "";
                                                    $variable_html = "";
                                                    if (isset($variable) && count($variable) > 0) {
                                                        $count_option_arr = count($variable);
                                                        $id_variable_parent = 0;
                                                        $id_variable_child = 0;
                                                        for ($j = 0; $j < $count_option_arr; $j++) {
                                                            $json_variable = json_decode($variable[$j]);
                                                            if (!WebService::objectEmpty($json_variable)) {
                                                                $id_variable_parent = $json_variable->parent_id;
                                                                $id_variable_child = $json_variable->id;
                                                                $title_variable_parent = Helpers::get_title_variable_theme_by_id($id_variable_parent);
                                                                $title_variable_child = Helpers::get_title_variable_theme_by_id($id_variable_child);
                                                                if ($id_variable_parent > 0 && $id_variable_child > 0) {
                                                                    $variable_html .= '<div style="margin-top:10px" class="attr-row">
                                                                        <div class="attr-key">' . Helpers::translate_no_object($title_variable_parent) . ':</div>
                                                                        <div class="attr-value">' . $title_variable_child . '</div>
                                                                    </div>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                                <div class="row item clear">
                                                    <div class="col-md-2 col-sm-2 col-xs-4  p-col product-img">
                                                        <img src="{{ $thumbnail }}" alt="{{ $name }}">
                                                    </div>
                                                    <div class="col-xs-7 valign-table p-col col-md-4 col-sm-4">
                                                        <div class="valign-cell prod-description">
                                                            <div data-block="regular" class="hidden-xs">
                                                                <div data-custom="false" class="attr-row">
                                                                    <div class="attr-name productName" data-content="">
                                                                        <a href="{!! Helpers::get_permalink_by_id($product_id) !!}"
                                                                           target="_blank" class="bold uppercase">
                                                                            {{ $name }}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                {!! $variable_html !!}
                                                            </div>

                                                            <div data-block="mobile" class="hidden-lg hidden-md hidden-sm">
                                                                <div data-custom="false" class="attr-row">
                                                                    <div class="attr-name productName" data-content="">
                                                                        <a href="{!! Helpers::get_permalink_by_id($product_id) !!}"
                                                                           target="_blank" class="bold uppercase">
                                                                            {{ $name }}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                {!! $variable_html !!}
                                                                <div class="prod-summary-info attr-row">
                                                                    <span data-resource="checkout.ProductQty" class="qtyText">Quantity</span>:
                                                                    <b data-custom-prop="Quantity" class="qtyValue">{{ $quantity }}</b>
                                                                    <div data-custom="false" class="attr-row">
                                                                        <div class="attr-key attr-total-key">Total:</div>
                                                                        <div class="attr-value attr-total-value prod-price-mobile" data-content="">
                                                                            {!! WebService::formatMoney12($money) !!}{!! $currency !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2 valign-table p-col col-md-2 hidden-xs col-sm-2">
                                                        <div class="valign-cell product-qty">{{ $quantity }}</div>
                                                    </div>
                                                    <div class="col-xs-2 valign-table p-col col-md-2 hidden-xs col-sm-2">
                                                        <div class="valign-cell product-price">{!! WebService::formatMoney12($price) !!}{!! $currency !!}</div>
                                                    </div>
                                                    <div class="col-xs-12 valign-table p-col col-md-2 col-sm-2 subtotal hidden-xs">
                                                        <div class="valign-cell product-price">
                                                            <div class="hidden-xs">
                                                                {!! WebService::formatMoney12($money) !!}{!! $currency !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="row clear" id="minitotalsContainer">
                                <div class="col-xs-6 col-md-1 minitotals-col-price">{!! WebService::formatMoney12(Cart::total()) !!}{!! $currency !!}</div>
                                <div class="col-xs-5 col-md-2 minitotals-col-caption"><b>Items total</b></div>
                            </div>
                            <?php
                            $user_check = 0;
                            if (Auth::guard('customer')->check()) {
                                $user_check = 1;
                                $user = Auth::guard('customer')->user();
                                $full_name = $user->first_name . ' ' . $user->last_name;
                                $first_name = $user->first_name;
                                $last_name = $user->last_name;
                                $phone = $user->phone;
                                $email = $user->email;
                                $address = $user->address;
                                $country = $user->country;
                                if ($country != 240) {
                                    $state = $user->state;
                                    $city = $user->city;
                                    $ward = 0;
                                } else {
                                    $state = $user->province;
                                    $city = $user->district;
                                    $ward = $user->ward;
                                }
                                $zipcode = $user->zipcode;
                            } else {
                                $country = 0;
                                $state = 0;
                                $city = 0;
                                $ward = 0;
                            }
                            ?>
                            <form action="{{ route('checkoutPayment') }}" method="POST" id="geCheckoutFrm">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="checkout" value="true"/>
                                <input type="hidden" name="language_check" value="{!! LaravelLocalization::getCurrentLocale() !!}"/>
                                <input type="hidden" name="cart_total" id="cart_total" value="{{ Cart::total() }}"/>
                                <div class="row clear">
                                    <div class="col-lg-6 col-md-12 col-12">
                                        <div class="sectionheader">
                                            <div class="generalhead">
                                                Billing Address
                                            </div>
                                        </div>
                                        <div class="form-group row clear">
                                            <label for="full-name" class="col-sm-3 control-label">
                                                {!! Helpers::translate_no_object('First Name') !!}(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="first-name" name="first_name"
                                                       value="@if($user_check == 1) {{ $first_name }} @endif" required
                                                       placeholder="{!! Helpers::translate_no_object('Enter your first name') !!}">
                                            </div>
                                        </div>
                                        <div class="form-group row clear">
                                            <label for="full-name" class="col-sm-3 control-label">
                                                {!! Helpers::translate_no_object('Last Name') !!}(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="last-name" name="last_name"
                                                       value="@if($user_check == 1) {{ $last_name }} @endif" required
                                                       placeholder="{!! Helpers::translate_no_object('Enter your last name') !!}">
                                            </div>
                                        </div>
                                        <div class="form-group row clear">
                                            <label for="phone" class="col-sm-3 control-label">
                                                {!! Helpers::translate_no_object('Phone') !!}(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                       value="@if($user_check == 1) {{ $phone }} @endif"
                                                       placeholder="{!! Helpers::translate_no_object('Enter your Phone Number') !!}">
                                            </div>
                                        </div>
                                        <div class="form-group row clear">
                                            <label for="your-email" class="col-sm-3 control-label">
                                                Email(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" id="your-email" name="email"
                                                       value="@if($user_check == 1) {{ $email }} @endif"
                                                       placeholder="{!! Helpers::translate_no_object('Enter your Email') !!}">
                                            </div>
                                        </div>
                                        <div class="form-group use-for-vn row clear">
                                            <label for="slt_countries" class="col-sm-3 control-label">
                                                {!! Helpers::translate_no_object('Countries') !!}(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <select name="slt_countries" id="slt_countries" class="form-control">
                                                    <option value="">{!! Helpers::translate_no_object('Select Countries') !!}</option>
                                                    <?php
                                                    $data_countries = App\Model\Countries::orderBy('name', 'ASC')
                                                        ->select('id', 'name')
                                                        ->get();
                                                    ?>
                                                    @foreach($data_countries as $item)
                                                        @if($item->id == $country)
                                                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group use-for-vn row clear">
                                            <label for="slt_states" class="col-sm-3 control-label">
                                                {!! Helpers::translate_no_object('States') !!}
                                            </label>
                                            <div class="col-sm-9">
                                                <select name="slt_states" id="slt_states" class="form-control">
                                                    @if($country != 0)
                                                        {!! WebService::getOptionStateByID($country, $state) !!}
                                                    @else
                                                        <option value="">{!! Helpers::translate_no_object('Select States') !!}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group use-for-vn row clear">
                                            <label for="slt_cities" class="col-sm-3 control-label">
                                                {!! Helpers::translate_no_object('Cities') !!}
                                            </label>
                                            <div class="col-sm-9">
                                                <select name="slt_cities" id="slt_cities" class="form-control">
                                                    @if($state != 0 && $country != 0)
                                                        {!! WebService::getOptionCityByID($country, $state, $city) !!}
                                                    @else
                                                        <option value="">{!! Helpers::translate_no_object('Select Cities') !!}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row clear">
                                            <label for="slt_wards" class="col-sm-3 control-label ward-control @if($country == 240) active @endif">
                                                {!! Helpers::translate_no_object('Wards') !!}(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9 ward-control @if($country == 240) active @endif">
                                                <select name="slt_wards" id="slt_wards" class="form-control" style="width: 100% !important;">
                                                    @if($state != 0 && $country == 240 && $city != '')
                                                        {!! WebService::getOptionWardByID($city, $ward) !!}
                                                    @else
                                                        <option value="">{!! Helpers::translate_no_object('Select Wards') !!}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row clear">
                                            <label for="zipcode" class="col-sm-3 control-label">
                                                {!! Helpers::translate_no_object('Zipcode') !!}(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="zipcode" name="zipcode"
                                                       value="@if($user_check == 1) {{ $zipcode }} @endif"
                                                       placeholder="{!! Helpers::translate_no_object('Zipcode') !!}">
                                            </div>
                                        </div>
                                        <div class="form-group row clear">
                                            <label for="address" class="col-sm-3 control-label">
                                                {!! Helpers::translate_no_object('Shipping Address') !!} (<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="address" name="address"
                                                       value="@if($user_check == 1) {{ $address }} @endif"
                                                       placeholder="{!! Helpers::translate_no_object('Shipping Address') !!}">
                                            </div>
                                        </div>
                                        @if($user_check == 0)
                                            <div class="form-group">
                                                <label for="register_account" class="control-label">
                                                    <b>{!! Helpers::translate_no_object('Register account') !!}?</b>
                                                </label>
                                                <input type="checkbox" id="register_account" name="register_account" value="1"
                                                       style="margin-left: 10px;">
                                            </div>
                                            <div class="register_account_password">
                                                <div class="form-group row clear">
                                                    <label for="address" class="col-sm-3 control-label">
                                                        {!! Helpers::translate_no_object('Password') !!}
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" id="password" name="password">
                                                    </div>
                                                </div>
                                                <div class="form-group row clear">
                                                    <label for="address" class="col-sm-3 control-label">
                                                        {!! Helpers::translate_no_object('Confirm Password') !!}
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <textarea class="form-control" id="message" name="message"
                                                      placeholder="{!! Helpers::translate_no_object('Billing information') !!}"
                                                      rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-12">
                                        <div class="sectionheader">
                                            <div class="generalhead">
                                                Delivery Address
                                            </div>
                                        </div>
                                        <div style="padding-left: 30px">
                                            <div class="radio radio-box radio-box-checked">
                                                <input checked="checked" class="custom-radio-input" id="shippingDefault"
                                                       name="shipping_type" type="radio" value="ShippingSameAsBilling">
                                                <label class="custom-radio-label" for="shippingDefault"><b>Default (same as billing address)</b></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="payment_method" class="payment_block">
                                    <div class="sectionheader">
                                        <div class="generalhead">
                                            Payment
                                        </div>
                                    </div>
                                    <div id="paymentMessageContainer">
                                        Please choose your payment method
                                    </div>
                                    <div class="box-option-payment">
                                        <div style="padding-left: 30px">
                                            <div class="radio radio-box radio-box-checked">
                                                <input checked="checked" class="custom-radio-input" id="shipping_method_1" name="shipping_method" type="radio" value="bank">
                                                <label class="custom-radio-label" for="shipping_method_1"><b>Bank/Visa/Mastercard</b></label>
                                            </div>
                                        </div>
                                        <div style="padding-left: 30px">
                                            <div class="radio radio-box radio-box-checked">
                                                <input class="custom-radio-input" id="shipping_method_2" name="shipping_method" type="radio" value="paypal">
                                                <label class="custom-radio-label" for="shipping_method_2"><b><i class="fa fa-paypal" aria-hidden="true"></i> Paypal</b></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="billing_summary" class="payment_block">
                                    <div class="sectionheader">
                                        <div class="generalhead">
                                            BILLING SUMMARY
                                        </div>
                                    </div>

                                    <div class="box-inner row clear" id="voucherAndCouponsContainer">
                                        <div class="row clear">
                                            <div class="col-xs-8" id="voucherFieldDiv">
                                                <input id="voucherInput" maxlength="200" class="form-control" type="text"
                                                       name="discount_code" placeholder="Please enter coupon code">
                                            </div>
                                            <div class="col-xs-4">
                                                <input type="button" id="applyVoucherBtn" onclick="check_code_discount()" value="Apply">
                                            </div>
                                        </div>
                                        <div id="voucherAndCouponsErrorsContainer"></div>
                                        <div id="voucherAndCouponsSuccessContainer"></div>
                                    </div>

                                    <?php
                                    if ($country > 0) {
                                        $user_countries = App\Model\Countries::where('id', $country)
                                            ->select('id', 'name', 'iso2')
                                            ->first();
                                        if (LaravelLocalization::getCurrentLocale() == 'en') {
                                            if ($user_countries->iso2 == 'VN' || strtoupper($user_countries->iso2) == 'vn') {
                                                $cart_tax = Helpers::get_option_minhnn('cart-tax-for-vn');
                                                if (Cart::total() > Helpers::get_option_minhnn('free-ship-for-vietnamese-bill')) {
                                                    $delivery = 0;
                                                } else {
                                                    $delivery = Helpers::get_option_minhnn('shipping-in-vietnam');
                                                }
                                            } else {
                                                $cart_tax = Helpers::get_option_minhnn('cart-tax');
                                                if (Cart::total() > Helpers::get_option_minhnn('free-ship-for-international-bill')) {
                                                    $delivery = 0;
                                                } else {
                                                    $delivery = Helpers::get_option_minhnn('international-shipping');
                                                }
                                            }
                                        } else {
                                            if ($user_countries->iso2 == 'VN' || strtoupper($user_countries->iso2) == 'vn') {
                                                $cart_tax = Helpers::get_option_minhnn('cart-tax-for-vn');
                                                if (Cart::total() > Helpers::get_option_minhnn('free-ship-for-vietnamese-bill-language-vi')) {
                                                    $delivery = 0;
                                                } else {
                                                    $delivery = Helpers::get_option_minhnn('shipping-in-vietnam-language-vi');
                                                }
                                            } else {
                                                $cart_tax = Helpers::get_option_minhnn('cart-tax');
                                                if (Cart::total() > Helpers::get_option_minhnn('free-ship-for-international-bill-language-vi')) {
                                                    $delivery = 0;
                                                } else {
                                                    $delivery = Helpers::get_option_minhnn('international-shipping-language-vi');
                                                }
                                            }
                                        }
                                    } else {
                                        $cart_tax = Helpers::get_option_minhnn('cart-tax');
                                        $delivery = 0;
                                    }
                                    $tax = Cart::total() * $cart_tax / 100;
                                    $total_cart = Cart::total() + $tax + $delivery;
                                    ?>

                                    <div class="form-horizontal box-inner row clear" id="totalsContainer">
                                        <div class="row clear">
                                            <div class="col-xs-8 totals-col-caption">
                                                Items total
                                            </div>
                                            <div class="col-xs-4 totals-col-price"> {{ WebService::formatMoney12(Cart::total()) }}{{ $currency }} </div>
                                        </div>
                                        <div class="total-seperator"></div>
                                        <div class="row clear">
                                            <div class="col-xs-8 totals-col-caption">
                                                Tax
                                            </div>
                                            <div class="col-xs-4 totals-col-price" id="BillingSummaryTax"> {{ WebService::formatMoney12($tax) }}{{ $currency }} </div>
                                        </div>
                                        <div class="total-seperator"></div>
                                        <div class="row clear">
                                            <div class="col-xs-8 totals-col-caption">
                                                Shipping
                                            </div>
                                            <div class="col-xs-4 totals-col-price" id="BillingSummaryShipping"> {{ WebService::formatMoney12($delivery) }}{{ $currency }} </div>
                                        </div>
                                        <div class="total-seperator"></div>

                                        <div class="row clear totals-row-summary">
                                            <div class="col-xs-8 totals-col-caption bold" id="BillingSummaryTotalPriceLabel">
                                                Total For Your Order
                                            </div>
                                            <div class="col-xs-4 totals-col-price" id="BillingSummaryTotalPrice">
                                                {{ WebService::formatMoney12($total_cart) }}{{ $currency }}
                                            </div>
                                        </div>
                                        <div class="total-seperator"></div>
                                    </div>

                                    <div class="form-group group-policy">
                                        @if(LaravelLocalization::getCurrentLocale() == 'en')
                                            <label for="policy">
                                                <input type="checkbox" name="policy" id="policy" value="1" required>
                                                I agree to the <a href="{{ route('category.list', 'privacy-policy') }}">
                                                    Privacy Policy
                                                </a>
                                                and <a href="{{ route('category.list', 'policies-and-general-provisions') }}">
                                                    Terms of Use
                                                </a>.
                                            </label>
                                        @else
                                            <label for="policy">
                                                <input type="checkbox" name="policy" id="policy" value="1" required>
                                                Tôi đồng ý với các <a href="{{ route('category.list', 'privacy-policy') }}">
                                                    Điều Khoản Bảo Mật
                                                </a>
                                                và <a href="{{ route('category.list', 'policies-and-general-provisions') }}">
                                                    Điều Kiện
                                                </a>
                                                khi thanh toán.
                                            </label>
                                        @endif
                                    </div>

                                    <div id="paymentButtonBox">
                                        <div class="pay-button-wrapper text-center">
                                            <button type="submit" class="btn checkout-button-1" id="btnPay">Pay and place order</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--rightContent-->
                </div>
            </section>
            <!--section-->
            <script type="text/javascript">
                String.prototype.replaceAll = function (search, replace) {
                    //if replace is not sent, return original string otherwise it will
                    //replace search string with 'undefined'.
                    if (replace === undefined) {
                        return this.toString();
                    }

                    return this.replace(new RegExp('[' + search + ']', 'g'), replace);
                };

                function formatNumber(num) {
                    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
                }

                jQuery(document).ready(function ($) {


                    // validate country
                    jQuery.validator.addMethod("validateByCountry", function (value, element) {
                        var country_id = $('#slt_countries').val();
                        if (country_id == 240) {
                            if ($('#slt_wards').val() === '') {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    });
                    $("#geCheckoutFrm").validate({
                        onfocusout: false,
                        onkeyup: false,
                        onclick: false,
                        rules: {
                            first_name: "required",
                            last_name: "required",
                            phone: "required",
                            email: "required",
                            slt_countries: 'required',
                            slt_wards: {
                                validateByCountry: true
                            },
                            zipcode: "required",
                            address: "required",
                            policy: "required"
                        },
                        messages: {
                            first_name: "First name is required.",
                            last_name: "Last name is required.",
                            phone: "Phone is required.",
                            email: "Email is required.",
                            slt_countries: "Country is required.",
                            slt_wards: {
                                validateByCountry: "Ward is required."
                            },
                            zipcode: "Zipcode is required.",
                            address: "Address is required.",
                            policy: "Policy is required."
                        },
                        errorElement: 'div',
                        errorLabelContainer: '.errorTxt',
                        invalidHandler: function (event, validator) {
                            $('html, body').animate({
                                scrollTop: 0
                            }, 500);
                        }
                    });
                    $("#geCheckoutFrm #submit").on('click', function () {
                        if ($("#geCheckoutFrm").valid()) {
                            //stop submitting the form to see the disabled button effect
                            $('#geCheckoutFrm').submit();
                            $('.payment-form').css('opacity', '0.3');
                            $('#geCheckoutFrm .loading').addClass('loader');
                            //disable the submit button
                        }
                    });
                });
            </script>
        </div>
        <!--body-container-->
    </div>
    <!--main_content-->
@endsection
