<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstants;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Libraries\Helpers;
use Illuminate\Support\Str;
use DB, File, Image, Config;

class CategoryProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function listProductCategories(Request $request)
    {
        $query = ProductCategory::select('*');
        if ($request->search_title != '') {
            $query->where('name', 'LIKE', '%' . $request->search_title . '%');
        }
        $data_category = $query->orderBy('created_at', 'DESC')
            ->paginate(20);
        return view('admin.category-product.index')->with(['data_category' => $data_category]);
    }

    public function createProductCategory()
    {
        $listCategories = ProductCategory::where('status', BaseConstants::ACTIVE)->get()->toArray();
        return view('admin.category-product.single', compact('listCategories'));
    }

    public function productCategoryDetail($id)
    {
        $detail = ProductCategory::where('product_categories.id', $id)->first();
        if ($detail) {
            $listCategories = ProductCategory::where('status', BaseConstants::ACTIVE)
                ->where('id', '<>', $id)
                ->get()
                ->toArray();
            return view('admin.category-product.single', compact('detail', 'listCategories'));
        } else {
            return view('404');
        }
    }

    public function storeProductCategory(Request $request)
    {
        $id = $request->id;

        $slug = addslashes($request->slug);
        if (empty($slug) || $slug == '') {
            $slug = Str::slug($request->name);
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
        //$path_img = $year . '/' . $month . '/' . $date . '/';
        $datetime_now=date('Y-m-d H:i:s');
        $datetime_convert=strtotime($datetime_now);
        //xử lý thumbnail
        $thumbnail_alt = $request->post_thumb_alt;
        $name_field = "thumbnail_file";
        if ($request->thumbnail_file) {
            $file = $request->file($name_field);
            $name = $datetime_convert.'-'.$file->getClientOriginalName();
            $name_thumb_img1 = $name;
            $url_folder_upload = "/images/category/";
            $file->move(base_path() . $url_folder_upload, $name);
        } else {
            $name_thumb_img1 = "";
            if (isset($request->thumbnail_file_link) && $request->thumbnail_file_link != "") {
                $name_thumb_img1 = $request->thumbnail_file_link;
            }
        }

        //xử lý gallery
        $count_item_gallery = (int)$request->gallery_item_count;
        $array_group_gallery = [];
        for ($m = 0; $m < $count_item_gallery; $m++) {
            $k = $m + 1;
            /********File upload******************************************************/
            if ($request->hasFile('upload_gallery_file0')) {
                $file = $request->file('upload_gallery_file0');
                if (isset($file[$m]) && $file[$m]->getClientOriginalName() != '') {
                    $link_use_thumbnail_gallery = $datetime_convert.'_'.$file[$m]->getClientOriginalName();
                    $link_use_thumbnail_gallery = str_replace(' ', '', $link_use_thumbnail_gallery);
                    $file[$m]->move(base_path() . '/images/category/', $link_use_thumbnail_gallery);
                    $link_use_thumbnail_gallery = $link_use_thumbnail_gallery;
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
            if ($link_use_thumbnail_gallery != '') {
                $array_group_gallery[] = $link_use_thumbnail_gallery;
            }
        }
        $galleries = json_encode($array_group_gallery);
        //end xử lý gallery

        $sort = (int)$request->sort;

        $seo_title = $request->seo_title;
        $seo_keyword = $request->seo_keyword;
        $seo_description = $request->seo_description;
        $status = (int)$request->status;

        $data = array(
            'name' => $request->name,
            'name_en' => $request->name_en,
            'slug' => $slug,
            'parent' => (int)$request->parent,
            'description' => $description,
            'description_en' => $description_en,
            'content' => $content,
            'content_en' => $content_en,
            'thumbnail' => $name_thumb_img1,
            'thumbnail_alt' => $thumbnail_alt,
            'sort' => $sort,
            'seo_title' => $seo_title,
            'seo_keyword' => $seo_keyword,
            'seo_description' => $seo_description,
            'status' => $status,
            'show_in_home' => ($request->show_in_home) ? $request->show_in_home : 0,
            'galleries' => $galleries
        );

        if ($id > 0) {
            //update
            ProductCategory::where("id", $id)->update($data);
            $msg = "Product category has been updated.";
            $url = route('admin.productCategoryDetail', $id);
            Helpers::msg_move_page($msg, $url);
        } else {
            // insert
            $response = ProductCategory::create($data);
            $id_insert = $response->id;
            if ($id_insert > 0) {
                $msg = "Product category has been created.";
                $url = route('admin.productCategoryDetail', $id_insert);
                Helpers::msg_move_page($msg, $url);
            }
        }
    }
}
