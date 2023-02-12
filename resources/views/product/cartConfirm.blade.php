@extends('layouts.app')
@section('seo')
<?php
$title='Order confirmation |'.Helpers::get_setting('seo_title');
$description=$title.Helpers::get_setting('seo_description');
$keyword='cart,confirm cart, add to cart,'.Helpers::get_setting('seo_keyword');
$thumb_img_seo=asset(Helpers::get_setting('seo_image'));
$data_seo = array(
    'title' => $title,
    'keywords' => $keyword,
    'description' =>$description,
    'og_title' => $title,
    'og_description' => $description,
    'og_url' => Request::url(),
    'og_img' => $thumb_img_seo,
    'current_url' =>Request::url(),
    'current_url_amp' => ''
);
$seo = WebService::getSEO($data_seo);
$request=new Request();
$query_remove=(int)Request::get('remove');
//dd($request);
if($query_remove!='' && $query_remove >0):
    $rowId_remove = Cart::search(array('id' => $query_remove));
    //dd($rowId_remove[0]);
    Cart::remove($rowId_remove[0]);
	//$content_cart=Cart::content();
    echo \Illuminate\Support\Facades\Redirect::route('cart');
endif;
//echo $query;
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
    </div><!--home-index-->
    <div class="main_content clear">
        <div class="body-container none_padding border-group clear">
                <section id="section" class="section clear">
                    <div class="group-section-wrap clear ">
					   <div class="container_cart clear">
						<div class="container clear">
						<div class="row">
						<div class="leftContent col-xs-12 col-sm-12 col-md-12"></div></div></div>
                            <div class="row-content-category clear">
                                <div class="listProduct woocommerce woocommerce-checkout clear">
                                    <div class="hidden container_theme_category">
                                        <h1 class="title_product"><span>Order confirmation</span></h1>
                                    </div>
									<div id="add_cart_container" class="news_page_gs group-my-cart-page clear">
									    @if(Cart::content()->count()>0)
                                            <div class="container_list_products clear">
                                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                                <!--List-->
                                                <?php $string_json=""; $id_cart=0;$cart_items="";  $url_img_sp='images/product/';?>
                                                <form class="woocommerce-cart-form" action="{{url('/')}}/cart" method="post">
                                                    <input type="hidden" name="update" value="on"/>
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                                                        <thead>
                                                        <tr>
                                                            <th class="product-thumbnail">Image</th>
                                                            <th class="product-name">Product</th>
                                                            <th class="product-price">Price</th>
                                                            <th class="product-quantity">Quantily</th>
                                                            <th class="product-subtotal">In total</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $Products="";?>
                                                        @foreach(Cart::content() as $cart_items)
                                                        <?php $avariable_html = ""; $group_combo = ""; ?>
                                                            @if($cart_items->qty >0)
                                                            <?php $id_cart=$cart_items->id; $Products=Helpers::get_product_by_id($id_cart); ?>
                                                                @if($Products)
                                                                <?php
                                                                    $id=$id_cart;
                                                                    $name=$Products->title;
                                                                    $code=$Products->theme_code;

                                                                    $date_now = date("Y-m-d h:i:s");
                                                                    $discount_for_brand = App\Model\Discount_for_brand::where('brand_id', '=', $Products->id_brand)
                                                                        ->where('start_event', '<', $date_now)
                                                                        ->where('end_event', '>', $date_now)
                                                                        ->first();
                                                                    if($discount_for_brand){
                                                                        $price= $Products->price_origin - $Products->price_origin*$discount_for_brand->percent/100;
                                                                    } else{
                                                                        if(!empty($Products->start_event) && !empty($Products->end_event)){
                                                                            $date_start_event = $Products->start_event;
                                                                            $date_end_event = $Products->end_event;
                                                                            $price_sale=$Products->price_origin;
                                                                            $price_regular=$Products->price_promotion;
                                                                            if(strtotime($date_now) < strtotime($date_end_event) && strtotime($date_now) > strtotime($date_start_event)){
                                                                                if($price_regular !="" && $price_regular > 1): $price=$price_regular; else: $price=$price_sale; endif;
                                                                            } else{
                                                                                $price=$price_sale;
                                                                            }
                                                                        } else{
                                                                            $price_sale=$Products->price_origin;
                                                                            $price_regular=$Products->price_promotion;
                                                                            if($price_regular !="" && $price_regular > 1): $price=$price_regular; else: $price=$price_sale; endif;
                                                                        }
                                                                    }

                                                                    $quantity=$cart_items->qty;
                                                                    $avariable=$cart_items->options;
                                                                    $money= $quantity*$price;
                                                                    $post_thumbnail_news=$url_img_sp.$Products->thubnail;

                                                                    $count_option_arr = count($avariable);
                                                                    if($count_option_arr == 1){
                                                                        $avariable_html = '';
                                                                    }else{
                                                                        if($avariable[0] != ""){
                                                                            $group_combo = unserialize($avariable[0]);
                                                                            $count_group_combo_item = count($group_combo);
                                                                        }
                                                                        if(isset($group_combo) && $group_combo != ""){

                                                                            for ($i=0; $i < $count_group_combo_item; $i++) {
                                                                                $avariable_html .= '<p class="item_combo">';
                                                                                $item_title = App\Model\Theme::where('theme.id', '=', $group_combo[$i])->select('theme.title')->first();

                                                                                $avariable_html .="<span class='item_combo_title'>".$item_title->title."</span> - ";

                                                                                for ($j=1; $j < $count_option_arr; $j++) {
                                                                                    $color_item = WebService::objectToArray($avariable[$j]);
                                                                                    if(isset($color_item[$group_combo[$i]])){
                                                                                        $name_color = $color_item[$group_combo[$i]];
                                                                                        break;
                                                                                    } else{
                                                                                        $name_color = 'Không';
                                                                                    }
                                                                                }

                                                                                $avariable_html .="<span class='avariable_html'>Color: <span>".$name_color."</span></span>";
                                                                                $avariable_html .= '</p>';
                                                                            }

                                                                        } else{
                                                                            for ($j=1; $j < $count_option_arr; $j++) {
                                                                                $color_item = WebService::objectToArray($avariable[$j]);
                                                                                if(isset($color_item[$Products->id])){
                                                                                    $name_color = $color_item[$Products->id];
                                                                                }
                                                                            }
                                                                            $avariable_html .="<p class='avariable_html'>Color:<span>".$name_color."</span></p>";
                                                                        }
                                                                    }
                                                                ?>
                                                        <tr class="woocommerce-cart-form__cart-item cart_item">
                                                            <td class="product-thumbnail">
                                                                <a href="{!!Helpers::get_permalink_by_id($cart_items->id)!!}">
                                                                    <img width="425" height="500" src="{{$post_thumbnail_news}}" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt=""/>
                                                                </a>
                                                            </td>
                                                            <td class="product-name" data-title="Product">
                                                                <a href="{!!Helpers::get_permalink_by_id($cart_items->id)!!}" target="_blank" class="bold uppercase">{{$name}}</a>
                                                                <?php echo $avariable_html;?>
                                                            </td>
                                                            <td class="product-price" data-title="Price">
                                                                <span class="woocommerce-Price-amount amount">{!!WebService::formatMoney12($price)!!}<span class="woocommerce-Price-currencySymbol">{!!Helpers::get_option_minhnn('currency')!!}</span></span>						</td>
                                                            <td class="product-quantity" data-title="Quantily">
                                                                {{$quantity}}
                                                            </td>

                                                            <td class="product-subtotal" data-title="In total">
                                                                <span class="woocommerce-Price-amount amount">{!!WebService::formatMoney12($money)!!}<span class="woocommerce-Price-currencySymbol">{!!Helpers::get_option_minhnn('currency')!!}</span></span>						</td>
                                                        </tr>
                                                                @endif
                                                           @endif
                                                       @endforeach
                                                        </tbody>
                                                    </table>
                                                </form>
                                                <!--List-->
                                            </div><!--container_list_products-->

                                            <!--Form ***************************************************************************************-->
                                            <div class="form-customer-order">
                                                <?php
                                                    session_start();
                                                    $data_cart = $_SESSION['data_cart'];
                                                ?>
                                                <form id="check_out_frm" name="frm-checkout" method="post" action="{{route('cart-post')}}">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="checkout" value="true"/>
                                                    <div class="container_checkout clear">
                                                        <div class="row justify-content-center">
                                                            @if ($errors->any())
                                                               <div class="col-md-6 col-md-offset-3 mgt-10 alert alert-danger alert-dismissible fade in">
                                                               <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                                  <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                      <li><i class="glyphicon glyphicon-remove"></i> {{ $error }}</li>
                                                                    @endforeach
                                                                  </ul>
                                                               </div>
                                                            @endif
                                                            @if(Session::has('success_msg'))
                                                                <div class="col-md-6 col-md-offset-3 mgt-10  alert alert-success alert-dismissible fade in" role="alert">
                                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                                     {{ Session::get('success_msg') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 form_user_cart">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body form-horizontal payment-form confrim-card">
                                                                        <div class="form-group">
                                                                            <label for="full-name" class="col-sm-2 control-label">FullName(<span class="hitsu">*</span>)</label>
                                                                            <div class="col-sm-10">
                                                                                {{$data_cart['cart_hoten']}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="phone" class="col-sm-2 control-label">Phone(<span class="hitsu">*</span>)</label>
                                                                            <div class="col-sm-10">
                                                                                {{$data_cart['cart_phone']}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="your-email" class="col-sm-2 control-label">Email(<span class="hitsu">*</span>)</label>
                                                                            <div class="col-sm-10">
                                                                                {{$data_cart['cart_email']}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="slt_province" class="col-sm-2 control-label">Province/City(*)(<span class="hitsu">*</span>)</label>
                                                                            <div class="col-sm-4">
                                                                                {{$data_cart['cart_province']}}
                                                                            </div>
                                                                            <label for="slt_district" class="col-sm-2 control-label">District(<span class="hitsu">*</span>)</label>
                                                                            <div class="col-sm-4">
                                                                                {{$data_cart['cart_district']}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="slt_district" class="col-sm-2 control-label">Wards(<span class="hitsu">*</span>)</label>
                                                                            <div class="col-sm-4">
                                                                                {{$data_cart['cart_ward']}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="address" class="col-sm-2 control-label">Shipping Address(<span class="hitsu">*</span>)</label>
                                                                            <div class="col-sm-10">
                                                                                {{$data_cart['cart_address']}}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col-sm-12">
                                                                                {{$data_cart['cart_note']}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!--form_user_cart-->
                                                            <div class="col-lg-6 col-md-6 col-xs-12 total_cart_container cart-collaterals">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body form-horizontal payment-form">
                                                                        <div class="cart_totals clear">
                                                                            <h2>Discount code</h2>
                                                                            <div class="discount-input">
                                                                                <?php
                                                                                $checkcode = App\Model\Discount_code::where('discount_code.code', '=', $data_cart['discount_code'])
                                                                                    ->where('discount_code.status', '=', 0)
                                                                                    ->first();
                                                                                $code_true = "";
                                                                                $discount = 0;
                                                                                if($checkcode){
                                                                                    $date_now = date("Y-m-d H:i:s");
                                                                                    if(strtotime($date_now) < strtotime($checkcode->expired)){
                                                                                        $code_true = $data_cart['discount_code'];
                                                                                    } else{
                                                                                        $code_true = "";
                                                                                    }
                                                                                } else{
                                                                                    $code_true = "";
                                                                                }
                                                                                ?>
                                                                                {{$code_true}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body form-horizontal payment-form">
                                                                        <div class="cart_totals clear">
                                                                    <h2>Total</h2>
                                                                    <table cellspacing="0" class="shop_table shop_table_responsive">
                                                                        <tbody>
                                                                        <tr class="shipping">
                                                                            <th>Delivery</th>
                                                                            <td data-title="Giao hàng 1">
                                                                                {{$data_cart['cart_pay_method']}}
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="order-total">
                                                                            <th>In Total</th>
                                                                            <td data-title="Tổng cộng" class="total_cart_order">
                                                                            	@if($data_cart['cart_province']=="Thành phố Hồ Chí Minh")
                                                                            		<input type="hidden" name="type_shipping" value="ghtk">
                                                                            	@else
                                                                            		<input type="hidden" name="type_shipping" value="viettelpost">
                                                                            	@endif
                                                                            	<?php
                                                                            		if($data_cart['cart_province'] == "Thành phố Hồ Chí Minh"):
																			            if(strtotime($date_now) < strtotime(Helpers::get_option_minhnn('free-ship-end')) && strtotime($date_now) > strtotime(Helpers::get_option_minhnn('free-ship-start')) && $data_cart['cart_total'] > Helpers::get_option_minhnn('free-for-bill')):
																			            	$fee_html = "+0";
																				            $arr = array(
																				                'fee_html' => $fee_html,
																				                'fee' => 0,
																				                'cart_total' => $data_cart['cart_total'],
																				            );
																			        	else:
																			        		$fee_html = "+".WebService::formatMoney12(20000);
																			        		$arr = array(
																				                'fee_html' => $fee_html,
																				                'fee' => 20000,
																				                'cart_total' => $data_cart['cart_total']+ 20000,
																				            );
																			        	endif;
                                                                                        //Free for total bill 400k
                                                                                        if($data_cart['cart_total'] > Helpers::get_option_minhnn('free-for-total-bill')):
                                                                                            $fee_html = "+0";
                                                                                            $arr = array(
                                                                                                'fee_html' => $fee_html,
                                                                                                'fee' => 0,
                                                                                                'cart_total' => $data_cart['cart_total'],
                                                                                            );
                                                                                        endif;
                                                                            		else:
																						if(strtotime($date_now) < strtotime(Helpers::get_option_minhnn('free-ship-end')) && strtotime($date_now) > strtotime(Helpers::get_option_minhnn('free-ship-start')) && $data_cart['cart_total'] > Helpers::get_option_minhnn('free-for-bill')):
																			            	$fee_html = "+0";
																				            $arr = array(
																				                'fee_html' => $fee_html,
																				                'fee' => 0,
																				                'cart_total' => $data_cart['cart_total'],
																				            );
																			        	else:
																			        		$fee_html = "+".WebService::formatMoney12(30000);
																			        		$arr = array(
																				                'fee_html' => $fee_html,
																				                'fee' => 30000,
																				                'cart_total' => $data_cart['cart_total']+30000,
																				            );
																			        	endif;
                                                                                        //free for total bill 400k
                                                                                        if($data_cart['cart_total'] > Helpers::get_option_minhnn('free-for-total-bill')):
                                                                                            $fee_html = "+0";
                                                                                            $arr = array(
                                                                                                'fee_html' => $fee_html,
                                                                                                'fee' => 0,
                                                                                                'cart_total' => $data_cart['cart_total'],
                                                                                            );
                                                                                        endif;
                                                                            		endif;
                                                                            	?>
                                                                                <div class="total_fee_shipping">Delivery charges: <span id="shipping-fee">{{htmlspecialchars_decode($arr['fee_html'])}}<span class="woocommerce-Price-currencySymbol">{!!Helpers::get_option_minhnn('currency')!!}</span></span>
                                                                                    <input type="hidden" id="shipping_fee" name="shipping_fee" value="{{$arr['fee']}}"></div>
                                                                                <span class="price_discount"></span>
                                                                                <strong><span class="woocommerce-Price-amount amount">{!!WebService::formatMoney12($arr['cart_total'])!!} <span class="woocommerce-Price-currencySymbol">{!!Helpers::get_option_minhnn('currency')!!}</span></span></strong>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div><!--cart_totals-->
                                                                    </div>
                                                                </div>
                                                            </div><!--total-cart_container-->
                                                        </div>
                                                        <div class="checkout_submit_form_order clear">
                                                            <div class="wc-proceed-to-checkout">
                                                                <button type="submit" id="submit" name="tbl_submit" class="btn btn-danger"><span class="dslc-icon-ext-paperplane"></span> Đặt hàng</button>
                                                            </div>
                                                        </div><!--checkout_submit_form_order-->
                                                    </div><!--container_checkout-->
                                                </form>
                                            </div><!--form-customer-order-->
                                            <!--End Form **********************************************************************************-->

									    @else
											@if(Session::has('success_msg'))
												<script language="javascript">
													alertView("Order","You have successfully placed an order. We will process and contact you soon!");
												</script>
												<div class="col-md-6 col-md-offset-3">
													<div class="alert alert-dismissable alert-success">
														<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
														{{Session::get('success_msg')}}
													</div>
												</div>
												<p></p>
											@endif
											<div class="col-md-6 col-md-offset-3">
												<div class="alert alert-danger alert-dismissible fade show" role="alert">
													  <strong>Cart!</strong> Your shopping cart is currently empty.
													  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													  </button>
												</div>
											</div>
									    @endif
									</div><!--add_cart_container-->
                                </div><!--listProduct-->
                            </div><!--row-content-->
                        </div><!--leftContent-->
                        <div class="container clear"><div class="row"><div class="rightContent col-xs-12 col-sm-12 col-md-12"></div></div>
                            @include('layouts.footer_public')
                        </div><!--rightContent-->
                    </div>
                </section><!--section-->
            </div><!--body-container-->
    </div><!--main_content-->
@endsection
