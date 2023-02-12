@extends('layouts.app')
@section('seo')
    <?php
    $title = $page->title . ' | ' . Helpers::get_setting('seo_title');
    $description = $page->title . ' | ' . Helpers::get_setting('seo_description');
    $keyword = $page->title . ',' . Helpers::get_setting('seo_keyword');
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
    <div class="breadcrumbs-group-container clear">
        <div class="container clear">
            <div class="breadcrumbs_top_page clear">
                <div class="breadcrumbs-item clear">
                    {!! Breadcrumbs::render('page.index',$page) !!}
                </div>
            </div>
        </div>
    </div><!--home-index-->
    <div class="main_content gray_bg clear">
        <div class="container clear">
            <div class="body-container border-group clear">
                <section id="section" class="section clear">
                    <div class="group-section-wrap clear row">
                        <div class="leftContent col-xs-12 col-sm-12 col-md-12">
                            <div class="row-content-page clear">
                                <div class="listNews default_page clear">
                                    <h1 class="title_product"><span>{{$page->title}}</span></h1>
                                    <div class="description hidden">
                                        {{htmlspecialchars_decode($page->description)}}
                                    </div>
                                    <div class="contentNews news_page_gs boxImg clear">
                                        @if($page)
                                            <div class="entry-post entry_content_details entry_content_news clear">
                                                <div class=" hidden likeButton firts_like clear">
                                                    <div class="l author_pulic">
                                                        <span class="title_rat">Đánh giá bài viết:</span><span
                                                            class="rating r-5" title="Rated 5"></span> <span
                                                            class="dot">. Bởi:</span> <?php if($page->id % 2 == 0): ?>
                                                        <span class="pople">Mạnh Cường</span><?php else: ?><span
                                                            class="pople">Sơn Tùng</span><?php endif; ?>
                                                    </div>
                                                    <div class="fbLikeButton r">
                                                        <ul class="social_like_single social_end_content clear">
                                                            <li class="linkin">
                                                                <script type="IN/Share"
                                                                        data-url="{!!URL::current()!!}"></script>
                                                            </li>
                                                            <li class="twitter_btn">
                                                                <a href="https://twitter.com/share"
                                                                   class="twitter-share-button"
                                                                   data-url="{{URL::current()}}"
                                                                   rel="nofollow">Tweet</a>
                                                            </li>
                                                            <li class="fb_btn fb-like-tbn">
                                                                <div class="fb-like" data-href="{!!URL::current()!!}"
                                                                     data-layout="button_count" data-action="like"
                                                                     data-show-faces="true" data-share="false"></div>
                                                            </li>
                                                            <li class="fb_btn fb-share-tbn">
                                                                <div class="fb-share-button"
                                                                     data-href="{!!URL::current()!!}"
                                                                     data-layout="button_count" data-size="small"
                                                                     data-mobile-iframe="true"></div>
                                                            </li>

                                                        </ul><!--social_tbl_like_group-->
                                                    </div>
                                                </div><!--likeButton-->
                                                <div class="clear details-sumary">
                                                    {!! htmlspecialchars_decode($page->content) !!}
                                                </div><!--details-sumary-->
                                                <div class="likeButton firts_like clear">
                                                    <div class="fbLikeButton r">
                                                        <ul class="social_like_single social_end_content clear">
                                                            <li class="linkin">
                                                                <script type="IN/Share"
                                                                        data-url="{!!URL::current()!!}"></script>
                                                            </li>
                                                            <li class="twitter_btn">
                                                                <a href="https://twitter.com/share"
                                                                   class="twitter-share-button"
                                                                   data-url="{{URL::current()}}"
                                                                   rel="nofollow">Tweet</a>
                                                            </li>
                                                            <li class="fb_btn fb-like-tbn">
                                                                <div class="fb-like" data-href="{!!URL::current()!!}"
                                                                     data-layout="button_count" data-action="like"
                                                                     data-show-faces="true" data-share="false"></div>
                                                            </li>
                                                            <li class="fb_btn fb-share-tbn">
                                                                <div class="fb-share-button"
                                                                     data-href="{!!URL::current()!!}"
                                                                     data-layout="button_count" data-size="small"
                                                                     data-mobile-iframe="true"></div>
                                                            </li>
                                                        </ul><!--social_tbl_like_group-->
                                                    </div>
                                                </div><!--likeButton-->
                                            </div><!--entry-company-details-->
                                        @endif
                                    </div><!--post_category_list-->

                                </div><!--listNews-->
                            </div><!--row-content-->
                        </div><!--leftContent-->
                    </div>
                </section><!--section-->
            </div><!--body-container-->
        </div><!--container-->
    </div><!--main_content-->
@endsection
