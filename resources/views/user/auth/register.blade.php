@extends('layouts.app')
@section('seo')
<?php
$data_seo = array(
    'title' => 'Member registration - '.Helpers::get_setting('seo_title'),
    'keywords' => Helpers::get_setting('seo_keyword'),
    'description' => Helpers::get_setting('seo_description'),
    'og_title' => 'Member registration - '.Helpers::get_setting('seo_title'),
    'og_description' => Helpers::get_setting('seo_description'),
    'og_url' => Request::url(),
    'og_img' => asset(Helpers::get_setting('seo_image')),
);
$seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')

<div class="breadcrumbs-group-container details_product_bg clear">
    <div class="container clear">
         <div class="breadcrumbs_top_page clear">
             <div class="breadcrumbs-item fl">
                    {!! Breadcrumbs::render('customer.register') !!}
             </div>
         </div>
    </div>
</div><!--home-index-->
<div class="main_content details_product_bg clear">
    <div class="container">
        <div class="center_form form_customer">
            <div class="panel panel-default">
                <div class="panel-heading title-form">Sign up for an account</div>
                <div class="panel-body">
                    <form id="customer-register" class="form-customer-register" role="form" method="POST" action="{{route('postRegisterCustomer')}}">
                        {{ csrf_field() }}
                        <h2 class="legend">User information</h2>
                        <div class="row clear">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="first_name" class="col-md-12 control-label">First-Name<span class="required">*</span></label>
                                    <input id="first_name" type="text" placeholder="First-Name" class="form-control" name="first_name" value="{{ old('first_name') }}">

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <label for="last_name" class="col-md-12 control-label">Last-Name<span class="required">*</span></label>
                                    <input id="last_name" type="text" class="form-control" placeholder="Last-Name" name="last_name" value="{{ old('last_name') }}">

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-12 control-label">E-Mail<span class="required">*</span></label>
                                    <input id="email" type="email" class="form-control" placeholder="E-mail" name="email" value="{{ old('email') }}">
                                    <div id="error-form-email"></div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <h2 class="legend infor-login">Login information</h2>
                        <div class="row clear">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-md-12 control-label">Password<span class="required">*</span></label>
                                    <input id="password" type="password" placeholder="Password" class="form-control" name="password">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="password-confirm" class="col-md-12 control-label">Confirm password<span class="required">*</span></label>
                                    <input id="password-confirm" type="password" placeholder="Confirm password" class="form-control" name="password_confirmation">

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('birthday_day') ? ' has-error' : '' }}">
                                    <label for="birthday" class="col-md-12 control-label">Birthday<span class="required">*</span></label>
                                    <div>
                                        <select name="birthday_day" class="cls_birthday form-control" id="birthday_day">
                                            @for($i=1; $i <= 31; $i++)
                                                @if($i < 10)
                                                <option value="{{'0'.$i}}">{{'0'.$i}}</option>
                                                @else
                                                <option value="{{$i}}">{{$i}}</option>
                                                @endif
                                            @endfor
                                        </select>
                                        <select name="birthday_month" class="cls_birthday form-control" id="birthday_month">
                                            @for($i=1; $i <= 12; $i++)
                                                @if($i < 10)
                                                <option value="{{'0'.$i}}">{{'0'.$i}}</option>
                                                @else
                                                <option value="{{$i}}">{{$i}}</option>
                                                @endif
                                            @endfor
                                        </select>
                                        <select name="birthday_year" class="cls_birthday form-control" id="birthday_year">
                                            @for($i=1960; $i <= date("Y"); $i++)
                                                <option value="{{$i}}" @if($i == date("Y")) selected @endif>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label for="phone" class="col-md-12 control-label">Phone<span class="required">*</span></label>
                                    <input id="phone" type="text" class="form-control" placeholder="Phone" name="phone" value="{{ old('phone') }}">
                                    <div id="error-form-phone"></div>
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group{{ $errors->has('slt_countries') ? ' has-error' : '' }}">
                                    <label for="slt_countries" class="col-sm-12 control-label">Countries<span class="required">*</span></label>
                                    <select name="slt_countries" id="slt_countries">
                                        <option value="">Select Countries</option>
                                        <?php
                                            $data_countries = App\Model\Countries::orderBy('name', 'ASC')->select('id', 'name')->get();
                                            foreach($data_countries as $item):
                                        ?>
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        <?php endforeach; ?>
                                    </select>
                                    @if ($errors->has('slt_countries'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('slt_countries') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('slt_states') ? ' has-error' : '' }}">
                                    <label for="slt_states" class="col-sm-12 control-label">States<span class="required">*</span></label>
                                    <select name="slt_states" id="slt_states">
                                        <option value="">Select States</option>
                                    </select>
                                    @if ($errors->has('slt_states'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('slt_states') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group{{ $errors->has('slt_cities') ? ' has-error' : '' }}">
                                    <label for="slt_cities" class="col-sm-12 control-label">Cities<span class="required">*</span></label>
                                    <select name="slt_cities" id="slt_cities">
                                        <option value="">Select Cities</option>
                                    </select>
                                    @if ($errors->has('slt_cities'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('slt_cities') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 ward-control">
                                <div class="form-group{{ $errors->has('slt_wards') ? ' has-error' : '' }}">
                                    <label for="slt_wards" class="col-sm-12 control-label">Wards<span class="required">*</span></label>
                                    <select name="slt_wards" id="slt_wards">
                                        <option value="">Select Wards</option>
                                    </select>
                                    @if ($errors->has('slt_wards'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('slt_wards') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                    <label for="address" class="col-md-12 control-label">Address<span class="required">*</span></label>
                                    <input id="address" type="text" class="form-control" placeholder="Address" name="address" value="{{ old('address') }}">
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-submit-customer">
                                    <i class="fa fa-btn fa-user"></i> Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
