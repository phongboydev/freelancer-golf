@extends('layouts.app')
@section('seo')
<?php
    $title = 'Shopping cart | '.Helpers::get_setting('seo_title');
    $description=$title.Helpers::get_setting('seo-description-add');
    $keyword = Helpers::get_setting('seo_keyword');
    $thumb_img_seo = Helpers::get_setting('seo_image');
    $data_seo = array(
       'title' => $title,
       'keywords' => $keyword,
       'description' =>$description,
       'og_title' => $title,
       'og_description' => $description,
       'og_url' => Request::url(),
       'og_img' => $thumb_img_seo,
       'current_url' => Request::url(),
       'current_url_amp' => ''
    );
    $seo = WebService::getSEO($data_seo);
    $currency = Helpers::get_option_minhnn('currency');
    $agent = new  Jenssegers\Agent\Agent();
   ?>
@include('partials.seo')
@endsection
@section('content')
<div class="breadcrumbs-group-container clear">
   <div class="container clear">
      <div class="breadcrumbs_top_page clear">
         <div class="breadcrumbs-item fl">
            {!! Breadcrumbs::render('cart') !!}
         </div>
      </div>
   </div>
</div>
<!--home-index-->
<div class="main_content clear">
   <div class="body-container none_padding border-group clear" id="shoppingBag">
      <section id="section" class="section clear">
         <div class="group-section-wrap clear ">
            <div class="container_cart clear">
               <div class="container clear" id="cartPage">
                  @if(!empty($cart) && count($cart) > 0)
                  <div class="row">
                     <div class="shopping-bag-content col-12 col-lg-8 col-md-12">
                        <div class="headings">
                           <div class="flex middle headings__row clear">
                              <h1 class="h5 text-uppercase" style="width: 50%">
                                 SHOPPING BAG
                              </h1>
                              <div class="pre-headline--right pre-headline text-uppercase cart-item-count text-right" style="width: 50%; font-size: 16px">
                                 <span class="cart-count liquid--10">{{ count($cart) }}</span> <span class="cart-count-text">ITEM</span>
                              </div>
                           </div>
                        </div>
                        <div class="shopping-bag-block shopping-bag-block__new">
                           <div class="cart-items cart-page">
                              <?php
                                $url_img_sp = 'images/product/';
                                $cartTotal = Helpers::getCartTotal($cart);
                              ?>
                              @foreach($cart as $cartItem)
                                 @if($cartItem['quantity'] > 0)
                                    <?php
                                       $product = Helpers::getPriceProductStock($cartItem['product_id']);
                                    ?>
                                    @if($product)
                                        <?php
                                        $id = $cartItem['product_id'];
                                        $name = $product->title;
                                        $sku = $product->sku;
                                        $date_now = date("Y-m-d h:i:s");
                                        $price = $product->final_price;
                                        $quantity = $cartItem['quantity'];
                                        $money = $quantity * $price;
                                        $thumbnail = asset('images/product/' . $product->thumbnail);
                                        $product_link = Helpers::get_permalink_by_id($cartItem['product_id']);
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
                                            <div class="CartItemShell cart-item">
                                                <div class="shopping-bag-item shopping-bag-item__new ">
                                                    <div class="flex__wrapper--row mobile p-l-0 p-r-0">
                                                        <h5 class="list-header text-uppercase item-title__list-header">
                                                            <a href="{{ $product_link }}">
                                                                {{ $name }}
                                                            </a>
                                                        </h5>
                                                        <div class="col-md-2 col-sm-2 col-4 text-right item price__wrapper--new p-r-0">
                                                   <span class="price " style="visibility: visible;">
                                                      <span data-total-id="{{ $id }}">{!! WebService::formatMoney12($money) !!}</span>{!! $currency !!}
                                                   </span>
                                                        </div>
                                                    </div>
                                                    <div class="VariantDisplayUI clear">
                                                        <div class="item-img item-img__new">
                                                            <a href="{{ $product_link }}">
                                                                <img src="{{ $thumbnail }}" alt="{{ $name }}">
                                                            </a>
                                                        </div>
                                                        <div class="item-descr">
                                                            <div class="row clear">
                                                                <div class="col-md-6 col-sm-8 col-12 item">
                                                                    <h5 class="list-header text-uppercase">
                                                                        <a class="pc product_title--link" href="#">
                                                                            {{ $name }}
                                                                        </a>
                                                                    </h5>
                                                                    <span class="small-p small-p--black small-p--uppercase d-block" style="visibility: visible;">
                                                                        {!! $variable !!}
                                                                    </span><br>
                                                                    <span class="small-p small-p--black small-p--uppercase d-block" style="visibility: visible;">
                                                                        SKU: {{ $sku }}
                                                                    </span>
                                                                    <div class="bottom-spacing"></div>
                                                                </div>
                                                                <div class="col-md-4 col-sm-5 col-12 item text-right pc pr-0">
                                                         <span class="quantity__wrapper ">
                                                            <button class="button__minus--new pc qty-down" type="button" aria-label="Decrement quantity">
                                                               <svg class="icon__minus--new icon__increment" xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink" width="9" height="3"><defs><path id="a" d="M156.5 474v9"></path></defs><use fill="#fff" fill-opacity="0" stroke="#ccc" stroke-miterlimit="50" stroke-width="1.5" href="#a" transform="rotate(90 319 164)"></use></svg>
                                                            </button>
                                                               <input type="text" readonly="true" data-step="1" data-min="0"
                                                                      data-id-pro="{{ $id }}" name="qty[{{ $id }}]" value="{{ $quantity }}"
                                                                      class="item__quantity input-text text qty-val"
                                                                      aria-label="Quantity input">
                                                            <button class="button__plus--new pc qty-up" type="button" aria-label="Increment quantity">
                                                               <svg class="icon__plus--new icon__increment" xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink" width="9" height="9" viewBox="0 0 9 9"><defs><path id="xnwwa" d="M203.5 474v9"></path></defs><g><g transform="translate(-199 -474)"><g><use fill="#fff" fill-opacity="0" stroke="#ccc" stroke-miterlimit="50" stroke-width="1.5" href="#xnwwa"></use></g><g transform="rotate(-270 203.5 478.5)"><use fill="#fff" fill-opacity="0" stroke="#ccc" stroke-miterlimit="50" stroke-width="1.5" href="#xnwwa"></use></g></g></g></svg>
                                                            </button>
                                                         </span>
                                                                </div>
                                                                <div class="col-md-2 col-sm-2 col-12 text-right item price__wrapper--new pl-0 pc">
                                                         <span class="price" style="visibility: visible;">
                                                            <span data-total-id="{{ $id }}">{!! WebService::formatMoney12($money) !!}</span>{!! $currency !!}
                                                         </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item__buttons">
                                                   <span class="quantity__wrapper mobile">
                                                      <button class="button__minus--new qty-down" type="button" aria-label="Decrement quantity">
                                                         <svg class="icon__minus--new icon__increment" xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink" width="9" height="3"><defs><path id="a" d="M156.5 474v9"></path></defs><use fill="#fff" fill-opacity="0" stroke="#ccc" stroke-miterlimit="50" stroke-width="1.5" href="#a" transform="rotate(90 319 164)"></use></svg>
                                                      </button>
                                                      <input type="text" readonly="true" data-step="1" data-min="0"
                                                             data-id-pro="{{ $id }}" name="qty[{{ $id }}]" value="{{ $quantity }}"
                                                             class="item__quantity input-text text qty-val"
                                                             aria-label="Quantity input">
                                                      <button class="button__plus--new qty-up" type="button" aria-label="Increment quantity">
                                                         <svg class="icon__plus--new icon__increment" xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink" width="9" height="9" viewBox="0 0 9 9"><defs><path id="xnwwa" d="M203.5 474v9"></path></defs><g><g transform="translate(-199 -474)"><g><use fill="#fff" fill-opacity="0" stroke="#ccc" stroke-miterlimit="50" stroke-width="1.5" href="#xnwwa"></use></g><g transform="rotate(-270 203.5 478.5)"><use fill="#fff" fill-opacity="0" stroke="#ccc" stroke-miterlimit="50" stroke-width="1.5" href="#xnwwa"></use></g></g></g></svg>
                                                      </button>
                                                   </span>
                                                            <a href="javascript:void(0)" class="remove remove__from__cart remove__from__cart--cart" onclick="removeCartItem({{ $id }})"
                                                               aria-label="Remove Item" data-product_id="{{ $id }}" data-product_sku="{{ $sku }}"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endif
                                 @endif
                              @endforeach
                           </div>
                        </div>
                     </div>
                     <div class="shopping-bag-sidebar shopping-bag-sidebar__new sidebar col-12 col-lg-4 col-md-12">
                        <div class="block block__new block__new--order-summary d-md-block">
                           <h3 class="pre-headline text-uppercase sidebar__title">ORDER SUMMARY</h3>
                           <div class="subtotal__wrapper">
                              <div class="small-p small-p--black" ge-dom-editor-item-handled="">
                                 Subtotal
                              </div>
                              <p class="small-p small-p--black cart__total--price" style="visibility: visible;">
                                 {!! WebService::formatMoney12($cartTotal) !!}{!! $currency !!}
                              </p>
                           </div>

                           <div class="d-flex urgency__notice urgency__notice--cart">
                              <svg width="17" height="17" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" stroke="#000" fill="none" fill-rule="evenodd"><circle cx="7.5" cy="7.5" r="7.5"></circle><path d="M7.348 2.5v5.188M10.103 9.309L7.5 8" stroke-linecap="square"></path></g></svg>
                              <div class="small-p">Items in bag are not held and may sell out</div>
                           </div>

                           <div class="d-flex urgency__notice urgency__notice--cart standard-shipping-message">
                              <svg xmlns="http://www.w3.org/2000/svg" width="23" height="17">
                                 <path fill="none" stroke="#000" strokemiterlimit="50" d="M5 2a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1z"></path>
                                 <path d="M2 4h9v1H2zM0 6h9v1H0zM2 8h9v1H2z"></path>
                                 <path fill="none" stroke="#000" strokemiterlimit="50" d="M16 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-5a.5.5 0 0 1-.5-.5z"></path>
                                 <path fill="#fff" d="M18 5h1v1h-1z"></path>
                                 <path fill="none" stroke="#fff" strokemiterlimit="50" d="M18 5h1v1h-1z"></path>
                                 <path fill="none" stroke="#000" strokemiterlimit="50" d="M16 5v4h5.621c.086 0 .17-.026.237-.073.163-.115.273.217.142.073l-3.115-3.875A.4.4 0 0 0 18.589 5z"></path>
                                 <path fill="#fff" d="M7 16a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                                 <path fill="none" stroke="#000" strokemiterlimit="50" d="M7 16a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                                 <g>
                                    <path fill="#fff" d="M19 16a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                                    <path fill="none" stroke="#000" strokemiterlimit="50" d="M19 16a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                                 </g>
                              </svg>
                              <div class="small-p shipping__free">
                                 <div>
                                    <span class="AloShippingEstimate_message_wrapper">{!! Helpers::checkShipping() !!}</span>
                                 </div>
                              </div>
                           </div>
                           <div>
                              <a href="{{ route('checkout') }}" class="main-button main-button--uppercase">CHECKOUT</a>
                           </div>
                        </div>
                        <div class="block block__new">
                           <details>
                              <summary>
                                 <div class="subtitle__wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="21" viewBox="0 0 20 21"><defs><path id="4k1pa" d="M1307.772 868.5v11.97l-8.352 2.645v-11.962zm-17.133 0l3.96 1.255-.037 3.159a.217.217 0 0 0 .06.153c.04.04.095.064.151.065a.217.217 0 0 0 .153-.062.217.217 0 0 0 .064-.151l.035-3.03 3.967 1.258v11.968l-8.353-2.646zm12.956-1.46l3.683 1.167-8.063 2.562-3.693-1.17zm-4.382-1.391l3.683 1.166-8.08 2.559-3.674-1.164zm-.006-.439a.214.214 0 0 0-.066.01l-8.733 2.774a.216.216 0 0 0-.125.053.216.216 0 0 0-.072.239v12.34a.216.216 0 0 0 .15.204l8.753 2.772a.216.216 0 0 0 .152.01h.004l8.78-2.78a.216.216 0 0 0 .15-.205v-12.419-.006-.003a.216.216 0 0 0-.183-.205l-8.747-2.773a.215.215 0 0 0-.063-.01z"></path></defs><g><g transform="translate(-1289 -864)"><use xlink:href="#4k1pa"></use><use fill="#fff" fill-opacity="0" stroke="#000" stroke-miterlimit="50" stroke-width=".5" xlink:href="#4k1pa"></use></g></g></svg>
                                    <h3 class="pre-headline text-uppercase w-100"
                                        id="cart-sidebar-returns-heading">
                                        Returns
                                    </h3>
                                    <span class="arrow arrow-right arrow-right--up"></span>
                                 </div>
                              </summary>
                              <div id="cart-sidebar-returns-copy" class="description description__new small-p">
                                  <p>You have 30 days from your order date to request a return. Once you have requested a return, you have 7 days to ship your item(s).
                                      “How do I return an item?” <a href="{{ route('category.list', 'return-and-refund') }}">Here are the deets</a></p>
                              </div>
                           </details>
                        </div>

                         <div class="block block__new">
                             <details>
                                 <summary>
                                     <div class="subtitle__wrapper">
                                         <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="17" height="20" viewBox="0 0 17 20"><defs><path id="tpp1a" d="M1299.326 714.61h-5.926c-1.906 0-3.4-1.625-3.4-3.698v-5.302c0-.162.133-.29.296-.29h12.148c.164 0 .297.128.297.29 0 .162-.133.29-.297.29h-11.851v5.012c0 1.533 1.05 3.118 2.807 3.118h5.926c1.661 0 3.118-1.458 3.118-3.118v-2.108c0-.163.133-.291.297-.291.163 0 .296.128.296.29v2.109c0 1.968-1.734 3.698-3.71 3.698z"></path><path id="tpp1e" d="M1287.04 702.42h18.963v15.096h-18.963z"></path><path id="tpp1b" d="M1302.146 705.61a.292.292 0 0 1-.297-.29v-3.118c0-2.777-2.602-4.431-5.118-4.431h-.74c-2.786 0-4.808 1.864-4.808 4.43v3.119c0 .077-.031.15-.087.205a.298.298 0 0 1-.21.084.292.292 0 0 1-.296-.29v-3.117c0-2.904 2.271-5.012 5.4-5.012h.741c3.202 0 5.71 2.2 5.71 5.012v3.118c.001.077-.03.15-.086.205a.298.298 0 0 1-.21.084z"></path><path id="tpp1g" d="M1287.63 694.29h17.778v14.226h-17.778z"></path><path id="tpp1c" d="M1296.362 711.862a.312.312 0 0 1-.208-.08l-2.176-2.12a.288.288 0 0 1-.002-.412.307.307 0 0 1 .42 0l1.993 1.94 8.684-6.365a.302.302 0 0 1 .397.072.287.287 0 0 1-.041.393l-8.89 6.514a.288.288 0 0 1-.177.058z"></path><path id="tpp1i" d="M1290.93 701.87h17.583v12.903h-17.583z"></path><clipPath id="tpp1d"><use xlink:href="#tpp1a"></use></clipPath><clipPath id="tpp1f"><use xlink:href="#tpp1b"></use></clipPath><clipPath id="tpp1h"><use xlink:href="#tpp1c"></use></clipPath></defs><g><g transform="translate(-1289 -696)"><g><g><g><use xlink:href="#tpp1a"></use><use fill="#fff" fill-opacity="0" stroke="#000" stroke-miterlimit="50" stroke-width=".75" xlink:href="#tpp1a"></use></g><g clip-path="url(#tpp1d)"><use xlink:href="#tpp1e"></use></g></g><g><g><use fill="#fff" fill-opacity="0" stroke="#000" stroke-miterlimit="50" stroke-width=".75" xlink:href="#tpp1b"></use></g><g clip-path="url(#tpp1f)"><use fill="#0a0b09" xlink:href="#tpp1g"></use></g></g><g><g><g><use fill="#fff" fill-opacity="0" stroke="#000" stroke-miterlimit="50" stroke-width=".75" xlink:href="#tpp1c"></use></g><g clip-path="url(#tpp1h)"><use fill="#0a0b09" xlink:href="#tpp1i"></use></g></g></g></g></g></g></svg>
                                         <h3 class="pre-headline text-uppercase w-100">SECURE PAYMENTS</h3>
                                         <span class="arrow arrow-right arrow-right--up"></span>
                                     </div>
                                 </summary>
                                 <p class="description description__ne small-p">
                                     Payment information is transferred according to the highest security standards, your credit card details will be completely encrypted.
                                 </p>
                             </details>
                         </div>

                         <div class="block block__new">
                             <details>
                                 <summary>
                                     <div class="subtitle__wrapper">
                                         <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="18" viewBox="0 0 20 18"><defs><path id="rmeaa" d="M1305.865 879.119h-5.306a.6.6 0 0 0-.384.138l-3.138 2.869v-2.43a.566.566 0 0 0-.565-.578h-4.424c-1.06 0-1.92-.878-1.92-1.966v-8.029c0-1.087.858-1.967 1.92-1.967h13.817c1.06 0 1.92.88 1.92 1.967v8.029c0 1.087-.86 1.967-1.92 1.967zm0-13.119h-13.817c-1.67 0-3.048 1.41-3.048 3.123v8.029c0 1.712 1.377 3.123 3.048 3.123h3.86v3.147c0 .232.136.44.339.532a.777.777 0 0 0 .226.046.6.6 0 0 0 .383-.139l3.906-3.586h5.08c1.67 0 3.048-1.412 3.048-3.123v-8.029c.023-1.712-1.355-3.123-3.025-3.123z"></path></defs><g><g transform="translate(-1289 -866)"><g><use xlink:href="#rmeaa"></use></g></g></g></svg>
                                         <h3 class="pre-headline text-uppercase w-100">NEED HELP?</h3>
                                         <span class="arrow arrow-right arrow-right--up"></span>
                                     </div>
                                 </summary>
                                 <p class="description description__new description__new--margin small-p">
                                     <a href="/contact"><u>Visit our FAQ page</u></a> or call us at <a href="tel:0899992088 "><u>089 999 20 88</u></a>
                                     Monday through Saturday from 8:00am - 5:00PM.</p>
                                 <p class="description description__new small-p">
                                     <a href="mailto:customercare@olaben.com"><u>Email us</u></a> anytime: customercare@olaben.com. If you contact us after business hours, we will get back to you the following business day.
                                 </p>
                                 <p class="description description__new description__new--margin small-p">Let’s chat! We offer live support Monday through Saturday from 8:00am-5:00pm.</p>
                             </details>
                         </div>
                     </div>
                  </div>
                  @else
                     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Cart!</strong> Your shopping cart is currently empty.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  @endif
               </div>
            </div>
            <!--leftContent-->
            <div class="container clear">
               @include('layouts.footer_public')
            </div>
            <!--rightContent-->
         </div>
      </section>
      <!--section-->
      <script type="text/javascript">
         String.prototype.replaceAll = function(search, replace)
         {
            //if replace is not sent, return original string otherwise it will
            //replace search string with 'undefined'.
            if (replace === undefined) {
               return this.toString();
            }
            return this.replace(new RegExp('[' + search + ']', 'g'), replace);
         };

         function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
         }

         jQuery(document).ready(function($) {
            // validate country
            //event
            /*
             $('body').on('DOMSubtreeModified', '#price_subtotal', function(){
                if($(this).text() !=''){
                   var pricesubtotal=$(this).text().replaceAll(',', '');
                   var language=$('input[name="language_check"]').val();
                   var price_total=$('.total_cart_tt').html().replace(',', '');
                   //console.log('price_curent:'+pricesubtotal);
                   //console.log('total1:'+price_total);
                   if(language =='en')
                   {
                      //Buy 150$ get 15% off
                      //Buy 350$ get 20% off
                      //Buy 550$ get 25%  off
                      if(pricesubtotal >= 1500){
                         //console.log('>550');
                           $('#note_event_view').html('<span class="sales-rule-message">You scored 25% off!</span>');
                           var discount=25*pricesubtotal/100;
                           //var total_end=price_total-discount;
                           $('#cart_subtotal_discount_event').html('-$'+parseFloat(discount));
                           $('#discount_applied_event_view').show();

                      }else if(pricesubtotal >= 500){
                         //console.log('>350');
                         price_total=$('.total_cart_tt').text().replace(',', '');
                         $('#note_event_view').html('<span class="sales-rule-message">' +
                                 'You scored 15% off! You are $'+parseFloat(1500-pricesubtotal)+' from getting 25% off your sale items!</span>');
                         var discount=15*pricesubtotal/100;
                         var total_end=price_total-discount;
                         $('#cart_subtotal_discount_event').html('-$'+parseFloat(discount));
                         $('#discount_applied_event_view').show();
                      }else if(pricesubtotal >= 200){
                         //console.log('>150');
                         $('#note_event_view').html('<span class="sales-rule-message">' +
                                 'You scored 10% off! You are $'+parseFloat(500-pricesubtotal)+' from getting 15% off your sale items!</span>');
                         var discount=10*pricesubtotal/100;
                         $('#cart_subtotal_discount_event').html('-$'+parseFloat(discount));
                         $('#discount_applied_event_view').show();
                         //console.log('total end:'+total_end);
                      }else{
                         //console.log('<150');
                         $('#discount_applied_event_view').hide();
                         $('#cart_subtotal_discount_event').empty();
                         $('#note_event_view').html('<span class="sales-rule-message">' +
                                 'You are $'+parseFloat(200-pricesubtotal)+' from getting 10% off your sale items!</span>');
                      }
                   }
                   else
                   {
                      //console.log(pricesubtotal);
                      //Mua 3 triệu - giảm 15%
                      //Mua 7 triệu - giảm 20%
                      //Mua 12 triệu - giảm 25%
                      if(pricesubtotal >= 30000000){
                         $('#note_event_view').html('<span class="sales-rule-message">Đơn hàng của bạn được giảm 25%!</span>');
                         var discount=25*pricesubtotal/100;
                         //var total_end=price_total-discount;
                         $('#cart_subtotal_discount_event').html('- '+formatNumber(parseInt(discount))+' &#8363;');
                         $('#discount_applied_event_view').show();
                      }else if(pricesubtotal >= 10000000){
                         //console.log('>350');
                         price_total=$('.total_cart_tt').text().replace(',', '');
                         $('#note_event_view').html('<span class="sales-rule-message">' +
                                 'Đơn hàng của bạn được giảm giá 15%! Bạn cần mua thêm '+formatNumber(parseInt(30000000-pricesubtotal))+' &#8363; để được giảm giá 25%!</span>');
                         var discount=15*pricesubtotal/100;
                         var total_end=price_total-discount;
                         $('#cart_subtotal_discount_event').html('- '+formatNumber(parseInt(discount))+' &#8363;');
                         $('#discount_applied_event_view').show();
                      }else if(pricesubtotal >= 3000000){
                         //console.log('>150');
                         $('#note_event_view').html('<span class="sales-rule-message">' +
                                 'Đơn hàng của bạn được giá giá 10%! Bạn cần mua thêm '+formatNumber(parseInt(10000000-pricesubtotal))+' &#8363; để được giảm giá 15%!</span>');
                         var discount=10*pricesubtotal/100;
                         $('#cart_subtotal_discount_event').html('- '+formatNumber(parseInt(discount))+' &#8363;');
                         $('#discount_applied_event_view').show();
                         //console.log('total end:'+total_end);
                      }else{
                         //console.log('<150');
                         $('#discount_applied_event_view').hide();
                         $('#cart_subtotal_discount_event').empty();
                         $('#note_event_view').html('<span class="sales-rule-message">' +
                                 'Đơn bàng của bạn cần mua thêm '+formatNumber(parseInt(3000000-pricesubtotal))+' &#8363; để được giảm giá 10%!</span>');
                      }
                   }
                   //console.log(language);
                }
             });
             */
         });
      </script>
   </div>
   <!--body-container-->
</div>
<!--main_content-->
@endsection
