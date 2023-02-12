<?php

namespace App\Http\Controllers\Admin;

use App\Models\Theme;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting, App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Helpers;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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

    public function changePassword()
    {
        return view('admin.change-password');
    }

    public function storeChangePassword(Request $rq)
    {
        $user = Auth::guard('admin')->user();
        $id = $user->id;
        if (Hash::check($rq->current_password, $user->password)) {
            if ($rq->new_password == $rq->confirm_password) {
                $data = array(
                    'password' => bcrypt($rq->new_password)
                );
            } else {
                $msg = 'Mật khẩu xác nhận không trùng khớp';
                return Redirect::back()->withErrors($msg);
            }
        } else {
            $msg = 'Mật khẩu hiện tại không chính xác';
            return Redirect::back()->withErrors($msg);
        }
        Admin::where("id", $id)->update($data);
        $msg = "Password has been updated";
        $url = route('admin.dashboard');
        Helpers::msg_move_page($msg, $url);
    }

    public function getMenu()
    {
        return view('admin.setting.menu');
    }

    public function getThemeOption()
    {
        return view('admin.setting.theme-option');
    }

    public function storeThemeOption(Request $rq)
    {
        $array_option_tdr = array();
        $theme_options = "";
        if (isset($rq->header_option_values_line) && !empty($rq->header_option_values_line) && $rq->header_option_values_line != ''):
            for ($i = 0; $i < count($rq->header_option_values_line); $i++):
                $header_option_texts_line = ($rq->header_option_texts_line[$i] != '') ? $rq->header_option_texts_line[$i] : '';
                $header_option_values_line = ($rq->header_option_values_line[$i] != '') ? $rq->header_option_values_line[$i] : '';
                if (!empty($header_option_texts_line)):
                    $header_option_texts_lines = strtolower(str_replace(" ", "_", $header_option_texts_line));
                    $header_option_texts_lines = Str::slug($header_option_texts_lines);
                    $header_option_values_line = htmlspecialchars(base64_encode($header_option_values_line));
                    $array_list_option = array(
                        $header_option_texts_lines => $header_option_values_line,
                        "group_tdr" => array(
                            "tdr_name" => $header_option_texts_lines,
                            "tdr_choise" => "line",
                            "tdr_value" => $header_option_values_line,
                            $header_option_texts_lines => $header_option_values_line
                        )
                    );
                    array_push($array_option_tdr, $array_list_option);
                endif;
            endfor;
        endif;
        if (isset($rq->header_option_values_muti_line) && !empty($rq->header_option_values_muti_line) && $rq->header_option_values_muti_line != ''):
            for ($i = 0; $i < count($rq->header_option_values_muti_line); $i++):
                $header_option_texts_muti_line = ($rq->header_option_texts_muti_line[$i] != '') ? $rq->header_option_texts_muti_line[$i] : '';
                $header_option_values_muti_line = ($rq->header_option_values_muti_line[$i] != '') ? $rq->header_option_values_muti_line[$i] : '';
                if (!empty($header_option_texts_muti_line)):
                    $header_option_texts_muti_lines = strtolower(str_replace(" ", "_", $header_option_texts_muti_line));
                    $header_option_texts_muti_lines = Str::slug($header_option_texts_muti_lines);
                    $header_option_values_muti_line = htmlspecialchars(base64_encode($header_option_values_muti_line));
                    $array_list_option = array(
                        $header_option_texts_muti_lines => $header_option_values_muti_line,
                        "group_tdr" => array(
                            "tdr_name" => $header_option_texts_muti_lines,
                            "tdr_choise" => "muti_line",
                            "tdr_value" => $header_option_values_muti_line,
                            $header_option_texts_muti_lines => $header_option_values_muti_line
                        )
                    );
                    array_push($array_option_tdr, $array_list_option);
                endif;
            endfor;
        endif;
        $theme_options = serialize($array_option_tdr);
        //$res_checkbox = delete_data("ace_setting");
        $res_checkbox = Setting::whereNotNull('id')->delete();

        $datas = array(
            "name_setting" => "e-bike",
            "value_setting" => $theme_options,
            "status" => 0
        );
        $respons = Setting::create($datas);
        $id_insert = $respons->id;
        Cache::forget('theme_option');
        if ($id_insert > 0):
            $msg = "Option has been registered";
            $url = route('admin.themeOption');
            Helpers::msg_move_page($msg, $url);
        endif;
    }
}
