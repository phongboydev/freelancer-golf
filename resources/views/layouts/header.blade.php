<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie" dir="ltr" lang="en">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie" dir="ltr" lang="en">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie" dir="ltr" lang="en">
<![endif]-->
<!--[if IE 9]>
<html class="ie" dir="ltr" lang="en">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ Helpers::get_setting('favicon') }}" rel="shortcut icon" />
    <link rel="apple-touch-icon" href="{{ Helpers::get_setting('favicon') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('seo')
    <!-- Styles -->
    <?php $version = '1.0.0'; ?>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Vendor Styles including: Font Icons, Plugins, etc.-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/drift-zoom/1.3.1/drift-basic.min.css">
    <!-- Main Theme Styles + Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/jPushMenu.css') }}">
    <link rel="stylesheet" media="screen" href="{{ asset('css/theme.min.css') }}">
    <link rel="stylesheet" media="screen" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mystyle.css') }}">

    <!-- Vendor scrits: js libraries and plugins-->
    <script src="{{ asset('js/jquery.main.js') }}"></script>
    <?php
    $agent = new  Jenssegers\Agent\Agent();
    $url_site_default = Request::segment(0);
    ?>

    <script type="text/javascript">
        var islogin = '@if(Auth::check()) {{ 1 }} @endif';
        var isFreeShip = '@if(Helpers::get_option_minhnn('free-ship') == 1) {{ 1 }} @endif';
        var site = '{{ route('index') }}';
        var site_default = '{{ $url_site_default }}';
        var isMobile = '@if($agent->isMobile()) {{ 1 }} @endif';
    </script>
    <meta name="_token" content="{{ csrf_token() }}">
	{!! Helpers::get_option_minhnn('header') !!}
</head>
<body class="handheld-toolbar-enabled" id="app">
<!-- Sign in / sign up modal-->
<div class="modal fade" id="signin-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link fw-medium active" href="#signin-tab" data-bs-toggle="tab" role="tab"
                                            aria-selected="true"><i class="ci-unlocked me-2 mt-n1"></i>Sign in</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#signup-tab" data-bs-toggle="tab" role="tab"
                                            aria-selected="false"><i class="ci-user me-2 mt-n1"></i>Sign up</a></li>
                </ul>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body tab-content py-4">
                <form class="needs-validation tab-pane fade show active" autocomplete="off" novalidate id="signin-tab">
                    <div class="mb-3">
                        <label class="form-label" for="si-email">Email address</label>
                        <input class="form-control" type="email" id="si-email" placeholder="johndoe@example.com" required>
                        <div class="invalid-feedback">Please provide a valid email address.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="si-password">Password</label>
                        <div class="password-toggle">
                            <input class="form-control" type="password" id="si-password" required>
                            <label class="password-toggle-btn" aria-label="Show/hide password">
                                <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 d-flex flex-wrap justify-content-between">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="si-remember">
                            <label class="form-check-label" for="si-remember">Remember me</label>
                        </div><a class="fs-sm" href="#">Forgot password?</a>
                    </div>
                    <button class="btn btn-primary btn-shadow d-block w-100" type="submit">Sign in</button>
                </form>
                <form class="needs-validation tab-pane fade" autocomplete="off" novalidate id="signup-tab">
                    <div class="mb-3">
                        <label class="form-label" for="su-name">Full name</label>
                        <input class="form-control" type="text" id="su-name" placeholder="John Doe" required>
                        <div class="invalid-feedback">Please fill in your name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="su-email">Email address</label>
                        <input class="form-control" type="email" id="su-email" placeholder="johndoe@example.com" required>
                        <div class="invalid-feedback">Please provide a valid email address.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="su-password">Password</label>
                        <div class="password-toggle">
                            <input class="form-control" type="password" id="su-password" required>
                            <label class="password-toggle-btn" aria-label="Show/hide password">
                                <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="su-password-confirm">Confirm password</label>
                        <div class="password-toggle">
                            <input class="form-control" type="password" id="su-password-confirm" required>
                            <label class="password-toggle-btn" aria-label="Show/hide password">
                                <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                            </label>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-shadow d-block w-100" type="submit">Sign up</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="page-site-container page-wrapper">
    <!-- Navbar 3 Level (Light)-->
    <header id="header" class="shadow-sm">
        <!-- Topbar-->
        <div class="top_header_mobile topbar topbar-dark bg-black">
            <div class="container">
                <div class="topbar-text dropdown d-md-none">
                    <a class="topbar-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Tiện ích</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="tel: 0243 202 8666">
                                <i class="ci-phone"></i> {{ Helpers::get_setting('hotline') }}</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="tro-giup.html">
                                <i class="ci-location text-muted me-2"></i>Trợ giúp</a>
                        </li>
                    </ul>
                </div>
                <div class="topbar-text text-nowrap d-none d-md-inline-block">
                    <a class="topbar-link me-4 d-none d-md-inline-block" href="/lien-he.html">
                        <i class="ci-location"></i>{!! Helpers::get_setting('address')  !!}
                    </a>
                    <a class="topbar-link" href="tel: {{ Helpers::get_setting('hotline') }}">
                        <i class="ci-phone"></i> {{ Helpers::get_setting('hotline') }}
                    </a>
                </div>
                <div class="ms-3 text-nowrap">
                    <a class="topbar-link me-4 d-none d-md-inline-block" href="/tro-giup.html">Trợ giúp</a>
                    <a class="btn-social bs-light bs-youtube" href="{!! Helpers::get_setting('youtube') !!}"><i class="ci-youtube"></i></a>
                    <a class="btn-social bs-light bs-instagram" href="{!! Helpers::get_setting('instagram') !!}"><i class="ci-instagram"></i></a>
                    <a class="btn-social bs-light bs-facebook" href="{!! Helpers::get_setting('facebook') !!}"><i class="ci-facebook"></i></a>
                </div>
            </div>
        </div>
        <!-- Remove "navbar-sticky" class to make navigation bar scrollable with the page.-->
        <div class="header_body_view navbar-sticky bg-light">
            <div class="navbar navbar-expand-lg navbar-light">
                <div class="container">
                    <a class="logo_pc navbar-brand d-none d-sm-block flex-shrink-0" href="{{ route('index') }}">
                        <img src="{{ asset('img/logo-golf-japan.png') }}" width="142" alt="Japan Golf">
                    </a>
                    <a class="logo_mobile navbar-brand d-sm-none flex-shrink-0 me-2" href="{{ route('index') }}">
                        <img src="{{ asset('img/logo-golf-japan.png') }}" width="74" alt="Japan Golf">
                    </a>
                    <div class="contaner_menu_box_search_menu input-group d-none d-lg-flex mx-4">
                        <!-- Primary menu-->
                        @include('layouts.menu_primary')
                        <div id="box_search_pc" class="search">
                            <form name="quicksearch" id="search_single_form" method="get" action="{{route('product.search')}}">
                                <input class="form-control pe-5" type="text" name="q" placeholder="Tìm kiếm" value="{{request()->has('q') ? request('q') : ''}}" >
                                <button type="submit" class="tbl_search search_go tbn_search_home_primary">
                                <i class="ci-search position-absolute top-50 end-0 translate-middle-y fs-base me-3"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <button class="btn_search">
                        <img src="{{asset('img/i_search.svg')}}" alt="button search"/>
                    </button>
                    <div class="btl_icon_add_cart navbar-toolbar d-flex flex-shrink-0 align-items-center">
                        <?php
                        $count_cart = 0;
                        if (Auth::check()) {
                            $cart = \App\Models\Cart::where('user_id', Auth::user()->id)->get()->toArray();
                        } else {
                            $cart = (session('cart') != '') ? session('cart') : [];
                        }
                        if (count($cart)) {
                            foreach($cart as $cart_item) {
                                $count_cart += $cart_item['quantity'];
                            }
                        }
                        $cart_total = Helpers::getCartTotal($cart);
                        ?>
                        <div class="navbar-tool dropdown">
                            <a class="navbar-tool-icon-box dropdown-toggle" href="{{ route('cart') }}">
                                <span class="navbar-tool-label">{{ $count_cart }}</span>
                                <i class="navbar-tool-icon ci-cart"></i>
                            </a>
                            <p>Giỏ hàng</p>
                            <a class="navbar-tool-text hidden" href="{{ route('cart') }}">
                                <small>Giỏ hàng</small> {!! WebService::formatMoney12($cart_total) !!}
                            </a>
                            @if($count_cart > 0)
                                <!-- Cart dropdown-->
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="widget widget-cart px-3 pt-2 pb-3" style="width: 20rem;">
                                        <div style="height: 15rem;" data-simplebar data-simplebar-auto-hide="false">
                                            @foreach($cart as $cart_item)
                                                @if($cart_item['quantity'] > 0)
                                                    <?php
                                                    $product_id = $cart_item['product_id'];
                                                    $product = Helpers::getProductStockById($product_id);
                                                    ?>
                                                    @if($product)
                                                        <?php
                                                        $name = $product->title;
                                                        $code = $product->sku;
                                                        if (($product->price_promotion < $product->price_origin) && $product->price_promotion > 0) {
                                                            $price = $product->price_promotion;
                                                        } else {
                                                            $price = $product->price_origin;
                                                        }
                                                        $quantity = $cart_item['quantity'] ;
                                                        $thumbnail = asset('images/product/' . $product->thumbnail);
                                                        $product_link = Helpers::get_permalink_by_id($product_id);
                                                        ?>
                                                        <div class="widget-cart-item pb-2 border-bottom">
                                                            <button class="btn-close text-danger" aria-label="Remove"
                                                                    onclick="removeCartItem({{ $product_id }})" type="button">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <div class="d-flex align-items-center">
                                                                <a class="flex-shrink-0" href="{!! $product_link !!}">
                                                                    <img src="{{ $thumbnail }}" width="64" alt="{{ $name }}">
                                                                </a>
                                                                <div class="ps-2">
                                                                    <h6 class="widget-product-title">
                                                                        <a href="{!! $product_link !!}">{{ $name }}</a>
                                                                    </h6>
                                                                    <div class="widget-product-meta">
                                                                        <span class="text-accent me-2">
                                                                            {!! WebService::formatMoney12($price) !!}
                                                                        </span>
                                                                        <span class="text-muted">x{{ $quantity }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="d-flex flex-wrap justify-content-between align-items-center py-3">
                                            <div class="fs-sm me-2 py-2">
                                                <span class="text-muted">Tổng cộng:</span>
                                                <span class="text-accent fs-base ms-1">{!! WebService::formatMoney12($cart_total) !!}</span>
                                            </div>
                                        </div>
                                        <a class="btn btn-primary btn-sm d-block w-100" href="{{ route('cart') }}">
                                            <i class="ci-card me-2 fs-base align-middle"></i>Thanh toán
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <button type="button" class="navbar-toggle menu_mobile_header toggle-menu menu-left push-body jPushMenuBtn" data-toggle="collapse" data-target="#main_navigation" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="bar1"></span> <span class="bar2"></span> <span class="bar3"></span> </button>
                </div>
            </div>
        </div>
        <div class="search_layer search ">
            <form name="quicksearch" method="get" action="{{route('product.search')}}">
                <div class="form_inner">
                    <input name="q" type="text" class="search_input" placeholder="Tìm kiếm..">
                    <button type="submit" class="search_go">
                        <img src="{{asset('img/i_search.png')}}" alt="icon search">
                    </button>
                </div>
            </form>
            <button class="btn_search_close">
                <i class="ci-close"></i>
            </button>
        </div>
    </header>
