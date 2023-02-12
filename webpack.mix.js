const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

// mix admin
mix.scripts([
    'public/js/popper.min.js',
    'public/plugins/jquery/jquery.min.js',
    'public/plugins/jquery-ui/jquery-ui.min.js',
    'public/plugins/bootstrap-4/js/bootstrap.min.js',
    'public/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'public/plugins/chart.js/Chart.min.js',
    'public/plugins/sparklines/sparkline.js',
    'public/plugins/jqvmap/jquery.vmap.min.js',
    'public/plugins/jqvmap/maps/jquery.vmap.usa.js',
    'public/plugins/jquery-knob/jquery.knob.min.js',
    'public/plugins/moment/moment.min.js',
    'public/plugins/daterangepicker/daterangepicker.js',
    'public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
    'public/plugins/bootstrap-toggle/bootstrap-toggle.min.js',
    'public/plugins/summernote/summernote-bs4.min.js',
    'public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
    'public/plugins/datatables/jquery.dataTables.min.js',
    'public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
    'public/plugins/datatables-responsive/js/dataTables.responsive.min.js',
    'public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
    'public/plugins/jquery-validation/jquery.validate.min.js',
    'public/plugins/jquery-validation/additional-methods.min.js',
    'public/plugins/bootstrap-multiselect/bootstrap-multiselect.js',
    'public/plugins/select2/js/select2.full.min.js',
    'public/plugins/jQuery-tagEditor/jquery.caret.min.js',
    'public/plugins/jQuery-tagEditor/jquery.tag-editor.min.js',
    'public/js/adminlte.js',
    'public/js/jquery-confirm.min.js',
    'public/js/slugify.js',
    'public/js/demo.js'
], 'public/js/js_admin.min.js');

mix.styles([
    'public/plugins/fontawesome-free/css/all.min.css',
    'public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
    'public/plugins/bootstrap-toggle/bootstrap-toggle.min.css',
    'public/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
    'public/plugins/jqvmap/jqvmap.min.css',
    'public/plugins/jquery-ui/jquery-ui.min.css',
    'public/css/adminlte.min.css',
    'public/plugins/bootstrap-4/css/bootstrap.min.css',
    'public/css/bootstrap-theme.min.css',
    'public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
    'public/plugins/daterangepicker/daterangepicker.css',
    'public/plugins/summernote/summernote-bs4.min.css',
    'public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
    'public/plugins/bootstrap-multiselect/bootstrap-multiselect.css',
    'public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css',
    'public/plugins/select2/css/select2.min.css',
    'public/plugins/jQuery-tagEditor/jquery.tag-editor.css',
    'public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
    'public/css/jquery-confirm.min.css'
], 'public/css/style_admin.min.css');

mix.scripts([
    'public/js/jquery.min.js',
    'public/js/jquery-ui-1.11.js',
    'public/js/popper.min.js',
    'public/js/jquery.validate.min.js',
    'public/js/jquery.lazyload.min.js',
    'public/js/jquery.cookie.js',
    'public/js/jquery.lazyscript.min.js',
    'public/js/slick.min.js',
    'public/js/owl.carousel.min.js',
    'public/plugins/rateyo/jquery.rateyo.min.js',
    'public/js/jquery.fancybox.min.js',
    'public/plugins/select2/js/select2.full.min.js',
    'public/plugins/sweetalert2/sweetalert2.min.js',
], 'public/js/jquery.main.js');

mix.styles([
    'public/css/font-awesome.min.css',
    'public/css/iconfont_style.css',
    'public/css/animate.css',
    'public/plugins/rateyo/jquery.rateyo.min.css',
    'public/css/slick.css',
    'public/css/slick-theme.css',
    'public/css/owl.carousel.min.css',
    'public/css/jquery.fancybox.min.css',
    'public/plugins/select2/css/select2.min.css',
    'public/plugins/sweetalert2/sweetalert2.min.css',
], 'public/css/style.min.css');
