@extends('admin.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'Cài đặt mạng xã hội | '.Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Cài đặt mạng xã hội | '.Helpers::get_setting('seo_title'),
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
                    <h1 class="m-0 text-dark">Cài đặt mạng xã hội</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('index')}}">Home</a></li>
                        <li class="breadcrumb-item active">Cài đặt mạng xã hội</li>
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
                            <h3 class="card-title">Cài đặt mạng xã hội</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{route('admin.storeSettingEnv')}}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="bg-box">
                                            <h5 class="mb-2"><b>Facebook Setting</b></h5>
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="FACEBOOK_CLIENT_ID">
                                                <label for="FACEBOOK_CLIENT_ID">FACEBOOK CLIENT ID</label>
                                                <input type="text" class="form-control" name="FACEBOOK_CLIENT_ID" id="FACEBOOK_CLIENT_ID"
                                                       value="{{  env('FACEBOOK_CLIENT_ID') }}" placeholder="FACEBOOK CLIENT ID">
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="FACEBOOK_CLIENT_SECRET">
                                                <label for="FACEBOOK_CLIENT_SECRET">FACEBOOK CLIENT SECRET</label>
                                                <input type="text" class="form-control" name="FACEBOOK_CLIENT_SECRET" id="FACEBOOK_CLIENT_SECRET"
                                                       value="{{  env('FACEBOOK_CLIENT_SECRET') }}" placeholder="FACEBOOK CLIENT SECRET">
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="FACEBOOK_CALLBACK">
                                                <label for="FACEBOOK_CALLBACK">FACEBOOK CALLBACK</label>
                                                <input type="text" class="form-control" name="FACEBOOK_CALLBACK" id="FACEBOOK_CALLBACK"
                                                       value="{{  env('FACEBOOK_CALLBACK') }}" placeholder="FACEBOOK CALLBACK">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-box">
                                            <h5 class="mb-2"><b>Google Setting</b></h5>
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="GOOGLE_CLIENT_ID">
                                                <label for="GOOGLE_CLIENT_ID">GOOGLE CLIENT ID</label>
                                                <input type="text" class="form-control" name="GOOGLE_CLIENT_ID" id="GOOGLE_CLIENT_ID"
                                                       value="{{  env('GOOGLE_CLIENT_ID') }}" placeholder="GOOGLE CLIENT ID">
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="GOOGLE_CLIENT_SECRET">
                                                <label for="GOOGLE_CLIENT_SECRET">GOOGLE CLIENT SECRET</label>
                                                <input type="text" class="form-control" name="GOOGLE_CLIENT_SECRET" id="GOOGLE_CLIENT_SECRET"
                                                       value="{{  env('GOOGLE_CLIENT_SECRET') }}" placeholder="GOOGLE CLIENT SECRET">
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="GOOGLE_CALLBACK">
                                                <label for="GOOGLE_CALLBACK">GOOGLE CALLBACK</label>
                                                <input type="text" class="form-control" name="GOOGLE_CALLBACK" id="GOOGLE_CALLBACK"
                                                       value="{{  env('GOOGLE_CALLBACK') }}" placeholder="GOOGLE CALLBACK">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
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
