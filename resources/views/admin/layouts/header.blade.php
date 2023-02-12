<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset(Helpers::get_setting('favicon')) }}" rel="shortcut icon"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@yield('seo')
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Font Awesome -->
    <!-- Admin Css -->
    <?php
    $version = '1.0.4';
    ?>
    <link rel="stylesheet" href="{{asset('css/style_admin.min.css')}}?ver={{ $version }}">
    <link rel="stylesheet" href="{{asset('css/style_admin.css')}}?ver={{ $version }}">
    <!-- Admin js -->
    <script src="{{asset('js/js_admin.min.js')}}?ver={{ $version }}"></script>
    <script src="{{asset('js/js_admin.js')}}?ver={{ $version }}"></script>
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <script type="text/javascript">
        var admin_url = '{{route('admin.dashboard')}}';
    </script>
