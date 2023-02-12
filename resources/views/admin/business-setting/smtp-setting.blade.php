@extends('admin.layouts.app')
@section('seo')
    <?php
    $data_seo = array(
        'title' => 'Cài đặt SMTP | '.Helpers::get_setting('seo_title'),
        'keywords' => Helpers::get_setting('seo_keyword'),
        'description' => Helpers::get_setting('seo_description'),
        'og_title' => 'Cài đặt SMTP | '.Helpers::get_setting('seo_title'),
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
                    <h1 class="m-0 text-dark">Cài đặt SMTP</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('index')}}">Home</a></li>
                        <li class="breadcrumb-item active">Cài đặt SMTP</li>
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
                            <h3 class="card-title">Cài đặt SMTP</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            @if(Session::has('success_msg'))
                                <div class="mgt-10  alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    {{ Session::get('success_msg') }}
                                </div>
                            @endif
                            <form action="{{route('admin.storeSettingEnv')}}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="MAIL_MAILER">
                                        <label for="MAIL_MAILER">Type</label>
                                        <select class="form-control" id="MAIL_MAILER" name="MAIL_MAILER">
                                            <option value="sendmail" @if (env('MAIL_MAILER') == "sendmail") selected @endif>Sendmail</option>
                                            <option value="smtp" @if (env('MAIL_MAILER') == "smtp") selected @endif>SMTP</option>
                                            <option value="mailgun" @if (env('MAIL_MAILER') == "mailgun") selected @endif>Mailgun</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="MAIL_HOST">
                                        <label for="MAIL_HOST">MAIL HOST</label>
                                        <input type="text" class="form-control" name="MAIL_HOST" id="MAIL_HOST"
                                               value="{{  env('MAIL_HOST') }}" placeholder="MAIL HOST">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="MAIL_PORT">
                                        <label for="MAIL_PORT">MAIL PORT</label>
                                        <input type="text" class="form-control" name="MAIL_PORT" id="MAIL_PORT"
                                               value="{{  env('MAIL_PORT') }}" placeholder="MAIL PORT">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="MAIL_USERNAME">
                                        <label for="MAIL_USERNAME">MAIL USERNAME</label>
                                        <input type="text" class="form-control" name="MAIL_USERNAME" id="MAIL_USERNAME"
                                               value="{{  env('MAIL_USERNAME') }}" placeholder="MAIL USERNAME">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="MAIL_PASSWORD">
                                        <label for="MAIL_PASSWORD">MAIL PASSWORD</label>
                                        <input type="text" class="form-control" name="MAIL_PASSWORD" id="MAIL_PASSWORD"
                                               value="{{  env('MAIL_PASSWORD') }}" placeholder="MAIL PASSWORD">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="MAIL_ENCRYPTION">
                                        <label for="MAIL_ENCRYPTION">MAIL ENCRYPTION</label>
                                        <input type="text" class="form-control" name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION"
                                               value="{{  env('MAIL_ENCRYPTION') }}" placeholder="MAIL ENCRYPTION">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="MAIL_FROM_ADDRESS">
                                        <label for="MAIL_FROM_ADDRESS">MAIL FROM ADDRESS</label>
                                        <input type="text" class="form-control" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS"
                                               value="{{  env('MAIL_FROM_ADDRESS') }}" placeholder="MAIL FROM ADDRESS">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="MAIL_FROM_NAME">
                                        <label for="MAIL_FROM_NAME">MAIL FROM NAME</label>
                                        <input type="text" class="form-control" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME"
                                               value="{{  env('MAIL_FROM_NAME') }}" placeholder="MAIL FROM NAME">
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
