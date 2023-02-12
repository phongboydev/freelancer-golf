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
        <div class="row-title">HISTORY PAYMENT</div>
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
                                                    @if( $referral->discount_commission && $referral->discount_commission !='')
                                                        <span class="currency">$</span> <span id="price_en_{{ $user->id }}" class="price">{{  number_format($referral->discount_commission , 2) }}</span>
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
    <div class="row-title title_primary_profile"><strong>HISTORY message</strong></div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <div class="account_infomation clear">
        <div class="container_profile_show clear">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="clear product_aff_list_user">
                        @if($commissionHistories && count($commissionHistories)>0)
                            <table id="cell_product_aff" class="cell_product_aff cell_message_aff" style="width: 100%;"></table>
                            <script type="text/javascript">
                                jQuery( document ).ready(function($) {
                                    var data2 ={!! $data !!};
                                    $('#cell_product_aff').DataTable({
                                        data: data2,
                                        keys: true,
                                        'pageLength': 50,
                                        order: [[ 4, "desc" ]],
                                        columns: [
                                            {title: 'Price $', data: 'new_price'},
                                            {title: 'Message', data: 'message'},
                                            {
                                                title: 'Image', render: function (data, type, row) {//data
                                                    if (row.image !== null) {
                                                        return '<a href="' + row.image + '" target="_blank"><img src="' + row.image + '" style="max-width: 100px"></a>';
                                                    } else {
                                                        return '';
                                                    }
                                                }
                                            },
                                            {
                                                title: 'TIME', orderable: true, render: function (data, type, row) {//data
                                                    return moment(row.updated_at).format('DD/MM/YYYY');
                                                }
                                            },
                                        ],
                                        columnDefs: [
                                            {//Information
                                                visible: true,
                                                targets: 0,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    var giamoi = $.fn.dataTable.render.number( ',', '.', 2, '$' ).display(full.new_price);
                                                    var giacu = $.fn.dataTable.render.number( ',', '.', 2, '$' ).display(full.current_price);
                                                    return '<div class="info_price_usr">' +
                                                        '<p><span class="pr1">' + giamoi + '</span></p>'+
                                                        '<p><span class="pr2">' + giacu + '</span></p>' +
                                                        '</div>';
                                                }
                                            },
                                            {
                                                visible: true,
                                                targets: 1,
                                                className: 'text-center',
                                                render: function (data, type, full, meta) {
                                                    if(meta.row / 2 == 1){
                                                        return '<div class="alert alert-success text_ls alert-dismissible">' + data + '</div>';
                                                    }else{
                                                        return '<div class="alert alert-info text_ls alert-dismissible">' + data + '</div>';
                                                    }

                                                }
                                            }
                                        ]
                                    });
                                });
                            </script>
                        @else
                            <div class="alert alert-warning" role="alert">
                                Affiliate histories is empty !!!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
