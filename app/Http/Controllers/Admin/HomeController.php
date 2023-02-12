<?php

namespace App\Http\Controllers\Admin;

use App\Libraries\Helpers;
use App\Models\Theme;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.home');
    }

    public function updateCategorySupportSearch()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $categorySlug = Helpers::getCategoryProductSlug($product->id);
            if ($categorySlug != '') {
                Product::where('id', $product->id)->update(
                    [
                        'category_support_search' => $categorySlug
                    ]
                );
            }
        }
        return redirect()->route('admin.dashboard')->with(['success' => 'Đã update lại slug danh mục trong sản phẩm.']);
    }
}
