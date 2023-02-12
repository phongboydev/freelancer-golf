<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstants;
use App\Models\Admin;
use App\Models\Module;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\Rating_Product;
use App\Models\Role;
use App\Models\RoleDetail;
use App\Tasks\Admin\RoleTask;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Page;
use App\Models\Join_Category_Post;
use App\Models\Category;
use App\Models\Product;
use App\Models\Join_Category_Product;
use App\Models\ProductCategory;
use App\Models\Variable_Product;
use App\Models\Slishow;
use App\Models\Discount_code;

class AjaxController extends Controller
{
    public function __construct()
    {

    }

    public function ajax_delete(Request $request)
    {
        $type = $request->type;
        $check_data = $request->seq_list;
        $arr = array();
        $values = "";
        for ($i = 0; $i < count($check_data); $i++):
            $values .= (int)$check_data[$i] . ",";
            $arr[] = (int)$check_data[$i];
        endfor;
        $user_role = $request->user_role;

        $user_role_id = $request->user_role['role_id'];
        $is_super_admin = ($user_role_id == BaseConstants::SUPER_ADMIN_ROLE_ID) ? true : false;

        switch ($type) {
            case 'module':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('super-admin', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    Module::whereIn('id', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            case 'role':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('super-admin', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    Role::whereIn('id', $arr)->delete();
                    RoleDetail::whereIn('role_id', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            case 'manager':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('super-admin', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    Admin::whereIn('id', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            case 'page':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('page-management', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    //xóa thumbnail
                    $url_upload = $_SERVER['DOCUMENT_ROOT'] . '/images/page/';
                    foreach ($arr as $it) {
                        $data_page = Page::where('id', '=', $it)->get();
                        foreach ($data_page as $row) {
                            $img = $row->thumbnail;
                            if ($img != '') {
                                $pt = $url_upload . $img;
                                if (file_exists($pt)) {
                                    unlink($pt);
                                }
                            }
                        }
                    }
                    Page::whereIn('id', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            case 'post':
                $url_upload = $_SERVER['DOCUMENT_ROOT'] . '/images/article/';
                if ($is_super_admin) {
                    //xóa thumbnail
                    foreach ($arr as $it) {
                        $data_post = Post::where('id', $it)->first();
                        if ($data_post->enable_edit == 0) {
                            $img = $data_post->thumbnail;
                            if ($img != '') {
                                $pt = $url_upload . $img;
                                if (file_exists($pt)) {
                                    unlink($pt);
                                }
                            }
                        }
                    }
                    Post::whereIn('id', $arr)->delete();
                    Join_Category_Post::whereIn('id_post', $arr)->delete();
                    return 1;
                } else {
                    $deletePermission = app(RoleTask::class)
                        ->checkPermission('post-management', [BaseConstants::DELETE_PERMISSION], $user_role);
                    if ($deletePermission) {
                        //xóa thumbnail
                        foreach ($arr as $it) {
                            $data_post = Post::where('id', $it)->first();
                            if ($data_post->enable_edit == 0) {
                                $img = $data_post->thumbnail;
                                if ($img != '') {
                                    $pt = $url_upload . $img;
                                    if (file_exists($pt)) {
                                        unlink($pt);
                                    }
                                }
                                Post::where('id', $it)->delete();
                                Join_Category_Post::where('id_post', $it)->delete();
                            }
                        }
                        return 1;
                    } else {
                        return 0;
                    }
                }
                break;
            case 'category':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('post-category-management', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    //xóa thumbnail
                    $url_upload = $_SERVER['DOCUMENT_ROOT'] . '/images/category/';
                    foreach ($arr as $it) {
                        $data_category = Category::where('categoryID', '=', $it)->get();
                        foreach ($data_category as $row) {
                            $img = $row->thumbnail;
                            if ($img != '') {
                                $pt = $url_upload . $img;
                                if (file_exists($pt)) {
                                    unlink($pt);
                                }
                            }
                        }
                    }
                    Category::whereIn('categoryID', $arr)->delete();
                    Join_Category_Post::whereIn('id_category', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            case 'product':
                $url_upload = $_SERVER['DOCUMENT_ROOT'] . '/images/product/';
                if ($is_super_admin) {
                    foreach ($arr as $it) {
                        $data_product = Product::find($it);
                        $img = $data_product->thumbnail;
                        if ($img != '') {
                            $pt = $url_upload . $img;
                            if (file_exists($pt)) {
                                unlink($pt);
                            }
                        }
                        $gallerys = $data_product->gallery_images;
                        if ($gallerys) {
                            $gallerys = unserialize($gallerys);
                            foreach ($gallerys as $key => $gallery) {
                                $gallery_path = $url_upload . $gallery;
                                if (file_exists($gallery_path)) {
                                    unlink($gallery_path);
                                }
                            }
                        }
                    }
                    Product::whereIn('id', $arr)->delete();
                    ProductStock::whereIn('product_id', $arr)->delete();
                    Rating_Product::whereIn('product_id', $arr)->delete();
                    Join_Category_Product::whereIn('product_id', $arr)->delete();
                    return 1;
                } else {
                    $deletePermission = app(RoleTask::class)
                        ->checkPermission('product-management', [BaseConstants::DELETE_PERMISSION], $user_role);
                    if ($deletePermission) {
                        //xóa thumbnail
                        foreach ($arr as $it) {
                            $data_product = Product::find($it);
                            if ($data_product->enable_edit == 0) {
                                $img = $data_product->thumbnail;
                                if ($img != '') {
                                    $pt = $url_upload . $img;
                                    if (file_exists($pt)) {
                                        unlink($pt);
                                    }
                                }
                                $gallerys = $data_product->gallery_images;
                                if ($gallerys) {
                                    $gallerys = unserialize($gallerys);
                                    foreach ($gallerys as $key => $gallery) {
                                        $gallery_path = $url_upload . $gallery;
                                        if (file_exists($gallery_path)) {
                                            unlink($gallery_path);
                                        }
                                    }
                                }
                                Product::where('id', $it)->delete();
                                ProductStock::where('product_id', $it)->delete();
                                Rating_Product::where('id_product', $it)->delete();
                                Join_Category_Product::where('product_id', $it)->delete();
                            }
                        }
                        return 1;
                    } else {
                        return 0;
                    }
                }
                break;
            case 'product_category':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('product-category-management', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    //xóa thumbnail
                    $url_upload = $_SERVER['DOCUMENT_ROOT'] . '/images/category/';
                    foreach ($arr as $it) {
                        $data_category = ProductCategory::where('categoryID', '=', $it)->get();
                        foreach ($data_category as $row) {
                            $img = $row->thumbnail;
                            if ($img != '') {
                                $pt = $url_upload . $img;
                                if (file_exists($pt)) {
                                    unlink($pt);
                                }
                            }
                        }
                    }
                    ProductCategory::whereIn('categoryID', $arr)->delete();
                    Join_Category_Product::whereIn('category_id', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            case 'order':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('order-management', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    Order::whereIn('id', $arr)->delete();
                    OrderDetail::whereIn('order_id', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            case 'variable_product':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('product-variable-management', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    Variable_Product::whereIn('id', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            case 'slider':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('slider-management', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    //xóa thumbnail
                    $url_upload = $_SERVER['DOCUMENT_ROOT'];
                    foreach ($arr as $it) {
                        $data_slider = Slishow::where('id', '=', $it)->get();
                        foreach ($data_slider as $row) {
                            $img_pc = $row->src;
                            if ($img_pc != '') {
                                $pt = $url_upload . $img_pc;
                                if (file_exists($pt)) {
                                    unlink($pt);
                                }
                            }

                            $img_mobile = $row->src_mobile;
                            if ($img_mobile != '') {
                                $pt = $url_upload . $img_mobile;
                                if (file_exists($pt)) {
                                    unlink($pt);
                                }
                            }
                        }
                    }
                    Slishow::whereIn('id', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            case 'discount-code':
                $deletePermission = app(RoleTask::class)
                    ->checkPermission('slider-management', [BaseConstants::DELETE_PERMISSION], $user_role);
                if ($deletePermission) {
                    Discount_code::whereIn('id', $arr)->delete();
                    return 1;
                } else {
                    return 0;
                }
                break;
            default:
                # code...
                break;
        }
    }

    public function processThemeFast(Request $request)
    {
        $id = (int)$request->id;
        $origin_price = $request->origin_price;
        $promotion_price = $request->promotion_price;
        $start_event = $request->start_event;
        $end_event = $request->end_event;
        if ($id > 0) {
            $data = array(
                'price_origin' => $origin_price,
                'price_promotion' => $promotion_price,
                'start_event' => $start_event,
                'end_event' => $end_event
            );
            Product::where("id", $id)->update($data);
            echo 'OK';
        } else {
            echo 'Lỗi';
        }
        exit();
    }

    public function productUpdateOption(Request $request)
    {
        $type = $request->type;
        $value = $request->value;
        switch ($type) {
            case 'arrival':
                Product::where("id", $request->id)->update(['new_arrival' => $value]);
                break;
            case 'hot_deal':
                Product::where("id", $request->id)->update(['hot_deal' => $value]);
                break;
            case 'propose':
                Product::where("id", $request->id)->update(['propose' => $value]);
                break;
            default:
                Product::where("id", $request->id)->update(['best_seller' => $value]);
                break;
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Product has been updated.'
            ]
        );
    }

    public function updateStoreStatus(Request $request)
    {
        if (isset($request['check']) && $request['sid'] != "") {
            $status = $request['check'];
            $product_id = (int)$request['sid'];
            if ($product_id > 0) {
                Product::where("id", $product_id)->update(['store_status' => $status]);
                echo "OK";
            } else {
                echo "Lỗi";
            }
        }
    }
}
