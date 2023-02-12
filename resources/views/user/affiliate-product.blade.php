@extends('user.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'Account - '.Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Account - '.Helpers::get_setting('seo_title'),
        'og_description' => Helpers::get_setting('seo_description'),
        'og_url' => Request::url(),
        'og_img' => asset(Helpers::get_setting('seo_image')),
    );
    $seo = WebService::getSEO($data_seo);
    ?>
    @include('partials.seo')
@endsection
@section('content')
    <div class="my-dashboard">
        <div class="row-title">AFFILIATE PRODUCTS ORDER</div>
        <div class="hello">Hello, <strong>{{ $user->first_name }} {{ $user->last_name }}</strong></div>
    </div>
    <div class="address_book">
        <div class="row-title title_primary_profile"><strong>Revenue</strong></div>
        <div class="container_profile_show clear">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="revenue_view clear">
                        <div class="form-group clear">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box_price_member clear">
                                        <div class="card-header">
                                            <h4 class="my-0 font-weight-normal">USD</h4>
                                        </div>
                                        <div class="card_body_member clear">
                                            <div class="card_body_member_content clear">
                                                <div class="card_member_price clear">
                                                    @if($referral->discount_commission && $referral->discount_commission !='')
                                                        <span class="currency">$</span> <span id="price_en_{{ $user->id}}" class="price">{{  number_format($referral->discount_commission , 2) }}</span>
                                                    @else
                                                        <span class="currency">$</span> <span class="price">0</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <div class="account_infomation">
        <div class="container_profile_show clear">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="clear product_aff_list_user">

                        @if($referralHistories && count($referralHistories)>0)
                            <table id="cell_product_aff" class="cell_product_aff" style="width: 100%;"></table>
                            <script type="text/javascript">
                                jQuery( document ).ready(function($) {
                                    var data2 ={!! $referralHistories !!};
                                    $('#cell_product_aff').DataTable({
                                        data: data2,
                                        keys: true,
                                        'pageLength': 10,
                                        columns: [
                                            {title: 'Name', data: 'title'},
                                            {title: 'Quantity', data: 'quantity'},
                                            {title: 'Turnover value', data: 'total'},
                                            {title: 'Total Commission'},
                                            {
                                                title: 'Time', orderable: true, render: function (data, type, row) {//data
                                                    return moment(row.created_at).format('DD/MM/YYYY');
                                                }
                                            },
                                        ],
                                        order: [[ 4, "desc" ]],
                                        columnDefs: [
                                            {//Name
                                                visible: true,
                                                targets: 0,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    //return data;
                                                    return '<p class="tbl_title_v">'+data+'</p><p><img width="50" src="/images/product/'+full.thumbnail+'" alt="'+full.title+'"></p>';
                                                }
                                            },
                                            {//Name
                                                visible: true,
                                                targets: 1,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    return '<div class="quantity_sf">'+data+'</div>';
                                                }
                                            },
                                            {//Information
                                                visible: true,
                                                targets: 2,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    if (full.currency == "USD") {
                                                        var giatien = $.fn.dataTable.render.number( ',', '.', 2, '$' ).display(data);
                                                        var chietkhau = $.fn.dataTable.render.number( ',', '.', 2, '$' ).display(full.commission);
                                                    } else {
                                                        var giatien = $.fn.dataTable.render.number( ',', '.', 0, '','₫' ).display(data);
                                                        var chietkhau = $.fn.dataTable.render.number( ',', '.', 0, '','₫' ).display(full.commission);
                                                    }

                                                    return '<div class="info_sing_disc">'+
                                                     '<b>Total: </b><span class="sl2">'+ giatien +'</span>'+
                                                     '<p><b>Commission Rate: </b><span class="sl3">'+ full.percent +'%</span>'+
                                                     '<p><b>Commission: </b><span class="sl4">' + chietkhau +'</span></p></div>';
                                                }
                                            },
                                            {//Value
                                                visible: true,
                                                targets: 3,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    if (full.currency == "USD"){
                                                        var chietkhau = $.fn.dataTable.render.number( ',', '.', 2, '$' ).display(full.commission);
                                                    } else {
                                                        var chietkhau = $.fn.dataTable.render.number( ',', '.', 0, '','₫' ).display(full.commission);
                                                    }
                                                    return '<span class="sl4-v">' + chietkhau +'</span>';
                                                }
                                            },
                                            {//Create
                                                visible: true,
                                                targets: 4,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    return data;
                                                }
                                            }
                                        ],
                                    });
                                });
                            </script>
                        @else
                            <div class="alert alert-warning" role="alert">
                                Referral histories is empty !!!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
