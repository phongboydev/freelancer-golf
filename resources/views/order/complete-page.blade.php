@extends('layouts.app')
@section('seo')
    <?php
    $title = 'Order successful |' . Helpers::get_setting('seo_title');
    $description = $title . Helpers::get_setting('seo_description');
    $keyword = 'shop, shops, olaben,' . Helpers::get_setting('seo_keyword');
    $thumb_img_seo = asset(Helpers::get_setting('seo_image'));
    $data_seo = array(
        'title' => $title,
        'keywords' => $keyword,
        'description' => $description,
        'og_title' => $title,
        'og_description' => $description,
        'og_url' => Request::url(),
        'og_img' => $thumb_img_seo,
        'current_url' => Request::url(),
        'current_url_amp' => ''
    );
    $seo = WebService::getSEO($data_seo);
    ?>
    @include('partials.seo')
@endsection
@section('content')
    <div class="main_content clear">
        <div class="container clear">
            <div class="body-container none_padding border-group clear">
                <section id="section" class="section clear">
                    <div class="group-section-wrap clear row">
                        <div class="col-md-12">
                            <div class="center_txt thank_container" style="padding: 80px 0">
                                <div class="img_checked">
                                    <img src="{{asset('/img/icon-checked.png')}}" width="80">
                                </div>
                                <h1 class="title_page_thank">Order successful!</h1>
                                <div class="tks_page_cnt">
                                    <p>Olaben would like to thank you for your confidence.</p>
                                    <p>We will contact you as soon as possible!</p>
                                </div>
                                <script type="text/javascript">
                                    var time = 7;
                                    setInterval(function () {
                                        var seconds = time % 60;
                                        var minutes = (time - seconds) / 60;
                                        if (seconds.toString().length == 1) {
                                            seconds = seconds;
                                        }
                                        document.getElementById("time").innerHTML = seconds;
                                        time--;
                                        if (time == 0) {
                                            window.location.href = '<?php echo route('index'); ?>';
                                        }
                                    }, 1000);
                                </script>
                                @if(session()->has('data_complete'))
                                    <?php
                                    $data_complete = session()->get('data_complete');
                                    Session::forget('data_mail_customer');
                                    Session::forget('data_mail_admin');
                                    ?>
                                    <script>
                                        var orderid = "{!! $data_complete['orderid'] !!}";
                                        var totalvalue = "{!! $data_complete['totalvalue'] !!}";
                                        var paidcurrency = "{!! $data_complete['paidcurrency'] !!}";
                                        var shippingfee = "{!! $data_complete['shippingfee'] !!}";

                                        gtag('event', 'purchase', {
                                            "transaction_id": orderid,
                                            "value": totalvalue,
                                            "currency": paidcurrency,
                                            "shipping": shippingfee
                                        });
                                    </script>
                                @endif
                                <div class="timer" onload="timer(1800)" style="padding-top: 20px;">
                                    <div>Go to the home page in <strong><span id="time">7</span>s</strong> or click <a
                                            href="{{route('index')}}" style="color: red">this</a>!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
