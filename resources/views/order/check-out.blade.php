@extends('layouts.app')
@section('seo')
    <?php
    $title = 'Giỏ hàng | ' . Helpers::get_setting('seo_title');

    $description = $title . Helpers::get_setting('seo_description');
    $keyword = Helpers::get_setting('seo_keyword');
    $thumb_img_seo = Helpers::get_setting('seo_image');
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
    $agent = new  Jenssegers\Agent\Agent();
    $currency = Helpers::get_option_minhnn('currency');
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
                                    <div class="col-xs-2 d-xs-none col-sm-2 col-md-2 header-qty">Quantity</div>
                                    <div class="col-xs-2 d-xs-none  col-sm-2 col-md-2">Price</div>
                                    <div class="col-xs-6 col-md-2 col-sm-2 subtotal d-xs-none">Total</div>
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
                                    @if ($errors->any())
                                        <div class="mgt-10 alert alert-danger alert-dismissible">
                                            <button type="button" class="close"
                                                    data-dismiss="alert"
                                                    aria-label="Close"><span
                                                    aria-hidden="true">×</span></button>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>
                                                        <ion-icon name="close-outline"></ion-icon> {{ $error }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <?php
                                    $url_img = 'images/product/';
                                    ?>
                                    @if(count($cart) > 0)
                                        @foreach($cart as $cartItem)
                                            <?php
                                                $product_id = $cartItem['product_id'];
                                                $product = Helpers::getPriceProductStock($product_id);
                                            ?>
                                            @if($product)
                                                <?php
                                                    $thumbnail = asset($url_img . $product->thumbnail);
                                                    $name = $product->title;
                                                    $code = $product->sku;
                                                    $date_now = date("Y-m-d h:i:s");
                                                    $price = $product->final_price;
                                                    $quantity = $cartItem['quantity'];
                                                    $money = $quantity * $price;
                                                    $link = Helpers::get_permalink_by_id($product_id);
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
                                                <div class="row item clear">
                                                    <div class="col-md-2 col-sm-2 col-xs-4  p-col product-img">
                                                        <img src="{{ $thumbnail }}" alt="{{ $name }}">
                                                    </div>
                                                    <div class="col-xs-7 valign-table p-col col-md-4 col-sm-4">
                                                        <div class="valign-cell prod-description">
                                                            <div data-block="regular" class="d-xs-none">
                                                                <div data-custom="false" class="attr-row">
                                                                    <div class="attr-name productName" data-content="">
                                                                        <a href="{!! $link !!}"
                                                                           target="_blank" class="bold uppercase">
                                                                            {{ $name }}
                                                                        </a> <br>
                                                                        <span class="small-p small-p--black small-p--uppercase d-block"
                                                                              style="visibility: visible;">
                                                                            {!! $variable !!}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div data-block="mobile" class="d-lg-none d-md-none d-sm-none">
                                                                <div data-custom="false" class="attr-row">
                                                                    <div class="attr-name productName" data-content="">
                                                                        <a href="{!! $link !!}"
                                                                           target="_blank" class="bold uppercase">
                                                                            {{ $name }}
                                                                        </a> <br>
                                                                        <span class="small-p small-p--black small-p--uppercase d-block"
                                                                              style="visibility: visible;">
                                                                            {!! $variable !!}
                                                                        </span>
                                                                    </div>
                                                                </div>
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
                                                    <div class="col-xs-2 valign-table p-col col-md-2 d-xs-none col-sm-2">
                                                        <div class="valign-cell product-qty">{{ $quantity }}</div>
                                                    </div>
                                                    <div class="col-xs-2 valign-table p-col col-md-2 d-xs-none col-sm-2">
                                                        <div class="valign-cell product-price">{!! WebService::formatMoney12($price) !!}{!! $currency !!}</div>
                                                    </div>
                                                    <div class="col-xs-12 valign-table p-col col-md-2 col-sm-2 subtotal d-xs-none">
                                                        <div class="valign-cell product-price">
                                                            <div class="d-xs-none">
                                                                {!! WebService::formatMoney12($money) !!}{!! $currency !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="alert alert-danger">
                                            Giỏ hàng của bạn hiện đang trống. Hãy mua sắm thêm.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row justify-content-end" id="minitotalsContainer">
                                <div class="col-xs-5 col-md-2 minitotals-col-caption"><b>Tổng cộng</b></div>
                                <div class="col-xs-6 col-md-1 minitotals-col-price">{!! WebService::formatMoney12($cartTotal) !!}{!! $currency !!}</div>
                            </div>
                            <form action="{{ route('payment') }}" method="POST" id="geCheckoutFrm">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="checkout" value="true"/>
                                <input type="hidden" name="currency" value="{{ $currency }}"/>

                                <input type="hidden" name="cart_total" id="cart_total" value="{{ $cartTotal }}"/>
                                <div class="row clear">
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="sectionheader">
                                            <div class="generalhead">
                                                Địa chỉ thanh toán
                                            </div>
                                        </div>
                                        <div class="mb-3 row clear">
                                            <label for="full-name" class="col-sm-3 control-label">
                                                Họ(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="first-name" name="first_name"
                                                       value="{{ old('first_name') }}" required
                                                       placeholder="Họ của bạn">
                                            </div>
                                        </div>
                                        <div class="mb-3 row clear">
                                            <label for="full-name" class="col-sm-3 control-label">
                                                Tên(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="last-name" name="last_name"
                                                       value="{{ old('last_name') }}" required
                                                       placeholder="Tên của bạn">
                                            </div>
                                        </div>
                                        <div class="mb-3 row clear">
                                            <label for="phone" class="col-sm-3 control-label">
                                                Số điện thoại(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                       value="{{ old('phone') }}"
                                                       placeholder="0912345678">
                                            </div>
                                        </div>
                                        <div class="mb-3 row clear">
                                            <label for="your-email" class="col-sm-3 control-label">
                                                Email(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" id="your-email" name="email"
                                                       value="{{ old('email') }}"
                                                       placeholder="example@gmail.com">
                                            </div>
                                        </div>
                                        <div class="mb-3 row clear">
                                            <label for="zipcode" class="col-sm-3 control-label">
                                                Mã bưu chính(<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="zipcode" name="zipcode"
                                                       value="{{ old('zipcode') }}"
                                                       placeholder="Mã bưu chính">
                                            </div>
                                        </div>
                                        <div class="mb-3 row clear">
                                            <label for="address" class="col-sm-3 control-label">
                                                Địa chỉ giao hàng (<span class="hitsu">*</span>)
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="address" name="address"
                                                       value="{{ old('zipcode') }}"
                                                       placeholder="Địa chỉ giao hàng">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <textarea class="form-control" id="message" name="message"
                                                      placeholder="Ghi chú" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div id="billing_summary" class="payment_block">
                                    <div class="sectionheader">
                                        <div class="generalhead">
                                            Thành tiền
                                        </div>
                                    </div>

                                    <div class="box-inner row clear" id="voucherAndCouponsContainer">
                                        <div class="row clear">
                                            <div class="col-lg-8 col-md-8 col-xs-8" id="voucherFieldDiv">
                                                <input id="voucherInput" maxlength="200" class="form-control" type="text"
                                                       name="discount_code" placeholder="Nhập mã giảm giá">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-4">
                                                <input type="button" id="applyVoucherBtn" onclick="check_code_discount()" value="Áp dụng">
                                            </div>
                                        </div>
                                        <div id="voucherAndCouponsErrorsContainer"></div>
                                        <div id="voucherAndCouponsSuccessContainer"></div>
                                    </div>

                                    <?php
                                    $cartItem_tax = Helpers::get_option_minhnn('cart-tax');
                                    $delivery = 0;
                                    $tax = $cartTotal * $cartItem_tax / 100;
                                    $total_cart = $cartTotal + $tax + $delivery;
                                    ?>

                                    <div class="form-horizontal box-inner row clear" id="totalsContainer">
                                        <div class="row clear">
                                            <div class="col-lg-8 col-md-8 totals-col-caption">
                                                Tổng cộng
                                            </div>
                                            <div class="col-lg-4 col-md-4 totals-col-price"> {{ WebService::formatMoney12($cartTotal) }}{{ $currency }} </div>
                                        </div>
                                        <div class="total-seperator"></div>
                                        <div class="row clear">
                                            <div class="col-lg-8 col-md-8 totals-col-caption">
                                                Thuế
                                            </div>
                                            <div class="col-lg-4 col-md-4 totals-col-price" id="BillingSummaryTax"> {{ WebService::formatMoney12($tax) }}{{ $currency }} </div>
                                        </div>
                                        <div class="total-seperator"></div>
                                        <div class="row clear">
                                            <div class="col-lg-8 col-md-8 totals-col-caption">
                                                Phí giao hàng
                                            </div>
                                            <div class="col-lg-4 col-md-4 totals-col-price" id="BillingSummaryShipping"> {{ WebService::formatMoney12($delivery) }}{{ $currency }} </div>
                                        </div>
                                        <div class="total-seperator"></div>

                                        <div class="row clear totals-row-summary">
                                            <div class="col-lg-8 col-md-8 totals-col-caption bold" id="BillingSummaryTotalPriceLabel">
                                                Thành tiền
                                            </div>
                                            <div class="col-lg-4 col-md-4 totals-col-price" id="BillingSummaryTotalPrice">
                                                {{ WebService::formatMoney12($total_cart) }}{{ $currency }}
                                            </div>
                                        </div>
                                        <div class="total-seperator"></div>
                                    </div>
                                    <div id="paymentButtonBox">
                                        <div class="pay-button-wrapper text-center">
                                            <button type="submit" class="btn checkout-button-1" id="btnPay">Đặt hàng</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                    $("#geCheckoutFrm").validate({
                        onfocusout: false,
                        onkeyup: false,
                        onclick: false,
                        rules: {
                            first_name: "required",
                            last_name: "required",
                            phone: "required",
                            email: "required",
                            zipcode: "required",
                            address: "required"
                        },
                        messages: {
                            first_name: "First name is required.",
                            last_name: "Last name is required.",
                            phone: "Phone is required.",
                            email: "Email is required.",
                            zipcode: "Zipcode is required.",
                            address: "Address is required."
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
