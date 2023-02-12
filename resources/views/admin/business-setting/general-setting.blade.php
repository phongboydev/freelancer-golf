@extends('admin.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'Cài đặt chung | '.Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Cài đặt chung | '.Helpers::get_setting('seo_title'),
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
                    <h1 class="m-0 text-dark">Cài đặt chung</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('index')}}">Home</a></li>
                        <li class="breadcrumb-item active">Cài đặt chung</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cài đặt chung</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{route('admin.storeGeneralSetting')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="site_name">Tên Website</label>
                                            <input type="text" name="site_name"
                                                   id="site_name" class="form-control"
                                                   value="{{ Helpers::get_setting('site_name') }}"
                                                   placeholder="Tên Website">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="site_slogan">Site Slogan</label>
                                            <input type="text" name="site_slogan"
                                                   id="site_slogan" class="form-control"
                                                   value="{{ Helpers::get_setting('site_slogan') }}"
                                                   placeholder="Site Slogan">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo">Logo</label>
                                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                                            <?php
                                            $logo = Helpers::get_setting('logo');
                                            if ($logo == '') {
                                                $logo = asset('img/default-150x150.png');
                                            } else {
                                                $logo = asset($logo);
                                            }
                                            ?>
                                            <div class="mt-2 text-center">
                                                <img src="{{ $logo }}" style="max-width: 150px">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="favicon">Favicon</label>
                                            <input type="file" name="favicon" id="favicon" class="form-control" accept="image/*">
                                            <?php
                                            $favicon = Helpers::get_setting('favicon');
                                            if ($favicon == '') {
                                                $favicon = asset('img/default-150x150.png');
                                            } else {
                                                $favicon = asset($favicon);
                                            }
                                            ?>
                                            <div class="mt-2 text-center">
                                                <img src="{{ $favicon }}" style="max-width: 64px">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="company_name">Tên công ty</label>
                                    <input type="text" name="company_name"
                                           id="company_name" class="form-control"
                                           value="{{ Helpers::get_setting('company_name') }}"
                                           placeholder="Tên công ty">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Địa chỉ</label>
                                            <input type="text" name="address"
                                                   id="address" class="form-control"
                                                   value="{{ Helpers::get_setting('address') }}"
                                                   placeholder="Địa chỉ">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="google_map">Link Google Map</label>
                                            <input type="text" name="google_map"
                                                   id="google_map" class="form-control"
                                                   value="{{ Helpers::get_setting('google_map') }}"
                                                   placeholder="Link Google Map">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitude">Toạ độ Latitude</label>
                                            <input type="text" name="latitude"
                                                   id="latitude" class="form-control"
                                                   value="{{ Helpers::get_setting('latitude') }}"
                                                   placeholder="Toạ độ Latitude">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitude">Toạ độ Longitude</label>
                                            <input type="text" name="longitude"
                                                   id="longitude" class="form-control"
                                                   value="{{ Helpers::get_setting('longitude') }}"
                                                   placeholder="Toạ độ Longitude">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hotline">Hotline</label>
                                            <input type="text" name="hotline"
                                                   id="hotline" class="form-control"
                                                   value="{{ Helpers::get_setting('hotline') }}"
                                                   placeholder="Hotline">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="admin_email">Admin Email</label>
                                            <input type="admin_email" name="admin_email"
                                                   id="admin_email" class="form-control"
                                                   value="{{ Helpers::get_setting('admin_email') }}"
                                                   placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email"
                                                   id="email" class="form-control"
                                                   value="{{ Helpers::get_setting('email') }}"
                                                   placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cc_mail">CC Mail</label>
                                            <input type="email" name="cc_mail"
                                                   id="cc_mail" class="form-control"
                                                   value="{{ Helpers::get_setting('cc_mail') }}"
                                                   placeholder="Email">
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-box mb-4">
                                    <h4 class="mb-2 text-center"><b>Meta SEO</b></h4>
                                    <div class="form-group">
                                        <label for="seo_title">SEO Title</label>
                                        <input type="text" name="seo_title"
                                               id="seo_title" class="form-control"
                                               value="{{ Helpers::get_setting('seo_title') }}"
                                               placeholder="SEO Title">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="seo_description">SEO Description</label>
                                                <textarea name="seo_description" id="seo_description"
                                                          placeholder="SEO Description" class="form-control"
                                                          cols="5" rows="5">{!! Helpers::get_setting('seo_description') !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="seo_keyword">SEO Keyword</label>
                                                <textarea name="seo_keyword" id="seo_keyword"
                                                          placeholder="SEO Keyword" class="form-control"
                                                          cols="5" rows="5">{!! Helpers::get_setting('seo_keyword') !!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="seo_title">SEO Image</label>
                                        <input type="file" name="seo_image" id="seo_image" class="form-control" accept="image/*">
                                        <?php
                                        $seo_image = Helpers::get_setting('seo_image');
                                        if ($seo_image == '') {
                                            $seo_image = asset('img/default-150x150.png');
                                        } else {
                                            $seo_image = asset($seo_image);
                                        }
                                        ?>
                                        <div class="mt-2 text-center">
                                            <img src="{{ $seo_image }}" style="max-width: 150px">
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-box my-4">
                                    <h4 class="mb-2 text-center"><b>Mạng xã hội</b></h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="facebook">Facebook</label>
                                                <input type="text" name="facebook"
                                                       id="facebook" class="form-control"
                                                       value="{{ Helpers::get_setting('facebook') }}"
                                                       placeholder="Link Facebook">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="instagram">Instagram</label>
                                                <input type="text" name="instagram"
                                                       id="instagram" class="form-control"
                                                       value="{{ Helpers::get_setting('instagram') }}"
                                                       placeholder="Link Instagram">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="zalo">Zalo</label>
                                                <input type="text" name="zalo"
                                                       id="zalo" class="form-control"
                                                       value="{{ Helpers::get_setting('zalo') }}"
                                                       placeholder="Link Zalo">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="youtube">Youtube</label>
                                                <input type="text" name="youtube"
                                                       id="youtube" class="form-control"
                                                       value="{{ Helpers::get_setting('youtube') }}"
                                                       placeholder="Link Youtube">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="messenger_facebook">Messenger Facebook</label>
                                                <input type="text" name="messenger_facebook"
                                                       id="messenger_facebook" class="form-control"
                                                       value="{{ Helpers::get_setting('messenger_facebook') }}"
                                                       placeholder="Link Messenger Facebook">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="header">Header (Optional)</label>
                                    <textarea name="header" id="header" cols="6" rows="6"
                                              class="form-control">{!! Helpers::get_setting('header') !!}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="footer">Footer (Optional)</label>
                                    <textarea name="footer" id="footer" cols="6" rows="6"
                                              class="form-control">{!! Helpers::get_setting('footer') !!}</textarea>
                                </div>
                                <div class="form-group text-center mt-3">
                                    <input type="submit" class="btn btn-primary" value="Lưu">
                                </div>
                            </form>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </section>
@endsection
