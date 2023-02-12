<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstants;
use App\Models\OrderReturnHistory;
use App\Models\VtcPayTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductStock;
use App\Libraries\Helpers;
use App\Exports\OrderExport;
use Illuminate\Support\Facades\Mail;
use PDF;
use ZanySoft\Zip\Zip;

class OrderController extends Controller
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

    public function listOrders()
    {
        $data_order = Order::with(
            [
                'order_details' => function ($query) {
                    $query->select('*');
                }
            ]
        )
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        return view('admin.orders.index')->with(['data_order' => $data_order]);
    }

    public function excelOrder(Request $request)
    {
        $data_order = Order::orderByDesc('id')
            ->get();
        if ($request->search_title != '' || $request->from_date != '' || $request->to_date != '') {
            $code = $request->search_title;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            if ($code != ''):
                $data_order->where('code', 'like', "%" . $code . "%");
            endif;
            if ($from_date != ''):
                $data_format = date('Y-m-d', strtotime($from_date));
                $data_order->where('created_at', '>=', $data_format);
            endif;
            if ($to_date != ''):
                $to_date = date('Y-m-d', strtotime($to_date));
                $data_order->where('created_at', '<=', $to_date);
            endif;
        }
        if ($request->has('order_status') && $request->order_status != '') {
            $status = $request->order_status;
            $data_order->where('status', $status);
        }
        $arr = array();
        $cart_code = $cart_date = $cart_hoten = $cart_email = $cart_phone = $cart_address = $shipping_free = $province_name = $district_name = $ward_name = "";
        $order_details = array();
        $detail = "";
        $cart_id = 0;

        foreach ($data_order as $row) {
            $cart_id = $row->cart_id;
            $cart_code = $row->cart_code;
            $cart_date = $row->updated;
            $cart_hoten = $row->cart_hoten;
            $cart_email = $row->cart_email;
            $cart_phone = $row->cart_phone;
            $cart_address = $row->cart_address;
            $shipping_free = $row->shipping_fee;

            $province_name = $row->cart_province;
            $district_name = $row->cart_district;
            $ward_name = $row->cart_ward;

            $cart_pay = "";
            switch ($row->cart_pay_method) {
                case 'cod':
                    $cart_pay = "COD";
                    break;
                default:
                    break;
            }
            $order_status = "";
            switch ($row->cart_status) {
                case '1':
                    $order_status = "Mới đặt";
                    break;
                case '2':
                    $order_status = "Đã xác nhận";
                    break;
                case '3':
                    $order_status = "Đang giao";
                    break;
                case '4':
                    $order_status = "Hoàn thành";
                    break;
                case '5':
                    $order_status = "Đã hủy";
                    break;
                default:
                    break;
            }
            //$order_details = $row->order_details;
            $order_details = OrderDetail::where('order_id', $cart_id)->orderBy('updated_at', 'DESC')->get();
            if (isset($order_details) && $order_details):
                try {
                    $j = 0;
                    $qty = 0;
                    $k = 1;
                    $product_sku = "";
                    $product_name = "";
                    $product_quantity = 1;
                    $product_price = 0;
                    $product_total = 0;
                    $product_id = 0;
                    $gift_item = "";
                    $gift = "";
                    $girt_title = "";
                    $product_show = "";
                    foreach ($order_details as $detail):
                        $k++;
                        $product_sku = $detail->product_sku;
                        $product_name = $detail->product_name;
                        $product_quantity = $detail->quantity;
                        $product_price = $detail->price;
                        $product_id = $detail->product_id;
                        $gift_item = "";
                        $gift = "";
                        $girt_title = "";
                        if ($product_id > 0):
                            $product_show = Product::where('id', $product_id)->where('status', 0)->first();
                            if ($product_show):
                                if (isset($product_show->gift) && $product_show->gift != ''):
                                    $gift = json_decode($product_show->gift, true);
                                    if (isset($gift['gift_products'])):
                                        $product_gift = "";
                                        foreach ($gift['gift_products'] as $gift_item):
                                            $product_gift = Product::where('id', $gift_item)
                                                ->where('status', 0)
                                                ->first();
                                            if ($product_gift):
                                                $girt_title .= $product_gift->title . PHP_EOL;
                                            endif;
                                        endforeach;
                                    endif;
                                endif;
                            endif;
                        endif;
                        //dd($girt_title);
                        $product_total = $detail->total;
                        $o_arr = array(
                            'Order_Code' => $cart_code,
                            'Order_Date' => $cart_date,
                            'Customer' => $cart_hoten,
                            'Email' => $cart_email,
                            'Tel' => $cart_phone,
                            'Address' => $cart_address,
                            'Province' => $province_name,
                            'District' => $district_name,
                            'Ward' => $ward_name,
                            'Product_Sku' => $product_sku,
                            'Product_Name' => $product_name,
                            'Product_price' => $product_price,
                            'Product_quantity' => $product_quantity,
                            'Product_total' => $product_total,
                            'Product_gift' => $girt_title,
                            'Pay_Method' => $cart_pay,
                            'Shipping_Fee' => $shipping_free,
                            'Status' => $order_status
                        );
                        array_push($arr, $o_arr);
                    endforeach;
                } catch (\Exception $ex) {
                    echo "Lỗi:" . $ex;
                }
            endif;
        } // endforeach
        //print_r($arr);
        //exit();
        return (new OrderExport($arr))->download('Order-Yoy-' . date('d-m-Y H:i:s') . '.xlsx');
    }

    public function searchOrder(Request $request)
    {
        $data_order = Order::orderByDesc('id');
        if ($request->search_title != '' || $request->from_date != '' || $request->to_date != '') {
            $code = $request->search_title;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            if ($code != ''):
                $data_order->where('code', 'like', "%" . $code . "%");
            endif;
            if ($from_date != ''):
                $data_format = date('Y-m-d', strtotime($from_date));
                $data_order->where('created_at', '>=', $data_format);
            endif;
            if ($to_date != ''):
                $to_date = date('Y-m-d', strtotime($to_date));
                $data_order->where('created_at', '<=', $to_date);
            endif;
        }
        if ($request->has('order_status') && $request->order_status != '') {
            $status = $request->order_status;
            $data_order->where('status', $status);
        }
        //phân trang
        $data_order = $data_order->paginate(20);
        return view('admin.orders.index')->with(['data_order' => $data_order]);
    }

    public function orderDetail($id)
    {
        $order_detail = Order::with(
            [
                'order_details' => function ($q) {
                    $q->select('*');
                }
            ]
        )
            ->where('id', $id)
            ->first();
        if ($order_detail) {
            return view('admin.orders.single', compact('order_detail'));
        } else {
            return view('404');
        }
    }

    public function storeOrderDetail(Request $request)
    {
        $id = $request->id;

        //xử lý content
        $content = $request->admin_note;
        $status_order = (int)$request->order_status;
        if ($id > 0) {
            //update
            $data = array(
                "admin_note" => $content,
                "status" => $status_order,
            );
            Order::where("id", $id)->update($data);
            $msg = "Order has been Updated";
            $url = route('admin.orderDetail', $id);
            Helpers::msg_move_page($msg, $url);
        }
    }

    public function printOrder($id)
    {
        $order = Order::with(
            [
                'order_details' => function ($q) {
                    $q->select('*');
                }
            ]
        )
            ->where('cart_id', $id)
            ->first();
        PDF::loadView(
            'admin.orders.print',
            [
                'order' => $order
            ],
            [],
            []
        )->save(base_path() . '/order/' . $order->code . '.pdf');
        return PDF::loadView(
            'admin.orders.print',
            [
                'order' => $order
            ],
            [],
            []
        )->download($order->cart_code . '.pdf');
    }

    public function printMultipleOrders(Request $request)
    {
        $from = date($request->from . ' 00:00:00');
        $to = date($request->to . ' 23:59:59');
        $query = Order::with(
            [
                'order_details' => function ($q) {
                    $q->select('*');
                }
            ]
        )
            ->whereBetween('created_at', [$from, $to]);
        if ($request->status != '') {
            $query->where('status', $request->status);
        }
        $orders = $query->get();

        if (count($orders) > 0) {
            if ($request->type == 'pdf') {
                return PDF::loadView(
                    'admin.orders.print-multiple',
                    [
                        'orders' => $orders
                    ],
                    [],
                    []
                )->download($request->from . '-to-' . $request->to . '.pdf');
            } else {
                $files = [];
                $zip_file = 'order/' . date('Y-m-d') . '-' . time() . '.zip'; // Name of our archive to download
                $zip = new \ZipArchive();
                $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                foreach ($orders as $order) {
                    $filePath = base_path() . '/order/' . $order->cart_code . '.pdf';
                    PDF::loadView(
                        'admin.orders.print',
                        [
                            'order' => $order
                        ],
                        [],
                        []
                    )->save($filePath);
                    $files[] = $filePath;
                    $zip->addFile(base_path() . '/order/' . $order->cart_code . '.pdf', $order->cart_code . '.pdf');
                }
                $zip->close();
                return response()->download(base_path($zip_file));
            }
        } else {
            return false;
        }
    }
}
