@include('layouts.header')
    <?php $segment_check=""; ?>
    <main id="site-content" class="site-content clear">
        <div id="public-main" class="clear body_content_inc">
            @yield('content')
        </div><!--public-main-->
    </main>
@include('layouts.footer')

