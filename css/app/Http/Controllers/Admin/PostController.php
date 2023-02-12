<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstants;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Join_Category_Post;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Helpers;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function listPost(Request $request)
    {
        $data_post = Post::select('post.id', 'post.title', 'post.slug', 'post.thumbnail', 'post.status', 'post.created')
            ->orderBy('post.created', 'DESC')
            ->paginate(20);
        return view('admin.post.index')->with(['data_post' => $data_post]);
    }

    public function searchPost(Request $request)
    {
        $query = Post::select('post.id', 'post.title', 'post.slug', 'post.thumbnail', 'post.status', 'post.created')
            ->orderBy('post.created', 'DESC');

        if (isset($request->search_title) && $request->search_title != '') {
            $query = $query->where('post.title', 'LIKE', '%' . $request->search_title . '%');
        }

        if (isset($request->category) && $request->category != '') {
            $query = $query->join('join_category_post', 'join_category_post.id_post', 'post.id')
                ->join('category', 'categories.categoryID', 'join_category_post.id_category')
                ->where('categories.categoryID', $request->category);
        }

        $data_post = $query->paginate(20);

        return view('admin.post.filter')->with(['data_post' => $data_post]);
    }

    public function createPost()
    {
        return view('admin.post.single');
    }

    public function postDetail(Request $request, $id)
    {
        $user_role_id = $request->user_role['role_id'];
        $is_super_admin = ($user_role_id == BaseConstants::SUPER_ADMIN_ROLE_ID) ? true : false;
        if ($is_super_admin) {
            $post_detail = Post::where('id', $id)->first();
        } else {
            $post_detail = Post::where('id', $id)
                ->where('user_id', Auth::guard('admin')->user()->id)
                ->first();
        }
        if ($post_detail) {
            return view('admin.post.single')->with(['post_detail' => $post_detail]);
        } else {
            return view('404');
        }
    }

    public function storePostDetail(Request $request)
    {
        //id post
        $id = $request->sid;

        $title_new = $request->post_title;
        $title_en = $request->post_title_en;

        $title_slug = addslashes($request->post_slug);
        if (empty($title_slug) || $title_slug == ''):
            $title_slug = Str::slug($title_new);
        endif;

        //xử lý description
        $description = htmlspecialchars($request->post_description);
        $description_en = htmlspecialchars($request->post_description_en);

        //xử lý content
        $content = htmlspecialchars($request->post_content);
        $content_en = htmlspecialchars($request->post_content_en);

        //xử lý thumbnail
        $thumbnail_alt = addslashes($request->post_thumb_alt);
        $name_field = "thumbnail_file";
        if ($request->thumbnail_file):
            $file = $request->file($name_field);
            $name = time() . '-' . $file->getClientOriginalName();
            $name_thumb_img1 = $name;
            $url_folder_upload = "/images/article/";
            $file->move(base_path() . $url_folder_upload, $name);
        else:
            if (isset($request->thumbnail_file_link) && $request->thumbnail_file_link != ""):
                $name_thumb_img1 = $request->thumbnail_file_link;
            else:
                $name_thumb_img1 = "";
            endif;
        endif;

        $gallery_checked = 0;
        if (isset($request->gallery_checked)):
            $gallery_checked = (int)$request->gallery_checked;
        endif;

        $seo_title = $request->seo_title;
        $seo_keyword = $request->seo_keyword;
        $seo_description = $request->seo_description;
        $count_item_gallery = (int)$request->gallery_item_count;
        $array_group_gallery = array();

        //xử lý gallery
        for ($m = 0; $m < $count_item_gallery; $m++) {
            $k = $m + 1;
            /********File upload******************************************************/
            $thumbnail_name_arr = "";
            if ($request->hasFile('upload_gallery_file' . $k)) {
                $file = $request->file('upload_gallery_file' . $k);
                $thumbnail_name_arr = time() . $k . '_gallery_' . $file->getClientOriginalName();
                $link_use_thumbnail_gallery = '/img/uploads/posts/' . $thumbnail_name_arr;
                $file->move(base_path() . '/img/uploads/posts/', $thumbnail_name_arr);
            } else {
                if ($request->input('upload_gallery' . $k) != "") {
                    $link_use_thumbnail_gallery = $request->input('upload_gallery' . $k);
                } else {
                    $link_use_thumbnail_gallery = "";
                }
            }
            /****************End*******************/
            if (strlen($link_use_thumbnail_gallery) > 0):
                array_push($array_group_gallery, $link_use_thumbnail_gallery);
            endif;
        }

        $store_gallery = serialize($array_group_gallery);
        $order_short = addslashes($request->post_order);
        $updated = $request->created;
        $status = (int)$request->status;

        $data = [
            'title' => $title_new,
            'title_en' => $title_en,
            'slug' => $title_slug,
            'description' => $description,
            'content' => $content,
            'description_en' => $description_en,
            'content_en' => $content_en,
            'thumbnail' => $name_thumb_img1,
            'thumbnail_alt' => $thumbnail_alt,
            'seo_title' => $seo_title,
            'seo_keyword' => $seo_keyword,
            'seo_description' => $seo_description,
            'gallery_images' => $store_gallery,
            'gallery_checked' => $gallery_checked,
            'order_short' => $order_short,
            'status' => $status
        ];

        if ($id > 0) {
            //update
            $data['updated'] = date('Y-m-d h:i:s');
            Join_Category_Post::where('id_post', $id)->delete();

            $category_items = [];
            $category_items = isset($request->category_item) ? $request->category_item : $category_items;
            for ($u = 0; $u < count($category_items); $u++) {
                if ($category_items[$u] > 0) {
                    Join_Category_Post::create(
                        [
                            "id_category" => $category_items[$u],
                            "id_post" => $id
                        ]
                    );
                }
            }

            Post::where("id", $id)->update($data);
            $msg = "Post has been Updated";
            $url = route('admin.postDetail', array($id));
            Helpers::msg_move_page($msg, $url);
        } else {
            // insert
            $data['created'] = $updated;
            $data['updated'] = $updated;

            $response = Post::create($data);
            $id_insert = $response->id;

            if ($id_insert > 0) {
                $category_items = [];
                $category_items = isset($request->category_item) ? $request->category_item : $category_items;
                for ($u = 0; $u < count($category_items); $u++) {
                    if ($category_items[$u] > 0) {
                        Join_Category_Post::create(
                            [
                                "id_category" => $category_items[$u],
                                "id_post" => $id_insert
                            ]
                        );
                    }
                }
                $msg = "Post has been registered";
                $url = route('admin.postDetail', array($id_insert));
                Helpers::msg_move_page($msg, $url);
            }
        }
    }
}
