<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Libraries\Helpers;
use Illuminate\Support\Str;
use DB, File, Image, Config;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function listPostCategories(){
        $data_category = Category::select('categoryName', 'categorySlug', 'thumbnail', 'created', 'status', 'categoryID')
            ->orderBy('created', 'DESC')
            ->get();
        return view('admin.category.index')->with(['data_category' => $data_category]);
    }

    public function createPostCategory(){
        return view('admin.category.single');
    }

    public function postCategoryDetail($id){
        $post_category = Category::where('categoryID', $id)->first();
        if($post_category){
            return view('admin.category.single')->with(['post_category' => $post_category]);
        } else{
            return view('404');
        }
    }

    public function storePostCategoryDetail(Request $request){
        //id post
        $sid = $request->sid;
        $title_new = $request->post_title;
        $title_en = $request->post_title_en;

        $title_slug = addslashes($request->post_slug);
        if(empty($title_slug) || $title_slug == ''):
           $title_slug = Str::slug($title_new);
        endif;
        $category_parent = (int)$request->category_parent;

        //xử lý description
        $description = htmlspecialchars($request->post_description);
        $description_en = htmlspecialchars($request->post_description_en);

        //xử lý thumbnail
        $thumbnail_alt = addslashes($request->post_thumb_alt);
        $name_field = "thumbnail_file";
        $datetime_now=date('Y-m-d H:i:s');
        $datetime_convert=strtotime($datetime_now);
        if($request->thumbnail_file):
            $file = $request->file($name_field);
            $timestamp = $datetime_convert;
            $name = "category_post-".$timestamp. '-' .$file->getClientOriginalName();
            $name_thumb_img1 = $name;
            $url_folder_upload = "/images/category/";
            $file->move(base_path().$url_folder_upload,$name);
        else:
           if(isset($request->thumbnail_file_link) && $request->thumbnail_file_link !=""):
               $name_thumb_img1 = $request->thumbnail_file_link;
           else:
               $name_thumb_img1 = "";
           endif;
        endif;

        $categoryShort=(int)$request->post_short;
        $categoryIndex=0;
        if(isset($request->categoryIndex)):
            $categoryIndex=(int)$request->categoryIndex;
        endif;

        $seo_title = $request->seo_title;
        $seo_keyword = $request->seo_keyword;
        $seo_description = $request->seo_description;

        $updated = $request->created;
        $status = (int)$request->status;

        if($sid > 0){
            //update
            $data = array(
                'categoryName' => $title_new,
                'categoryName_en' => $title_en,
                'categorySlug' => $title_slug,
                'categoryParent' => $category_parent,
                'categoryDescription' => $description,
                'categoryDescription_en' => $description_en,
                'thumbnail' => $name_thumb_img1,
                'thumbnail_alt' => $thumbnail_alt,
                'categoryShort' => $categoryShort,
                'categoryIndex' => $categoryIndex,
                'seo_title' => $seo_title,
                'seo_keyword' =>$seo_keyword,
                'seo_description' =>$seo_description,
                'updated' => date('Y-m-d h:i:s'),
                'status' => $status
            );
            Category::where ("categoryID", $sid)->update($data);
            $msg = "Category has been updated";
            $url = route('admin.postCategoryDetail', array($sid));
            Helpers::msg_move_page($msg,$url);
        } else{
            // insert
            $data = array(
                'categoryName' => $title_new,
                'categoryName_en' => $title_en,
                'categorySlug' => $title_slug,
                'categoryParent' => $category_parent,
                'categoryDescription' => $description,
                'categoryDescription_en' => $description_en,
                'thumbnail' => $name_thumb_img1,
                'thumbnail_alt' => $thumbnail_alt,
                'categoryShort' => $categoryShort,
                'categoryIndex' => $categoryIndex,
                'seo_title' => $seo_title,
                'seo_keyword' =>$seo_keyword,
                'seo_description' =>$seo_description,
                'created' => $updated,
                'updated' => $updated,
                'status' => $status
            );
            $response = Category::create($data);
            $id_insert= $response->id;
            if($id_insert > 0):
                $msg = "Category has been registered";
                $url = route('admin.postCategoryDetail', array($id_insert));
                Helpers::msg_move_page($msg,$url);
            endif;
        }

    }
}
