<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Artisan;
use App\Constants\BaseConstants;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BusinessSettingsController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\ProductVariableController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryProductController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\ReviewProductController;
use App\Http\Controllers\Admin\EmailContactController;
use App\Http\Controllers\Admin\UserController;

// Route xử lý cho admin
Route::namespace('Admin')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm']);
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');

   	Route::group(['middleware' => ['admin']], function () {
        Route::get('/clear-cache', function() {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            return redirect()->route('admin.dashboard')->with(['success' => 'Đã xoá cache trên trang.']);
        })->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION)->name('admin.clearCache');

        Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');

        Route::get('/update-category-support-search', [HomeController::class, 'updateCategorySupportSearch'])
            ->name('admin.updateCategorySupportSearch')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::get('/update-product-categories', [ProductController::class, 'updateProductCategories'])
            ->name('admin.updateProductCategories')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);

        //update information
        Route::get('/account-information', [ManagerController::class, 'accountInformation'])
            ->name('admin.accountInformation');
        Route::post('/store-account-information', [ManagerController::class, 'storeAccountInformation'])
            ->name('admin.storeAccountInformation');

        //Business Settings
        Route::get('general-setting', [BusinessSettingsController::class, 'generalSetting'])
            ->name('admin.generalSetting')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::post('general-setting', [BusinessSettingsController::class, 'storeGeneralSetting'])
            ->name('admin.storeGeneralSetting')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);

        Route::post('store-setting-env', [BusinessSettingsController::class, 'storeSettingEnv'])
            ->name('admin.storeSettingEnv')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);

        Route::get('shipping-setting', [BusinessSettingsController::class, 'shippingSetting'])
            ->name('admin.shippingSetting')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::post('shipping-setting', [BusinessSettingsController::class, 'storeShippingSetting'])
            ->name('admin.storeShippingSetting')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);

        Route::get('smtp-setting', [BusinessSettingsController::class, 'smtpSetting'])
            ->name('admin.smtpSetting')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::post('smtp-setting', [BusinessSettingsController::class, 'storeSmtpSetting'])
            ->name('admin.storeSmtpSetting')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);

        Route::get('social-setting', [BusinessSettingsController::class, 'socialSetting'])
            ->name('admin.socialSetting')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);


        //change password
        Route::get('change-password', [AdminController::class, 'changePassword'])
            ->name('admin.changePassword');
        Route::post('change-password', [AdminController::class, 'storeChangePassword'])
            ->name('admin.storeChangePassword');

        //ajax delete
        Route::post('delete-id', [AjaxController::class, 'ajax_delete'])
            ->name('admin.ajax_delete');
        Route::get('ajax/generate-select-variable-child', [ProductVariableController::class, 'generateSelectVariableChild'])
            ->name('admin.generateSelectVariableChild');

        //ajax process
        Route::post('ajax/product-quick-update', [AjaxController::class, 'processThemeFast'])
            ->middleware('role:product-management,' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.processThemeFast');
        Route::post('ajax/product-update-option', [AjaxController::class, 'productUpdateOption'])
            ->middleware('role:product-management,' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.productUpdateOption');
        Route::post('ajax/process_store_status', [AjaxController::class, 'updateStoreStatus'])
            ->middleware('role:product-management,' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.process_store_status');

        //Orders
        Route::get('orders', [OrderController::class, 'listOrders'])
            ->middleware('role:order-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.listOrders');
        Route::get('search-order', [OrderController::class, 'searchOrder'])
            ->middleware('role:order-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.searchOrder');
        Route::get('excel-order', [OrderController::class, 'excelOrder'])
            ->middleware('role:order-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.excelOrder');
        Route::get('order/{id}', [OrderController::class, 'orderDetail'])
            ->middleware('role:order-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.orderDetail');
        Route::post('order/store', [OrderController::class, 'storeOrderDetail'])
            ->middleware('role:order-management,' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storeOrderDetail');
        Route::get('order/print/{id}', [OrderController::class, 'printOrder'])
            ->middleware('role:order-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.printOrder');
        Route::get('print-multiple-orders', [OrderController::class, 'printMultipleOrders'])
            ->middleware('role:order-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.printMultipleOrders');

   		//Setting
        Route::get('theme-option', [AdminController::class, 'getThemeOption'])
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION)
            ->name('admin.themeOption');
        Route::post('theme-option', [AdminController::class, 'storeThemeOption'])
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION)
            ->name('admin.storeThemeOption');
        Route::get('menu', [AdminController::class, 'getMenu'])
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION)
            ->name('admin.menu');

        //Page
        Route::get('pages', [PageController::class, 'listPage'])
            ->middleware('role:page-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.pages');
        Route::get('page/create', [PageController::class, 'createPage'])
            ->middleware('role:page-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.createPage');
        Route::get('page/{id}', [PageController::class, 'pageDetail'])
            ->middleware('role:page-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.pageDetail');
        Route::post('page/store', [PageController::class, 'storePageDetail'])
            ->middleware('role:page-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storePageDetail');

        //Post
        Route::get('list-posts', [PostController::class, 'listPost'])
            ->middleware('role:post-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.posts');
        Route::get('search-post', [PostController::class, 'searchPost'])
            ->middleware('role:post-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.searchPost');
        Route::get('post/create', [PostController::class, 'createPost'])
            ->middleware('role:post-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.createPost');
        Route::get('post/{id}', [PostController::class, 'postDetail'])
            ->middleware('role:post-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.postDetail');
        Route::post('post/store', [PostController::class, 'storePostDetail'])
            ->middleware('role:post-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storePostDetail');

        //Brand
        Route::get('/list-brand', 'BrandController@index')->name('admin.brand');
        Route::get('/brand/create', 'BrandController@create')->name('admin.brand.create');
        Route::get('/brand/{id}', 'BrandController@edit')->name('admin.brand.edit');
        Route::post('/brand/post', 'BrandController@post')->name('admin.brand.post');

        //Category Post
        Route::get('post-categories', [CategoryController::class, 'listPostCategories'])
            ->middleware('role:post-category-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.listPostCategories');
        Route::get('post-category/create', [CategoryController::class, 'createPostCategory'])
            ->middleware('role:post-category-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.createPostCategory');
        Route::get('post-category/{id}', [CategoryController::class, 'postCategoryDetail'])
            ->middleware('role:post-category-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.postCategoryDetail');
        Route::post('post-category/store', [CategoryController::class, 'storePostCategoryDetail'])
            ->middleware('role:post-category-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storePostCategoryDetail');
            
        //Product
        Route::get('products', [ProductController::class, 'listProduct'])
            ->middleware('role:product-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.listProduct');
        Route::get('product/create', [ProductController::class, 'createProduct'])
            ->middleware('role:product-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.createProduct');
        Route::get('product/{id}', [ProductController::class, 'productDetail'])
            ->middleware('role:product-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.productDetail');
        Route::post('product/store', [ProductController::class, 'storeProductDetail'])
            ->middleware('role:product-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storeProductDetail');
        // view product draft
        Route::get('/{slug1}/{slug2}.html', [ProductController::class, 'draftDetails'])
            ->middleware('role:product-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.draftDetails');

        //export - import product
        Route::get('export-products', [ProductController::class, 'exportProduct'])
            ->middleware('role:product-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.exportProduct');
        Route::get('export-products/create', [ProductController::class, 'exportProductAction'])
            ->middleware('role:product-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.exportProductAction');
        Route::get('import-products', [ProductController::class, 'importProduct'])
            ->middleware('role:product-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.importProduct');
        Route::post('import-products/store', [ProductController::class, 'importProductAction'])
            ->middleware('role:import-product-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.importProductAction');

        // Product Category
        Route::get('product-categories', [CategoryProductController::class, 'listProductCategories'])
            ->middleware('role:product-category-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.listProductCategories');
        Route::get('product-category/create', [CategoryProductController::class, 'createProductCategory'])
            ->middleware('role:product-category-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.createProductCategory');
        Route::get('product-category/{id}', [CategoryProductController::class, 'productCategoryDetail'])
            ->middleware('role:product-category-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.productCategoryDetail');
        Route::post('product-category/store', [CategoryProductController::class, 'storeProductCategory'])
            ->middleware('role:product-category-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storeProductCategory');

        //Variable Product
        Route::get('product-variables', [ProductVariableController::class, 'listProductVariables'])
            ->middleware('role:product-variable-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.listProductVariables');
        Route::get('search-product-variable', [ProductVariableController::class, 'searchProductVariable'])
            ->middleware('role:product-variable-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.searchProductVariable');
        Route::get('product-variable/create', [ProductVariableController::class, 'createProductVariable'])
            ->middleware('role:product-variable-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.createProductVariable');
        Route::get('product-variable/{id}', [ProductVariableController::class, 'productVariableDetail'])
            ->middleware('role:product-variable-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.productVariableDetail');
        Route::post('product-variable/store', [ProductVariableController::class, 'storeProductVariableDetail'])
            ->middleware('role:product-variable-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storeProductVariableDetail');

        // review product
        Route::get('all-review-products', [ReviewProductController::class, 'allReviewProducts'])
            ->middleware('role:review-product-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.allReviewProducts');
        Route::get('review-products/{id}', [ReviewProductController::class, 'index'])
            ->middleware('role:review-product-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.listReviewProducts');
        Route::get('review-product/{id}/create', [ReviewProductController::class, 'create'])
            ->middleware('role:review-product-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.reviewProduct.create');
        Route::get('review-product/{id}', [ReviewProductController::class, 'detail'])
            ->middleware('role:review-product-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.reviewProductDetail');
        Route::post('review-product/store', [ReviewProductController::class, 'store'])
            ->middleware('role:review-product-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storeReviewProduct');

        //Slider Home
        Route::get('sliders', [SliderController::class, 'listSliders'])
            ->middleware('role:slider-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.slider');
        Route::get('slider/create', [SliderController::class, 'createSlider'])
            ->middleware('role:slider-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.createSlider');
        Route::get('slider/{id}', [SliderController::class, 'sliderDetail'])
            ->middleware('role:slider-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.sliderDetail');
        Route::post('slider/store', [SliderController::class, 'storeSliderDetail'])
            ->middleware('role:slider-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storeSliderDetail');

        // Discount Code
        Route::get('discount-codes', [DiscountCodeController::class, 'listDiscountCodes'])
            ->middleware('role:discount-code-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.discountCode');
        Route::get('discount-code/create', [DiscountCodeController::class, 'createDiscountCode'])
            ->middleware('role:discount-code-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.createDiscountCode');
        Route::get('discount-code/{id}', [DiscountCodeController::class, 'discountCodeDetail'])
            ->middleware('role:discount-code-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.discountCodeDetail');
        Route::post('discount-code/store', [DiscountCodeController::class, 'storeDiscountCodeDetail'])
            ->middleware('role:discount-code-management,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION)
            ->name('admin.storeDiscountCodeDetail');

        // Email Contact
        Route::get('email-contact/welcome', [EmailContactController::class, 'welcome'])
            ->middleware('role:email-contact-management,' . BaseConstants::READ_PERMISSION)
            ->name('admin.emailContact.welcome');
        Route::get('email-contact/om-your-inbox', [EmailContactController::class, 'omYourInbox'])
            ->middleware('role:email-contact-management,' . BaseConstants::CREATE_PERMISSION)
            ->name('admin.emailContact.omYourInbox');

        // Users
        Route::get('users', [UserController::class, 'index'])
            ->name('admin.user.index')
            ->middleware('role:user-management,' . BaseConstants::READ_PERMISSION);
        Route::get('user/{id}', [UserController::class, 'detail'])
            ->name('admin.user.detail')
            ->middleware('role:user-management,' . BaseConstants::READ_PERMISSION);
        Route::post('user/store', [UserController::class, 'store'])
            ->name('admin.user.store')
            ->middleware('role:user-management,' . BaseConstants::UPDATE_PERMISSION);

        //Create Manager
        Route::get('managers', [ManagerController::class, 'listManagers'])
            ->name('admin.listManagers')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::get('manager/create', [ManagerController::class, 'createManager'])
            ->name('admin.createManager')
            ->middleware('role:super-admin,' . BaseConstants::CREATE_PERMISSION);
        Route::get('manager/{id}', [ManagerController::class, 'managerDetail'])
            ->name('admin.managerDetail')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::post('manager/post', [ManagerController::class, 'postManagerDetail'])
            ->name('admin.postManagerDetail')
            ->middleware('role:super-admin,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION);

        // Module
        Route::get('modules', [RoleController::class, 'listModules'])
            ->name('admin.listModules')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::get('module/create', [RoleController::class, 'createModule'])
            ->name('admin.createModule')
            ->middleware('role:super-admin,' . BaseConstants::CREATE_PERMISSION);
        Route::get('module/{id}', [RoleController::class, 'moduleDetail'])
            ->name('admin.moduleDetail')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::post('module/store', [RoleController::class, 'storeModuleDetail'])
            ->name('admin.storeModuleDetail')
            ->middleware('role:super-admin,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION);

        // Role
        Route::get('roles', [RoleController::class, 'listRoles'])
            ->name('admin.listRoles')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::get('role/create', [RoleController::class, 'createRole'])
            ->name('admin.createRole')
            ->middleware('role:super-admin,' . BaseConstants::CREATE_PERMISSION);
        Route::get('role/{id}', [RoleController::class, 'roleDetail'])
            ->name('admin.roleDetail')
            ->middleware('role:super-admin,' . BaseConstants::READ_PERMISSION);
        Route::post('role/store', [RoleController::class, 'storeRoleDetail'])
            ->name('admin.storeRoleDetail')
            ->middleware('role:super-admin,' . BaseConstants::CREATE_PERMISSION . ', ' . BaseConstants::UPDATE_PERMISSION);
   	});
});
