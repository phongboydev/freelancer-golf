<?php

namespace App\Jobs;

use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\Join_Category_Product;
use App\Models\ProductStock;
use App\Models\Theme;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $isLatest;

    public function __construct($data, $isLatest = false)
    {
        $this->data = $data;
        $this->isLatest = $isLatest;
    }

    public function handle()
    {
        foreach ($this->data as $item) {
            if ($item['id_san_pham'] != '' && $item['id_san_pham_chinh'] != '') {
                $product_parent = Product::where('id', $item['id_san_pham_chinh'])->first();
                if ($product_parent) {
                    $group_variable_product = ($product_parent->group_variable_product != '') ? json_decode($product_parent->group_variable_product, true) : [];
                    $isset_option = false;
                    if (count($group_variable_product) > 0) {
                        $variable_options = $group_variable_product['variable_option'];
                        $data_update = [];
                        foreach ($variable_options as $key => $variable_option) {
                            foreach ($variable_option as $key_1 => $value) {
                                if ($value['product_sku'] == $item['ma_san_pham']) {
                                    $key_option = $key_1;
                                    $key_arr = $key;
                                    $isset_option = true;

                                    $data_update['product_sku'] = $item['ma_san_pham'];
                                    $data_update['product_name'] = $item['title'];
                                    $data_update['product_subtitle'] = $item['subtitle'];
                                    $data_update['product_slug'] = $item['slug'];
                                    $data_update['product_stock'] = $item['so_luong_ton'];
                                    $data_update['price_origin'] = ($item['gia_goc'] == '') ? 0 : $item['gia_goc'];
                                    $data_update['price_promotion'] = ($item['gia_khuyen_mai'] == '') ? 0 : $item['gia_khuyen_mai'];

                                    $variable_options[$key_arr][$key_option]['product_sku'] = $data_update['product_sku'];
                                    $variable_options[$key_arr][$key_option]['product_name'] = $data_update['product_name'];
                                    $variable_options[$key_arr][$key_option]['product_subtitle'] = $data_update['product_subtitle'];
                                    $variable_options[$key_arr][$key_option]['product_slug'] = $data_update['product_slug'];
                                    $variable_options[$key_arr][$key_option]['product_stock'] = $data_update['product_stock'];
                                    $variable_options[$key_arr][$key_option]['price_origin'] = $data_update['price_origin'];
                                    $variable_options[$key_arr][$key_option]['price_promotion'] = $data_update['price_promotion'];

                                    ProductStock::where('id', $item['id_san_pham'])->update(
                                        [
                                            'price_origin' => ($item['gia_goc'] == '') ? 0 : $item['gia_goc'],
                                            'price_promotion' => ($item['gia_khuyen_mai'] == '') ? 0 : $item['gia_khuyen_mai'],
                                            'stock' => $item['so_luong_ton'],
                                            'title' => $item['title'],
                                            'subtitle' => $item['subtitle'],
                                            'slug' => $item['slug'],
                                            'variable_data' => json_encode($variable_options[$key_arr][$key_option])
                                        ]
                                    );
                                    break;
                                }
                            }
                            if ($isset_option) {
                                break;
                            }
                        }
                    } else {
                        ProductStock::where('id', $item['id_san_pham'])->update(
                            [
                                'price_origin' => ($item['gia_goc'] == '') ? 0 : $item['gia_goc'],
                                'price_promotion' => ($item['gia_khuyen_mai'] == '') ? 0 : $item['gia_khuyen_mai'],
                                'stock' => $item['so_luong_ton'],
                                'title' => $item['title'],
                                'subtitle' => $item['subtitle'],
                                'slug' => $item['slug']
                            ]
                        );
                    }

                    if ($isset_option) {
                        // process variable
                        $group_variable_product['variable_option'] = $variable_options;
                        $product_parent->group_variable_product = json_encode($group_variable_product);
                    }

                    // process gift
                    $gift = '';
                    if ($item['text_cua_qua_tang'] != '' && $item['ma_qua_tang_san_pham'] != '') {
                        $product_gift_sku = explode(',', $item['ma_qua_tang_san_pham']);
                        $gift_products = [];
                        foreach ($product_gift_sku as $gift_item) {
                            $gift_product = ProductStock::where('sku', $gift_item)->first();
                            if ($gift_product) {
                                $gift_products[] = $gift_product->id;
                            }
                        }
                        $gift = [
                            'gift_title' => $item['text_cua_qua_tang'],
                            'gift_products' => $gift_products
                        ];
                        $gift = json_encode($gift);
                    }
                    $product_parent->gift = $gift;

                    //process time promotion
                    $product_parent->start_event = $item['ngay_bat_dau_giam_gia'];
                    $product_parent->end_event = $item['ngay_ket_thuc_giam_gia'];

                    //process buy with combo
                    $combo_id = null;
                    if ($item['ma_combo'] != '') {
                        $combo = ProductStock::where('sku', $item['ma_combo'])->first();
                        if ($combo) {
                            $combo_id = $combo->id;
                        }
                    }
                    $product_parent->buy_with_combo = $combo_id;

                    /**
                     * Process collections
                     * Collection ID:
                     * 1 => Siêu thị collagen
                     * 3 => Black Friday
                     * 4 => Hot Deal
                     * 5 => Chống Covid
                     */

                    $collections = [];
                    if ($item['bo_suu_tap_collagen'] == 1) {
                        $collagen = Collection::where('id', 1)->first();
                        $collections[$collagen->slug] = [$collagen->id];
                    }
                    if ($item['black_friday'] == 1) {
                        $black_friday = Collection::where('id', 3)->first();
                        $collections[$black_friday->slug] = [$black_friday->id];
                    }
                    if ($item['hot_deal'] == 1) {
                        $hot_deal = Collection::where('id', 4)->first();
                        $collections[$hot_deal->slug] = [$hot_deal->id];
                    }
                    if ($item['chong_covid'] == 1) {
                        $covid = Collection::where('id', 5)->first();
                        $collections[$covid->slug] = [$covid->id];
                    }
                    $product_parent->collections = json_encode($collections);

                    /**
                     * Process Category
                     * Category ID:
                     * 46 => Sản phẩm bán chạy
                     * 47 => Đồng giá 1k-9k-99k
                     */
                    // xử lý thể loại
                    $list_category_slugs = explode(',', $item['list_the_loai']);
                    $categories = [];
                    foreach ($list_category_slugs as $category_slug) {
                        $category = ProductCategory::where('categorySlug', $category_slug)
                            ->first();
                        if ($category) {
                            $categories[] = $category->categoryID;
                            $check_isset_category = Join_ProductCategory::where('id_theme', $product_parent->id)
                                ->where('id_category_theme', $category->categoryID)
                                ->first();
                            if (!$check_isset_category) {
                                Join_ProductCategory::create(
                                    [
                                        'id_theme' => $product_parent->id,
                                        'id_category_theme' => $category->categoryID
                                    ]
                                );
                            }
                        }
                    }
                    Join_ProductCategory::where('id_theme', $product_parent->id)
                        ->whereNotIn('id_category_theme', $categories)
                        ->delete();

                    if ($item['dong_gia_1k_9k_99k'] == 1) {
                        $join_category_product = Join_ProductCategory::where('id_theme', $product_parent->id)
                            ->where('id_category_theme', 47)
                            ->first();
                        if (!$join_category_product) {
                            Join_ProductCategory::create(
                                [
                                    'id_theme' => $product_parent->id,
                                    'id_category_theme' => 47
                                ]
                            );
                        }
                        if (!in_array(47, $categories)) {
                            $categories[] = 47;
                        }
                    } else {
                        Join_ProductCategory::where('id_theme', $product_parent->id)
                            ->where('id_category_theme', 47)
                            ->delete();
                        foreach ($categories as $key => $value) {
                            if ($value == 47) {
                                unset($categories[$key]);
                                break;
                            }
                        }
                    }

                    if ($item['ban_chay'] == 1) {
                        $join_category_product = Join_ProductCategory::where('id_theme', $product_parent->id)
                            ->where('id_category_theme', 46)
                            ->first();
                        if (!$join_category_product) {
                            Join_ProductCategory::create(
                                [
                                    'id_theme' => $product_parent->id,
                                    'id_category_theme' => 46
                                ]
                            );
                        }
                        if (!in_array(46, $categories)) {
                            $categories[] = 46;
                        }
                    } else {
                        Join_ProductCategory::where('id_theme', $product_parent->id)
                            ->where('id_category_theme', 46)
                            ->delete();
                        foreach ($categories as $key => $value) {
                            if ($value == 46) {
                                unset($categories[$key]);
                                break;
                            }
                        }
                    }

                    $category_main_id = 0;
                    if ($item['the_loai_uu_tien'] != '') {
                        $category_main = ProductCategory::where('categorySlug', $item['the_loai_uu_tien'])->first();
                        if ($category_main) {
                            $category_main_id = $category_main->categoryID;
                        }
                    }

                    $product_parent->flash_sale = ($item['flash_sale'] == 1) ? 1 : 0;
                    $product_parent->flash_sale_22h = ($item['flash_sale_22h'] == 1) ? 1 : 0;
                    $product_parent->propose = ($item['san_pham_goi_y'] == 1) ? 1 : 0;
                    $product_parent->category_primary_id = $category_main_id;
                    $product_parent->categories = json_encode($categories);
                    $product_parent->enable_edit = ($item['khoa_chinh_sua'] == 1) ? 1 : 0;
                    $product_parent->buy_limit = $item['gioi_han_mua'];
                    $product_parent->status = ($item['trang_thai'] == 1) ? 0 : 1;

                    Product::withoutEvents(function () use ($product_parent) {
                        $product_parent->save();
                    });
                }
            }
        }

        if ($this->isLatest) {
            ElasticSearchReIndexJob::dispatch(Product::class)->delay(now()->addMinutes(5));
        }
    }
}
