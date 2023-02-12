<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductStock;
use App\Models\Theme;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rating_Product;
use App\Libraries\Helpers;

class ReviewProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
    }

    public function allReviewProducts(){
        $list = Rating_Product::with(
            [
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
                'theme' => function ($query) {
                    $query->select('id', 'title', 'slug');
                }
            ]
        )
            ->get();
        return view('admin.review-product.all', compact('list'));
    }

    public function index($id){
        $list = Rating_Product::with(
            [
                'user' => function ($query) {
                    $query->select('id', 'name');
                }
            ]
        )
            ->where('id_product', $id)
            ->get();
        $product = Product::find($id);
        return view('admin.review-product.index')->with(['list' => $list, 'product' => $product]);
    }

    public function create($id)
    {
        $product = Product::with(
            [
                'product_stocks' => function($query) {
                    $query->select('*');
                }
            ]
        )
            ->where('id', $id)
            ->first();
        if ($product) {
            return view('admin.review-product.single', compact('product'));
        } else {
            return view('404');
        }
    }

    public function detail($id){
        $detail = Rating_Product::with(
            [
                'user' => function ($query) {
                    $query->select('id', 'name');
                }
            ]
        )
            ->where('id', $id)
            ->first();
        if($detail){
            $product = Product::with(
                [
                    'product_stocks' => function($query) {
                        $query->select('*');
                    }
                ]
            )
                ->where('id', $id)
                ->first();
            return view('admin.review-product.single', compact('detail', 'product'));
        } else{
            return view('404');
        }
    }

    public function store(Request $request){
        //id
        $id = $request->id;
        $status = $request->status;
        $images = '';
        if ($request->hasFile('images')) {
            $images = [];
            $count_file = count($request->images);
            $files = $request->file('images');
            for ($i = 0; $i < $count_file; $i++) {
                if (isset($files[$i]) && $files[$i]->getClientOriginalName() != '') {
                    $image = time() . '-' . $files[$i]->getClientOriginalName();
                    $image = str_replace(' ', '', $image);
                    $files[$i]->move(base_path() . '/images/product-review/', $image);
                    $images[] = $image;
                }
            }
        }
        if ($images != '') {
            $images = json_encode($images);
        }
        $product_variable = ProductStock::where('id', $request->product_variable_id)->first();
        $data = [
            'id_product' => $request->id_product,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'product_name' => $product_variable->title,
            'product_variable_id' => $product_variable->id,
            'rating' => $request->rate,
            'status' => $request->status,
            'already_bought' => ($request->already_bought) ? 1 : 0,
            'review' => $request->review,
            'images' => $images,
        ];

        if ($id > 0) {
            Rating_Product::where("id", $id)->update(['status' => $status]);
            $msg = "Review product has been updated";
            $url= route('admin.reviewProductDetail', array($id));
            Helpers::msg_move_page($msg,$url);
        } else {
            $rating_product = Rating_Product::create($data);
            $id = $rating_product->id;
            $msg = "Review product has been created";
            $url= route('admin.reviewProductDetail', array($id));
            Helpers::msg_move_page($msg,$url);
        }
    }
}
