<?php

// Trang chủ
Breadcrumbs::register(
    'index',
    function ($breadcrumbs) {
        $breadcrumbs->push('Trang chủ', route('index'));
    }
);
// Trang chủ > Tin tuc
Breadcrumbs::register(
    'news',
    function ($breadcrumbs) {
        $breadcrumbs->parent('index');
        $breadcrumbs->push('Tin tức', route('pageNews'));
    }
);

// Trang chủ > Cart
Breadcrumbs::register('cart', function($breadcrumbs)
{
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Giỏ hàng', route('cart'));
});

// Trang chủ > Cart > Checkout
Breadcrumbs::register('checkout', function($breadcrumbs)
{
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Giỏ hàng', route('cart'));
});

// Trang chủ > Shop
Breadcrumbs::register(
    'shop',
    function ($breadcrumbs) {
        $breadcrumbs->parent('index');
        $breadcrumbs->push('Cửa hàng', route('shop'));
    }
);

// Trang chủ > Page
Breadcrumbs::register(
    'page.index',
    function ($breadcrumbs, $pages) {
        $breadcrumbs->parent('index');
        $breadcrumbs->push($pages->title, route('category.list', $pages->slug));
    }
);


// Trang chủ > tin tuc> the loai
Breadcrumbs::register(
    'single.category',
    function ($breadcrumbs, $name, $slug) {
        $breadcrumbs->parent('index');
        $breadcrumbs->push($name, route('category.list', $slug));
    }
);

// Trang chủ > tin tuc> details
Breadcrumbs::register('single.detail', function($breadcrumbs, $categoryName, $categorySlug, $title, $slug)
{
    $breadcrumbs->parent('index');
    $breadcrumbs->push($categoryName, route('category.list', $categorySlug));
    $breadcrumbs->push($title, route('single.detail', [$categorySlug, $slug]));
});

//Search
Breadcrumbs::register(
    'product.search',
    function ($breadcrumbs) {
        $breadcrumbs->parent('index');
        $breadcrumbs->push('Tìm Kiếm', route('product.search'));
    }
);

//Customer Register
Breadcrumbs::register(
    'customer.register',
    function ($breadcrumbs) {
        $breadcrumbs->parent('index');
        $breadcrumbs->push('Đăng ký thành viên', route('registerCustomer'));
    }
);

// Trang chủ > page
Breadcrumbs::register(
    'default.page',
    function ($breadcrumbs, $data_customers) {
        $breadcrumbs->parent('index');
        //$breadcrumbs->push('Tin tức', route('tin-tuc'));
        $breadcrumbs->push($data_customers->title, route('default.page', $data_customers->slug));
    }
);
// Trang chủ > tim kiếm
Breadcrumbs::register(
    'search.doanhnghiep',
    function ($breadcrumbs, $taxcode) {
        $breadcrumbs->parent('index');
        $breadcrumbs->push('Search', '#');
        $breadcrumbs->push('Search Keyword: ' . $taxcode);
    }
);
?>
