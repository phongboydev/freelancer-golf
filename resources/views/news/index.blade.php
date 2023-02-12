@extends('layouts.app')
@section('seo')
    <?php
    $title = 'Blogs -' . Helpers::get_setting('seo_title');
    $description = $title . Helpers::get_setting('seo_description');
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
        'current_url_amp' => ''
    );
    $seo = WebService::getSEO($data_seo);
    ?>
    @include('partials.seo')
@endsection
@section('content')
    <!--home-index-->
    <div class="main_content clear">
        <div class="container clear">
            <div class="body-container border-group clear">
                <section id="top_header_single" class="section clear">
                    <div class="clear title_single">
                        <h1 class="title_new_post"><span>Tin tức</span></h1>
                        <div class="breadcrumbs-item clear">
                            {!! Breadcrumbs::render('news') !!}
                        </div>
                    </div>
                </section>
                <section id="section" class="section clear">
                    <div class="group-section-wrap clear row">
                        <div class="rightContent col-md-3 col-lg-2 col-sm-12">
                            <h5 class="category-col-title">Danh mục tin tức</h5>
                            <!-- {!! WebService::CategoriesBlockRender() !!} -->
                        </div><!--rightContent-->
                        <div class="leftContent col-md-9 col-lg-10 col-sm-12 col-xs-12">
                            <div class="container_content_new clear box">
                                <div class="listNews clear">
                                    <div class="content_list_new_category clear">
                                        @if(count($data)>0)
                                            <div class="news_page_gs contentNews clear row boxImg">
                                                <?php $k = 0; $description = "";$thumbnail_thumb = "";$url_img = 'images/article';?>
                                                @foreach($data as $item)
                                                    <?php $k = $k + 1;
                                                        $title = $item->title;
                                                        if (!empty($item->description) && $item->description != "") {
                                                            $description = WebService::excerpts(htmlspecialchars_decode($item->description), 350);
                                                        } else {
                                                            $description = WebService::excerpts(htmlspecialchars_decode($item->content), 350);
                                                        }
                                                    if (!empty($item->thumbnail) && $item->thumbnail != ""):
                                                        $thumbnail_thumb = Helpers::getThumbnail(
                                                            $url_img,
                                                            $item->thumbnail,
                                                            400,
                                                            300,
                                                            "resize"
                                                        );
                                                        if (strpos($thumbnail_thumb, 'placehold') !== false):
                                                            $thumbnail_thumb = $url_img . $thumbnail_thumb;
                                                        endif;
                                                    else:
                                                        $thumbnail_thumb = "https://dummyimage.com/400x300/000/fff";
                                                    endif;
                                                    $route_list = route(
                                                        'single.detail',
                                                        array(
                                                            $item->categorySlug,
                                                            $item->slug
                                                        )
                                                    );
                                                    ?>
                                                    <div class="newslist_page_item col-12 clear">
                                                        <div class="news-item flex wrap">
                                                            <div class="newslist_img">
                                                                <a rel="nofollow" href="{{$route_list}}">
                                                                    <img class="larger" width="400" height="300"
                                                                         alt="{{$item->thumbnail_alt}}"
                                                                         src="{{$thumbnail_thumb}}"/>
                                                                </a>
                                                            </div><!--thumbnail-details-news-->
                                                            <div class="newslist_title">
                                                                <h3 class="blog_item_title"><a
                                                                        href="{{$route_list}}">{{$title}}</a></h3>
                                                                <div
                                                                    class="excerpt-news des clear">{{str_replace(array("&nbsp; &nbsp;","&nbsp;&nbsp;")," ",$description)}}</div>
                                                                <p class="icon_news">
                                                                    <span
                                                                        class="date pull-left"><i>{{date('d-m-Y',strtotime($item->updated))}}</i></span><span
                                                                        class="news_view hidden pull-right">({!!WebService::time_request($item->updated)!!})<i
                                                                            class="glyphicon glyphicon-user"></i>0</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div><!--contentNews-->
                                            <div class="page_navi clear">
                                                {{ $data->links() }}
                                            </div><!--page_navi-->
                                        @else
                                            <div class="alert alert-danger">
                                                <strong> Trống! </strong> Hiện chưa có bài viết nào cho mục này.
                                            </div>
                                        @endif
                                    </div><!--content_list_new_category-->
                                </div><!--listNews-->
                            </div><!--row-content-->
                        </div><!--leftContent-->
                    </div>
                </section><!--section-->
            </div><!--body-container-->
        </div><!--container-->
    </div><!--main_content-->
@endsection
