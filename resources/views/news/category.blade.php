@extends('layouts.app')
@section('seo')
    <?php
    if ($categories->seo_title != ""):
        $title = $categories->seo_title;
    else:
        $title = $categories->categoryName . ' - ' . Helpers::get_setting('seo_title');
    endif;
    if ($categories->seo_description != ""):
        $description = $categories->seo_description;
    else:
        $description = $title . ' - ' . Helpers::get_setting('seo_description');
    endif;
    if ($categories->seo_keyword != ""):
        $keyword = $categories->seo_keyword;
    else:
        $keyword = Helpers::get_setting('seo_keyword');
    endif;
    if ($categories->thumbnail != ""):
        $thumb_img_seo = url('/images/category/') . "/" . $categories->thumbnail;
    else:
        $thumb_img_seo = asset(Helpers::get_setting('seo_image'));
    endif;

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
    <div class="breadcrumbs-group-container clear">
        <div class="container clear">
            <div class="breadcrumbs_top_page clear">
                <div class="breadcrumbs-item clear">
                    {!! Breadcrumbs::render('single.category', $categories->categoryName, $categories->categorySlug) !!}
                </div>
            </div>
        </div>
    </div><!--home-index-->
    <div class="main_content clear">
        <div class="container clear">
            <div class="body-container border-group clear">
                <section id="section" class="section clear">
                    <div class="group-section-wrap clear row">
                        <div class="leftContent col-xs-12 col-sm-12 col-md-12">
                            <h1 class="title_new_post">Category: <span>{{$categories->categoryName}}</span></h1>
                            <div class="container_content_new clear box">
                                <div class="listNews clear">
                                    <div class="content_list_new_category clear">
                                        @if(count($data)>0)
                                            <div class="news_page_gs news_page_gs contentNews clear boxImg">
                                                <?php $k = 0; $description = "";$thumbnail_thumb = "";$url_img = 'images/article';?>
                                                @foreach($data as $item)
                                                    <?php $k = $k + 1;
                                                        $title = $item->title;
                                                        if (!empty($item->description) && $item->description != "") {
                                                            $description = WebService::excerpts(htmlspecialchars_decode($item->description), 350);
                                                        } else {
                                                            $description = WebService::excerpts(htmlspecialchars_decode($item->content), 350);
                                                        }

                                                    if (!empty($item->thumbnail) && $item->thumbnail != "") {
                                                        $thumbnail_thumb = Helpers::getThumbnail(
                                                            $url_img,
                                                            $item->thumbnail,
                                                            400,
                                                            300,
                                                            "resize"
                                                        );
                                                        if (strpos($thumbnail_thumb, 'placehold') !== false) {
                                                            $thumbnail_thumb = $url_img . $thumbnail_thumb;
                                                        }
                                                    } else {
                                                        $thumbnail_thumb = "https://dummyimage.com/400x300/000/fff";
                                                    }

                                                    $route_list = route('single.detail', [$item->categorySlug, $item->slug]);
                                                    ?>
                                                    <div class="newslist_page_item col-lg-6 col-md-6 col-xs-12 clear">
                                                        <div class="news-item flex wrap">
                                                            <div class="newslist_img">
                                                                <a rel="nofollow" href="{{ $route_list }}">
                                                                    <img class="larger" width="400" height="300"
                                                                         alt="{{ $item->title }}"
                                                                         src="{{ $thumbnail_thumb }}"/>
                                                                </a>
                                                            </div><!--thumbnail-details-news-->
                                                            <div class="newslist_title">
                                                                <h3 class="blog_item_title">
                                                                    <a href="{{$route_list}}">{{ $title }}</a>
                                                                </h3>
                                                                <div class="excerpt-news des clear">
                                                                    {{str_replace(["&nbsp; &nbsp;","&nbsp;&nbsp;"]," ",$description)}}
                                                                </div>
                                                                <p class="icon_news">
                                                                    <span class="date pull-left">
                                                                        <i>({!!WebService::time_request($item->updated)!!})</i>
                                                                    </span>
                                                                    <span class="news_view hidden pull-right">
                                                                        <i class="glyphicon glyphicon-user"></i>0
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div><!--news_page_gs-->
                                            <div class="page_navi clear">
                                                {{ $data->links() }}
                                            </div><!--page_navi-->
                                        @else
                                            <div class="alert alert-danger">
                                                <strong>Empty!</strong> No post for this category.
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
