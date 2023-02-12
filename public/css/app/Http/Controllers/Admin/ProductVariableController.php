<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstants;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Variable_Product;
use App\Libraries\Helpers;
use Illuminate\Support\Str;
use DB, File, Image, Config;

class ProductVariableController extends Controller
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

    public function listProductVariables(Request $request){
        $query = Variable_Product::select('*')
            ->where('parent', 0)
            ->orderBy('created_at', 'DESC');
        if($request->search_title != ''){
            $query->where('name', 'LIKE', '%' . $request->search_title . '%');
        }
        $data_variable = $query->paginate(20);
        return view('admin.variable.index')->with(['data_variable' => $data_variable]);
    }

    public function createProductVariable(){
        $listVariables = Variable_Product::where('status', BaseConstants::ACTIVE)
            ->where('parent', 0)
            ->get();
        return view('admin.variable.single', compact('listVariables'));
    }

    public function productVariableDetail($id){
        $detail = Variable_Product::where('id', $id)->first();
        if($detail){
            $listVariables = Variable_Product::where('status', BaseConstants::ACTIVE)
                ->where('id', '<>', $id)
                ->where('parent', 0)
                ->get();
            return view('admin.variable.single', compact('detail', 'listVariables'));
        } else{
            return view('404');
        }
    }

    public function storeProductVariableDetail(Request $request){
        //id post
        $id = $request->id;
        $title_new = $request->post_title;
        $title_en = $request->post_title_en;

        $slug = ($request->post_slug != '') ? $request->post_slug : Str::slug($title_new);
        $color_code = $request->color_code;
        $category_parent = (int)$request->category_parent;
        $status = (int)$request->status;

        $data = array(
            'name' => $title_new,
            'name_en' => $title_en,
            'slug' => $slug,
            'parent' => $category_parent,
            'status' => $status,
            'color_code' => $color_code
        );

        if($id > 0){
            //update
            Variable_Product::where ("id", $id)->update($data);
            $msg = "Variable has been updated.";
            $url = route('admin.productVariableDetail', array($id));
            Helpers::msg_move_page($msg,$url);
        } else{
            // insert
            $response = Variable_Product::create($data);
            $id_insert= $response->id;
            if($id_insert>0):
                $msg = "Variable has been created.";
                $url = route('admin.productVariableDetail', array($id_insert));
                Helpers::msg_move_page($msg,$url);
            endif;
        }
    }

    public function generateSelectVariableChild(Request $request)
    {
        $variable_parents = $request->variable_parents;
        $count_variable_parent = count($variable_parents);
        $result = '<input type="hidden" name="count_variable_parent" id="count_variable_parent" value="' . $count_variable_parent . '">';
        $i = 1;
        $select_box = '';
        $select_generate_gallery = '<option value="">Chọn biến thể</option>';
        foreach ($variable_parents as $variable_parent) {
            $variable_parent_id = Variable_Product::where('name', $variable_parent)->first(['id', 'slug']);
            if ($variable_parent_id) {
                $select_generate_gallery .= '<option value="' . $variable_parent_id->slug . '">' . $variable_parent . '</option>';
                $list_variable = Variable_Product::where('parent', $variable_parent_id->id)->get();
                $select_box .= '<div class="col-md-6 mt-2 select-variable">
                <label for="variable_child_' . $i . '">Chọn ' . $variable_parent . '</label>
                <select class="form-control ' . $variable_parent_id->slug . '" id="variable_child_' . $i . '" name="variable_child_' . $i . '[]" multiple="multiple" onchange="generateTableVariable()"
                            data-parent-slug="' . $variable_parent_id->slug . '">
                ';
                if (count($list_variable) > 0) {
                    foreach ($list_variable as $variable) {
                        $select_box .= '<option value="' . $variable->name . '">' . $variable->name . '</option>';
                    }
                }
                $select_box .= '</select></div>';
            }
            $i++;
        }

        $result .= '<div class="row">' . $select_box . '</div>
            <input type="hidden" name="variable_parent_gallery" id="variable_parent_gallery">
            <div class="form-row align-items-center mb-4 mt-4">
                <div class="col">
                    <b>Chọn biến thể cần tạo Gallery (Cần chọn các biến thể con trước khi tạo Gallery)</b>
                </div>
                <div class="col">
                    <select name="slt_variable_album" id="slt_variable_album" class="form-control">
                        ' . $select_generate_gallery . '
                    </select>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" class="btn btn-success" onclick="generateVariableGallery()">Tạo</a>
                </div>
            </div>
            <div id="generate-gallery-result">
                <input type="hidden" name="count_gallery_parent" id="count_gallery_parent" value="0">
            </div>

            <input type="hidden" name="variable_parent_icon" id="variable_parent_icon">
            <div class="form-row align-items-center mb-4 mt-4">
                <div class="col">
                    <b>Chọn biến thể cần tạo Icon</b>
                </div>
                <div class="col">
                    <select name="slt_variable_icon" id="slt_variable_icon" class="form-control">
                        ' . $select_generate_gallery . '
                    </select>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" class="btn btn-success" onclick="generateVariableIcon()">Tạo</a>
                </div>
            </div>
            <div id="generate-icon-result">
                <input type="hidden" name="count_icon_parent" id="count_icon_parent" value="0">
            </div>';
        return $result;
    }
}
