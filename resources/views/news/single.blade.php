@extends('layouts.app')
@section('seo')
<?php
if($post->seo_title != "") {
	$title=$post->seo_title;
} else {
    $title = $post->title . ' - '.Helpers::get_setting('seo_title');
}
if($post->seo_description != "") {
	$description = $post->seo_description;
} else {
	$description = $title . ' - '. Helpers::get_setting('seo_description');
}
if($post->seo_keyword != "") {
	$keyword = $post->seo_keyword;
} else {
	$keyword = Helpers::get_setting('seo_keywords');
}
if($post->thumbnail != "") {
	$thumb_img_seo = asset('images/article/' . $post->thumbnail);
} else {
	$thumb_img_seo = Helpers::get_setting('seo_image');
}

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
WebService::update_post_hit($post->id);
?>
@include('partials.seo')
@endsection
@section('content')
    <div class="breadcrumbs-group-container clear">
        <div class="container clear">
            <div class="breadcrumbs_top_page clear">
                <div class="breadcrumbs-item clear">
                    {!! Breadcrumbs::render(
                         'single.detail',
                         $post->categoryName,
                         $post->categorySlug,
                         $post->title,
                         $post->slug
                         ) !!}
                </div>
            </div>
        </div>
    </div><!--home-index-->
    <div class="main_content gray_bg clear single-post-news">
        <div class="container clear">
            <div class="body-container container_post border-group clear">
                <section id="section" class="section clear">
                    <div class="group-section-wrap clear row">
                        <div class="leftContent col-xs-12 col-sm-12 col-md-12">
                            <div class="container_single_post clear">
                                <div class="row row-content">
                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12 left_conainer_tintuc">
                                        <div class="listNews clear" itemscope itemtype="http://schema.org/Article">
                                        <!--****************************************************-->
                                        <div class="contentNews boxImg clear">
                                            <h1 class="titleView2" itemprop="headline name">
                                                {!! $post->title !!}
                                            </h1>
                                            <meta itemprop="mainEntityOfPage" content="{{ Request::url() }}">
                                            <meta itemprop="dateModified" content="{!! date('d-m-Y',strtotime($post->updated)) !!}">
                                            <div class=" hidden thumbnail_post_article clear">
                                                <div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                                                    <?php list($widths_t, $heights_t) = @getimagesize($post->thumbnail); ?>
                                                    <img class="img_aso_thumb" itemprop="contentUrl url" src="{{ asset('images/article/' . $post->thumbnail) }}" alt="{{ $post->title }}">
                                                    <meta itemprop="width" content="<?php echo (!empty($widths_t)) ? $widths_t : '500'; ?>">
                                                    <meta itemprop="height" content="<?php echo (!empty($heights_t)) ? $heights_t : '375'; ?>">
                                                </div>
                                                <div class="author_post_asolute" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                                                    <figure class="img_aso_thumb_logo" itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                                                        <img itemprop="url" alt="{{ Helpers::get_setting('site_name') }}" src="{{ asset('images/logo_1397577072.png') }}">
                                                        <meta itemprop="width" content="120">
                                                        <meta itemprop="height" content="60">
                                                    </figure>
                                                    <meta itemprop="name" content="">
                                                </div>
                                            </div><!--thumbnail_post_article-->
                                            <div class="group-top-single-new clear">
                                                <div class="l">
                                                    <time class="l" itemprop="datePublished" datetime="{!! date('d-m-Y',strtotime($post->updated)) !!}">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i> {!! date('d-m-Y',strtotime($post->updated)) !!}
                                                    </time>
                                                    <h2 class="title_product l">
                                                        <a href="{{ route('category.list', $post->categorySlug) }}">
                                                            {{ $post->categoryName }}
                                                        </a>
                                                    </h2>
                                                </div>
                                                <div  class="r">
                                                    <div class="social_single_news clear">
                                                        <!--this socail single new-->
                                                        <ul class="social_like_single clear">
                                                            <li class="fb_btn fb-like-tbn">
                                                                <div class="fb-like" data-href="{!!URL::current()!!}"
                                                                     data-layout="button_count" data-action="like"
                                                                     data-show-faces="true" data-share="false"></div>
                                                            </li>
                                                        </ul><!--social_tbl_like_group-->
                                                    </div><!--social_single_news-->
                                                </div>
                                            </div>
                                            <div class="hidden l author_pulic"> <i class="dslc-icon-ext-user3"></i> <span itemprop="author">Diệu Huyền</span></div>

                                            <div class="entry-content clear details-sumary" itemprop="articleBody">
                                                {!! Helpers::TableOfContents(htmlspecialchars_decode($post->content)) !!}
                                            </div><!--entry-content-->
                                            <div class="social_single_news clear">
                                                <!--this socail single new-->
                                                <ul class="social_like_single clear">
                                                    <li class="fb_btn fb-like-tbn">
                                                        <div class="fb-like" data-href="{!!URL::current()!!}"
                                                             data-layout="button_count" data-action="like"
                                                             data-show-faces="true" data-share="false"></div>
                                                    </li>
                                                </ul><!--social_tbl_like_group-->
                                            </div><!--social_single_news-->

                                            <div class="clear comment-facebook">
                                                 <div class="fb-comments" data-href="{!!URL::current()!!}"
                                                      data-width="100%" data-numposts="5" data-colorscheme="light"></div>
                                            </div><!--CommentFacebook-->
                                            <div class="hidden review_content_post" itemscope itemtype="https://schema.org/Review">
                                                <div class="content_review" itemprop="reviewBody">
                                                    <!--Review-->
                                                    <div class="row">
                                                        <div class="col-xs-4 col-sm-4 col-md-4">
                                                            <ul>
                                                                <li>
                                                                    <h4><span id="Nguoi_viet_danh_gia"><span>User rate</span></span></h4>
                                                                </li>
                                                                <li>
                                                                    <span class="sr-only">Rated 5 stars</span>
                                                                    <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                                                        <meta content="1" itemprop="worstRating">
                                                                        <meta content="5" itemprop="ratingValue">
                                                                        <meta content="5" itemprop="bestRating">
                                                                        <span class="rating r-5" title="Rated 5"></span>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <span class="fa fa-thumbs-up"></span><span class="hidden-xs"> Tuyệt vời</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-xs-4 col-sm-4 col-md-4"></div>
                                                        <div class="col-xs-4 col-sm-4 col-md-4">
                                                            <ul class="al-right">
                                                                <li><span class="editorrating_average"><span>100%</span></span></li>
                                                            </ul>
                                                        </div>
                                                    </div><!--row-->
                                                    <div class="row bor-tt">
                                                        <div class="col-md-6 wprs_mb_10">
                                                            <ul>
                                                                <li>
                                                                    <span class="item title fn" itemprop="itemReviewed" itemscope itemtype="https://schema.org/Thing">
                                                                        @if($post->id%2 == 0)
                                                                            <span itemprop="name">Nguyễn Tùng Dương</span>
                                                                        @else
                                                                            <span itemprop="name">Hoàng Khoa</span>
                                                                        @endif
                                                                    </span>
                                                                </li>
                                                                <li class="wprs_magic">Reviewed by:
                                                                    <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                                        <span class="reviewer byline vcard hcard">
                                                                            @if($post->id%2 == 0)
                                                                                <span class="me fn" itemprop="name">Thietkewebnhanh247</span>
                                                                            @else
                                                                                <span class="me fn" itemprop="name">Expro Việt Nam</span>
                                                                            @endif
                                                                        </span>
                                                                    </span>
                                                                </li>
                                                                <li class="wprs_magic">
                                                                    <div>Published on:
                                                                        <span class="dtreviewed rating_date">
                                                                            <span itemprop="datePublished" class=""
                                                                                  title="{!! date('d-m-Y',strtotime($post->updated)) !!}">
                                                                                {!! date('d-m-Y',strtotime($post->updated)) !!}
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                </li>
                                                                <li class=" wprs_magic">Last modified:
                                                                    <span class="dtmodified updated rating_date" itemprop="dateModified">
                                                                        <span title="{!! date('d-m-Y',strtotime($post->updated)) !!}">{!! date('d-m-Y',strtotime($post->updated)) !!}</span>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div><!--row-->
                                                    <!--End Review-->
                                                </div><!--content_review-->
                                            </div><!--.review_content_post-->
                                        </div><!--contentNews-->
                                        <!--***************************************************-->
                                    </div><!--listNews-->
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                        <div class="single_news_releated clear">
                                        <h2 class="other">Releated post</h2>
                                        <div class="list_item_news_releated">
                                            @if($related_posts)
                                                <div class="contentNews clear boxImg">
                                                    <?php $k=0; $url_img='images/article';?>
                                                    @foreach($related_posts as $related)
                                                        <?php $k = $k+1;
                                                        $title = $related->title;
                                                        if(!empty($related->thumbnail) && $related->thumbnail !="") {
                                                            $thumbnail_thumb= Helpers::getThumbnail($url_img,$related->thumbnail, 400, 400, "resize");
                                                            if(strpos($thumbnail_thumb, 'placehold') !== false) {
                                                                $thumbnail_thumb=$url_img . $thumbnail_thumb;
                                                            }
                                                        } else {
                                                            $thumbnail_thumb="https://dummyimage.com/400x400/000/fff";
                                                        }
                                                        $route_list =route('single.detail', [$related->categorySlug, $related->slug]);
                                                        $date_post = new DateTime($related->updated);
                                                        $date_post = $date_post->format('d-m-Y');
                                                        ?>
                                                        <div class="newslist_page clear">
                                                            <div class="newslist_img">
                                                                <a rel="nofollow" href="{{ $route_list }}">
                                                                    <img class="larger" width="570" height="300" alt="{{ $related->thumbnail_alt  }}"
                                                                         src="{{ $thumbnail_thumb }}" />
                                                                </a>
                                                            </div><!--thumbnail-details-news-->
                                                            <div class="newslist_title">
                                                                <h3 class="blog_item_title"><a href="{{$route_list}}">{{$title}}</a></h3>
                                                                <p class="icon_news">
                                                                    <span class="date pull-left"><i class="fa fa-calendar" aria-hidden="true"></i>{!!$date_post!!}</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div><!--contentNews-->
                                            @endif
                                        </div>
                                    </div><!--single_news_releated-->
                                    </div>
                                </div><!--row-content-->
                            </div>
                        </div><!--leftContent-->
                    </div>
                </section><!--section-->
            </div><!--body-container-->
        </div><!--container-->
    </div><!--main_content-->
@endsection
