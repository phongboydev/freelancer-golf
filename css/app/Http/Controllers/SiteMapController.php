<?php

namespace App\Http\Controllers;

use App\Constants\BaseConstants;
use App\Models\Category;
use App\Models\Page;
use App\Models\ProductCategory;

class SiteMapController extends Controller
{
    public function Home()
    {
        return response()->view(
            'sitemap.index',
            [
                'url_map' => route('index'),
                'datetime_format_php' => date('Y-m-d')
            ]
        )->header('Content-Type', 'text/xml');
    }

    public function mapStatic()
    {
        return response()->view(
            'sitemap.static',
            [
                'url_map' => route('index'),
                'datetime_format_php' => date('Y-m-d')
            ]
        )->header('Content-Type', 'text/xml');
    }

    public function Page()
    {
        $data_customers_news = Page::where('status', BaseConstants::ACTIVE)
            ->where('content', '!=', '')
            ->select('*')
            ->orderBy('updated', 'DESC')
            ->get();
        return response()->view(
            'sitemap.page',
            [
                'url_map' => route('index'),
                'data_customers' => $data_customers_news
            ]
        )->header('Content-Type', 'text/xml');
    }

    public function categories()
    {
        $categories = Category::where('status', BaseConstants::ACTIVE)
            ->select('*')
            ->get();
        return response()->view(
            'sitemap.category',
            [
                'url_map' => route('index'),
                'categories' => $categories
            ]
        )->header('Content-Type', 'text/xml');
    }

    public function Post()
    {
        $datas = Category::join('join_category_post', 'category.categoryID', 'join_category_post.id_category')
            ->join('post', 'join_category_post.id_post', 'post.id')
            ->where('post.status', BaseConstants::ACTIVE)
            ->orderByRaw('post.order_short DESC, post.updated DESC')
            ->select(
                'post.*',
                'category.categoryName',
                'category.categorySlug',
                'category.categoryDescription',
                'category.categoryID',
                'category.categoryParent'
            )
            ->get();
        return response()->view(
            'sitemap.post',
            [
                'url_map' => route('index'),
                'datas' => $datas
            ]
        )->header('Content-Type', 'text/xml');
    }

    public function productCategories()
    {
        $categories = ProductCategory::where('status', BaseConstants::ACTIVE)
            ->get();
        return response()->view(
            'sitemap.product-category',
            [
                'url_map' => route('index'),
                'categories' => $categories
            ]
        )->header('Content-Type', 'text/xml');
    }

    public function products()
    {
        $datas = ProductCategory::join('join_category_product', 'product_categories.id', 'join_category_product.category_id')
            ->join('products', 'join_category_product.product_id', 'products.id')
            ->where('products.status', BaseConstants::ACTIVE)
            ->orderByRaw('products.sort DESC, products.updated_at DESC')
            ->select(
                'products.*',
                'product_categories.name as categorySlug',
                'product_categories.description as categoryDescription',
                'product_categories.id as categoryID',
                'product_categories.parent as categoryParent'
            )
            ->get();
        return response()->view(
            'sitemap.product',
            [
                'url_map' => route('index'),
                'datas' => $datas
            ]
        )->header('Content-Type', 'text/xml');
    }
}
