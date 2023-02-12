@extends('layouts.app')
@section('seo')
    <?php

    if ($product->seo_title != "") {
        $title = $product->seo_title;
    } else {
        $title = $product->title . ' - ' . Helpers::get_setting('seo_title');
    }
    if ($product->seo_description != "") {
        $description = $product->seo_description;
    } else {
        $description = $title . ' - ' . Helpers::get_setting('seo_description');
    }
    if ($product->seo_keyword != "") {
        $keyword = $product->seo_keyword;
    } else {
        $keyword = Helpers::get_setting('seo_keyword');
    }
    if ($product->thumbnail != "") {
        $thumb_img_seo = asset('images/product/' . $product->thumbnail);
    } else {
        $thumb_img_seo = asset(Helpers::get_setting('seo_image'));
    }
    $data_seo = [
        'title' => $title,
        'keywords' => $keyword,
        'description' => $description,
        'og_title' => $title,
        'og_description' => $description,
        'og_url' => Request::url(),
        'og_img' => $thumb_img_seo,
        'current_url' => Request::url(),
        'current_url_amp' => ''
    ];
    $seo = WebService::getSEO($data_seo);
    $agent = new  Jenssegers\Agent\Agent();
    $version = '1.0.2';
    ?>
    @include('partials.seo')
@endsection
@section('content')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{ $product->title }}",
      "image": [ "{{ $thumb_img_seo }}" ],
      "description": "{{ $product->seo_description }}",
      "review": {
        "@type": "Review",
        "reviewRating": {
          "@type": "Rating",
          "ratingValue": "4",
          "bestRating": "5"
        },
        "author": {
          "@type": "Person",
          "name": "Japan Golf"
        }
      },
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.{!! rand(1, 9) !!}",
        "reviewCount": "{!! rand(700, 800) !!}"
      },
      "offers": {
        "@type": "Offer",
        "url": "{{ URL::current() }}",
        "priceCurrency": "VND",
        "price": "{{ $product->price_origin }}",
        "priceValidUntil": "2025-01-01",
        "itemCondition": "https://schema.org/UsedCondition",
        "availability": "https://schema.org/InStock"
      }
    }
    </script>
    <script type="text/javascript" src="{{asset('js/product.js')}}?ver={{ $version }}"></script>
    <div class="breadcrumbs-group-container details_product_bg clear">
        <div class="container clear">
            <div class="breadcrumbs_top_page clear">
                <div class="breadcrumbs-item clear">
                    {!! Breadcrumbs::render('single.detail', $product->categoryName, $product->categorySlug, $product->title, $product->slug) !!}
                </div>
            </div>
        </div>
    </div><!--home-index-->
    <div class="main_content details_product_bg clear">
        <div class="container clear">
            <div class="clear single_theme_content clear my-3">
                <!--****************************************************-->
                <div class="product_detail clear entry-single-content">
                    <meta itemprop="mainEntityOfPage" content="{!! URL::current() !!}">
                    <meta itemprop="dateModified" content="{{ date('d-m-Y', strtotime($product->updated_at)) }}">
                    <time class="hidden" itemprop="datePublished"
                          datetime="{{ date('d-m-Y', strtotime($product->updated_at)) }}"><i
                            class="fa fa-calendar" aria-hidden="true"></i> {{ date('d-m-Y', strtotime($product->updated_at)) }}</time>
                    <span class="hidden" itemprop="author">Vương Đỗ</span>
                    <div class=" hidden thumbnail_post_article">
                        @if($product->thumbnail != '')
                            <div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                    <?php list($widths_t, $heights_t) = @getimagesize($product->thumbnail); ?>
                                <img class="img_aso_thumb" itemprop="contentUrl url"
                                     src="{{ asset('images/product/' . $product->thumbnail) }}"
                                     alt="{{ $product->title }}">
                                <meta itemprop="width"
                                      content="{!! (!empty($widths_t)) ? $widths_t : '500' !!}">
                                <meta itemprop="height"
                                      content="{!! (!empty($heights_t)) ? $heights_t : '375' !!}">
                            </div>
                        @endif
                        <div class="author_post_asolute" itemprop="publisher" itemscope
                             itemtype="https://schema.org/Organization">
                            <figure class="img_aso_thumb_logo" itemprop="logo" itemscope
                                    itemtype="https://schema.org/ImageObject">
                                <img itemprop="url" alt="{{ Helpers::get_setting('site_name') }}" src="{{ Helpers::get_setting('logo') }}">
                                <meta itemprop="width" content="120">
                                <meta itemprop="height" content="60">
                            </figure>
                            <meta itemprop="name" content="{{ route('index') }}">
                        </div>
                    </div><!--thumbnail_post_article-->
                    <div class="clear product_detail_header">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {!! session()->get('error') !!}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {!! session()->get('success') !!}
                            </div>
                        @endif
                        <div class="row">
                            <div id="singleProductImg"
                                 class="col-lg-5 col-md-5 col-sm-12 no-padding-xs">
                                <div class="img_singleProduct clear">
                                    <div class="large-5 column clear">
                                        <?php
                                            $url_img = 'images/product';
                                            $store_gallery = ($product->gallery_images != "") ? unserialize($product->gallery_images) : "";
                                        ?>
                                        @if($store_gallery != "" && count($store_gallery) > 0)
                                            @include('layouts.gallery_theme_single', ['title' => $product->title])
                                        @else
                                            <?php
                                                if (!empty($product->thumbnail) && $product->thumbnail != ""):
                                                    $thumbnail_single = Helpers::getThumbnail($url_img, $product->thumbnail, 450, 450, "resize");
                                                    if (strpos($thumbnail_single, 'placehold') !== false):
                                                        $thumbnail_single = $url_img . $thumbnail_single;
                                                    endif;
                                                else:
                                                    $thumbnail_single = "https://dummyimage.com/450x450/FFF/000";
                                                endif;
                                            ?>
                                            <div class="gallery_product">
                                                <a href="{{ $thumbnail_single }}" data-fancybox="images-preview" class="gallery_product_item">
                                                    <img src="{{ $thumbnail_single }}" alt="">
                                                </a>
                                            </div>
                                            <div class="gallery_product_nav">
                                                <div class="gallery_product_nav_item">
                                                    <img src="{{ $thumbnail_single }}" alt=""/>
                                                </div>
                                            </div>
                                        @endif
                                    </div><!--large-5-->
                                </div><!--img_singleProduct-->
                                <div class="social_single_news clear">
                                    <div class="fb-like" data-href="{!! URL::current() !!}" data-width=""
                                         data-layout="button_count" data-action="like" data-size="small"
                                         data-share="true"></div>
                                </div><!--social_single_news-->
                            </div>
                            <div id="fixed_content_detail_parent" class="col-lg-7 col-md-7 col-sm-12">
                                <!--#fixed_content_detail_parent-->
                                <?php
                                $date_now = date("Y-m-d H:i:s");
                                $val_td = 0;
                                $percent = 0;
                                $html_percent = '';
                                $price_origin = $product->price_origin;
                                $price_promotion = $product->price_promotion;
                                $date_start_event = $product->start_event;
                                $date_end_event = $product->end_event;
                                $is_promotion = 1;
                                if (strtotime($date_now) < strtotime($date_end_event) && strtotime($date_now) > strtotime($date_start_event)) {
                                    if ($price_promotion < $price_origin) {
                                        $val_td = $price_origin - $price_promotion;
                                        $percent = ($val_td / $price_origin) * 100;
                                        $html_percent = '<span class="money_bottom">Tiết kiệm: <span class="percent">' . intval($percent) . '%</span></span>';
                                    } else {
                                        $price_origin = $product->price_origin;
                                        $price_promotion = $product->price_origin;
                                        $is_promotion = 0;
                                    }
                                } else {
                                    $price_origin = $product->price_origin;
                                    $price_promotion = $product->price_origin;
                                    $is_promotion = 0;
                                }
                                ?>

                                <div id="fixed_content_detail" class="content_detail-show clear">
                                    <h1 class="title_product_detail">{{ $product->title }}</h1>
                                    <ul class="price_single clear">
                                        <li class="newPrice">
                                            @if ($price_origin > $price_promotion)
                                                <span class="newPrice_content">
                                                    <input type="hidden" id="price_current_view" value="{{$price_promotion}}"/>
                                                    <span id="price_12" class="price2 price_primary_container">
                                                        <span id="price_origin" class="price1">
                                                            {{ WebService::formatMoney12($price_origin) }}
                                                            <span>{!! Helpers::get_option_minhnn('currency') !!}</span>
                                                        </span>
                                                        <span id="price_promotion">
                                                            {{ WebService::formatMoney12($price_promotion) }}
                                                            <span>{!! Helpers::get_option_minhnn('currency') !!}</span>
                                                        </span>
                                                    </span>
                                                </span>
                                            @elseif($price_origin == 0)
                                                <span class="newPrice_content">
                                                    <input type="hidden" id="price_current_view" value="{{ $price_promotion }}"/>
                                                    <span id="price_12" class="price2 price_primary_container">
                                                        <span id="price_origin" class="price1"></span>
                                                        <span id="price_promotion">Liên hệ</span>
                                                    </span>
                                                </span>
                                            @else
                                                <span class="newPrice_content">
                                                    <input type="hidden" id="price_current_view" value="{{ $price_promotion }}"/>
                                                    <span id="price_12" class="price2 price_primary_container">
                                                        <span id="price_origin" class="price1">
                                                        </span>
                                                        <span id="price_promotion">
                                                            {{ WebService::formatMoney12($price_origin) }}
                                                            <span>{!! Helpers::get_option_minhnn('currency') !!}</span>
                                                        </span>
                                                    </span>
                                                </span>
                                            @endif
                                        </li>
                                        @if($html_percent != '')
                                            <li>
                                                {!!$html_percent!!}
                                            </li>
                                        @endif
                                    </ul>
                                    @if($product->description != '')
                                        <div class="desc-single-product">
                                            {!! htmlspecialchars_decode($product->description) !!}
                                        </div>
                                    @endif
                                    <script type="application/javascript">
                                        var is_promotion = {!! $is_promotion !!};
                                        var currency = "{!! Helpers::get_option_minhnn('currency') !!}";
                                    </script>
                                    <?php
                                    $has_variable = ($product->group_variable_product != '') ? 1 : 0;
                                    ?>
                                    <input type="hidden" id="has_variable" name="has_variable" value="{{ $has_variable }}">
                                    <div class="container_bienthe_group clear">
                                        <script type="application/javascript">
                                            var is_promotion = {!! $is_promotion !!};
                                            var currency = "{!! Helpers::get_option_minhnn('currency') !!}";
                                            var key_option_data = "{!! $product->key_option !!}";
                                        </script>
                                        <div class="classlist">
                                            @include('product.variable-path')
                                        </div><!--classlist-->
                                    </div><!--excerpt_detail_product-->

                                    @if($product->price_origin != 0)
                                        <div class="custom_item_detail clear">
                                            <div class="quantityProduct">
                                                <form class="form_quantity">
                                                    <ul class="ul_quantity clear">
                                                        <li>Số Lượng</li>
                                                        <li>
                                                            <span class="btn btn-sm btn-quantity" onclick="addQuantityDetail(-1)">
                                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <input type="text" id="quantity" class="number_quantity"
                                                                   name="number_quantity" value="1">
                                                        </li>
                                                        <li>
                                                            <span class="btn btn-sm btn-quantity"
                                                                  onclick="addQuantityDetail(1)">
                                                                <i class="fa fa-plus"
                                                                   aria-hidden="true"></i>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            @if($product->store_status == 1)
                                                                Còn hàng
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-6">
                                                <a class="buy_repay" href="{!!  Helpers::get_setting('messenger_facebook') !!}" target="_blank">
                                                    <b><i class="fab fa-facebook-f"></i> Chat facebook</b>
                                                    <span>Liên hệ qua facebook</span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a class="buy_repay" href="tel:{!!  Helpers::get_setting('hotline') !!}">
                                                    <b><i class="fas fa-phone"></i> Gọi điện thoại</b>
                                                    <span>Liên hệ trực tiếp</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    @if($product->store_status == 1 && $product->price_origin != 0)
                                        <div class="container_tbl_add_cart_view clear">
                                            <div class="addmorecart_content">
                                                <a id="btn_cart_primary"
                                                   class="green_addtocart_btn btn-cart-list"
                                                   data-id="{{ $product->id }}"
                                                   data-product-parent="{{ $product->product_id }}"
                                                   data-quantity="1"
                                                   data-option="{{ $product->key_option }}"
                                                   onclick="addToCart(this)"
                                                >
                                                    <ion-icon name="cart-outline"></ion-icon> Thêm vào giỏ hàng
                                                </a>
                                            </div>
                                        </div><!--container_tbl_add_cart_view-->
                                    @endif
                                    <div class="single-contact mt-3">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <a class="buy_repay" target="_blank"
                                                   href="{!! Helpers::get_setting('messenger_facebook') !!}">
                                                    <b>
                                                        <ion-icon name="logo-facebook"></ion-icon> Chat facebook
                                                    </b>
                                                    <span>Liên hệ qua facebook</span>
                                                </a>
                                            </div>
                                            <div class="col-lg-6">
                                                <a class="buy_repay" href="tel:{!! Helpers::get_setting('hotline') !!}">
                                                    <b><ion-icon name="call-outline"></ion-icon> Gọi điện thoại</b>
                                                    <span>Liên hệ trực tiếp</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--product_detail-->
                <!--***************************************************-->
                <div id="details-tab-container-single" class="mt-4">
                    <div class="product_collateral clear">
                        <div id="contentDetails"
                             class="details-sumary single-product-box-content tab-pane in active">
                            {!! Helpers::TableOfContents(htmlspecialchars_decode($product->content)) !!}
                        </div>
                        <div class="clear comment-facebook mt-3">
                            <div class="fb-comments" data-href="{!!URL::current()!!}"
                                 data-width="100%" data-numposts="3"></div>
                        </div><!--CommentFacebook-->
                    </div><!--product_collateral-->
                </div>

                <div class="listNews list-productRelated releated_product_details clear">
                    <div class="container_releated_single clear">
                        <h3 class="title"><span>Sản phẩm liên quan</span></h3>
                        <div class="wrapper">
                            <div class="center-slider">
                                @foreach ($related_products as $item)
                                    {!! WebService::RenderBlockProduct($item) !!}
                                @endforeach
                            </div>
                        </div>
                    </div><!--container_releated_single-->
                </div><!--listNews-->
            </div><!--single_theme_content-->
        </div><!--container-->
    </div><!--main_content-->
@endsection
