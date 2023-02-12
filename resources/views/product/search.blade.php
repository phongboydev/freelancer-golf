@extends('layouts.app')
@section('seo')
    <?php
    $title = 'Tìm kiếm | ' . Helpers::get_setting('seo_title');
    $description = $title . ' | ' . Helpers::get_setting('seo_description');
    $keyword = Helpers::get_setting('seo_keyword');
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
    );
    $seo = WebService::getSEO($data_seo);
    $agent = new  Jenssegers\Agent\Agent();

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
    ?>
    @include('partials.seo')
@endsection
@section('content')
    <div class="banner_cate_top">
        {!! WebService::get_template_page('banner-top-search') !!}
    </div>
    <div class="breadcrumbs-group-container clear hidden">
        <div class="container clear">
            <div class="breadcrumbs_top_page clear">
                <div class="breadcrumbs-item fl">
                    {!! Breadcrumbs::render('product.search') !!}
                </div>
            </div>
        </div>
    </div><!--home-index-->
    <div class="main_content clear">
        <div class="container clear">
            <div class="body-container none_padding border-group clear">
                <section id="top_header_single" class="section clear">
                    <div class="clear title_single">
                        <h1 class="title_product"><span>Tìm kiếm từ khoá: <code>"{{ Request::get('q') }}"</code></span></h1>
                    </div>
                </section>
                <section id="section_single" class="section clear">
                    <div class="group-section-wrap clear row">
                        <div class="rightContent col-lg-2 col-sm-12 col-md-12">
                            <h5 class="category-col-title">Danh mục sản phẩm</h5>
                            {!! WebService::CategoriesBlockRender() !!}
                        </div><!--rightContent-->
                        <div class="leftContent col-lg-10 col-md-12 col-sm-12 col-xs-12">
                            <div class="listProduct box clear">
                                <div class="container_theme_category top_single_list clear">
                                    <div class="single_tab_fitter_top clear">
                                             <span id="count_products_value">
                                                 <span>{{ $data->total() }} </span>
                                                 Sản phẩm
                                             </span>
                                        <ul class="filter_content_top clear">
                                            <li>
                                                <a href="javascript:void(0)">
                                                    Featured &nbsp; <i class="ci-arrow-down"></i>
                                                </a>
                                                <ul>
                                                    <li>
                                                        <a onclick="fnSetSearchValue('orderby', 'time')">Mới nhất</a>
                                                    </li>
                                                    <li>
                                                        <a onclick="fnSetSearchValue('orderby', 'pricea')">
                                                            Giá: Tăng dần
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a onclick="fnSetSearchValue('orderby', 'priced')">
                                                            Giá: Giảm dần
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="list-items">
                                    @if(count($data) > 0)
                                        <div class="row">
                                            @foreach($data as $product)
                                                <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                    {!! WebService::RenderBlockProduct($product) !!}
                                                </div>
                                            @endforeach
                                        </div><!--row-->
                                        <div class="page_navi clear">
                                            {!! $data->links('vendor.pagination.custom-pagination') !!}
                                        </div><!--page_navi-->
                                    @else
                                        <div class="alert alert-danger">
                                            <strong> Trống! </strong> Hiện tại không có sản phẩm nào ở mục này.
                                        </div>
                                    @endif
                                </div><!--list_theme_category-->
                            </div><!--listProduct-->
                        </div><!--leftContent-->
                    </div>
                </section><!--section-->
            </div><!--body-container-->
        </div><!--container-->
    </div><!--main_content-->
@endsection
