@extends('user.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'Account information - '.Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Account information - '.Helpers::get_setting('seo_title'),
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
        <h1>Account information</h1>
    </div>
    <form action="{{ route('user.updateProfile') }}" method="POST" enctype="multipart/form-data" novalidate="novalidate" class="edit_profile">
        <div class="card">
            <div class="card-body">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="row">
                    <div class="col-md-12 pl-1">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" disabled="" id="email" name="email" required placeholder="Email" value="{{$data->email}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 pr-1">
                        <div class="form-group">
                            <label>Avatar</label>
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' disabled="" id="imageUpload" name="avatar_upload" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload"></label>
                                </div>
                                <div class="avatar-preview">
                                    <?php
                                    if($data->avatar != ''){
                                        $img_avatar = '/images/avatar/' . $data->avatar;
                                    }else{
                                        $img_avatar = asset('img/no-avatar.png');
                                    }
                                    ?>
                                    <div id="imagePreview" style="background-image: url({{$img_avatar}});">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 pr-1">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" disabled="" placeholder="First Name"
                                   name="first_name" required value="{{ $data->first_name }}" id="first_name">
                        </div>
                    </div>
                    <div class="col-md-6 pl-1">
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" disabled="" name="last_name" required
                                   placeholder="Last Name" value="{{ $data->last_name }}" id="last_name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" disabled="" name="phone" required id="phone"
                                   placeholder="Phone" value="{{$data->phone}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" disabled="" name="address" required id="address"
                                   placeholder="Address" value="{{$data->address}}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="about_me">About Me</label>
                            <textarea rows="4" cols="80" class="form-control" disabled="" name="about_me" id="about_me"
                                      placeholder="Here can be your description">{{$data->about_me}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row btn_action flex">
                    <div class="col-md-3 pr-1 btn_edit_form">
                        <div class="form-group">
                            <input type="button" class="form-control btn_edit" name="btn_edit" value="Edit profile">
                        </div>
                    </div>
                    <div class="col-md-3 pr-1 btn_update">
                        <div class="form-group">
                            <input type="submit" class="form-control" name="btn_update" value="Update">
                        </div>
                    </div>
                    <div class="col-md-3 pr-1 btn_cancel">
                        <div class="form-group">
                            <input type="button" class="form-control" name="btn_cancel" value="Cancel">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imageUpload").change(function() {
                readURL(this);
            });
        });
    </script>
@endsection
