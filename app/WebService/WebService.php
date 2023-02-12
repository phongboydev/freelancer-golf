<?php

namespace App\WebService;

use App\Constants\BaseConstants;
use App\Model\Variable_Theme;
use App\Models\City;
use App\Models\ProductStock;
use App\Models\Rating_Product;
use App\Models\State;
use App\Models\Variable_Product;
use App\Models\ViettelPostDistrict;
use App\Models\ViettelPostProvince;
use App\Models\ViettelPostWard;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use App\Models\Product;
use App\Models\Page;
use App\Models\Post;
use App\Models\Slishow;
use App\Models\ProductCategory;
use App\Models\Discount_for_brand;
use App\Models\Category;
use Jenssegers\Agent\Agent;
use DateTime;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class WebService
{
    // SEO
    public function getSEO($data = array())
    {
        $seo = array();
        $seo['title'] = isset($data['title']) ? $data['title'] : '';
        $seo['keywords'] = isset($data['keywords']) ? $data['keywords'] : '';
        $seo['description'] = isset($data['description']) ? $data['description'] : '';
        $seo['og_title'] = isset($data['og_title']) ? $data['og_title'] : '';
        $seo['og_description'] = isset($data['og_description']) ? $data['og_description'] : '';
        $seo['og_url'] = isset($data['og_url']) ? $data['og_url'] : '';
        $seo['og_img'] = isset($data['og_img']) ? $data['og_img'] : '';
        $seo['current_url'] = isset($data['current_url']) ? $data['current_url'] : '';
        $seo['current_url_amp'] = isset($data['current_url_amp']) ? $data['current_url_amp'] : '';
        return $seo;
    }

    public static function convertPermission($permission)
    {
        $permissions = [
            'create' => 1,
            'read' => 2,
            'update' => 4,
            'delete' => 8,
        ];

        $arr_permission = array();
        foreach ($permissions as $permission_key => $value) {
            if ($value & $permission) {
                array_push($arr_permission, $permission_key);
            }
        }
        return $arr_permission;
    }
    public function Get_Category_HomethemeFeatue($take=4){
        $result="";
        $list_cates=Helpers::get_option_minhnn('category-list-feature');
	logger($list_cates);
        $list_cates_arr=explode(",",$list_cates,);
        $id_cate=0;
        $url_img="images/category";
        $result .='<div class="home_cat_item clear row g-0 ps-1">';
        if(!empty($list_cates_arr)):
            for($i=0;$i<count($list_cates_arr);$i++):
                $id_cate=(int)$list_cates_arr[$i];
                $categorys=ProductCategory::where('status','=',1)
                    ->where('id','=',$id_cate)
                    ->orderBy('sort', 'DESC')
                    ->take(1)
                    ->select('product_categories.slug','product_categories.name','product_categories.thumbnail','product_categories.id','product_categories.thumbnail_alt')
                    ->get();
                //dd($categorys);
                if($categorys):
                    $thumbnail="";
                    foreach ($categorys as $category):
                        $result .='<div class="col-6 col-md-6 col-lg-3 px-2 px-md-3 mb-grid-gutter">';

                        if(!empty($category->thumbnail) && $category->thumbnail !=""):
                            /*
                            $thumbnail= Helpers::getThumbnail($url_img,$category->thumbnail, 150, 150, "resize");
                            if(strpos($thumbnail, 'placehold') !== false):
                                $thumbnail=$url_img."/".$thumbnail;
                            endif;
                            */
                            $thumbnail=route('index')."/".$url_img."/".$category->thumbnail;
                        else:
                            $thumbnail="https://dummyimage.com/300x300/000/fff";
                        endif;

                        //$thumbnail=url("/").'/'.$url_img."/".$category->thumbnail;
                        $result .='<a class="d-block text-decoration-none me-1" href="'.route('category.list',array($category->slug)).'">
                                    <div href="'.$thumbnail.'" class="d-block mask">
                                        <div class="img">
                                            <div>
                                                <img src="'.$thumbnail.'" alt="'.$category->name.'"/>
                                            </div>
                                        </div>
                                        <div class="caption">
                                            <h3 class="text-light banner-card-title mb-1">Bộ sưu tập</h3>
                                            <p class="banner-card-description">'.$category->name.'</p>
                                        </div>
                                    </div>
                                </a>';

                        $result .='</div>';
                    endforeach;

                endif;
            endfor;
        else:
            $categorys=ProductCategory::where('status','=',1)
                ->orderBy('sort', 'DESC')
                ->take($take)
                ->select('product_categories.slug','product_categories.name','product_categories.thumbnail','product_categories.id','product_categories.thumbnail_alt')
                ->get();
            $thumbnail="";
            foreach ($categorys as $category):
                $result .='<div class="col-6 col-md-6 col-lg-3 px-2 mb-grid-gutter">';

                if(!empty($category->thumbnail) && $category->thumbnail !=""):
                    $thumbnail=route('index')."/".$url_img."/".$category->thumbnail;
                /*
                    $thumbnail= Helpers::getThumbnail($url_img,$category->thumbnail, 150, 150, "resize");
                    if(strpos($thumbnail, 'placehold') !== false):
                        $thumbnail=$url_img."/".$thumbnail;
                    endif;
                */
                else:
                    $thumbnail="https://dummyimage.com/300x300/000/fff";
                endif;


                $result .='<a class="d-block text-decoration-none me-1" href="'.route('category.list',array($category->slug)).'">
                                    <div href="'.$thumbnail.'" class="d-block mask">
                                        <div class="img">
                                            <div>
                                                <img src="'.$thumbnail.'" alt="'.$category->name.'"/>
                                            </div>
                                        </div>
                                        <div class="caption">
                                            <h3 class="text-light banner-card-title mb-1">Bộ sưu tập</h3>
                                            <p class="banner-card-description">'.$category->name.'</p>
                                        </div>
                                    </div>
                                </a>';

                $result .='</div>';
            endforeach;
        endif;
        $result .='</div>';
        return $result;
    }
    public function Get_Category_HomethemeFeatueRender($take=4){
        return $this->Get_Category_HomethemeFeatue($take);
    }
    public function SliderList($rows)
    {
        $agent = new Agent();
        $result = "";
        $i = 0;
        if (count($rows) > 0):
            $result .= '<div class="main_slider_home owl-carousel owl-theme">';
            foreach ($rows as $row) {
                $i = $i + 1;
                $result .= "";
                $video_link = ($row->video_link_slider != "") ? htmlspecialchars_decode() : '';
                $video_link_mobile = ($row->video_link_slider_mobile != "") ? htmlspecialchars_decode(
                    $row->video_link_slider_mobile
                ) : '';

                $description = ($row->description != "") ? htmlspecialchars_decode() : '';
                if ($description != '') {
                    $cation = '<div class="carousel-caption">' . $description . '</div>';
                } else {
                    $cation = "";
                }

                if ($video_link != '') {
                    if (!$agent->isMobile()) {
                        $youtube_ID = Helpers::parse_youtubeID($video_link);
                        if ($youtube_ID) {
                            $link_you = '<iframe id="iframe_youtube" class="carousel__iframe" src="https://www.youtube.com/embed/' . $youtube_ID . '?autoplay=1&loop=1&autopause=0&showinfo=0&controls=0&mute=0" frameborder="0" frameborder="0" allowfullscreen></iframe>';
                        } else {
                            $link_you = '<iframe class="iframe_vimeo" id="iframe_vimeo_' . $i . '" src="' . $video_link . '?autoplay=1&loop=1&autopause=0&showinfo=0&controls=0&mute=0" width="100%" height="600" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
                        }
                        $result .= '<div class="carousel-item carousel-item-' . $i . ' ">
                            <div class="video-container">
                                <div class="backdrop"></div>
                                ' . $link_you . '
                            </div>
                        </div>';
                    } else {
                        if ($video_link_mobile != '') {
                            $youtube_ID_mobile = Helpers::parse_youtubeID($video_link_mobile);
                            if ($youtube_ID_mobile) {
                                $link_you = '<iframe id="iframe_youtube" class="carousel__iframe" src="https://www.youtube.com/embed/' . $youtube_ID_mobile . '?autoplay=1&loop=1&autopause=0&showinfo=0&controls=0&mute=0" frameborder="0" frameborder="0" allowfullscreen></iframe>';
                            } else {
                                $link_you = '<iframe class="iframe_vimeo" id="iframe_vimeo_' . $i . '" src="' . $video_link_mobile . '?autoplay=1&loop=1&autopause=0&showinfo=0&controls=0&mute=0" width="100%" height="600" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
                            }
                            $result .= '<div class="carousel-item carousel-item-' . $i . ' ">
                                <div class="video-container">
                                    <div class="backdrop"></div>
                                    ' . $link_you . '
                                </div>
                            </div>';
                        } else {
                            $youtube_ID = Helpers::parse_youtubeID($video_link);
                            if ($youtube_ID) {
                                $link_you = '<iframe id="iframe_youtube" class="carousel__iframe" src="https://www.youtube.com/embed/' . $youtube_ID . '?autoplay=1&loop=1&autopause=0&showinfo=0&controls=0&mute=0" frameborder="0" frameborder="0" allowfullscreen></iframe>';
                            } else {
                                $link_you = '<iframe class="iframe_vimeo" id="iframe_vimeo_' . $i . '" src="' . $video_link . '?autoplay=1&loop=1&autopause=0&showinfo=0&controls=0&mute=0" width="100%" height="600" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
                            }
                            $result .= '<div class="carousel-item carousel-item-' . $i . ' ">
                                <div class="video-container">
                                    <div class="backdrop"></div>
                                    ' . $link_you . '
                                </div>
                            </div>';
                        }
                    }
                } else {
                    if ($agent->isMobile()) {
                        if ($row->src_mobile != '') {
                            $src = $row->src_mobile;
                        } else {
                            $src = "";
                        }
                    } else {
                        $src = $row->src;
                    }

                    if ($src != "") {
                        if (!empty($row->link)) {
                            $result .= '<div class="carousel-item carousel-item-' . $i . ' ">
                                <a href="' . $row->link . '" target="' . $row->target . '">
                                    <img src="' . $src . '" alt="' . $row->name . '" alt="' . $row->name . '"/>
                                </a>
                                ' . $cation . '
                            </div>';
                        } else {
                            $result .= '<div class="carousel-item carousel-item-' . $i . ' ">
                                 <img src="' . $src . '" alt="' . $row->name . '" class="lazy-load" />
                                 ' . $cation . '
                            </div>';
                        }
                    }
                }
            }
            $result .= '</div>';
        endif;
        return $result;
    }

    public function SliderListRender()
    {
        return $this->SliderList(Slishow::where('status', BaseConstants::ACTIVE)->orderBy('order', 'DESC')->get());
    }

    public function ProductSliderRender($type, $take = 8): string
    {
        $html = '';
        $query = ProductCategory::join(
            'join_category_product',
            'product_categories.id',
            'join_category_product.category_id'
        )
            ->join('products', 'join_category_product.product_id', 'products.id')
            ->join('product_stocks', 'product_stocks.product_id', 'products.id')
            ->where('products.status', BaseConstants::ACTIVE)
            ->orderBy('product_stocks.sort', 'DESC')
            ->orderBy('product_stocks.created_at', 'DESC')
            ->take($take)
            ->select(
                'product_stocks.*',
                'products.start_event',
                'products.end_event',
                'products.rating',
                'products.total_rating',
                'products.store_status',
                'product_categories.name as categoryName',
                'product_categories.slug as categorySlug',
                'product_categories.id as categoryID'
            );

        if ($type == 'best-seller') {
            $query->where('products.best_seller', BaseConstants::ACTIVE);
            $titleBlock = '<p class="text-center text-primary text-brand">JAPAN GOLF</p>
                    <h2 class="text-center h1-title">Sản phẩm nổi bật</h2>';
            $link = '<div class="text-center pt-3">
                        <a class="btn btn--xemtatca" href="' . route('bestSeller') . '">
                            Xem tất cả<i class="ci-arrow-right ms-1"></i>
                        </a>
                    </div>';
        } else {
            $query->where('products.propose', BaseConstants::ACTIVE);
            $titleBlock = '';
            $link = '';
        }

        $rows = $query->get();
        if (count($rows) > 0) {
            $html = '<div id="trending-products-waves">
                <section class="container pt-md-3 pb-5">
                    ' . $titleBlock . '
                    <div class="wrapper">
                        <div class="center-slider product-item-home">';
            foreach ($rows as $item) {
                $html .= $this->RenderBlockProduct($item);
            }
            $html .= '</div>
                    </div>
                    ' . $link . '
                </section>
            </div>';
        }
        return $html;
    }

    public function RenderBlockProduct($product): string
    {
        $url_img = 'images/product';
        if (!empty($product->thumbnail) && $product->thumbnail != "") {
            $thumbnail = Helpers::getThumbnail($url_img, $product->thumbnail, 400, 300, "resize");
            if (strpos($thumbnail, 'placehold') !== false) {
                $thumbnail = $url_img . $thumbnail;
            }
        } else {
            $thumbnail = "https://dummyimage.com/400x600/000/fff";
        }

        $productLink = route('single.detail', [$product->categorySlug, $product->slug]);

        //render rating html
        $ratingHtml = '';
        $isDecimal = $this->is_decimal($product->rating);
        $countStarNotActive = 5 - (int)$product->rating;
        for ($i = 1; $i < $product->rating; $i++) {
            $ratingHtml .= '<i class="star-rating-icon ci-star-filled active"></i>';
        }

        for ($i = 0; $i < $countStarNotActive; $i++) {
            if ($i == 0 && $isDecimal) {
                $ratingHtml .= '<i class="star-rating-icon ci-star-half active"></i>';
            } else {
                $ratingHtml .= '<i class="star-rating-icon ci-star"></i>';
            }
        }

        $finalPrice = $product->price_origin;
        $today = date('Y-m-d H:i:s');
        if ($product->start_event <= $today && $product->end_event >= $today) {
            $finalPrice = $product->price_promotion;
        }
        $finalPrice = $this->formatMoney12($finalPrice);

        $storeStatus = 'Hết hàng';
        if ($product->stock > 0 && $product->store_status == BaseConstants::ACTIVE) {
            $storeStatus = 'Còn hàng';
        }
        return '<div class="product-card text-center">
            <div class="wrap-product-item clear">
                <a class="links-product" href="' . $productLink . '">
                    <img src="' . $thumbnail . '" alt="' . $product->title . '">
                </a>
                <div class="py-2">
                    <h3 class="product-title fs-sm">
                        <a href="' . $productLink . '">' . $product->title . '</a>
                    </h3>
                    <div class="star-rating product--pb-12">
                        ' . $ratingHtml . '
                        <span class="ps-1 star-rating__number">
                            <small>' . $product->rating . ' (' . $product->total_rating . ')</small>
                        </span>
                    </div>
                    <div class="product-price product--pb-12">
                        <span class="text-primary fw-700">' . $finalPrice . 'đ</span>
                    </div>
                    <a class="product-meta d-block fs-xs pb-3" href="' . $productLink . '">' . $storeStatus . '</a>
                </div>
            </div>
        </div>';
    }

    public function CategoriesBlockRender(): string
    {
        if (Cache::has('categoriesBlock')) {
            $html = htmlspecialchars_decode(Cache::get('categoriesBlock'));
        } else {
            $html = '';
            $categories = ProductCategory::where('status', BaseConstants::ACTIVE)
                ->where('parent', 0)
                ->get();
            if (count($categories)) {
                $html = '<ul class="list_category_sidebar_v pc">';
                foreach ($categories as $category) {
                    $categoryChilds = ProductCategory::where('parent', $category->id)
                        ->get();
                    $hasChild = '';
                    $subHtml = '';
                    if (count($categoryChilds)) {
                        $hasChild = 'has-sub';
                        $subHtml = '<ul>';
                        foreach ($categoryChilds as $child) {
                            $subHtml .= '<li class="">
                            <a href="' . route('category.list', $child->slug) . '" data-category="' . $child->id . '">
                                ' . $child->name . '
                            </a>
                        </li>';
                        }
                        $subHtml .= '</ul>';
                    }
                    $html .= '<li class="' . $hasChild . '">
                    <a href="' . route('category.list', $category->slug) . '" data-category="' . $category->id . '">
                        <strong>' . $category->name . '</strong>
                    </a>
                    ' . $subHtml . '
                </li>';
                }
                $html .= '</ul>';
            }
            $expiresAt = Carbon::now()->addMinutes(10);
            Cache::put('categoriesBlock', htmlspecialchars($html), $expiresAt);
        }
        return $html;
    }



    public function CategoryBlockRender($category_id,$take = 8): string
    {
        $html = '';
        $category = ProductCategory::where('id', $category_id)
            ->where('status', BaseConstants::ACTIVE)
            ->first();
        if ($category) {
            $galleries = ($category->galleries) ? json_decode($category->galleries) : [];
            if (count($galleries)) {
                $galleryHtml = '';
                foreach ($galleries as $item) {
                    $galleryHtml .= '<img src="' . asset('images/category/' . $item) . '" alt="' . $category->name . '">';
                }
            } else {
                $galleryHtml = '<img src="' . asset('img/home/categories/slider-gay-moi.png') . '" alt="' . $category->name . '">';
            }

            $products = ProductCategory::join(
                'join_category_product',
                'product_categories.id',
                'join_category_product.category_id'
            )
                ->join('products', 'join_category_product.product_id', 'products.id')
                ->join('product_stocks', 'product_stocks.product_id', 'products.id')
                ->where('products.status', BaseConstants::ACTIVE)
                ->where('product_categories.id', $category_id)
                ->orderBy('product_stocks.sort', 'DESC')
                ->orderBy('product_stocks.created_at', 'DESC')
                ->take($take)
                ->get(
                    [
                        'product_stocks.*',
                        'products.start_event',
                        'products.end_event',
                        'products.rating',
                        'products.total_rating',
                        'products.store_status',
                        'product_categories.name as categoryName',
                        'product_categories.slug as categorySlug',
                        'product_categories.id as categoryID'
                    ]
                );

            $firstCol = '';
            $secondCol = '';
            if (count($products)) {
                $i = 0;
                foreach ($products as $product) {
                    if ($i < 2) {
                        $firstCol .= '<div class="col-lg-6 col-6 px-0 px-sm-2 mb-sm-4">' . $this->RenderBlockProduct($product) . '</div>';
                    } else {
                        $secondCol .= $this->RenderBlockProduct($product);
                    }
                    $i++;
                }
            }

            $html .= '<div class="category">
                <section class="container">
                    <div class="d-flex justify-content-between py-grid-gutter">
                        <div class="category__title">
                            <p class="text-primary text-brand">JAPAN GOLF</p>
                            <h3 class="mb-1">' . $category->name . '</h3>
                        </div>
                        <div class="text-center pt-3">
                            <a class="btn btn--xemtatca" href="' . route('category.list', $category->slug) . '">
                                Xem tất cả<i class="ci-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Banner with controls-->
                        <div class="col-md-6">
                            <div class="tns-carousel tns-nav-enabled">
                                <div class="tns-carousel-inner" data-carousel-options=\'{"nav":false}\'>
                                    ' . $galleryHtml . '
                                </div>
                            </div>
                        </div>
                        <!-- Product grid (carousel)-->
                        <div class="col-md-6 pt-4 pt-md-0">
                            <div class="row mx-n2">
                                ' . $firstCol . '
                            </div>
                        </div>
                        <!-- Slider list products -->
                        <div class="carousel-custom py-lg-3">
                            <div class="tns-carousel tns-controls-static tns-controls-outside">
                                <div class="tns-carousel-inner-container" data-carousel-options=\'{"items": 2, "gutter": 16,"nav":false, "controls": true, "autoHeight": true, "loop": true, "responsiveClass":true, "responsive": {"0":{"items":1, "loop":true}, "480":{"items":2, "loop":true}, "720":{"items":3, "loop":true}, "991":{"items":2, "loop":true}, "1140":{"items":3, "loop":true}, "1300":{"items":4, "loop":true, "gutter": 20}, "1500":{"items":4, "loop":true, "gutter" : 20}}}\'>
                                    ' . $secondCol . '
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>';
        }
        return $html;
    }

    public function is_decimal( $val ): bool
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }

    public function ListNewsRender($take = 8) : string
    {
        $html = "";
        $rows = Post::where('post.status', BaseConstants::ACTIVE)
            ->join('join_category_post', 'post.id', 'join_category_post.id_post')
            ->join('categories', 'join_category_post.id_category', 'categories.categoryID')
            ->select(
                'post.title',
                'post.slug',
                'post.thumbnail',
                'post.thumbnail_alt',
                'post.description',
                'post.content',
                'categories.categoryName',
                'categories.categorySlug'
            )
            ->orderBy('post.order_short', 'DESC')
            ->orderBy('post.updated', 'DESC')
            ->take($take)
            ->get();
        $m = 0;
        if (count($rows)) {
            $url_img = "images/article";
            $newsBlock = '';
            foreach ($rows as $row) {
                if (!empty($row->thumbnail) && $row->thumbnail != "") {
                    $thumbnail = Helpers::getThumbnail($url_img, $row->thumbnail, 400, 300, "resize");
                    if (strpos($thumbnail, 'placehold') !== false) {
                        $thumbnail = $url_img . $thumbnail;
                    }
                } else {
                    $thumbnail = "https://dummyimage.com/640x590/000/fff";
                }
                $newsLink = route('single.detail', [$row->categorySlug, $row->slug]);
                $newsBlock .= '<div class="card product-card">
                    <a class="card-img-top d-block overflow-hidden" href="' . $newsLink . '">
                        <img src="' . $thumbnail . '" alt="' . $row->title . '">
                    </a>
                    <div class="card-body py-3 px-0">
                        <a class="product-meta d-block fs-xs pb-1" href="' . $newsLink . '">' . $row->created_at . '</a>
                        <h3 class="product-title">
                            <a href="' . $newsLink . '" class="new-title new-card-description">
                                ' . $row->title . '
                            </a>
                        </h3>
                        <div class="text-description new-short-body">
                            ' . htmlspecialchars_decode($row->description) . '
                        </div>
                    </div>
                </div>';
            }
            $html = '<div id="trending-products-waves">
                <section class="container">
                    <p class="text-center text-primary text-brand pt-4">JAPAN GOLF</p>
                    <h2 class="text-center h1-title">TIN TỨC & SỰ KIỆN</h2>
                    <!-- Slider list news -->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="news" class="carousel-custom">
                                <div class="tns-carousel tns-controls-static tns-controls-outside">
                                    <div class="tns-carousel-inner"
                                         data-carousel-options=\'{"items": 2,"nav":false, "controls": true, "autoHeight": true, "responsive": {"0":{"items":1}, "480":{"items":2}, "720":{"items":2, "gutter":20}, "991":{"items":2, "gutter":20}, "1140":{"items":2, "gutter":20}, "1300":{"items":3, "gutter": 20}, "1500":{"items":3}}}\'>
                                        ' . $newsBlock . '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center pt-3 pb-3 pb-md-5">
                        <a class="btn banner-btn-xemtatca" href="' . route('category.list', 'tin-tuc--su-kien') . '">
                            Xem tất cả<i class="ci-arrow-right ms-1"></i>
                        </a>
                    </div>
                </section>
            </div>';
        }
        return $html;
    }

    public function get_template_page_muti($slugs, $class = 'view_tem_plate_custom clear')
    {
        $content = "";
        if ($slugs != "") {
            $slug_exs = explode(",", $slugs);
            if (count($slug_exs) > 0) {
                for ($i = 0; $i < count($slug_exs); $i++) {
                    $slug = str_replace(" ", "", $slug_exs[$i]);
                    if ($slug != '') {
                        $page = Page::where('slug', $slug)
                            ->where('status', BaseConstants::ACTIVE)
                            ->where('template', BaseConstants::ACTIVE)
                            ->first();
                        if ($page) {
                            $content .= '<div class="' . $slug . '">' . htmlspecialchars_decode(
                                    $page->content
                                ) . '</div>';
                        }
                    }
                }
            }
        }
        return $content;
    }

    public function get_child_category_product($parent_id = 0, $count = 0)
    {
        $data_customers = ProductCategory::join(
            'join_category_product',
            'product_categories.id',
            'join_category_product.category_id'
        )
            ->join('products', 'join_category_product.product_id', 'products.id')
            ->where('product_categories.parent', $parent_id)
            ->where('products.status', BaseConstants::ACTIVE)
            ->groupBy('products.id')
            ->orderBy(DB::raw('count(products.id)'), 'DESC')
            ->select(
                DB::raw('DISTINCT product_categories.slug'),
                'product_categories.name',
                'product_categories.description',
                'product_categories.id'
            )
            ->offset(0)
            ->limit($count)
            ->get();
        if (count($data_customers) > 0) {
            return $data_customers;
        }
        return null;
    }

    function variableGetNameByID($id)
    {
        $name = "";
        $variable = Variable_Product::where('id', $id)->first();
        if ($variable) {
            $name = $variable->name;
        }
        return $name;
    }

    public function variableSlugBox($slug)
    {
        $result = "";
        $variables = Variable_Product::where('slug', $slug)->first();
        if ($variables) {
            $id_variable = $variables->id;
            $variable_lists = Variable_Product::where('parent', $id_variable)
                ->where('status', BaseConstants::ACTIVE)
                ->orderBy('name', 'ASC')
                ->get();
            if ($variable_lists) {
                $result .= '<ul class="list_variable clear">';
                foreach ($variable_lists as $variable_list) {
                    $id_list_child = $variable_list->id;
                    $name_list_child = $variable_list->name;
                    $slug_list_child = $variable_list->slug;
                    $url_param = Helpers::add_query_params([$slug => $id_list_child]);
                    $result .= '<li>';
                    if ($variables->slug == 'size') {
                        $result .= '<a href="' . $url_param . '" aria-label="' . $name_list_child . '" class="swatch-option-link-layered">
                             <div class="swatch-option text " tabindex="-1" option-type="0" option-id="' . $id_list_child . '" option-label="' . $name_list_child . '" option-tooltip-thumb="" option-tooltip-value="">' . $name_list_child . '</div>
                        </a>';
                    } else {
                        $result .= '<label class="checkbox" for="variable_item_search_' . $id_list_child . '">
                        <input type="checkbox" class="variable_search_sidebar_input" name="variable_search_sidebar[]" value="' . $id_list_child . '" id="variable_item_search_' . $id_list_child . '" onclick="window.location.href=
                       \'' . $url_param . '\'">' . $name_list_child . '
                        </label>';
                    }
                    $result .= '</li>';
                }
                $result .= '</ul>';
            }
        }
        return $result;
    }

    public function variableSlugBoxRender($slug)
    {
        return $this->variableSlugBox($slug);
    }

    public function loadNewMenu($limit = 4)
    {
        $result = "";
        $data_customers = Category::join(
            'join_category_post',
            'categories.categoryID',
            'join_category_post.id_category'
        )
            ->join('post', 'join_category_post.id_post', 'post.id')
            ->where('post.status', BaseConstants::ACTIVE)
            ->orderBy('post.updated', 'DESC')
            ->groupBy('post.slug')
            ->select(
                'post.*',
                'categories.categoryName',
                'categories.categorySlug',
                'categories.categoryDescription',
                'categories.categoryID'
            )
            ->take($limit)
            ->get();
        if (count($data_customers) > 0) {
            $url_img = "images/article/";
            $i = 0;
            $result .= '<div class="load_new_menu"><div class="row row_menu_post row_menu_post_blog">';
            foreach ($data_customers as $row) {
                $i = $i + 1;
                $result .= "<div class='col-lg-3 col-md-6 col-xs-12 item_post_menu'>";
                if (!empty($row->thumbnail) && $row->thumbnail != "") {
                    $url_var = explode('/', $row->thumbnail);
                    $result_filename = end($url_var);
                    $thumbnail = Helpers::getThumbnail($url_img, $result_filename, 500, 250, "resize");
                    if (strpos($thumbnail, 'placehold') !== false) {
                        $thumbnail = $url_img . $result_filename;
                    }
                } else {
                    $thumbnail = "https://dummyimage.com/500x250/000/fff";
                }
                $categoryName = $row->categoryName;
                $title = $row->title;
                $result .= '
                    <div class="item_post_list_menu clear">
                        <a class="effect" href="' . route('single.detail', array($row->categorySlug, $row->slug)) . '">
                            <div class="item-thumb ">
                                <img src="' . $thumbnail . '" alt="' . $row->thumbnail_alt . '"/>
                                <h3 class="title_cate_menu">' . $categoryName . '</h3>
                            </div>
                            <h4 class="title_menu">' . $title . '</h4>
                        </a>
                    </div>
                    ';
                $result .= "</div>";
            }
            $result .= "</div></div>";
        }
        return $result;
    }

    public function loadNewMenuRender($count = 4)
    {
        return $this->loadNewMenu($count);
    }

    public function ProductByID($id, $kb = '')
    {
        $html = '';
        $agent = new  Agent();
        $product = ProductCategory::join('join_category_product', 'product_categories.id', 'join_category_product.category_id')
            ->join('products', 'join_category_product.product_id', 'products.id')
            ->join('product_stocks', 'product_stocks.product_id', 'products.id')
            ->where('products.status', BaseConstants::ACTIVE)
            ->where('products.store_status', BaseConstants::ACTIVE)
            ->where('product_stocks.id', $id)
            ->select(
                'product_stocks.*',
                'products.start_event',
                'products.end_event',
                'products.group_variable_product',
                'product_categories.name as categoryName',
                'product_categories.slug as categorySlug',
                'product_categories.id as categoryID'
            )
            ->first();
        if ($product) {
            $url_img = 'images/product';
            if (!empty($product->thumbnail) && $product->thumbnail != "") {
                $thumbnail_thumb = Helpers::getThumbnail($url_img, $product->thumbnail, 320, 480, "resize");
                if (strpos($thumbnail_thumb, 'placehold') !== false) {
                    $thumbnail_thumb = $url_img . $thumbnail_thumb;
                }
            } else {
                $thumbnail_thumb = "https://dummyimage.com/320x480/000/fff";
            }

            $title = $product->title;
            $currency = Helpers::get_option_minhnn('currency');
            $price_origin = (int)$product->price_origin;
            $price_promotion = (int)$product->price_promotion;

            if (($price_promotion < $price_origin) && $price_promotion > 0) {
                $price_all = '<span class="special-price">
                        <span class="price-label">Special Price</span>
                        <span class="price" id="product-price-' . $product->id . '">
                            ' . Helpers::flagCurrency($price_promotion, $currency) . '
                        </span>
                    </span>
                    <span class="old-price">
                        <span class="price-label">Regular Price:</span>
                        <span class="price" id="old-price-' . $product->id . '">
                            ' . Helpers::flagCurrency($price_origin, $currency) . '
                        </span>
                    </span>';
            } else {
                if ($price_origin == 0) {
                    $price_origin = '<span class="red">Contact</span>';
                } else {
                    //$price_origin = $currency . number_format($price_origin);
                    $price_origin = Helpers::flagCurrency($price_origin, $currency);
                }
                $price_all = '<span class="regular-price" id="product-price-' . $product->id . '">
                        <span class="price">' . $price_origin . '</span>
                    </span>';
            }

            $variable_html = '';
//            if($product->group_variable_product != '[]' && $product->group_variable_product != '') {
//                $variable_option_child = true;
//                $variable_html = view('product.variable-path', compact('product', 'variable_option_child'))->render();
//            }

            if (!$agent->isMobile()) {
                $button_html = '<div class="tbl_add_cart asolute_cart clear">
                    <a rel="nofollow" id="tbl_add_cart_' . $product->id . '" class="text-center clear"
                    data-id="modal-content-' . $product->id . '" data-name="' . $product->title . '"
                    data-summary="' . $product->sku . '" tabindex="-1">ADD</a>
                </div>';
            } else {
                $button_html = '<div class="tbl_add_cart asolute_cart clear">
                    <a rel="nofollow" id="tbl_add_cart_' . $product->id . '" class="text-center clear"
                    data-id="modal-content-' . $product->id . '" data-name="' . $product->title . '"
                    data-summary="' . $product->sku . '" tabindex="-1">
                        <img src="' . asset('img/basket_ctl.png') . '">
                    </a>
                </div>';
            }

            $html .= '<div class="product-item ' . $kb . ' clear">
                <div class="item-thumb clear">
                    <a class="pop effect zoom images-rotation" data-id="modal-content-' . $product->id . '"
                    data-default="' . $thumbnail_thumb . '"
                    href="' . route('single.detail', [$product->categorySlug, $product->slug]) . '">
                        <img class="lazy-load owl-lazy" src="' . $thumbnail_thumb . '" alt="' . $title . '"/>
                    </a>
                </div>
                <div class="pro-info clear">
                    <h3 class="product-name">
                        <a class="pop" data-id="modal-content-' . $product->id . '" href="' . route('single.detail', [$product->categorySlug, $product->slug]) . '">
                            ' . $title . '
                        </a>
                    </h3>
                    <div class="price-box c">
                        ' . $price_all . '
                    </div>
                    <div class="size_option_complete clear">
                        ' . $variable_html . '
                    </div>
                    ' . $button_html . '
                </div>
            </div>';
        }
        return $html;
    }

    public function ProductByIDRender($id_product, $kb = '')
    {
        return $this->ProductByID($id_product, $kb);
    }

    public function ModalProductByID($id)
    {
        $html = '';
        $product_id = 0;
        $product = ProductCategory::join('join_category_product', 'join_category_product.category_id', 'product_categories.id')
            ->join('products', 'products.id', 'join_category_product.product_id')
            ->join('product_stocks', 'product_stocks.product_id', 'products.id')
            ->where('products.status', BaseConstants::ACTIVE)
            ->where('product_stocks.id', $id)
            ->select(
                'product_stocks.*',
                'products.start_event',
                'products.end_event',
                'products.group_variable_product',
                'products.fabric_care',
                'product_categories.name as categoryName',
                'product_categories.slug as categorySlug',
                'product_categories.id as categoryID'
            )
            ->first();
        if ($product) {
            $url_img = 'images/product';
            if (!empty($product->thumbnail) && $product->thumbnail != "") {
                $thumbnail_thumb = Helpers::getThumbnail($url_img, $product->thumbnail, 320, 480, "resize");
                if (strpos($thumbnail_thumb, 'placehold') !== false) {
                    $thumbnail_thumb = $url_img . $thumbnail_thumb;
                }
            } else {
                $thumbnail_thumb = "https://dummyimage.com/320x480/000/fff";
            }

            $price_origin = (int)$product->price_origin;
            $price_promotion = (int)$product->price_promotion;
            $currency = Helpers::get_option_minhnn('currency');

            if (($price_promotion < $price_origin) && $price_promotion > 0) {
                $price_all = '<span class="special-price">
                     <span class="price-label">Special Price</span>
                     <span class="price" id="product-price-' . $product->id . '">
                       ' . Helpers::flagCurrency($price_promotion, $currency) . '
                     </span>
                     </span>
                     <span class="old-price">
                         <span class="price-label">Regular Price:</span>
                         <span class="price" id="old-price-' . $product->id . '">
                              ' . Helpers::flagCurrency($price_origin, $currency) . '
                         </span>
                     </span>';
                $price_item_set = $price_promotion;
            } else {
                $price_all = '<span class="regular-price" id="product-price-' . $product->id . '">
                      <span class="price">' . Helpers::flagCurrency($price_origin, $currency) . '</span>
                </span>';
                $price_item_set = $price_origin;
            }

            $sku_html = '';
            if ($product->sku != '') {
                $sku_html .= '<div class="item-brand item-sku" id="product-sku">
                    <span class="tix">Sku: </span>
                    <h6 class="product_sku">' . $product->sku . '</h6>
                </div>';
            }

            $functionAddToWishList = "addToWishList('" . $product->id . "'); return false;";
            if (Auth::check()) {
                $check_wishlist = Wishlist::where('product_id', $product_id)
                    ->where('user_id', Auth::user()->id)
                    ->first();
                if ($check_wishlist) {
                    $functionAddToWishList = 'return false;';
                }
            }

            $fabric_care_html = '';
            if ($product->fabric_care != "") {
                $fabric_care_html = '<div class="quick__middle clear">
                    <h4 class="title-colap clear">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $product->id . '">
                            <i class="fa-caret-right fa"></i> FABRIC &amp; CARE
                        </a>
                    </h4>
                    <div id="collapse' . $product->id . '" class="panel-collapse collapse">
                        <div class="content-colap clear">
                            ' . htmlspecialchars_decode($product->fabric_care) . '
                        </div>
                    </div>
                </div>';
            }

            $variable_html = '';
            if($product->group_variable_product != '[]' && $product->group_variable_product != '') {
                $variable_option_child = true;
                $variable_html = view('product.variable-path', compact('product', 'variable_option_child'))->render();
            }

            $html .= '<aside role="dialog" class="modal-popup quick-view-popup modal-slide quick-view-popup_' . $product->id . '"
                data-quickview-id="' . $product->id . '" aria-describedby="modal-content-' . $product->id . '"
                data-role="modal" data-type="popup" tabindex="0">
                <div data-role="focusable-start" tabindex="0"></div>
                <div class="modal-inner-wrap" data-role="focusable-scope">
                    <header class="modal-header">
                        <button class="action-close" data-role="closeBtn" type="button">
                            <span>Close</span>
                        </button>
                    </header><!--modal-header-->
                    <div id="modal-content-' . $product->id . '" class="modal-content" data-role="content">
                        <div id="quickViewContainer">
                            <div class="quick__wrapper">
                                <div id="quick__slider_' . $product->id . '" class="quick__slider ">
                                    <img class="lazy-load owl-lazy" src="' . $thumbnail_thumb . '" alt="' . $product->title . '"/>
                                </div>
                                <div class="quick__content">
                                    <div class="spinner-border text-dark" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <h3 class="title_product_detail">' . $product->title . '</h3>
                                    ' . $sku_html . '
                                    <div class="price-box price_p c">
                                        ' . $price_all . '
                                    </div>
                                    <div class="container_bienthe_pop_group clear" id="container_bienthe_pop_group_' . $product->id . '" data-id="' . $product->id . '">
                                        <div class="classlist">
                                            ' . $variable_html . '
                                            <input type="hidden" name="_token" id="token" value="' . csrf_token() . '">
                                        </div><!--classlist-->
                                    </div><!--container_bienthe_pop_group-->
                                    <form class="frm-sold-out clear" action="' . route('contactToOrder') . '" method="POST">
                                        ' . csrf_field() . '
                                        <input type="hidden" name="data_option" id="sold_out_option">
                                        <input type="hidden" name="id_product" value="' . $product->id . '">
                                        <p>Be the first to know when your size is back in stock!</p>
                                        <div class="form-group">
                                            <input type="email" name="email_sold_out" class="form-control"
                                                   placeholder="Enter your email">
                                        </div>
                                        <div class="form-group text-center">
                                            <button type="submit" class="btn_let_me_know">Let me know!</button>
                                        </div>
                                    </form>
                                    <div class="container_tbl_add_cart_view clear">
                                        <div class="addmorecart_content">
                                            <a href="javascript:void(0)" id="btn_sold_out">SOLD OUT</a>
                                            <a id="btn_cart_primary"
                                               class="green_addtocart_btn btn-cart-list"
                                               data-id="' . $product->id . '"
                                               data-product-parent="' . $product->product_id . '"
                                               data-quantity="1"
                                               data-option="' . $product->key_option . '"
                                               onclick="addToCart(this)"
                                            >
                                                <i class="dslc-icon-ext-ecommerce_cart"></i> Add to Shopping bag
                                            </a>
                                        </div>
                                        <div class="cartbtn_bottom">
                                            <a href="javascript:void(0)" onclick="' . $functionAddToWishList . '" class="link-wishlist" title="Add to Wishlist">
                                            <i class="dslc-icon-ext-heart"></i> <span class="txt"></span>
                                            (<span class="ft">' . self::countTotalUserAddToWishlist($product->id) . '</span>)
                                        </a>
                                        </div>
                                    </div>
                                    ' . $fabric_care_html . '
                                    <div class="full_link clear">
                                        <a href="' . route('single.detail', [$product->categorySlug, $product->slug]) . '">
                                            Full Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-role="focusable-end" tabindex="0"></div>
            </aside>';
        }
        return $html;
    }

    public function ModalProductByIDRender($id_product)
    {
        return $this->ModalProductByID($id_product);
    }

    public function countTotalUserAddToWishlist($product_id)
    {
        return Wishlist::where('product_id', $product_id)
            ->groupBy('user_id')
            ->count();
    }

    public function ListMenuCateMobile()
    {
        $result = "";
        $categories = ProductCategory::where('show_in_menu', 1)
            ->where('status', BaseConstants::ACTIVE)
            ->orderBy('sort', 'DESC')
            ->get(
                [
                    'id',
                    'name',
                    'slug',
                    'parent'
                ]
            )
            ->toArray();
        $result .= self::showMenuMobileHtml($categories, 0, 0);
        return $result;
    }

    public function ListMenuCateMobileRender()
    {
        return $this->ListMenuCateMobile();
    }

    public function getOptionStateByID($country, $state)
    {
        $result = "";
        if ($country == 240) {
            $data_state = ViettelPostProvince::orderBy('name', 'ASC')->select('name', 'id')->get();
        } else {
            $data_state = State::where('country_id', $country)->orderBy('name', 'ASC')->select('name', 'id')->get();
        }
        if ($data_state) {
            foreach ($data_state as $item) {
                if ($item->id == $state) {
                    $result .= "<option value='" . $item->id . "' selected>" . $item->name . "</option>";
                } else {
                    $result .= "<option value='" . $item->id . "'>" . $item->name . "</option>";
                }
            }
        }
        return $result;
    }

    public function getOptionCityByID($country, $state, $city)
    {
        $result = "";
        if ($country == 240) {
            $data_city = ViettelPostDistrict::where('province_id', $state)
                ->orderBy('name', 'ASC')->get(['name', 'id']);
        } else {
            $data_city = City::where('state_id', $state)->orderBy('name', 'ASC')->select('name', 'id')->get();
        }
        if ($data_city) {
            foreach ($data_city as $item) {
                if ($item->id == $city) {
                    $result .= "<option value='" . $item->id . "' selected>" . $item->name . "</option>";
                } else {
                    $result .= "<option value='" . $item->id . "'>" . $item->name . "</option>";
                }
            }
        }
        return $result;
    }

    public function getOptionWardByID($city, $ward)
    {
        $result = "";
        $data_ward = ViettelPostWard::where('district_id', $city)
            ->orderBy('name', 'ASC')
            ->get(['name', 'id']);
        if ($data_ward) {
            foreach ($data_ward as $item) {
                if ($item->id == $ward) {
                    $result .= "<option value='" . $item->id . "' selected>" . $item->name . "</option>";
                } else {
                    $result .= "<option value='" . $item->id . "'>" . $item->name . "</option>";
                }
            }
        }
        return $result;
    }

    public function update_post_hit($id)
    {
        $post = Post::where('id', $id)
            ->select('hit')
            ->first();
        if ($post) {
            return Post::where("id", $id)->update(["hit" => (int)$post->hit + 1]);
        }
        return true;
    }

    public static function showMenuMobileHtml($menus, $menu_current, $id_parent = 0, &$html = '', &$i = 0)
    {
        $menu_tmp = array();
        foreach ($menus as $key => $item) {
            if ((int)$item['parent'] == (int)$id_parent) {
                $menu_tmp[] = $item;
                unset($menus[$key]);
            }
        }
        if (!empty($menu_tmp)) {
            if ($i == 0) {
                $attr = ' id="category_products_menu_static_mobile" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" class="main-menu category_menu_product_home_read_moblie  middle"';
                $html .= "<ul" . $attr . ">";
            } else {
                $attr = ' class="sub-menu"';
                $html .= "<ul" . $attr . ">";
            }
            $icon_wap = "";
            foreach ($menu_tmp as $item) {
                $i++;
                if ($menu_current == $item['id']) {
                    $html .= "<li class='active category_menu_list'><a href='" . route(
                            'category.list',
                            $item['slug']
                        ) . "'>" . $icon_wap . "<span class='text'>" . $item['name'] . "</span></a>";
                } else {
                    $html .= "<li class='category_menu_list'><a href='" . route(
                            'category.list',
                            $item['slug']
                        ) . "'>" . $icon_wap . "<span class='text'>" . $item['name'] . "</span></a>";
                }
                self::showMenuMobileHtml($menus, $menu_current, $item['id'], $html, $i);
                $html .= "</li>";
            }
            $html .= "</ul>";
        }
        return $html;
    }

    public static function showOptionCategory($menus, $menu_current, $id_parent = 0, $text = "&nbsp;")
    {
        $menu_tmp = array();
        foreach ($menus as $key => $item) {
            if ((int)$item['parent'] == (int)$id_parent) {
                $menu_tmp[] = $item;
                unset($menus[$key]);
            }
        }
        if (!empty($menu_tmp)) {
            foreach ($menu_tmp as $item) {
                if ($menu_current == $item['id']):
                    echo '<option selected="selected" value="' . $item['id'] . '">';
                else:
                    echo '<option value="' . $item['id'] . '">';
                endif;
                echo $text . $item['name'];
                echo '</option>';
                self::showOptionCategory($menus, $menu_current, $item['id'], $text . "&nbsp;&nbsp;");
            }
        }
    }

    public static function showMultipleCategory($menus, $array_current, $id_parent = 0, &$html = '', &$i = 0)
    {
        $menu_tmp = array();
        foreach ($menus as $key => $item) {
            if ((int)$item['parent'] == (int)$id_parent) {
                $menu_tmp[] = $item;
                unset($menus[$key]);
            }
        }
        if (!empty($menu_tmp)) {
            if ($i == 0):
                $attr = ' id="muti_menu_post" class="muti_menu_right_category"';
                $html .= "<ul" . $attr . ">";
            else:
                $attr = ' class="sub-menu"';
                $html .= "<ul" . $attr . ">";
            endif;
            foreach ($menu_tmp as $item) {
                $i++;
                if (in_array($item['id'], $array_current)):
                    $html .= "<li class='active category_menu_list'>
                                <label for='checkbox_cmc_" . $item['id'] . "'>
                                <input type='checkbox' class='category_item_input' name='category_item[]' value='" . $item['id'] . "' id='checkbox_cmc_" . $item['id'] . "' checked>" . $item['name'] . "</label>";
                else:
                    $html .= "<li class='category_menu_list'>
                                <label for='checkbox_cmc_" . $item['id'] . "'>
                                <input type='checkbox' class='category_item_input' name='category_item[]' value='" . $item['id'] . "' id='checkbox_cmc_" . $item['id'] . "'>" . $item['name'] . "</label>";
                endif;
                self::showMultipleCategory($menus, $array_current, $item['id'], $html, $i);
                $html .= "</li>";
            }
            $html .= "</ul>";
        }
        return $html;
    }

    public static function formatMoney12($number, $fractional = false)
    {
        if ($fractional) {
            $number = sprintf('%.2f', $number);
        }
        while (true) {
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
            if ($replaced != $number) {
                $number = $replaced;
            } else {
                break;
            }
        }
        return $number;
    }

    public static function format_price($price)
    {
        return number_format($price, 0, ',', '.');
    }

    function time_request($time)
    {
        $date_current = date('Y-m-d H:i:s');
        $s = strtotime($date_current) - strtotime($time);
        if ($s <= 60) { // if < 60 seconds
            return 'About 1 minute ago';
        } else {
            $t = intval($s / 60);
            if ($t >= 60) {
                $t = intval($t / 60);
                if ($t >= 24) {
                    $t = intval($t / 24);
                    if ($t >= 30) {
                        $t = intval($t / 30);
                        if ($t >= 12) {
                            $t = intval($t / 12);
                            return "About " . $t . ' year ago';
                        } else {
                            return "About " . $t . ' month ago';
                        }
                    } else {
                        return "About " . $t . ' day ago';
                    }
                } else {
                    return "About " . $t . ' hours ago';
                }
            } else {
                return "About " . $t . ' minute ago';
            }
        }
    }

    public static function getParentCategory($menus, $id_parent, &$html = '')
    {
        $menu_tmp = array();
        $attr = "";
        foreach ($menus as $key => $item) {
            if ((int)$item['id'] == (int)$id_parent) {
                $menu_tmp[] = $item;
                unset($menus[$key]);
            }
            if ($item['parent'] == 0):
                $attr = ' class="cate_post_clear"';
            else:
                $attr = "";
            endif;
        }

        if (!empty($menu_tmp)) {
            $html .= "<span" . $attr . ">";
            asort($menu_tmp);
            foreach ($menu_tmp as $item) {
                if ($item['parent'] >= 0):
                    $html .= " &#62; <a target='_blank' class='menu_parent_back' href='" . route(
                            'category.list',
                            $item['slug']
                        ) . "'>" . $item['name'] . "</a> ";
                endif;
                self::getParentCategory($menus, $item['parent'], $html);
            }
            $html .= "</span>";
        }
        return $html;
    }

    public function get_template_page($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('template', BaseConstants::ACTIVE)
            ->where('status', BaseConstants::ACTIVE)
            ->first();
        $content = "";
        if (isset($page) && $page) {
            $content = htmlspecialchars_decode($page->content);
        }
        return $content;
    }

    public static function objectEmpty($o)
    {
        if (empty($o)) {
            return true;
        } else {
            if (is_numeric($o)) {
                return false;
            } else {
                if (is_string($o)) {
                    return !strlen(trim($o));
                } else {
                    if (is_object($o)) {
                        return self::objectEmpty((array)$o);
                    }
                }
            }
        }
        // It's an array!
        foreach ($o as $element) {
            if (self::objectEmpty($element)) {
                continue;
            } // so far so good.
            else {
                return false;
            }
        }
        // all good.
        return true;
    }

    public function excerpts($str, $length = 200, $trailing = '..')
    {
        $str = str_replace("  ", " ", $str);
        if (!empty($str)):
            $str = strip_tags($str);
        endif;
        $str = strip_tags($str);
        $length -= mb_strlen($trailing);
        if (mb_strlen($str) > $length):
            return mb_strimwidth($str, 0, $length, $trailing, 'utf-8');
        else:
            $str = str_replace("  ", " ", $str);
            $res = $str;
        endif;
        return $res;
    }
}
