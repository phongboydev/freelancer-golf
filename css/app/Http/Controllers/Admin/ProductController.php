<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstants;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Jobs\ProcessImportData;
use App\Models\ProductImportHistory;
use App\Models\ProductStock;
use App\Models\Variable_Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Join_Category_Product;
use App\Libraries\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function listProduct(Request $request)
    {
        $query = Product::select('*');
        if (isset($request->search_title) && $request->search_title != '') {
            $query->where('title', 'LIKE', '%' . $request->search_title . '%');
        }

        if (isset($request->category_theme) && $request->category_theme != '') {
            $category_theme = $request->category_theme;
            $query->whereHas('categories', function ($q) use ($category_theme) {
                $q->where('id', $category_theme);
            });
        }

        $data_product = $query->orderBy('products.created_at', 'DESC')
            ->paginate(20);

        $list_cate = ProductCategory::orderBy('name', 'ASC')
            ->get(['id', 'name']);
        return view('admin.product.index', compact('data_product', 'list_cate'));
    }

    public function createProduct()
    {
        return view('admin.product.single');
    }

    public function productDetail($id)
    {
        $detail = Product::where('id', $id)->first();
        if ($detail) {
            return view('admin.product.single', compact('detail'));
        } else {
            return view('404');
        }
    }

    public function storeProductDetail(Request $request)
    {
        //id product
        $id = $request->id;
        $url_img_cut = 'images/product';

        $title = $request->title;
        $title_en = $request->title_en;
        $slug = $request->slug;
        if (empty($slug) || $slug == '') {
            $slug = Str::slug($title);
        }

        //xử lý description
        $description = htmlspecialchars($request->description);
        $description_en = htmlspecialchars($request->description_en);

        //xử lý content
        $content = htmlspecialchars($request->input('content'));
        $content_en = htmlspecialchars($request->content_en);

        $year = date('Y');
        $month = date('m');
        $date = date('d');
        $path_img = $year . '/' . $month . '/' . $date . '/';

        //xử lý thumbnail
        $thumbnail_alt = $request->thumb_alt;
        if ($request->thumbnail_file) {
            $file = $request->file('thumbnail_file');
            $name = time() . '-' . $file->getClientOriginalName();
            $name = str_replace(' ', '', $name);
            $name_thumb_img1 = $path_img . $name;
            $url_folder_upload = "/images/product/" . $path_img;
            $file->move(base_path() . $url_folder_upload, $name);
        } else {
            if (isset($request->thumbnail_file_link) && $request->thumbnail_file_link != "") {
                $name_thumb_img1 = $request->thumbnail_file_link;
            } else {
                $name_thumb_img1 = "";
            }
        }

        if ($name_thumb_img1 != '') {
            Helpers::getThumbnail($url_img_cut, $name_thumb_img1, 320, 480, "resize");
        }

        $sku = $request->sku;

        //time start - end event
        $start_event_get = $request->start_event;
        $start_event = date("Y-m-d H:i:s", strtotime($start_event_get));

        $end_event_get = $request->end_event;
        $end_event = date("Y-m-d H:i:s", strtotime($end_event_get));

        $store_status = 0;
        if (isset($request->store_status)) {
            $store_status = (int)$request->store_status;
        }

        //xử lý price
        $price_origin = addslashes($request->price_origin);
        $price_promotion = addslashes($request->price_promotion);
        if ($price_promotion == 0) {
            $price_promotion = $price_origin;
        }

        if ($price_origin == 0) {
            $price_origin = $price_promotion;
        }

        $category_primary_id= (int)$request->category_primary_id;
        $seo_title = $request->seo_title;
        $seo_keyword = $request->seo_keyword;
        $seo_description = $request->seo_description;

        //xử lý gallery
        $count_item_gallery = (int)$request->gallery_item_count;
        $array_group_gallery = array();
        for ($m = 0; $m < $count_item_gallery; $m++) {
            $k = $m + 1;
            /********File upload******************************************************/
            if ($request->hasFile('upload_gallery_file0')) {
                $file = $request->file('upload_gallery_file0');
                if (isset($file[$m]) && $file[$m]->getClientOriginalName() != '') {
                    $link_use_thumbnail_gallery = time() . '_' . $file[$m]->getClientOriginalName();
                    $link_use_thumbnail_gallery = str_replace(' ', '', $link_use_thumbnail_gallery);
                    $file[$m]->move(base_path() . '/images/product/' . $path_img, $link_use_thumbnail_gallery);
                    $link_use_thumbnail_gallery = $path_img . $link_use_thumbnail_gallery;
                } else {
                    $link_use_thumbnail_gallery = "";
                    if ($request->input('upload_gallery' . $k) != "") {
                        $link_use_thumbnail_gallery = $request->input('upload_gallery' . $k);
                    }
                }
            } else {
                if ($request->input('upload_gallery' . $k) != "") {
                    $link_use_thumbnail_gallery = $request->input('upload_gallery' . $k);
                } else {
                    $link_use_thumbnail_gallery = "";
                }
            }
            /****************End*******************/
            if (strlen($link_use_thumbnail_gallery) > 0) {
                array_push($array_group_gallery, $link_use_thumbnail_gallery);
            }
        }
        $store_gallery = serialize($array_group_gallery);
        //end xử lý gallery

        $sort = ($request->sort) ? $request->sort : 0;
        $status = (int)$request->status;

        // xử lý biến thể
        $group_variable_product = [];
        $arr_variable_option = [];
        if (isset($request->parent_variable) && isset($request->count_variable_parent) && $request->count_variable_parent > 0) {
            $group_parent_variable = $request->parent_variable;
            $group_parent_variable_id = [];
            $group_parent_variable_slug = [];

            foreach ($group_parent_variable as $parent_variable) {
                $data_parent_variable = Variable_Product::where('name', $parent_variable)->first(['id', 'slug']);
                if ($data_parent_variable) {
                    array_push($group_parent_variable_id, $data_parent_variable->id);
                    array_push($group_parent_variable_slug, $data_parent_variable->slug);
                }
            }

            $arr_parent = [];
            $count_variable_parent = $request->count_variable_parent;
            for ($i = 1; $i <= $count_variable_parent; $i++) {
                if ($request->input('variable_child_'. $i) != '') {
                    if (count($request->input('variable_child_'. $i)) > 0) {
                        $arr_variable_child = $request->input('variable_child_'. $i);
                        $parent_item = [];
                        foreach ($arr_variable_child as $variable_child_item) {
                            $data_variable_child = Variable_Product::where('name', $variable_child_item)
                                ->where('parent', '<>', 0)
                                ->first(['id', 'slug', 'name', 'parent']);
                            if ($data_variable_child) {
                                if (in_array($data_variable_child->parent, $group_parent_variable_id)) {
                                    $get_slug_parent = Variable_Product::where('id', $data_variable_child->parent)
                                        ->first(['id', 'slug']);
                                    if ($get_slug_parent) {
                                        if (isset($parent_item[$get_slug_parent->slug])) {
                                            $parent_item[$get_slug_parent->slug][$variable_child_item] = [];
                                        } else {
                                            $parent_item[$get_slug_parent->slug] = [
                                                $variable_child_item => []
                                            ];
                                        }

                                    }
                                }
                            }
                        }
                        $arr_parent = array_merge($arr_parent, $parent_item);
                    }
                }
            }

            //xử lý gallery biến thể
            $count_gallery_parent = $request->count_gallery_parent;
            if ($count_gallery_parent > 0) {
                for ($i = 1; $i <= $count_gallery_parent; $i++) {
                    $count_item_gallery = $request->input('count_item_gallery_' . $i);
                    for ($j = 1; $j <= $count_item_gallery; $j++) {
                        $count_gallery_variable = $request->input('count_gallery_variable_' . $i . '_' . $j);
                        $name_gallery_variable = $request->input('name_gallery_variable_' . $i . '_' . $j);
                        $slug_parent_variable = $request->input('slug_parent_variable_' . $i . '_' . $j);
                        if ($count_gallery_variable > 0 && $slug_parent_variable != '') {
                            $image_gallery = [];
                            $count_input_k = 1;
                            for ($k = 0; $k < $count_gallery_variable; $k++) {
                                if ($request->hasFile('variable_image_upload_' . $i . '_' . $j)) {
                                    $file = $request->file('variable_image_upload_' . $i . '_' . $j);
                                    if (isset($file[$k]) && $file[$k]->getClientOriginalName() != '') {
                                        $variable_image = time() . '_' . $file[$k]->getClientOriginalName();
                                        $variable_image = str_replace(' ', '', $variable_image);
                                        $file[$k]->move(base_path() . '/images/product/' . $path_img, $variable_image);
                                        $variable_image = $path_img . $variable_image;
                                        array_push($image_gallery, $variable_image);
                                        Helpers::getThumbnail($url_img_cut, $variable_image, 70, 70, "resize");
                                    }
                                }

                                if ($request->input('variable_image_name_' . $i . '_' . $j . '_' . $count_input_k) != "") {
                                    $variable_image = $request->input('variable_image_name_' . $i . '_' . $j . '_' . $count_input_k);
                                    array_push($image_gallery, $variable_image);
                                    Helpers::getThumbnail($url_img_cut, $variable_image, 450, 450, "resize");
                                    Helpers::getThumbnail($url_img_cut, $variable_image, 70, 70, "resize");
                                }
                                $count_input_k++;
                            }
                            $arr_parent[$slug_parent_variable][$name_gallery_variable]['gallery'] = $image_gallery;
                        }
                    }
                }
            }

            //xử lý icon ảnh biến thể
            $url_icon_cut = 'images/product/icon';
            $count_icon_parent = $request->count_icon_parent;
            if ($count_icon_parent > 0) {
                for ($i = 1; $i <= $count_icon_parent; $i++) {
                    $count_item_icon = $request->input('count_item_icon_' . $i);
                    for ($j = 1; $j <= $count_item_icon; $j++) {
                        $name_icon_variable = $request->input('name_icon_variable_' . $i . '_' . $j);
                        $slug_parent_variable = $request->input('slug_icon_parent_variable_' . $i . '_' . $j);
                        if ($request->hasFile('upload_icon_variable_' . $i . '_' . $j)) {
                            $file = $request->file('upload_icon_variable_' . $i . '_' . $j);
                            if (isset($file) && $file->getClientOriginalName() != '') {
                                $image_icon = time() . '_' . $file->getClientOriginalName();
                                $image_icon = str_replace(' ', '', $image_icon);
                                $file->move(base_path() . '/images/product/icon/' . $path_img, $image_icon);
                                $image_icon = $path_img . $image_icon;
                            } else {
                                $image_icon = '';
                            }
                        } else {
                            $image_icon = "";
                            if ($request->input('icon_variable_name_' . $i . '_' . $j) != "") {
                                $image_icon = $request->input('icon_variable_name_' . $i . '_' . $j);
                            }
                        }
                        if ($image_icon != '') {
                            Helpers::getThumbnail($url_icon_cut, $image_icon, 70, 70, "resize");
                        }
                        $arr_parent[$slug_parent_variable][$name_icon_variable]['icon'] = $image_icon;
                    }
                }
            }

            // xử lý tên, giá biến thể
            $count_item_variable_generate = (isset($request->count_item_variable_generate) ? $request->count_item_variable_generate : 0);
            if ($count_item_variable_generate > 0) {
                $count_variable_option = 1;
                for ($i = 1; $i <= $count_item_variable_generate; $i++) {
                    $check_isset_variable = false;
                    $data_option_item = [];
                    foreach ($group_parent_variable_slug as $parent_variable_slug) {
                        if (isset($request->{'variable_name_' . $parent_variable_slug . '_' . $i})) {
                            $data_option_item[$parent_variable_slug] = $request->input('variable_name_' . $parent_variable_slug . '_' . $i);
                            $check_isset_variable = true;
                        }
                    }
                    if ($check_isset_variable) {

                        //xử lý ảnh đại diện biến thể
                        $variable_thumbnail = "";
                        $name_field = 'product_thumbnail_variable_' . $i;
                        if ($request->file('product_thumbnail_variable_' . $i)):
                            $file = $request->file($name_field);
                            $filename = "product-" . time() . '-' . $file->getClientOriginalName();
                            $filename = str_replace(' ', '', $filename);
                            $url_folder_upload = "/images/product/" . $path_img;
                            $file->move(base_path() . $url_folder_upload, $filename);
                            $variable_thumbnail = $path_img . $filename;
                        else:
                            if ($request->input('product_thumbnail_link_variable_' . $i) != ""):
                                $variable_thumbnail = $request->input('product_thumbnail_link_variable_' . $i);
                            else:
                                $variable_thumbnail = "";
                            endif;
                        endif;

                        if ($variable_thumbnail != '') {
                            Helpers::getThumbnail($url_img_cut, $variable_thumbnail, 300, 300, "resize");
                        }

                        $data_option_item['product_name'] = $request->input('product_name_variable_' . $i);
                        $data_option_item['product_slug'] = ($request->input('product_slug_variable_' . $i) != '') ? $request->input('product_slug_variable_' . $i) : Str::slug($data_option_item['product_name']);
                        $data_option_item['product_sku'] = $request->input('product_variable_sku_' . $i);
                        $data_option_item['product_stock'] = (int)$request->input('product_stock_variable_' . $i);
                        $data_option_item['sort'] = ($request->input('sort_variable_' . $i) != '') ? (int)$request->input('sort_variable_' . $i) : 0;
                        $data_option_item['price_origin'] = (double)$request->input('price_origin_variable_' . $i);
                        $data_option_item['price_promotion'] = (double)$request->input('price_promotion_variable_' . $i);
                        $data_option_item['thumbnail'] = $variable_thumbnail;

                        $variable_option_name = 'variable_option' . $count_variable_option;
                        $variable_option = [
                            $variable_option_name => $data_option_item
                        ];

                        array_push($arr_variable_option, $variable_option);
                        $count_variable_option++;
                    }
                }
            }
            $group_variable_product = [
                'parent' => $arr_parent,
                'variable_option' => $arr_variable_option
            ];
        }
        // end xử lý biến thể

        $category_items = [];
        $category_items = isset($request->category_item) ? $request->category_item : $category_items;
        if (count($category_items) > 0) {
            for ($i = 0; $i < count($category_items); $i++) {
                $category_items[$i] = (int)$category_items[$i];
            }
        }
        $get_slug_cate = ProductCategory::where('id', $category_items[0])
            ->select('slug')
            ->first();
        $category_support_search = "";
        if ($get_slug_cate) {
            $category_support_search = $get_slug_cate->slug;
        }

        $data = array(
            'title' => $title,
            'title_en' => $title_en,
            'sku' => $sku,
            'slug' => $slug,
            'price_origin' => $price_origin,
            'price_promotion' => $price_promotion,
            'start_event' => $start_event,
            'end_event' => $end_event,
            'description' => $description,
            'description_en' => $description_en,
            'content' => $content,
            'content_en' => $content_en,
            'thumbnail' => $name_thumb_img1,
            'thumbnail_alt' => $thumbnail_alt,
            'store_status' => $store_status,
            'seo_title' => $seo_title,
            'seo_keyword' => $seo_keyword,
            'seo_description' => $seo_description,
            'gallery_images' => $store_gallery,
            'sort' => $sort,
            'status' => $status,
            'category_support_search' => $category_support_search,
            'group_variable_product' => json_encode($group_variable_product),
            'category_primary_id' => $category_primary_id,
            'categories' => json_encode($category_items)
        );

        if ($id > 0) {
            //update
            Join_Category_Product::where('product_id', $id)->delete();
            for ($u = 0; $u < count($category_items); $u++) {
                if ($category_items[$u] > 0) {
                    Join_Category_Product::create(
                        [
                            'category_id' => $category_items[$u],
                            'product_id' => $id
                        ]
                    );
                }
            }

            // kiểm tra sản phẩm có biến thể hay không
            if (count($group_variable_product) == 0) {
                ProductStock::updateOrCreate(
                    [
                        'product_id' => $id,
                        'sku' => $sku
                    ],
                    [
                        'title' => $title,
                        'is_main' => 1,
                        'slug' => $slug,
                        'price_origin' => $price_origin,
                        'price_promotion' => $price_promotion,
                        'thumbnail' => $name_thumb_img1,
                        'sort' => $sort,
                        'stock' => 999,
                        'key_option' => '',
                        'variable_data' => ''
                    ]
                );
            } else {
                ProductStock::where('is_main', 1)
                    ->where('product_id', $id)
                    ->delete();
            }

            $arr_sku = [];
            // xử lý thêm biến thể sản phẩm vào bản phụ
            if (count($arr_variable_option) > 0) {
                $arrayParent = $group_variable_product['parent'];
                foreach ($arr_variable_option as $item) {
                    foreach ($item as $key => $value) {

                        if ($value['product_sku'] != '') {
                            array_push($arr_sku, $value['product_sku']);
                        }
                        if ($value['product_sku'] != '') {
                            ProductStock::updateOrCreate(
                                [
                                    'product_id' => $id,
                                    'sku' => $value['product_sku'],
                                    'is_main' => 0
                                ],
                                [
                                    'title' => $value['product_name'],
                                    'slug' => $value['product_slug'],
                                    'price_origin' => $value['price_origin'],
                                    'price_promotion' => ($value['price_promotion'] != '') ? $value['price_promotion'] : $value['price_origin'],
                                    'thumbnail' => $value['thumbnail'],
                                    'stock' => ($value['product_stock'] != '') ? $value['product_stock'] : 0,
                                    'sort' => ($value['sort'] != '') ? $value['sort'] : 0,
                                    'key_option' => $key,
                                    'variable_data' => json_encode($value)
                                ]
                            );
                        }
                    }
                }

                // xoá biến thể không có hoặc đã ngưng bán
                if (count($arr_sku) > 0) {
                    ProductStock::whereNotIn('sku', $arr_sku)
                        ->where('product_id', $id)
                        ->delete();
                }
            }

            Product::where("id", $id)->update($data);
            $msg = "Product has been updated";
            $url = route('admin.productDetail', array($id));
            Helpers::msg_move_page($msg, $url);
        } else {
            // insert
            $response = Product::create($data);
            $id_insert = $response->id;

            if ($id_insert > 0) {
                for ($u = 0; $u < count($category_items); $u++) {
                    if ($category_items[$u] > 0):
                        Join_Category_Product::create(
                            [
                                'category_id' => $category_items[$u],
                                'product_id' => $id_insert
                            ]
                        );
                    endif;
                }

                // kiểm tra sản phẩm có biến thể hay không
                if (count($group_variable_product) == 0) {
                    ProductStock::updateOrCreate(
                        [
                            'product_id' => $id_insert,
                            'sku' => $sku
                        ],
                        [
                            'title' => $title,
                            'is_main' => 1,
                            'slug' => $slug,
                            'price_origin' => $price_origin,
                            'price_promotion' => $price_promotion,
                            'thumbnail' => $name_thumb_img1,
                            'stock' => 999,
                            'key_option' => '',
                            'variable_data' => ''
                        ]
                    );
                }

                // xử lý thêm biến thể sản phẩm vào bản phụ
                if (count($arr_variable_option) > 0) {
                    foreach ($arr_variable_option as $item) {
                        foreach ($item as $key => $value) {
                            if ($value['product_sku'] != '' && $value['price_origin'] > 0) {

                                ProductStock::updateOrCreate(
                                    [
                                        'product_id' => $id_insert,
                                        'sku' => $value['product_sku'],
                                        'is_main' => 0
                                    ],
                                    [
                                        'title' => $value['product_name'],
                                        'slug' => $value['product_slug'],
                                        'price_origin' => $value['price_origin'],
                                        'price_promotion' => ($value['price_promotion'] != '') ? $value['price_promotion'] : $value['price_origin'],
                                        'thumbnail' => $value['thumbnail'],
                                        'stock' => ($value['product_stock'] != '') ? $value['product_stock'] : 0,
                                        'key_option' => $key,
                                        'variable_data' => json_encode($value)
                                    ]
                                );
                            }
                        }
                    }
                }

                $msg = "Product has been created.";
                $url = route('admin.productDetail', array($id_insert));
                Helpers::msg_move_page($msg, $url);
            }
        }
    }

    public function exportProduct()
    {
        $categories = ProductCategory::where('status', 0)->get();
        return view('admin.product.export', compact('categories'));
    }

    public function exportProductAction(Request $request)
    {
        $export_all = (isset($request->export_all)) ? $request->export_all : 0;
        $category = (isset($request->category)) ? $request->category : '';
        $searchString = (isset($request->search_string)) ? $request->search_string : '';

        $query = ProductStock::join('products', 'products.id', 'product_stocks.product_id')
            ->orderBy('products.id', 'DESC');
        if ($export_all) {
            $list_products = $query->get(
                [
                    'product_stocks.id',
                    'product_stocks.product_id',
                    'product_stocks.sku',
                    'product_stocks.title',
                    'product_stocks.slug',
                    'product_stocks.thumbnail',
                    'product_stocks.price_origin',
                    'product_stocks.price_promotion',
                    'product_stocks.stock',
                    'products.start_event',
                    'products.end_event',
                    'products.categories',
                    'products.status',
                    'products.category_primary_id',
                    'products.categories',
                    'product_stocks.created_at',
                    'product_stocks.updated_at',
                ]
            );
        } else {
            if ($searchString != '') {
                $query->where('product_stocks.title', 'LIKE', '%' . $searchString . '%');
            }

            if ($category != '') {
                $query->join('join_category_product', 'join_category_product.product_id', 'products.id')
                    ->join('category_theme', 'product_categories.id', 'join_category_product.category_id')
                    ->where('product_categories.id', $category)
                    ->groupBy('product_stocks.title');
            }
            $list_products = $query->get(
                [
                    'product_stocks.id',
                    'product_stocks.product_id',
                    'product_stocks.sku',
                    'product_stocks.title',
                    'product_stocks.slug',
                    'product_stocks.thumbnail',
                    'product_stocks.price_origin',
                    'product_stocks.price_promotion',
                    'product_stocks.stock',
                    'products.group_filter',
                    'products.buy_limit',
                    'products.start_event',
                    'products.end_event',
                    'products.category_primary_id',
                    'products.categories',
                    'product_stocks.created_at',
                    'product_stocks.updated_at',
                ]
            );
        }
        $array_products = [];
        foreach ($list_products as $product) {

        }

        return (new ProductExport($array_products))->download('products-' . date('d-m-Y') . '.xlsx');
    }

    public function importProduct()
    {
        $import_histories = ProductImportHistory::get();
        return view('admin.product.import', compact('import_histories'));
    }

    public function importProductAction(Request $request)
    {
        $file = $request->file('import_file');
        $name = time() . '-' . $file->getClientOriginalName();
        $url_folder_upload = "/excel/";
        $url_full_path = $url_folder_upload . $name;
        $file->move(base_path() . $url_folder_upload, $name);
        ProductImportHistory::create(
            [
                'filename' => $name,
                'file_location' => $url_full_path,
                'process' => 0
            ]
        );

        $importJob = new ProcessImportData($url_full_path);
        $importJob->delay(Carbon::now()->addSeconds(3));
        dispatch($importJob);

        return redirect()->back()->with(['success_msg' => 'Thêm thành công. Vui lòng chờ hệ thống xử lý.']);
    }

    public function updateProductCategories()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $array_categories = Join_Category_Product::where('product_id', $product->id)->pluck('category_id')->toArray();
            Product::where('id', $product->id)->update(
                [
                    'categories' => json_encode($array_categories)
                ]
            );
        }
        return redirect()->route('admin.dashboard')->with(['success' => 'Success update product categories.']);
    }

    public function draftDetails(Request $request, $slug1, $slug2)
    {
        $product = ProductCategory::join('join_category_product', 'product_categories.id', 'join_category_product.category_id')
            ->join('theme', 'join_category_product.product_id', 'products.id')
            ->join('product_stocks', 'product_stocks.product_id', 'products.id')
            ->where('product_stocks.slug', $slug2)
            ->where('product_categories.slug', $slug1)
            ->select(
                'product_stocks.id',
                'product_stocks.title',
                'product_stocks.sku',
                'product_stocks.slug',
                'product_stocks.price_origin',
                'product_stocks.price_promotion',
                'product_stocks.thumbnail',
                'product_stocks.key_option',
                'product_stocks.variable_data',
                'product_stocks.product_id as parent_id',
                'products.description',
                'products.content',
                'products.start_event',
                'products.end_event',
                'products.store_status',
                'products.status',
                'products.gallery_images',
                'products.seo_title',
                'products.seo_keyword',
                'products.seo_description',
                'products.group_variable_product',
                'product_categories.name as categoryName',
                'product_categories.slug as categorySlug',
                'product_categories.parent as categoryParent',
                'product_categories.id as categoryID',
                'product_categories.seo_title as seo_title_category'
            )
            ->first();
        if (isset($product) && $product) {
            $product_id = $product->id;
            $related_products = ProductCategory::join('join_category_product', 'product_categories.id', 'join_category_product.category_id')
                ->join('products', 'join_category_product.product_id', 'products.id')
                ->join('product_stocks', 'product_stocks.product_id', 'products.id')
                ->where('products.id', '!=', $product_id)
                ->where('products.status', BaseConstants::ACTIVE)
                ->where('products.admin_status', 1)
                ->where('product_categories.categorySlug', $slug1)
                ->groupBy('product_stocks.product_id')
                ->offset(0)
                ->limit(4)
                ->orderBy('products.order_short', 'DESC')
                ->get(
                    [
                        'product_stocks.id',
                        'product_stocks.title',
                        'product_stocks.slug',
                        'product_stocks.thumbnail',
                        'product_stocks.price_origin',
                        'product_stocks.price_promotion',
                        'products.group_variable_product',
                        'products.group_combo',
                        'products.start_event',
                        'products.end_event',
                        'products.total_rate',
                        'product_categories.slug as categorySlug'
                    ]
                );

            return view('products.single')
                ->with('id_category', $product->categoryID)
                ->with('data_customers', $product)
                ->with('related_products', $related_products)
                ->with('model', Product::where('slug', $slug2)->where('status', 0)->first());
        } else {
            return view('errors.404');
        }
    }
}
