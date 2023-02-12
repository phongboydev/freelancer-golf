<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Helpers;
use Illuminate\Support\Str;
use DB, File, Image;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function listPage()
    {
        $data_page = Page::get();
        return view('admin.page.index')->with(['data_page' => $data_page]);
    }

    public function createPage()
    {
        return view('admin.page.single');
    }

    public function pageDetail($id)
    {
        $page_detail = Page::where('page.id', '=', $id)->first();
        if ($page_detail) {
            return view('admin.page.single')->with(['page_detail' => $page_detail]);
        } else {
            return view('404');
        }
    }

    public function storePageDetail(Request $rq)
    {
        //id page
        $sid = $rq->sid;

        $title_new = $rq->post_title;
        $title_slug = addslashes($rq->post_slug);
        $title_en = $rq->post_title_en;

        if (empty($title_slug) || $title_slug == ''):
            $title_slug = Str::slug($title_new);
        endif;
        //xử lý description
        $description = htmlspecialchars($rq->post_description);
        $description_en = htmlspecialchars($rq->post_description_en);

        //xử lý content
        $content = htmlspecialchars($rq->post_content);
        $content_en = htmlspecialchars($rq->post_content_en);

        $thumbnail_alt = addslashes($rq->post_thumb_alt);

        //xử lý thumbnail
        $name_field = "thumbnail_file";
        $datetime_now = date('Y-m-d H:i:s');
        $datetime_convert = strtotime($datetime_now);
        if ($rq->thumbnail_file):
            $file = $rq->file($name_field);
            $timestamp = $datetime_convert;
            $name = "page-" . $timestamp . '-' . $file->getClientOriginalName();
            $name_thumb_img1 = $name;
            $url_folder_upload = "/images/page/";
            $file->move(base_path() . $url_folder_upload, $name);
        else:
            if (isset($rq->thumbnail_file_link) && $rq->thumbnail_file_link != ""):
                $name_thumb_img1 = $rq->thumbnail_file_link;
            else:
                $name_thumb_img1 = "";
            endif;
        endif;

        //template
        if ($rq->template) {
            $template = $rq->template;
        } else {
            $template = 0;
        }

        //show footer
        if ($rq->show_footer) {
            $show_footer = $rq->show_footer;
        } else {
            $show_footer = 0;
        }


        if ($sid == 0) {
            $data = array(
                'title' => $title_new,
                'slug' => $title_slug,
                'description' => $description,
                'content' => $content,
                'title_en' => $title_en,
                'description_en' => $description_en,
                'content_en' => $content_en,
                'template' => $template,
                'show_footer' => $show_footer,
                'thumbnail' => $name_thumb_img1,
                'thumbnail_alt' => $thumbnail_alt,
                'status' => $rq->status,
                'updated' => $rq->created,
                'created' => $rq->created,
            );
            $response = Page::create($data);
            $id_insert = $response->id;
            if ($id_insert > 0):
                $msg = "Page has been registered";
                $url = route('admin.pages');
                Helpers::msg_move_page($msg, $url);
            endif;
        } else {
            $data = array(
                'title' => $title_new,
                'slug' => $title_slug,
                'description' => $description,
                'content' => $content,
                'title_en' => $title_en,
                'description_en' => $description_en,
                'content_en' => $content_en,
                'template' => $template,
                'show_footer' => $show_footer,
                'thumbnail' => $name_thumb_img1,
                'thumbnail_alt' => $thumbnail_alt,
                'status' => $rq->status,
                'updated' => date('Y-m-d h:i:s')
            );
            Page::where("id", "=", $sid)->update($data);
            $msg = "Page has been Updated";
            $url = route('admin.pageDetail', array($sid));
            Helpers::msg_move_page($msg, $url);
        }
    }
}
