<?php
/**
 * Author: bavuong0810@gmail.com
 * Date: 06/12/2022
 * Time: 15:50 PM
 */

namespace App\Http\Controllers;

use App\Constants\BaseConstants;
use App\Models\OrderDetail;
use App\WebService\WebService;
use App\Models\Cart;
use App\Models\Discount_code;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Tasks\TelegramTask;

class PaymentController extends Controller
{
    private $telegramTask;

    public function __construct()
    {
        $this->telegramTask = new TelegramTask();
    }
    public function payment(Request $request)
    {
        $user_id = 0;
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $cart = Cart::where('user_id', $user_id)->get()->toArray();
        } else {
            $cart = session('cart');
        }

        if (empty($cart) || count($cart) == 0) {
            return redirect()->route('cart');
        }

        $this->validate(
            $request,
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
                'address' => 'required',
                'zipcode' => 'required'
            ],
            [
                'first_name.required' => 'Họ và tên là trường bắt buộc.',
                'last_name.required' => 'Họ và tên là trường bắt buộc.',
                'phone.required' => 'Số điện thoại là trường bắt buộc.',
                'email.required' => 'Email là trường bắt buộc.',
                'email.email' => 'Email không đúng định dạng',
                'address.required' => 'Địa chỉ là trường bắt buộc.',
                'zipcode.required' => 'Mã bưu chính là trường bắt buộc.'
            ]
        );

        $discountData = '';
        $subtotal = Helpers::getCartTotal($cart);
        if ($request->discount_code != '') {
            $discountResult = Helpers::getFinalPriceWithDiscountCode($request->discount_code, $subtotal);
            if ($discountResult['finalPrice'] != $subtotal) {
                $discountData = [
                    'data' => $discountResult['discountCode'],
                    'totalDiscount' => $subtotal - $discountResult['finalPrice']
                ];
            }
        }

        $taxPercent = Helpers::get_option_minhnn('cart-tax');
        $tax = $subtotal * $taxPercent / 100;
        $cartTotal = $subtotal + $tax;

        $code = Helpers::generateCodeOrder();
        $checkCode = false;
        while(!$checkCode) {
            $orderCode = Order::where('code', $code)->first();
            if ($orderCode) {
                $code = Helpers::generateCodeOrder();
            } else {
                $checkCode = true;
            }
        }

        $data = [
            'user_id' => $user_id,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address_id' => 0,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'subtotal' => $subtotal,
            'shipping_fee' => 0,
            'tax' => $tax,
            'total' => $cartTotal,
            'discount_data' => ($discountData != '') ? json_encode($discountData) : null,
            'code' => $code,
            'status' => Order::Status['New'],
            'note' => $request->message
        ];

        $order = Order::create($data);

        if ($order) {
            $order_id = $order->id;
            foreach ($cart as $cart_item) {
                $product = Helpers::getPriceProductStock($cart_item['product_id']);
                if ($product) {
                    OrderDetail::create(
                        [
                            'order_id' => $order_id,
                            'product_id' => $cart_item['product_id'],
                            'product_parent_id' => $product->product_id,
                            'quantity' => $cart_item['quantity'],
                            'price' => $product->final_price,
                            'price_promotion' => $product->price_promotion,
                            'price_origin' => $product->price_origin,
                            'total' => $product->final_price * $cart_item['quantity'],
                            'product_name' => $product->title,
                            'product_sku' => $product->sku,
                            'product_thumbnail' => $product->thumbnail,
                            'product_variants' => $product->variable_data
                        ]
                    );
                }
            }

            if ($discountData != '') {
                $discountType = $discountData['data']->type;
                if ($discountType == Discount_code::Type['OneTime']) {
                    Discount_code::where('code', $request->discount_code)
                        ->update(
                            [
                                'status' => BaseConstants::INACTIVE,
                                'used_at' => date('Y-m-d H:i:s')
                            ]
                        );
                }
            }

            $subtotal = WebService::formatMoney12($order->subtotal) . ' VNĐ';
            $shipping_fee = WebService::formatMoney12($order->shipping_fee) . ' VNĐ';
            $tax = WebService::formatMoney12($order->tax) . ' VNĐ';
            $total = WebService::formatMoney12($order->total) . ' VNĐ';
            $adminLink = route('admin.orderDetail', $order->id);
            $text = "<b>[Production]</b> New order\n"
                . "<b>Order Code: </b>$order->code\n"
                . "<b>Trạng thái: </b>Đơn hàng mới\n"
                . "<b>Họ và tên: </b>$order->first_name $order->last_name\n"
                . "<b>Email: </b>$order->email\n"
                . "<b>Số điện thoại: </b>$order->phone\n"
                . "<b>Địa chỉ: </b>$order->address\n"
                . "<b>Ghi chú: </b>$order->note\n"
                . "<b>Tổng cộng: </b>$subtotal\n"
                . "<b>Phí vận chuyển: </b>$shipping_fee\n"
                . "<b>Thuế: </b>$tax\n"
                . "<b>Thành tiền: </b>$total\n"
                . "<b>Admin link: </b>$adminLink\n";

            $result = $this->telegramTask->sendMessage($text);
            return redirect()->route('completeOrder')->with('data', ['user_id' => $user_id]);
        } else {
            return redirect()->back()->withInput()->withErrors('Không tạo được đơn hàng. Quý khách vui lòng thử lại.');
        }
    }
}
