<?php

namespace App\Http\Controllers;

use App\Constants\BaseConstants;
use App\Jobs\SendPreOrderDiscount;
use App\Jobs\SendWelcomeDiscount;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Discount_code;
use App\Models\EmailContact;
use App\Models\Order;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use App\Models\Rating_Product;
use App\Models\User;
use App\Models\Variable_Product;
use App\Models\ViettelPostDistrict;
use App\Models\ViettelPostProvince;
use App\Models\ViettelPostWard;
use App\Models\Wishlist;
use App\WebService\WebService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function pageNews()
    {
        $data = Category::join('join_category_post', 'categories.categoryID', 'join_category_post.id_category')
            ->join('post', 'join_category_post.id_post', 'post.id')
            ->where('post.status', BaseConstants::ACTIVE)
            ->select('post.*', 'categories.categoryName', 'categories.categorySlug', 'categories.categoryID')
            ->groupBy('post.id')
            ->orderByRaw('post.updated DESC')
            ->paginate(Helpers::get_option_minhnn('total-item-in-category'));
        return view('news.index', compact('data'));
    }

    public function productSearch(Request $request)
    {
        $query = ProductCategory::join(
            'join_category_product',
            'product_categories.id',
            'join_category_product.category_id'
        )
            ->join('products', 'join_category_product.product_id', 'products.id')
            ->join('product_stocks', 'product_stocks.product_id', 'products.id')
            ->where('products.status', BaseConstants::ACTIVE)
            ->where('products.updated_at', '<=', date('Y-m-d H:i:s'))
            ->groupBy('product_stocks.group_by_color')
            ->select(
                'product_stocks.id',
                'product_stocks.product_id',
                'product_stocks.title',
                'product_stocks.sku',
                'product_stocks.slug',
                'product_stocks.price_origin',
                'product_stocks.price_promotion',
                'product_stocks.thumbnail',
                'product_stocks.key_option',
                'product_stocks.variable_data',
                'product_stocks.product_id as parent_id',
                'product_stocks.group_related',
                'products.description',
                'products.content',
                'products.start_event',
                'products.end_event',
                'products.store_status',
                'products.status',
                'products.gallery_images',
                'products.seo_title',
                'products.seo_keyword',
                'products.seo_description',
                'products.group_variable_product',
                'products.size_fit',
                'products.fabric_care',
                'products.note',
                'products.favorite_customer_group',
                'product_categories.name as categoryName',
                'product_categories.slug as categorySlug',
                'product_categories.parent as categoryParent',
                'product_categories.id as categoryID',
                'product_categories.seo_title as seo_title_category'
            );

        if ($request->size != '') {
            $sizeVariable = Variable_Product::where('id', $request->size)->first();
            if ($sizeVariable) {
                $query->where('product_stocks.variable_data', 'LIKE', '%"size":"' . $sizeVariable->name . '"%');
            }
        }

        if ($request->color != '') {
            $colorVariable = Variable_Product::where('id', $request->color)->first();
            if ($colorVariable) {
                $query->where('product_stocks.variable_data', 'LIKE', '%"color":"' . (string)$colorVariable->name . '"%');
            }
        }

        if ($request->query_string != '') {
            $query->where('product_stocks.title', 'LIKE', '%' . $request->query_string . '%');
        }

        if ($request->orderby != "") {
            if ($request->orderby == "time") {
                $data = $query->paginate(Helpers::get_option_minhnn('total-item-in-category'));
            } else {
                $temp = $query->get();
                foreach ($temp as $item) {
                    if (strtotime($item->start_event) < time() && strtotime($item->end_event) > time()) {
                        $item->final_price = $item->price_promotion;
                    } else {
                        $item->final_price = $item->price_origin;
                    }
                }

                if ($request->orderby == 'pricea') {
                    $sort = collect($temp)->sortByDesc('final_price')->reverse()->toArray();
                } else {
                    $sort = collect($temp)->sortBy('final_price')->reverse()->toArray();
                }

                $data = (object)Helpers::arrayPaginator($sort, $request);
            }
        } else {
            $data = $query->paginate(Helpers::get_option_minhnn('total-item-in-category'));
        }

        return view('product.search', compact('data'));
    }

    public function productSearchAutocomplete(Request $request)
    {
        if (isset($request->query_string)) {
            $data = ProductStock::with(
                [
                    'product' => function ($query) {
                        $query->select('id', 'title', 'slug', 'start_event', 'end_event');
                    },
                    'product.categories' => function ($query) {
                        $query->select('id', 'name', 'slug');
                        $query->first();
                    }
                ]
            )
                ->whereHas(
                    'product',
                    function ($query) {
                        $query->where('status', BaseConstants::ACTIVE);
                        $query->where('updated_at', '<=', date('Y-m-d H:i:s'));
                    }
                )
                ->where('title', 'LIKE', '%' . $request->query_string . '%')
                ->orderBy('title', 'ASC')
                ->paginate(12);
            if ($data) {
                $suggestions = [];
                foreach ($data as $item) {
                    $item_array = [
                        "type" => "product",
                        "title" => $item->title,
                        "url" => route('single.detail', [$item->product->categories->slug, $item->slug])
                    ];
                    array_push($suggestions, $item_array);
                }
                return response()->json(['suggestions' => $suggestions]);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function viewOrder()
    {
        return view('view-order');
    }

    public function viewOrderDetail(Request $request)
    {
        $email = $request->order_email;
        $order_number = $request->order_number;
        $data = Order::where('code', $order_number)
            ->where('email', $email)
            ->first();
        if ($data) {
            return view('user.order-detail', compact('data'));
        } else {
            return redirect()->back()->withErrors("Order not found!");
        }
    }

    public function addToCart(Request $request)
    {
        $query = ProductStock::with(
            [
                'product' => function ($query) {
                    $query->select(
                        'id',
                        'start_event',
                        'end_event',
                        'store_status'
                    );
                }
            ]
        )
            ->where('product_id', $request->parent_id);
        if ($request->option != '') {
            $query->where('key_option', $request->option);
        } else {
            $query->where('id', $request->product_id);
        }
        $product = $query->first();
        if ($product) {
            $product_id = $product->id;
            $quantity = (int)$request->quantity;
            if ($product->product->store_status == BaseConstants::INACTIVE) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Vui lòng liên hệ để đặt hàng cho sản phẩm này.'
                    ]
                );
            }

            if ($product->stock < $quantity) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Sản phẩm trong kho không đủ.'
                    ]
                );
            }

            if (Auth::check()) {
                Cart::updateOrCreate(
                    [
                        'user_id' => Auth::user()->id,
                        'product_id' => $product_id,
                    ],
                    [
                        'quantity' => $quantity
                    ]
                );
            } else {
                $data = session('cart');
                if (!empty($data) && count($data) > 0) {
                    $checkItem = false;
                    foreach ($data as $key => $item) {
                        if ($item['product_id'] == $product_id) {
                            $item['quantity'] = $item['quantity'] + $quantity;
                            $data[$key] = $item;
                            $checkItem = true;
                            break;
                        }
                    }

                    if (!$checkItem) {
                        $item = [
                            'product_id' => $product_id,
                            'quantity' => $quantity
                        ];
                        $data[] = $item;
                    }
                } else {
                    $data[] = [
                        'product_id' => $product_id,
                        'quantity' => $quantity
                    ];
                }
                $request->session()->put('cart', $data);
            }
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Đã thêm vào giỏ hàng.'
                ]
            );

        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm!'
                ]
            );
        }
    }

    public function updateCart(Request $request)
    {
        $product_id = $request->product_id;
        $quantity = (int)$request->quantity;
        $product = Helpers::getPriceProductStock($product_id);
        if ($product->product->store_status == BaseConstants::INACTIVE) {
            return redirect()->route('cart')->withErrors('Please contact to order this product.');
        }

        if ($product->stock < $quantity) {
            return redirect()->route('cart')->withErrors('Product in stock is not enough.');
        }

        if (Auth::check()) {
            Cart::updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'product_id' => $product_id,
                ],
                [
                    'quantity' => $quantity
                ]
            );
        } else {
            $data = session('cart');
            if (count($data) > 0) {
                $checkItem = false;
                foreach ($data as $item) {
                    if ($item['product_id'] == $product_id) {
                        $checkItem = true;
                        $item['quantity'] = $item['quantity'] + $quantity;
                    }
                }

                if (!$checkItem) {
                    $item = [
                        'product_id' => $product_id,
                        'quantity' => $quantity
                    ];
                    $data[] = $item;
                }
            } else {
                $data[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity
                ];
            }
            $request->session()->put('cart', $data);
        }
        return redirect()->route('cart')->with('success_msg', 'Cart has been updated.');
    }

    public function removeCartItem(Request $request)
    {
        $product_id = $request->product_id;
        if (Auth::check()) {
            Cart::where('user_id', Auth::user()->id)
                ->where('product_id', $product_id)
                ->delete();
        } else {
            $data = session('cart');
            $i = 0;
            foreach ($data as $key => $cartItem) {
                if ($cartItem['product_id'] == $product_id) {
                    $i = $key;
                    break;
                }
            }
            unset($data[$i]);
            $request->session()->put('cart', $data);
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Cart item has been remove.'
            ]
        );
    }

    public function reloadCart(Request $request)
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::user()->id)->get()->toArray();
        } else {
            $cart = session('cart');
        }

        $html = '';
        $totalItem = 0;
        $currency = Helpers::get_option_minhnn('currency');
        foreach ($cart as $item) {
            $totalItem += $item['quantity'];
        }
        $html .= '<a class="icon_cart_tbn_a" href="' . route('cart') . '" >
            <svg class="icon-bag icon-bag-static" xmlns="http://www.w3.org/2000/svg" width="18" height="24"><g><path class="bag-path-static" d="M17.54 5.424a.47.47 0 0 1 .46.474v17.627a.47.47 0 0 1-.46.475H.46a.47.47 0 0 1-.46-.475V5.898a.47.47 0 0 1 .46-.474h4.795v-1.56C5.255 1.733 6.935 0 9 0c2.065 0 3.745 1.733 3.745 3.864v1.56zm-11.365 0h5.64v-1.56c0-1.608-1.264-2.915-2.82-2.915-1.555 0-2.82 1.307-2.82 2.915zm10.905.949h-4.335V8.61a.47.47 0 0 1-.46.475.47.47 0 0 1-.46-.475V6.373h-5.65V8.61a.47.47 0 0 1-.46.475.47.47 0 0 1-.46-.475V6.373H.92V23.05h16.16z"></path></g></svg><svg class="icon-bag icon-bag-hover" width="18" height="24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path class="bag-path-hover" d="M18.04 5.424h-4.795v-1.56C13.245 1.733 11.565 0 9.5 0 7.435 0 5.755 1.733 5.755 3.864v1.56H.96a.47.47 0 0 0-.46.474v17.627c0 .26.208.475.46.475h17.08a.47.47 0 0 0 .46-.475V5.898a.47.47 0 0 0-.46-.474z" fill="#000"></path><path d="M6.675 3.864C6.675 2.256 7.942.95 9.5.95c1.558 0 2.825 1.307 2.825 2.915v1.56h-5.65v-1.56z" fill="#FFF"></path></g></svg>
                <span class="basel-cart-totals">
                   (<span class="basel-cart-number badge badge-notify my-cart-badge">' . $totalItem . '</span>)
                </span>
        </a>';
        $html .= '<div class="dropdown-wrap-cat">
        <div class="dropdown-cat">
            <a href="javascript:void(0)" class="close-cart" onclick="closeCart()">
              <span>&times;</span>
            </a>
            <div class="widget woocommerce widget_shopping_cart">
                <div class="widget_shopping_cart_content">';
        if (count($cart) > 0) {
            $url_img_sp = 'images/product/';
            $html .= '<ul class="woocommerce-mini-cart cart_list product_list_widget ">';
            foreach ($cart as $cartItem) {
                if ($cartItem['quantity'] > 0) {
                    $product_id = $cartItem['product_id'];
                    $product = Helpers::getPriceProductStock($product_id);
                    if ($product) {
                        $name = $product->title;
                        $date_now = date("Y-m-d H:i:s");
                        if (!empty($product->start_event) && !empty($product->end_event)) {
                            $date_start_event = $product->start_event;
                            $date_end_event = $product->end_event;
                            $price_sale = $product->price_promotion;
                            $price_regular = $product->price_origin;
                            if (strtotime($date_now) < strtotime($date_end_event) && strtotime($date_now) > strtotime($date_start_event)) {
                                if ($price_regular != "" && $price_regular > $price_sale) {
                                    $price = $price_sale;
                                } else {
                                    $price = $price_regular;
                                }
                            } else {
                                $price = $price_regular;
                            }
                        } else {
                            $price_sale = $product->price_promotion;
                            $price_regular = $product->price_origin;
                            if ($price_regular != "" && $price_regular > $price_sale) {
                                $price = $price_sale;
                            } else {
                                $price = $price_regular;
                            }
                        }

                        $quantity = $cartItem['quantity'];
                        $thumbnail = $url_img_sp . $product->thumbnail;

                        $html .= '<li class="woocommerce-mini-cart-item mini_cart_item">
                        <a href="javascript:void(0)" class="remove remove_from_cart_button" onclick="removeCartItem(' . $product_id . ')">×</a>
                        <a class="cart_item_title" href="' . Helpers::get_permalink_by_id($product_id) . '">
                            <img width="300" height="300" src="' . $thumbnail . '" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt=""/>&nbsp;' . $name . '&nbsp;
                        </a>
                        <span class="quantity">' . $quantity . ' × <span class="woocommerce-Price-amount amount">' . WebService::formatMoney12($price) . ' <span class="woocommerce-Price-currencySymbol"></span>' . $currency . '</span></span>
                    </li>';
                    }
                }
            }
            $html .= '</ul>';
        }
        $cartTotal = Helpers::getCartTotal($cart);
        $html .= '<p class="woocommerce-mini-cart__total total"><strong>TOTAL:</strong> <span class="woocommerce-Price-amount amount" id="price_total_view2">' . WebService::formatMoney12($cartTotal) . '<span class="woocommerce-Price-currencySymbol">' . $currency . '</span></span></p>
                        <p class="woocommerce-mini-cart__buttons buttons"><a href="' . route('cart') . '" class="button checkout wc-forward mini-cart-checkout">CHECKOUT</a></p>
                    </div><!--widget_shopping_cart_content-->
                </div><!--widget_shopping_cart-->
            </div><!--dropdown-cat-->
        </div><!--dropdown-wrap-cat-->';
        return $html;
    }

    public function cart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::user()->id)->get()->toArray();
        } else {
            $cart = (session('cart')) ? session('cart') : [];
        }

        $cartTotal = (!empty($cart)) ? Helpers::getCartTotal($cart) : 0;
        return view('order.check-out', compact('cart', 'cartTotal'));
    }

    public function shop(Request $request)
    {
        $query = ProductStock::join('products', 'products.id', 'product_stocks.product_id')
            ->join('join_category_product', 'join_category_product.product_id', 'products.id')
            ->join('product_categories', 'product_categories.id', 'join_category_product.category_id')
            ->where('products.status', BaseConstants::ACTIVE)
            ->orderBy('products.sort', 'DESC')
            ->orderBy('products.updated_at', 'DESC')
            ->groupBy('products.id')
            ->select(
                'product_stocks.*',
                'products.start_event',
                'products.end_event',
                'products.rating',
                'products.total_rating',
                'products.store_status',
                'product_categories.name as categoryName',
                'product_categories.slug as categorySlug',
                'product_categories.id as categoryID'
            );

        if ($request->orderby != "") {
            if ($request->orderby == "time") {
                $query->orderBy('product_stocks.created_at', 'DESC');
                $data = $query->paginate(Helpers::get_option_minhnn('total-item-in-category'));
            } else {
                $temp = $query->get();
                foreach ($temp as $item) {
                    if (strtotime($item->start_event) < time() && strtotime($item->end_event) > time()) {
                        $item->final_price = $item->price_promotion;
                    } else {
                        $item->final_price = $item->price_origin;
                    }
                }

                if ($request->orderby == 'pricea') {
                    $sort = collect($temp)->sortByDesc('final_price')->reverse()->toArray();
                } else {
                    $sort = collect($temp)->sortBy('final_price')->reverse()->toArray();
                }

                $data = (object)Helpers::arrayPaginator($sort, $request);
            }
        } else {
            $data = $query->paginate(Helpers::get_option_minhnn('total-item-in-category'));
        }

        return view('product.single-page', compact('data'));
    }

    public function addToWishList(Request $request)
    {
        $check_wishlist = Wishlist::where('product_id', $request->product_id)
            ->where('user_id', Auth::user()->id)
            ->get();
        if (count($check_wishlist) > 0) {
            return false;
        } else {
            Wishlist::create(
                [
                    'product_id' => $request->product_id,
                    'user_id' => Auth::user()->id
                ]
            );
            return true;
        }
    }

    public function checkDiscountCode(Request $request)
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::user()->id)->get()->toArray();
        } else {
            $cart = session('cart');
        }

        // cart total
        $cart_total = 0;
        $subtotal = 0;
        foreach ($cart as $cart_item) {
            $product = Helpers::getPriceProductStock($cart_item['product_id']);
            if ($product) {
                $price = $product->final_price;
                $quantity = $cart_item['quantity'];
                $cart_total += $quantity * $price;
                $subtotal += $quantity * $price;
            }
        }

        $code = $request->code_discount;
        $currency = Helpers::get_option_minhnn('currency');
        $checkCode = Discount_code::where('code', $code)
            ->where('start_date', '<', date('Y-m-d H:i:s'))
            ->where('expired', '>', date('Y-m-d H:i:s'))
            ->where('status', BaseConstants::ACTIVE)
            ->first();
        if ($checkCode) {
            $group_code = ($checkCode->group_code != "") ? json_decode($checkCode->group_code) : [];
            if (count($group_code) > 0) {
                $price_discount_item = $this->getPriceDiscountItem($cart, $cart_total, $group_code);
            } else {
                $price_discount_item = 0;
            }

            if ($checkCode->apply_for_order > 0 && $cart_total < $checkCode->apply_for_order) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Voucher required minimum order value is " . WebService::formatMoney12(
                                $checkCode->apply_for_order
                            ) . $currency
                    ]
                );
            }

            $price_discount_total = $cart_total - $price_discount_item;

            $country_id = $request->country;
            $country = Country::where('id', $country_id)
                ->select('id', 'name', 'iso2')
                ->first();
            $cart_tax = Helpers::get_option_minhnn('cart-tax');
            $currency = Helpers::get_option_minhnn('currency');
            $delivery = 0;
            if ($country) {
                if ($country->iso2 == 'VN' || strtoupper($country->iso2) == 'vn') {
                    $cart_tax = Helpers::get_option_minhnn('cart-tax-for-vn');
                    if ($price_discount_total > Helpers::get_option_minhnn('free-ship-for-vietnamese-bill')) {
                        $delivery = 0;
                    } else {
                        $delivery = Helpers::get_option_minhnn('shipping-in-vietnam');
                    }
                } else {
                    $cart_tax = Helpers::get_option_minhnn('cart-tax');
                    if ($price_discount_total > Helpers::get_option_minhnn('free-ship-for-international-bill')) {
                        $delivery = 0;
                    } else {
                        $delivery = Helpers::get_option_minhnn('international-shipping');
                    }
                }
            }

            $tax = $price_discount_total * $cart_tax / 100;
            $price_discount_total = $price_discount_total + $tax + $delivery;
            $discount = $price_discount_item;

            $price_discount_total = WebService::formatMoney12($price_discount_total) . $currency;
            $tax = WebService::formatMoney12($tax) . $currency;
            $discount = WebService::formatMoney12($discount) . $currency;
            $delivery = WebService::formatMoney12($delivery) . $currency;

            return response()->json(
                [
                    'success' => true,
                    'data' => [
                        'price_discount' => $price_discount_total,
                        'discount' => $discount,
                        'tax' => $tax,
                        'delivery' => $delivery,
                    ]
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Discount code incorrect.'
                ]
            );
        }
    }

    protected function getPriceDiscountItem($cart, $cart_total, $group_code)
    {
        $price_discount_item = 0;
        foreach ($group_code as $group_item) {
            if (count($group_item['apply_products']) > 0) {
                $validateCart = true;
                foreach ($cart as $cart_item) {
                    if (!in_array($cart_item->product_id, $group_item['apply_products'])) {
                        $validateCart = false;
                    }
                }

                if ($validateCart) {
                    if ($group_item['percent'] != 0) {
                        $price_discount_item += $cart_total * $group_item['percent'] / 100;
                    } else {
                        $price_discount_item += $group_item['discount_money'];
                    }
                }
            } elseif (count($group_item['except_products']) > 0) {
                $validateCart = true;
                foreach ($cart as $cart_item) {
                    if (in_array($cart_item->product_id, $group_item['except_products'])) {
                        $validateCart = false;
                    }
                }

                if ($validateCart) {
                    if ($group_item['percent'] != 0) {
                        $price_discount_item += $cart_total * $group_item['percent'] / 100;
                    } else {
                        $price_discount_item += $group_item['discount_money'];
                    }
                }
            } elseif ($group_item['percent'] != 0) {
                $price_discount_item += $cart_total * $group_item['percent'] / 100;
            } else {
                $price_discount_item += $group_item['discount_money'];
            }
        }
        return $price_discount_item;
    }

    public function getShippingFee(Request $request)
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::user()->id)->get()->toArray();
        } else {
            $cart = session('cart');
        }

        // cart total
        $cart_total = 0;
        $subtotal = 0;
        foreach ($cart as $cart_item) {
            $product = Helpers::getPriceProductStock($cart_item['product_id']);
            if ($product) {
                $price = $product->final_price;
                $quantity = $cart_item['quantity'];
                $cart_total += $quantity * $price;
                $subtotal += $quantity * $price;
            }
        }

        $country_id = $request->data;
        $country = Country::where('id', $country_id)
            ->select('id', 'name', 'iso2')
            ->first();
        $currency = Helpers::get_option_minhnn('currency');
        if ($country) {
            if ($country->iso2 == 'VN' || strtoupper($country->iso2) == 'vn') {
                $cart_tax = Helpers::get_option_minhnn('cart-tax-for-vn');
                if ($cart_total > Helpers::get_option_minhnn('free-ship-for-vietnamese-bill')) {
                    $delivery = 0;
                } else {
                    $delivery = Helpers::get_option_minhnn('shipping-in-vietnam');
                }
            } else {
                $cart_tax = Helpers::get_option_minhnn('cart-tax');
                if ($cart_total > Helpers::get_option_minhnn('free-ship-for-international-bill')) {
                    $delivery = 0;
                } else {
                    $delivery = Helpers::get_option_minhnn('international-shipping');
                }
            }

            $shipping_fee_html = WebService::formatMoney12($delivery) . $currency;
            $tax = $cart_total * $cart_tax / 100;
            $total = WebService::formatMoney12($cart_total + $tax + $delivery);
            $tax_html = WebService::formatMoney12($tax);
            return response()->json(
                [
                    'success' => true,
                    'data' => [
                        'shipping_fee_html' => $shipping_fee_html,
                        'total' => $total,
                        'shipping_fee' => $delivery,
                        'tax_html' => $tax_html
                    ]
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Country is required.'
                ]
            );
        }
    }

    public function getStates(Request $request)
    {
        $country_id = $request->country;
        if ($country_id == BaseConstants::VIETNAM_COUNTRY_ID) {
            $states = ViettelPostProvince::orderBy('name', 'ASC')
                ->get(['id', 'name']);
        } else {
            $states = State::where('country_id', $country_id)
                ->orderBy('name', 'ASC')
                ->get(['id', 'name']);
        }

        return response()->json(
            [
                'success' => true,
                'data' => $states
            ]
        );
    }

    public function getCities(Request $request)
    {
        $state_id = $request->state;
        $cities = City::where('state_id', $state_id)
            ->orderBy('name', 'ASC')
            ->get(['id', 'name']);
        return response()->json(
            [
                'success' => true,
                'data' => $cities
            ]
        );
    }

    public function getDistricts(Request $request)
    {
        $province_id = $request->state;
        $districts = ViettelPostDistrict::where('province_id', $province_id)
            ->orderBy('name', 'ASC')
            ->get(['id', 'name']);
        return response()->json(
            [
                'success' => true,
                'data' => $districts
            ]
        );
    }

    public function getWards(Request $request)
    {
        $district_id = $request->district;
        $wards = ViettelPostWard::where('district_id', $district_id)
            ->orderBy('name', 'ASC')
            ->get(['id', 'name']);
        return response()->json(
            [
                'success' => true,
                'data' => $wards
            ]
        );
    }

    public function category($slug, Request $request)
    {
        $categories = Category::where('categorySlug', $slug)
            ->where('status', BaseConstants::ACTIVE)
            ->first();
        if ($categories) {
            $data = Category::join('join_category_post', 'categories.categoryID', 'join_category_post.id_category')
                ->join('post', 'join_category_post.id_post', 'post.id')
                ->where('categories.categorySlug', $slug)
                ->where('post.status', BaseConstants::ACTIVE)
                ->orderByRaw('post.updated DESC')
                ->select(
                    'post.*',
                    'categories.categoryName',
                    'categories.categorySlug',
                    'categories.categoryDescription',
                    'categories.categoryID'
                )
                ->paginate(Helpers::get_option_minhnn('total-item-in-category'));
            return view('news.category', compact('categories', 'data'));
        } else {
            $productCategory = ProductCategory::where('slug', $slug)
                ->where('status', BaseConstants::ACTIVE)
                ->first();
            if ($productCategory) {
                $query = ProductStock::join('products', 'products.id', 'product_stocks.product_id')
                    ->join('join_category_product', 'join_category_product.product_id', 'products.id')
                    ->join('product_categories', 'product_categories.id', 'join_category_product.category_id')
                    ->where('products.status', BaseConstants::ACTIVE)
                    ->where('product_categories.slug', $slug)
                    ->select(
                        'product_stocks.*',
                        'products.end_event',
                        'products.start_event',
                        'products.rating',
                        'products.total_rating',
                        'products.store_status',
                        'product_categories.id as categoryID',
                        'product_categories.name as categoryName',
                        'product_categories.slug as categorySlug'
                    );

                if ($request->orderby != '') {
                    if ($request->orderby == 'time') {
                        $query->orderBy('product_stocks.created_at', 'DESC');
                        $data = $query->paginate(Helpers::get_option_minhnn('total-item-in-category'));
                    } else {
                        $temp = $query->get();
                        foreach ($temp as $item) {
                            if (strtotime($item->start_event) < time() && strtotime($item->end_event) > time()) {
                                $item->final_price = $item->price_promotion;
                            } else {
                                $item->final_price = $item->price_origin;
                            }
                        }

                        if ($request->orderby == 'pricea') {
                            $sort = collect($temp)->sortByDesc('final_price')->reverse()->toArray();
                        } else {
                            $sort = collect($temp)->sortBy('final_price')->reverse()->toArray();
                        }
                        $data = Helpers::arrayPaginator($sort, $request);
                    }
                } else {
                    $query->orderBy('product_stocks.sort', 'DESC');
                    $query->orderBy('product_stocks.created_at', 'DESC');
                    $data = $query->paginate(Helpers::get_option_minhnn('total-item-in-category'));
                }

                return view('product.category', compact('productCategory', 'data'));
            } else {
                $page = Page::where('slug', $slug)
                    ->where('status', BaseConstants::ACTIVE)
                    ->first();
                if ($page) {
                    return view('page.index', compact('page'));
                } else {
                    return view('errors.404');
                }
            }
        }
    }

    public function singleDetail($slug1, $slug2)
    {
        $post = Category::join('join_category_post', 'categories.categoryID', 'join_category_post.id_category')
            ->join('post', 'join_category_post.id_post', 'post.id')
            ->where('post.slug', $slug2)
            ->where('categories.categorySlug', $slug1)
            ->where('post.status', BaseConstants::ACTIVE)
            ->select(
                'post.*',
                'categories.categoryName',
                'categories.categorySlug',
                'categories.categoryParent',
                'categories.categoryID',
                'categories.seo_title as seo_title_category',
                'categories.seo_keyword as seo_keyword_category',
                'categories.seo_description as seo_description_category'
            )
            ->first();
        if ($post) {
            $related_posts = Category::join('join_category_post', 'categories.categoryID', 'join_category_post.id_category')
                ->join('post', 'join_category_post.id_post', 'post.id')
                ->where('post.slug', $slug2)
                ->where('categories.categorySlug', $slug1)
                ->where('post.status', BaseConstants::ACTIVE)
                ->where('post.id', '!=', $post->id)
                ->select(
                    'post.*',
                    'categories.categoryName',
                    'categories.categorySlug',
                    'categories.categoryParent',
                    'categories.categoryID',
                    'categories.seo_title as seo_title_category',
                    'categories.seo_keyword as seo_keyword_category',
                    'categories.seo_description as seo_description_category'
                )
                ->orderBy('post.updated', 'DESC')
                ->take(Helpers::get_option_minhnn('total-item-related'))
                ->get();
            return view('news.single', compact('post', 'related_posts'));
        } else {
            $product = ProductCategory::join(
                'join_category_product',
                'product_categories.id',
                'join_category_product.category_id'
            )
                ->join('products', 'join_category_product.product_id', 'products.id')
                ->join('product_stocks', 'product_stocks.product_id', 'products.id')
                ->where('product_stocks.slug', $slug2)
                ->where('product_categories.slug', $slug1)
                ->where('products.status', BaseConstants::ACTIVE)
                ->first(
                    [
                        'product_stocks.id',
                        'product_stocks.product_id',
                        'product_stocks.title',
                        'product_stocks.sku',
                        'product_stocks.slug',
                        'product_stocks.price_origin',
                        'product_stocks.price_promotion',
                        'product_stocks.thumbnail',
                        'product_stocks.key_option',
                        'product_stocks.variable_data',
                        'product_stocks.product_id as parent_id',
                        'products.description',
                        'products.content',
                        'products.start_event',
                        'products.end_event',
                        'products.store_status',
                        'products.status',
                        'products.gallery_images',
                        'products.seo_title',
                        'products.seo_keyword',
                        'products.seo_description',
                        'products.group_variable_product',
                        'product_categories.name as categoryName',
                        'product_categories.slug as categorySlug',
                        'product_categories.parent as categoryParent',
                        'product_categories.id as categoryID',
                        'product_categories.seo_title as seo_title_category'
                    ]
                );
            if ($product) {
                $review_product = Rating_Product::where('product_id', $product->id)
                    ->where('status', BaseConstants::ACTIVE)
                    ->orderBy('rating', 'DESC')
                    ->offset(0)
                    ->limit(10)
                    ->get();
                $related_products = ProductCategory::join(
                    'join_category_product',
                    'product_categories.id',
                    'join_category_product.category_id'
                )
                    ->join('products', 'join_category_product.product_id', 'products.id')
                    ->join('product_stocks', 'product_stocks.product_id', 'products.id')
                    ->where('product_stocks.id', '<>', $product->id)
                    ->where('product_categories.slug', $slug1)
                    ->where('products.status', BaseConstants::ACTIVE)
                    ->orderBy('product_stocks.sort', 'DESC')
                    ->orderBy('product_stocks.created_at', 'DESC')
                    ->take(Helpers::get_option_minhnn('total-item-related'))
                    ->get(
                        [
                            'product_stocks.*',
                            'products.start_event',
                            'products.end_event',
                            'products.rating',
                            'products.total_rating',
                            'products.store_status',
                            'product_categories.name as categoryName',
                            'product_categories.slug as categorySlug',
                            'product_categories.id as categoryID'
                        ]
                    );

                return view(
                    'product.single',
                    compact(
                        'product',
                        'review_product',
                        'related_products',
                    )
                );
            } else {
                return view('errors.404');
            }
        }
    }

    public function bestSeller(Request $request)
    {
        $query = ProductStock::join('products', 'products.id', 'product_stocks.product_id')
            ->join('join_category_product', 'join_category_product.product_id', 'products.id')
            ->join('product_categories', 'product_categories.id', 'join_category_product.category_id')
            ->where('products.status', BaseConstants::ACTIVE)
            ->where('products.best_seller', BaseConstants::ACTIVE)
            ->where('products.updated_at', '<', date('Y-m-d H:i:s'))
            ->orderBy('product_stocks.sort', 'DESC')
            ->orderBy('product_stocks.created_at', 'DESC')
            ->select(
                'product_stocks.*',
                'products.start_event',
                'products.end_event',
                'products.rating',
                'products.total_rating',
                'products.store_status',
                'product_categories.name as categoryName',
                'product_categories.slug as categorySlug',
                'product_categories.id as categoryID'
            );

        if ($request->orderby != '') {
            if ($request->orderby == 'time') {
                $query->orderBy('product_stocks.updated_at', 'DESC');
                $data = $query->paginate(Helpers::get_option_minhnn('total-item-in-category'));
            } else {
                $temp = $query->get();
                foreach ($temp as $item) {
                    if (strtotime($item->start_event) < time() && strtotime($item->end_event) > time()) {
                        $item->final_price = $item->price_promotion;
                    } else {
                        $item->final_price = $item->price_origin;
                    }
                }

                if ($request->orderby == 'pricea') {
                    $sort = collect($temp)->sortByDesc('final_price')->reverse()->toArray();
                } else {
                    $sort = collect($temp)->sortBy('final_price')->reverse()->toArray();
                }
                $data = Helpers::arrayPaginator($sort, $request);
            }
        } else {
            $query->orderBy('products.sort', 'DESC');
            $query->orderBy('product_stocks.updated_at', 'DESC');
            $data = $query->paginate(Helpers::get_option_minhnn('total-item-in-category'));
        }
        return view('product.single-page', compact('data'));
    }

    public function reviewProduct(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            return redirect()->back()->with('error', "Please login to review this product.");
        }
        $check_user_buy = UserAlreadyBoughtProduct::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();
        if ($check_user_buy) {
            $check_user_comment = Rating_Product::where('user_id', $user->id)
                ->where('product_id', $request->product_id)
                ->first();
            if ($check_user_comment) {
                return redirect()->back()->with('error', "You have already rated this product.");
            } else {
                $product = ProductStock::where('id', $request->product_id)
                    ->first();
                if ($product) {
                    Rating_Product::create(
                        [
                            'product_id' => $product->product_id,
                            'user_id' => $user->id,
                            'name' => $user->first_name . ' ' . $user->last_name,
                            'product_name' => $product->title,
                            'product_variable_id' => $product->id,
                            'rating' => $request->user_rating,
                            'status' => BaseConstants::ACTIVE,
                            'already_bought' => BaseConstants::ACTIVE,
                            'link_product' => $request->current_link,
                            'review' => $request->txt_review,
                        ]
                    );
                    return redirect()->back()->with('success', "Thanks you for your review.");
                } else {
                    return redirect()->back()->with('error', "Product not found!.");
                }
            }
        } else {
            return redirect()->back()->with(
                'error',
                "We are sorry, only customers who purchased this product can write a review."
            );
        }
    }

    public function checkRegister(Request $request)
    {

        if ($request->email) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return true;
            }
        }
        if ($request->phone) {
            $user = User::where('phone', $request->phone)->first();
            if ($user) {
                return true;
            }
        }
    }

    public function moreReviews(Request $request)
    {
        $html = '';
        $product_id = $request->media_id;
        $current_page = $request->current_page;
        $next_page = $request->next_page;
        $offset = $current_page * 10;
        $review_product = Rating_Product::where('product_id', $product_id)
            ->where('status', BaseConstants::ACTIVE)
            ->orderBy('rating', 'DESC')
            ->offset($offset)
            ->limit(10)
            ->get();
        if ($review_product) {
            $total_item = 0;
            $html .= '<ul class="lst_rv">';
            foreach ($review_product as $row) {
                $total_item++;
                $rating = $row->rating * 2 * 10;
                $html .= '<li>
                            <div class="name-reviewer">
                                <b><i class="fa fa-user" aria-hidden="true"></i> ' . $row->name . '</b>
                            </div>
                            <div class="review-rate">
                                <div class="jq-ry-container" style="width: 90px;"><div class="jq-ry-group-wrapper"><div class="jq-ry-normal-group jq-ry-group"><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="gray"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="gray" style="margin-left: 0px;"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="gray" style="margin-left: 0px;"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="gray" style="margin-left: 0px;"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="gray" style="margin-left: 0px;"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg></div><div class="jq-ry-rated-group jq-ry-group" style="width: ' . $rating . '%;"><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="#f39c12"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="#f39c12" style="margin-left: 0px;"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="#f39c12" style="margin-left: 0px;"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="#f39c12" style="margin-left: 0px;"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="18px" height="18px" fill="#f39c12" style="margin-left: 0px;"><polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon></svg></div></div></div>
                            </div>
                            <div class="review-content">
                                <p>' . $row->review . '</p>
                            </div>
                        </li>';
            }
            $next_page_s = $next_page + 1;
            $current_page_s = $current_page + 1;
            if ($total_item == 10) {
                $html .= '</ul>
                    <div class="paging"><button class="btn btn-more-review btn-lg btn-block" onclick="more_reviews(' . $product_id . ',' . $current_page_s . ',' . $next_page_s . ');">Get more 10 reviews</button></div>
                  </div>
                </div>';
            }
        }
        return $html;
    }
}
