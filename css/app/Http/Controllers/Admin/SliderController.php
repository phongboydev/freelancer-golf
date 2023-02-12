<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slishow;
use App\Libraries\Helpers;
use Illuminate\Support\Facades\Cache;

class SliderController extends Controller
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

    public function listSliders()
    {
        $data_slider = Slishow::get();
        return view('admin.slider-home.index')->with(['data_slider' => $data_slider]);
    }

    public function createSlider()
    {
        return view('admin.slider-home.single');
    }

    public function sliderDetail($id)
    {
        $data_slider = Slishow::where('id', $id)->first();
        if ($data_slider) {
            return view('admin.slider-home.single', compact('data_slider'));
        } else {
            return view('404');
        }
    }

    public function storeSliderDetail(Request $request)
    {
        //id page
        $id = $request->sid;
        $datetime_now = date('Y-m-d H:i:s');
        $datetime_convert = strtotime($datetime_now);

        $title_new = $request->post_title;

        /*PC up load*/
        $name_field_pc = "csv_slishow";
        $name_text_pc = "slishow_upload";
        if ($request->hasFile($name_field_pc)):
            $file = $request->file($name_field_pc);
            $name = time() . '-' . $file->getClientOriginalName();
            $name_thumb_pc = '/img/uploads/slider/' . $name;
            $file->move(base_path() . '/img/uploads/slider/', $name);
        else:
            if ($request->input($name_text_pc) != ""):
                $name_thumb_pc = $request->input($name_text_pc);
            else:
                $name_thumb_pc = "";
            endif;
        endif;
        /*End pc upload*/

        /*Mobile up load*/
        $name_field_mobile = "csv_slishow_mobile";
        $name_text_mobile = "slishow_upload_mobile";
        if ($request->hasFile($name_field_mobile)):
            $file = $request->file($name_field_mobile);
            $name = time() . '-' . $file->getClientOriginalName();
            $name_thumb_mobile = '/img/uploads/slider/' . $name;
            $file->move(base_path() . '/img/uploads/slider/', $name);
        else:
            if ($request->input($name_text_mobile) != ""):
                $name_thumb_mobile = $request->input($name_text_mobile);
            else:
                $name_thumb_mobile = "";
            endif;
        endif;
        /*End Mobile upload*/

        Cache::forget('slider_home');
        if ($id == 0) {
            $data = array(
                'name' => $title_new,
                'src' => $name_thumb_pc,
                'src_mobile' => $name_thumb_mobile,
                'order' => $request->order,
                'link' => $request->link,
                'description' => htmlspecialchars($request->description),
                'target' => $request->target,
                'status' => $request->status,
                'updated' => $request->created,
                'created' => $request->created,
                'video_link_slider' => $request->video_link_slider,
                'video_link_slider_mobile' => $request->video_link_slider_mobile
            );
            $response = Slishow::create($data);
            $id_insert = $response->id;
            if ($id_insert > 0):
                $msg = "Slider has been registered";
                $url = route('admin.slider');
                Helpers::msg_move_page($msg, $url);
            endif;
        } else {
            $data = array(
                'name' => $title_new,
                'src' => $name_thumb_pc,
                'src_mobile' => $name_thumb_mobile,
                'order' => $request->order,
                'link' => $request->link,
                'description' => '',
                'target' => $request->target,
                'status' => $request->status,
                'updated' => date('Y-m-d h:i:s'),
                'video_link_slider' => $request->video_link_slider,
                'video_link_slider_mobile' => $request->video_link_slider_mobile
            );
            Slishow::where("id", $id)->update($data);
            $msg = "Slider has been Updated";
            $url = route('admin.sliderDetail', array($id));
            Helpers::msg_move_page($msg, $url);
        }
    }
}
