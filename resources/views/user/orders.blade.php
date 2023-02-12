@extends('user.layouts.app')
@section('seo')
<?php
    use App\Models\Order;
    use App\Models\OrderReturnHistory;
    $data_seo = array(
        'title' => 'Orders - '.Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Orders - '.Helpers::get_setting('seo_title'),
        'og_description' => Helpers::get_setting('seo_description'),
        'og_url' => Request::url(),
        'og_img' => asset(Helpers::get_setting('seo_image')),
    );
    $seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')
<div class="page-title">
    <h1>ORDER & RETURN</h1>
</div>
<div class="list-order">
	<ul class="my-list-order">
		<?php
        $currency = Helpers::get_option_minhnn('currency');
		?>
        @if(count($orders) > 0)
            @foreach($orders as $order)
                <?php
                    $time_order = $order->created_at;
                    $amount = WebService::formatMoney12($order->total) . $currency;
                    $orderReturnHistories = OrderReturnHistory::where('order_id', $order->id)
                        ->where('user_id', $user->id)
                        ->first();
                    $order->return_status = '';
                    if ($orderReturnHistories) {
                        $order->return_status = $orderReturnHistories->process;
                    }
                ?>
                <li class="clear">
                    <div class="time-order">
                        <p>{{ date('M', strtotime($time_order)) }}</p>
                        <p style="font-size: 17px;"><b>{{ date('d', strtotime($time_order)) }}</b></p>
                        <p>{{ date('Y', strtotime($time_order)) }}</p>
                    </div>
                    <a href="{{route('user.orderDetail', $order->id)}}" >
                        <b>Order #:</b> {{ $order->code }} <br>
                        <b>Total:</b> {{ $amount }} <br>
                        <b>Payment Status:</b>
                        <?php
                            if ($order->payment_status == Order::PaymentStatus['Paid']) {
                                echo 'Paid';
                            } else {
                                echo 'Not Paid';
                            }
                        ?> <br>
                        <b>Status:</b>
                        <?php
                            if ($order->status == Order::Status['Refund']) {
                                echo 'Returned';
                            } elseif ($order->return_status == OrderReturnHistory::Process['In-Progress']) {
                                echo 'Return pending';
                            } elseif($order->status == Order::Status['Confirm']) {
                                echo 'Confirmed';
                            } elseif ($order->status == Order::Status['Delivery'] || $order->status == Order::Status['DHL Delivery']) {
                                echo 'Delivery';
                            } elseif ($order->status == Order::Status['Completed']) {
                                echo 'Completed';
                            } elseif ($order->status == Order::Status['New']) {
                                echo 'New';
                            } else {
                                echo 'Canceled';
                            }
                        ?>
                    </a>
                </li>
            @endforeach
		@endif
	</ul>
</div>
@endsection
