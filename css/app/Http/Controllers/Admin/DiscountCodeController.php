<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstants;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Discount_code;
use App\Libraries\Helpers;
use Illuminate\Support\Str;
use DB, File, Image;

class DiscountCodeController extends Controller
{
    public function __construct(){
    }

    public function listDiscountCodes(){
        $data_code = Discount_code::get();
        return view('admin.discount-code.index')->with(['data_discount_code' => $data_code]);
    }

    public function createDiscountCode(){
        $listProducts = ProductStock::join('products', 'products.id', 'product_stocks.product_id')
            ->where('products.status', BaseConstants::ACTIVE)
            ->get(['product_stocks.id', 'product_stocks.title', 'product_stocks.slug']);
        return view('admin.discount-code.single', compact('listProducts'));
    }

    public function discountCodeDetail($id){
        $detail = Discount_code::where('id', '=', $id)->first();
        if($detail){
            $listProducts = ProductStock::join('products', 'products.id', 'product_stocks.product_id')
                ->where('products.status', BaseConstants::ACTIVE)
                ->get(['product_stocks.id', 'product_stocks.title', 'product_stocks.slug']);
            return view('admin.discount-code.single', compact('detail', 'listProducts'));
        } else{
            return view('404');
        }
    }

    public function storeDiscountCodeDetail(Request $request){
        //id page
        $id = $request->id;
        $code = $request->code;
        $expired = date('Y-m-d H:i:s', strtotime($request->expired));
        $start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
        $type = $request->type_discount;

        $apply_for_order = 0;
        if($request->apply_for_order != '' && $request->apply_for_order > 0){
            $apply_for_order = $request->apply_for_order;
        }

        $group_code = [];
        $count_group_discount = $request->count_group_discount;
        for ($i = 1; $i <= $count_group_discount; $i++) {
            if ($request->input('percent_' . $i) != '' && $request->input('percent_' . $i) > 0) {
                $percent = $request->input('percent_' . $i);
                $discount_money = 0;
            } else {
                $discount_money = $request->input('discount_money_' . $i);
                $percent = 0;
            }

            $apply_products = ($request->input('apply_products_' . $i) != '') ? $request->input('apply_products_' . $i) : [];
            $except_products = ($request->input('except_products_' . $i) != '') ? $request->input('except_products_' . $i) : [];

            $group_item = array(
                'percent' => $percent,
                'discount_money' => $discount_money,
                'apply_products' => $apply_products,
                'except_products' => $except_products
            );
            array_push($group_code, $group_item);
        }

        $data = array(
            'code' => $code,
            'expired' => $expired,
            'start_date' => $start_date,
            'type' => $type,
            'apply_for_order' => $apply_for_order,
            'status' => $request->status,
            'group_code' => json_encode($group_code)
        );

        if ($id == 0) {
            $response = Discount_code::create($data);
            $id_insert = $response->id;
            if ($id_insert > 0) {
                $msg = "Discount Code has been registered";
                $url = route('admin.discountCode');
                Helpers::msg_move_page($msg,$url);
            }
        } else{
            Discount_code::where ("id", $id)->update($data);
            $msg = "Discount code has been updated";
            $url = route('admin.discountCodeDetail', array($id));
            Helpers::msg_move_page($msg,$url);
        }
    }
}
