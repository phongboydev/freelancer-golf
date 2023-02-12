@extends('admin.layouts.app')
<?php
    use App\Constants\BaseConstants;

    $title = $detail->name;
    $name = $detail->name;
    $email  = $detail->email;
    $birthday = $detail->birthday;
    $phone = $detail->phone;
    $status = $detail->status;
    $address = $detail->address;
    $province = $detail->province;
    $district = $detail->district;
    $ward = $detail->ward;
    $country = $detail->country;
    $state = $detail->state;
    $city = $detail->city;
    $avatar = $detail->avatar;
    $id = $detail->id ;
?>
@section('seo')
<?php
$data_seo = array(
    'title' => $title.' | '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => $title.' | '.Helpers::get_setting('seo_title'),
    'og_description' => Helpers::get_setting('seo_description'),
    'og_url' => Request::url(),
    'og_img' => asset('images/logo_seo.png'),
    'current_url' =>Request::url(),
    'current_url_amp' => ''
);
$seo = WebService::getSEO($data_seo);
?>
@include('admin.partials.seo')
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{ $title }}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
  	<div class="container-fluid">
        <form action="{{route('admin.user.store')}}" method="POST" id="frm-create-category" enctype="multipart/form-data">
            @csrf
            @if(Session::has('success_msg'))
                <div class="mgt-10  alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ Session::get('success_msg') }}
                </div>
            @endif
            <input type="hidden" name="id" value="{{ $id }}">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$title}}</h3>
                </div> <!-- /.card-header -->
                <div class="card-body">
                    <!-- show error form -->
                    <div class="errorTxt"></div>
                    <div class="form-group text-center">
                        <div class="avatar-wrapper mb-3">
                            <img class="profile-pic" width="150"
                                 src="{{ $avatar }}"/>
                            <div class="upload-button">
                                <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                            </div>
                            <input class="file-upload" type="file" name="avatar" accept="image/*"/>
                            <input type="hidden" name="avatar_file_link"
                                   value="{{ $avatar }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Name" value="{{ $name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birthday">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" value="{{ $birthday }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="symbol">Email</label>
                                <input type="email" class="form-control" id="email" name="email" readonly
                                       placeholder="Email" value="{{ $email }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                       placeholder="Phone Number" value="{{ $phone }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                       placeholder="Address" value="{{ $address }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $countryItem)
                                        <option value="{{ $countryItem->id }}"
                                                @if($country == $countryItem->id) selected @endif>
                                            {{ $countryItem->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 @if($country == BaseConstants::VIETNAM_COUNTRY_ID) hidden @endif">
                            <div class="form-group">
                                <label for="state">State</label>
                                <select name="state" id="state" class="form-control">
                                    <option value="">Select State</option>
                                    @if(count($states) > 0)
                                        @foreach($states as $stateItem)
                                            <option value="{{ $stateItem->id }}"
                                                    @if($state == $stateItem->id) selected @endif>
                                                {{ $stateItem->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 @if($country == BaseConstants::VIETNAM_COUNTRY_ID) hidden @endif">
                            <div class="form-group">
                                <label for="city">City</label>
                                <select name="city" id="city" class="form-control">
                                    <option value="">Select City</option>
                                    @if(count($cities) > 0)
                                        @foreach($cities as $cityItem)
                                            <option value="{{ $cityItem->id }}"
                                                    @if($city == $cityItem->id) selected @endif>
                                                {{ $cityItem->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 @if($country != BaseConstants::VIETNAM_COUNTRY_ID) hidden @endif">
                            <div class="form-group">
                                <label for="province">Province</label>
                                <select name="province" id="province" class="form-control">
                                    <option value="">Select Province</option>
                                    @foreach($provinces as $key => $provinceItem)
                                        <option value="{{ $key }}"
                                                @if($province == $key) selected @endif>
                                            {{ $provinceItem }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 @if($country != BaseConstants::VIETNAM_COUNTRY_ID) hidden @endif">
                            <div class="form-group">
                                <label for="district">District</label>
                                <select name="district" id="district" class="form-control">
                                    <option value="">Select Province</option>
                                    @if(count($districts) > 0)
                                        @foreach($districts as $key => $districtItem)
                                            <option value="{{ $key }}"
                                                    @if($district == $key) selected @endif>
                                                {{ $districtItem }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 @if($country != BaseConstants::VIETNAM_COUNTRY_ID) hidden @endif">
                            <div class="form-group">
                                <label for="ward">Ward</label>
                                <select name="ward" id="ward" class="form-control">
                                    <option value="">Select Ward</option>
                                    @if(count($wards) > 0)
                                        @foreach($wards as $key => $wardItem)
                                            <option value="{{ $key }}"
                                                    @if($ward == $key) selected @endif>
                                                {{ $wardItem }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <h4 class="btn btn-secondary my-3">Affiliate Marketing</h4>
                    <div class="form-group">
                        <input class="form-check-input" type="checkbox" data-toggle="toggle" data-size="sm" id="activeReferral"
                               name="activeReferral" value="1" @if($detail->referral != '') checked @endif>
                        <label for="activeReferral">Active Referral</label>
                    </div>
                    @if($detail->referral != '')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="percent">Percent</label>
                                    <input type="number" name="percent" id="percent" placeholder="%"
                                           value="{{ $detail->referral->percent }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">Slug (Example: olaben-2022, olaben-master)</label>
                                    <input type="text" name="slug" id="slug" placeholder="Referral Slug"
                                           value="{{ $detail->referral->slug }}" class="form-control">
                                    @if($detail->referral->slug != '')
                                        <div class="p-2 bg-box my-2">
                                            <b style="color: #0000cc;">Link Affiliate: </b><a href="">#</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php
                                    $group_products = json_decode($detail->referral->group_products);
                                    ?>
                                    <label for="group_products">Group Products</label>
                                    <select name="group_products[]" id="group_products" class="form-control" multiple="multiple">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                    @if(in_array($product->id, $group_products)) selected @endif>
                                                {{ $product->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php
                                    $except_products = json_decode($detail->referral->except_products);
                                    ?>
                                    <label for="except_products">Except Products</label>
                                    <select name="except_products[]" id="except_products" class="form-control" multiple="multiple">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                    @if(in_array($product->id, $except_products)) selected @endif>
                                                {{ $product->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Marketing Revenue</h3>
                                    </div> <!-- /.card-header -->
                                    <div class="card-body">
                                        <?php
                                        $discount_commission = ($detail->referral->discount_commission != '') ? $detail->referral->discount_commission : 0;
                                        $discount_commission_currency = ($detail->referral->discount_commission_currency != '') ? $detail->referral->discount_commission_currency : '$';
                                        ?>
                                        <div class="my-5 text-center" style="font-size: 18px">
                                            <b style="color: red">{{ $discount_commission }}</b>{{ $discount_commission_currency }}
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 text-center">
                                                <div class="form-group">
                                                    <button class="btn btn-success" type="button">
                                                        Withdraw
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <button class="btn btn-danger" type="button">
                                                    Withdraw Histories
                                                </button>
                                            </div>
                                        </div>
                                    </div> <!-- /.card-body -->
                                </div><!-- /.card -->
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="percent">Percent</label>
                                    <input type="number" name="percent" id="percent" placeholder="%" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug">Slug (Example: olaben-2022, olaben-master)</label>
                                    <input type="text" name="slug" id="slug" placeholder="Referral Slug" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="group_products">Group Products</label>
                                    <select name="group_products[]" id="group_products" class="form-control" multiple="multiple">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="except_products">Except Products</label>
                                    <select name="except_products[]" id="except_products" class="form-control" multiple="multiple">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group text-center">
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="radioDraft" name="status" value="0" @if($status == 0) checked @endif>
                            <label for="radioDraft">Inactive</label>
                        </div>
                        <div class="icheck-primary d-inline" style="margin-left: 15px;">
                            <input type="radio" id="radioPublic" name="status" value="1" @if($status == 1) checked @endif>
                            <label for="radioPublic">Active</label>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div> <!-- /.card-body -->
            </div><!-- /.card -->
        </form>
  	</div> <!-- /.container-fluid -->
</section>
<script type="text/javascript">
    jQuery(document).ready(function ($){
        //Date range picker
        $('#group_products').select2();
        $('#except_products').select2();

        //xử lý validate
        $("#frm-create-category").validate({
            rules: {
                name: "required",
            },
            messages: {
                name: "Name is required.",
            },
            errorElement : 'div',
            errorLabelContainer: '.errorTxt',
            invalidHandler: function(event, validator) {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });
    });
</script>
@endsection
