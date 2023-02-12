<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TelegramBotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::group(['prefix'=>'sitemap-index','as'=>'sitemap.'], function(){
    Route::get('/', [SiteMapController::class, 'Home'])->name('index');
    Route::get('/static.xml', [SiteMapController::class, 'mapStatic'])->name('static');
    Route::get('/page.xml', [SiteMapController::class, 'Page'])->name('page');
    Route::get('/post.xml', [SiteMapController::class, 'Post'])->name('post');
    Route::get('/categories.xml', [SiteMapController::class, 'categories'])->name('categories');
    Route::get('/products.xml', [SiteMapController::class, 'products'])->name('products');
    Route::get('/product-categories.xml', [SiteMapController::class, 'ProductCategories'])->name('productCategories');
});

Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provide}/callback', [SocialAuthController::class, 'handleProviderCallback']);
Route::group(['prefix' => 'user'], function () {
    Route::get('register', [UserController::class, 'registerForm'])->name('user.registerForm');
    Route::post('register', [UserController::class, 'register'])->name('user.register');
    Route::get('login', [UserController::class, 'loginForm'])->name('user.loginForm');
    Route::post('login', [UserController::class, 'login'])->name('user.login');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('profile', [UserController::class, 'profile'])->name('user.profile');
        Route::post('profile', [UserController::class, 'updateProfile'])->name('user.updateProfile');

        Route::get('wish-list', [UserController::class, 'wishList'])->name('user.wishList');
        Route::get('orders', [UserController::class, 'orders'])->name('user.orders');
        Route::get('order/{id}', [UserController::class, 'orderDetail'])->name('user.orderDetail');
        Route::get('reviews', [UserController::class, 'reviews'])->name('user.reviews');

        Route::get('change-password', [UserController::class, 'changePasswordForm'])->name('user.changePasswordForm');
        Route::post('change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
        Route::post('store-review', [UserController::class, 'storeReview'])->name('user.storeReview');
        Route::post('logout', [UserController::class, 'logout'])->name('user.logout');
    });
});

Route::get('404', [HomeController::class, 'notFound'])->name('404');

Route::get('lien-he', [HomeController::class, 'pageContact'])->name('pageContact');
Route::post('lien-he', [HomeController::class, 'storeContact'])->name('storeContact');

Route::post('dang-ky-tu-van', [HomeController::class, 'signUpForAConsultation'])->name('signUpForAConsultation');

Route::get('tin-tuc', [MainController::class, 'pageNews'])->name('pageNews');
Route::get('search', [MainController::class, 'productSearch'])->name('product.search');
Route::get('autocomplete', [MainController::class, 'productSearchAutocomplete'])->name('product.searchAutocomplete');

Route::get('gio-hang', [MainController::class, 'cart'])->name('cart');
Route::post('add-to-cart', [MainController::class, 'addToCart'])->name('addToCart');
Route::post('remove-cart-item', [MainController::class, 'removeCartItem'])->name('removeCartItem');
Route::post('update-cart', [MainController::class, 'updateCart'])->name('updateCart');
Route::post('ajax/update-cart', [MainController::class, 'ajaxUpdateCart'])->name('ajaxUpdateCart');
Route::get('gio-hang', [MainController::class, 'cart'])->name('cart');
Route::post('thanh-toan', [PaymentController::class, 'payment'])->name('payment');
Route::get('dat-hang-thanh-cong', [HomeController::class, 'completeOrder'])->name('completeOrder');

Route::get('ajax/reload-cart', [MainController::class, 'reloadCart'])->name('reloadCart');

Route::post('add-to-wishlist', [MainController::class, 'addToWishList'])->name('addToWishList');
Route::post('check-discount-code', [MainController::class, 'checkDiscountCode'])->name('checkDiscountCode');

Route::get('{slug}.html', [MainController::class, 'category'])
    ->name('category.list');
Route::get('{slug1}/{slug2}.html', [MainController::class, 'singleDetail'])
    ->name('single.detail');

Route::get('cua-hang', [MainController::class, 'shop'])->name('shop');
Route::get('khuyen-mai', [MainController::class, 'promotionPage'])->name('promotionPage');
Route::get('best-seller', [MainController::class, 'bestSeller'])->name('bestSeller');
Route::post('review-product', [MainController::class, 'reviewProduct'])->name('reviewProduct');
Route::post('ajax/check-register', [MainController::class, 'checkRegister'])->name('checkRegister');
Route::get('ajax/more-reviews', [MainController::class, 'moreReviews'])->name('moreReviews');

Route::get('telegram/update-activity', [TelegramBotController::class, 'updatedActivity'])->name('updatedActivity');
