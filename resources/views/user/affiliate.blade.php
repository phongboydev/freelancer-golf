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
        <div class="row-title">Affiliate marketing information</div>
        <div class="hello">Hello, <strong>{{ $user->first_name }} {{ $user->last_name }}</strong></div>
    </div>
    @if(Session::has('success_msg'))
        <div class="mgt-10  alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            {{ Session::get('success_msg') }}
        </div>
    @endif
    <div class="account_infomation">
        <div class="contact_title padding-tit row-title title_primary_profile">
            <strong>AFFILIATE DETAILS</strong>
        </div>
        <div class="container_profile_show clear">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="contact_infomation">
                        <div class="profile_view_usr clear">
                            <div class="item percent">
                                <span class="label">Percent Referral:</span>
                                <span class="value">{{ $referral->percent }}%</span>
                            </div>
                            <div class="item link">
                                <span class="label">Url Link:</span>
                                <span class="value"><a target="_blank" href="{{route('shop.referral', $referral->slug) }}">{{ route('shop.referral', $referral->slug) }}</a></span>
                            </div>
                            <div class="item product_aff">
                                <span class="label">Products Order:</span>
                                <span class="value"><a href="{{ route('user.affiliateProducts') }}">View Products Order</a></span>
                            </div>
                            <div class="item product_aff">
                                <span class="label">History Payment:</span>
                                <span class="value"><a href="{{ route('user.affiliateHistories') }}">View History Payment</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="account_infomation">
        <div class="contact_title padding-tit row-title title_primary_profile">
            <strong>BANK INFORMATION</strong>
        </div>
        <div class="container_profile_show clear">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#bankInfoModal" style="background: #000">
                Update
            </button>
            <!-- Modal -->
            <div class="modal fade" id="bankInfoModal" tabindex="-1" role="dialog" aria-labelledby="bankInfoModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="margin-top: 110px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bankInfoModalLabel">Bank Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('user.updateBankInfo') }}" method="post">
                                <?php
                                $bank_info = ($referral->bank_info != '') ? json_decode($referral->bank_info) : "";
                                ?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="bank_name"><b>Bank Name</b></label>
                                            <input type="text" class="form-control" name="bank_name" id="bank_name"
                                                   @if($bank_info != '') value="{{ $bank_info->bank_name }}" @endif
                                                   placeholder="example DONG A BANK" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for=""><b>Account Name</b></label>
                                            <input type="text" class="form-control" name="account_name" id="account_name"
                                                   @if($bank_info != '') value="{{ $bank_info->account_name }}" @endif
                                                   placeholder="example Jonny Dang" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_number"><b>Account Number</b></label>
                                            <input type="text" class="form-control" name="account_number" id="account_number"
                                                   @if($bank_info != '') value="{{ $bank_info->account_number }}" @endif
                                                   placeholder="example 123456789" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="swift"><b>SWIFT/BIC</b></label>
                                            <input type="text" class="form-control" name="swift" id="swift"
                                                   @if($bank_info != '') value="{{ $bank_info->swift }}" @endif
                                                   placeholder="example ABCDVNBBXXX">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn login_btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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
                        <div class="tbl_request_pay clear">
                            <form action="{{ route('user.requestPayment') }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <button type="submit" data-id="modal-content-{{ $user->id}}"
                                        class="tbl_button_member_aff tbl_button_member_minus price_usd_member_minus btn btn-lg btn-block btn-danger">
                                    <i class="dslc-icon-ext-power"></i> Request Payment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
