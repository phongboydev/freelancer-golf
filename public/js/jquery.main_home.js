"use strict";
jQuery(document).ready(function ($) {
    homeProductSlider();
    ChildProductSlider();
    LookboxProductSlider();
    AddReadMore();
    //show modal welcome
    var welcomeModal = sessionStorage.getItem("welcome-modal");
    if (welcomeModal !== 'true') {
        $(window).load(function () {
            setTimeout(function () {
                $('#welcomeModal').show();
            }, 1500);
            sessionStorage.setItem("welcome-modal", "true");
        });
    }
    $('#welcomeModal .close').on('click', function () {
        $('#welcomeModal').hide();
    });
    $('#welcomeModal .close-welcome').on('click', function () {
        $('#welcomeModal').hide();
    });
    $('#welcomeModal .continue-welcome').on('click', function () {
        $('#welcomeModal').hide();
    });
    //end show modal welcome

    var referral_cookie = $.cookie("referral_cookie");
    if (referral_cookie !== '') {
        $('#referral_slug').val(referral_cookie);
    }
    var site_default = site.replace("/vi", "");

    //event is comming
    var hidden_event_coming = $('#hidden_event_coming').val();
    if (hidden_event_coming !== "") {
        // Set the date we're counting down to
        var countDownDate = Date.parse(parseDateString(hidden_event_coming));

        // Update the count down every 1 second
        var x = setInterval(function () {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"
            document.getElementById("head_event_coming").innerHTML = days + "<span class='txt-time'>D</span>" + hours + "<span class='txt-time'>H</span>"
                + minutes + "<span class='txt-time'>M</span>" + seconds + "<span class='txt-time'>S</span>";

            // If the count down is over, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("head_event_coming").innerHTML = "EXPIRED";
            }
        }, 600);
    }


    // if(hidden_event_coming != ""){
    //     $("#head_event_coming").timeTo({
    //         timeTo: hidden_event_coming,
    //         countdown: true,
    //         displayDays: 2,
    //         theme: "white",
    //         displayCaptions: true,
    //         fontSize: 15,
    //         captionSize: 14,
    //         languages: {
    //             en: { days: "D",   hours: "H",  min: "M",  sec: "S" }
    //         },
    //         lang: "en"
    //     });
    // }
    $('#close-tab-event').on('click', function (e) {
        e.preventDefault();
        $('#event_coming').remove();
    });

    //get title top check shipping
    if ($('#top_notification_shipping').length > 0) {
        var text_top_shipping = $('#top_notification_shipping').html();
        $('.top_notification_shipping_mobile').html(text_top_shipping);
    } else {
        $('.top_notification_shipping_mobile').remove();
    }

    $('input#register_account').on('change', function (e) {
        e.preventDefault();
        if ($('input#register_account:checkbox:checked').length > 0) {
            $('.register_account_password').addClass('active');
        } else {
            $('.register_account_password').removeClass('active');
        }
    });
    //studio_schedule
    $('input[name="studio_schedule"]').on('change', function () {
        if ($(this).val() == 'PDF') {
            $('#hidden_schedule_pdf').addClass('active');
            $('#hidden_schedule_url').removeClass('active');
        } else {
            $('#hidden_schedule_pdf').removeClass('active');
            $('#hidden_schedule_url').addClass('active');
        }
    });
    if ($('#sec-olaben-fam').length > 0) {
        $("#certificate").fileinput({
            'theme': 'explorer-fas',
            'showUpload': false,
            overwriteInitial: false,
            initialPreviewAsData: true,
            allowedFileExtensions: ['jpg', 'png', 'gif'],
            maxFileSize: 20000,
            maxFilesNum: 10,
            initialPreview: [],
            initialPreviewConfig: []
        });

        $("#schedule_pdf").fileinput({
            'theme': 'explorer-fas',
            'showUpload': false,
            overwriteInitial: false,
            initialPreviewAsData: true,
            allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf'],
            maxFileSize: 20000,
            maxFilesNum: 10,
            initialPreview: [],
            initialPreviewConfig: []
        });

        $("#frm-olaben-fam").validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            rules: {
                fullname: "required",
                email_olaben_fam: "required",
                address: "required",
                slt_countries: "required",
                slt_states: "required",
                slt_cities: "required",
                zipcode: "required",
                terms_conditions: "required",
                certificate: {
                    required: true,
                    extension: "jpeg|jpg|png|pdf"
                }
            },
            messages: {
                fullname: "Full Name is required.",
                email_olaben_fam: "Email is required.",
                address: "Address is required.",
                slt_countries: "Country is required.",
                slt_states: "State is required.",
                slt_cities: "City is required.",
                zipcode: "Zipcode is required.",
                terms_conditions: "Terms and Conditions is required.",
                certificate: {
                    required: "Teaching Certificate is required.",
                    extension: "File is not in type jpeg, jpg, png, pdf."
                }
            },
            errorElement: 'div',
            errorLabelContainer: '.errorTxt',
            invalidHandler: function (event, validator) {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });
    }

    //when user keyup quantity
    $('input#quantity').on('input', function (e) {
        $('#btn_cart_primary').attr('data-quantity', $(this).val());
        $('#buy_now_single_button').attr('data-quantity', $(this).val());
    });

    $(document).on('click', '.filter-options .filter-options-title', function (e) {
        $('.wrapper_filters').removeClass('active');
        $(this).closest('.filter-options').find('.wrapper_filters').addClass('active');
    });

    $(document).on('click', 'ul#footer_menu>li', function (e) {
        $('ul#footer_menu>li').removeClass('active');
        $(this).addClass('active');
    });


    $(document).on('click', '.product_feature_item .item-thumb a.pop', function (e) {
        e.preventDefault();
        var id_content = $(this).attr('data-id');
        if (id_content !== '') {
            $('aside.modal-popup[aria-describedby="' + id_content + '"]').addClass('_show').after('<div class="modals-overlay"></div>');
            $('aside.modal-popup[aria-describedby="' + id_content + '"]').css('z-index', 902);
        } else {
            return false;
        }
    });

    $(document).on('click', '.product_feature_item .pro-info .tbl_add_cart a', function (e) {
        e.preventDefault();
        var id_content = $(this).attr('data-id');
        if (id_content !== '') {
            $('aside.modal-popup[aria-describedby="' + id_content + '"]').addClass('_show').after('<div class="modals-overlay"></div>');
            $('aside.modal-popup[aria-describedby="' + id_content + '"]').css('z-index', 902);
        } else {
            return false;
        }
    });

    $('.tab_sidebar_menu_product h4.title_cat').on('click', function () {
        if ($('#product_siderbar_menu').hasClass('active')) {
            $('#product_siderbar_menu').removeClass('active');
        } else {
            $('#product_siderbar_menu').addClass('active');
        }
        if ($('.tab_sidebar_menu_product h4.title_cat span.down').hasClass('active')) {
            $('.tab_sidebar_menu_product h4.title_cat span.down').removeClass('active');
        } else {
            $('.tab_sidebar_menu_product h4.title_cat span.down').addClass('active');
        }
    });

    $(document).on('click', '.modal-slide .modal-header', function (e) {
        e.preventDefault();
        $('aside.modal-popup').removeClass('_show').after('<div class="modals-overlay"></div>');
        $('.modals-overlay').remove();
        $('aside.modal-popup').removeAttr('style');
    });

    $(window).on('load', function () {
        append_menu('.load_new_menu');
        append_menu('.menu-shop');
        var iframe = $('.iframe_vimeo');
        $.each(iframe, function (index, value) {
            var player = new Vimeo.Player(value);
            player.setVolume(0);
            player.on('play', function () {
                console.log('played the video!');
            });
        });
    });

    $(document).on('click', '.poster_videos', function () {
        $(this).hide();
        var fm = $(this).parent().find('.vimeo_single_content_iframe');
        var player = new Vimeo.Player(fm);
        player.setVolume(0);
        player.play();
    });

    $('a.images-rotation').hover(
        function () {
            var image_src_change = $(this).attr('data-images');
            $(this).find('img').attr('src', image_src_change);
        }, function () {
            var image_src_current = $(this).attr('data-default');
            $(this).find('img').attr('src', image_src_current);
        }
    );

    $("#search_input").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "/autocomplete",
                dataType: "json",
                data: {
                    query_string: request.term
                },
                success: function (data) {
                    response($.map(data.suggestions, function (item) {
                        return {
                            label: item.title,
                            url: item.url,
                            type: item.type
                        };
                    }));
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            window.location = ui.item.url;
        }
    });

    $("#search_input_mobile").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "/autocomplete",
                dataType: "json",
                data: {
                    query_string: request.term
                },
                success: function (data) {
                    response($.map(data.suggestions, function (item) {
                        return {
                            label: item.title,
                            url: item.url,
                            type: item.type
                        };
                    }));
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            window.location = ui.item.url;
        }
    });

    /*Menu check has child*/
    $("#category_products_menu_static li a").each(function () {
        if ($(this).parent().children("ul").length) {
            $(this).addClass("sub");
        }
    });

    //check form register
    $('#email').on('change', function () {
        var data = {
            'email': $('#email').val(),
            '_token': getMetaContentByName("_token")
        };
        $.ajax({
            url: site + '/ajax/check-register',
            type: "post",
            data: data,
            success: function (result) {
                if (result) {
                    $("#error-form-email").html('Email already used.');
                    $('.btn-submit-customer').attr("disabled", true);
                } else {
                    $("#error-form-email").html('');
                    $('.btn-submit-customer').attr("disabled", false);
                }
            }
        });
    });

    $('#phone').on('change', function () {
        var data = {
            'phone': $('#phone').val(),
            '_token': getMetaContentByName("_token")
        };
        $.ajax({
            url: site + '/ajax/check-register',
            type: "post",
            data: data,
            success: function (result) {
                if (result) {
                    $("#error-form-phone").html('Phone number already in use.');
                    $('.btn-submit-customer').attr("disabled", true);
                } else {
                    $("#error-form-phone").html('');
                    $('.btn-submit-customer').attr("disabled", false);
                }
            }
        });
    });

    $('.btn_edit').on('click', function () {
        $('.btn_update').addClass('active');
        $('.btn_cancel').addClass('active');
        $('.btn_edit_form').addClass('active');
        $('.avatar-wrapper').addClass('active');
        $('.avatar_user').addClass('active');
        //remove attr
        $('.edit_profile input[name="first_name"]').removeAttr("disabled");
        $('.edit_profile input[name="phone"]').removeAttr("disabled");
        $('.edit_profile input[name="last_name"]').removeAttr("disabled");
        $('.edit_profile input[name="address"]').removeAttr("disabled");
        $('.edit_profile textarea[name="about_me"]').removeAttr("disabled");
    });

    $('.btn_cancel input[type="button"]').on('click', function () {
        location.reload();
    });

    $(document).on('click', '#filter_content_sidebar .box_group_fitter', function () {
        $(this).toggleClass("active", 1000);
        $(this).parent().find("ul").toggleClass("active", 10);
    });

    $('a.images-rotation').hover(
        function () {
            var image_src_change = $(this).attr('data-images');
            $(this).find('img').attr('src', image_src_change);
        }, function () {
            var image_src_current = $(this).attr('data-default');
            $(this).find('img').attr('src', image_src_current);
        }
    );

    //rewrite url pagenavi
    $('.pagination a').each(function () {
        var old_href_pagenavi = $(this).attr('href');
        var url_current = window.location.href;
        var check_1 = url_current.replace('?', 'isset');
        if (check_1 != url_current) {
            var last_word = old_href_pagenavi.length;
            var last_word_current_url = url_current.length;
            var cut_word = old_href_pagenavi.lastIndexOf('?');
            var check_position_page = url_current.indexOf('page=');
            var page_number_navi = url_current.substring(check_position_page, last_word_current_url);
            var check_2 = url_current.replace('page=', 'isset');
            var c = check_2.normalize();
            var b = url_current.normalize();
            if (c === b) {
                var word = old_href_pagenavi.substring(cut_word, last_word);
                word = word.replace('?', '&');
                var new_url = url_current + word;
            } else {
                var page_number = old_href_pagenavi.substring(old_href_pagenavi.indexOf('page='), last_word);
                var new_url = url_current.replace(page_number_navi, page_number);
                console.log(page_number_navi);
            }
            $(this).attr('href', new_url);
        }
    });

    $('.categories_list .title_block').on('click', function () {
        if ($('.filter').hasClass('active')) {
            $('.filter').removeClass('active');
        } else {
            $('.filter').addClass('active');
        }
    });


    $.ajaxSetup({cache: false});
    headerStyle();
    $(".chat_fb").click(function () {
        $('.fchat').toggle('slow');
    });

    $(window).on('load resize', function () {
        /*xzoom gallery*/
        if (window.innerWidth > 768) {
            var img = $("#xzoom-default").height();
            $("#xzoom-thumbs").css({"max-height": img + 'px', "overflow": "auto"})
        } else {
            var img = $("#xzoom-default").width();
            $("#xzoom-thumbs").css({"max-width": img + 'px', "overflow": "auto", "white-space": "nowrap"})
        }
        $('#xzoom-default').trigger("mouseover");
    });
    $('#size_sp_choose').click(function () {
        $("#size_sp_click").animate({
            height: 'toggle'
        });
    });
    /*End xzoom gallery*/

    var max_width_default = $("#singleProductImg").width();
    $('#xzoom-img').css("max-width", max_width_default);
    $('.thumb-nav-xzoom').css("max-width", max_width_default);
    var players = [];
    $('.top_slider_home_text').owlCarousel({
        autoplay: true,
        autoplayTimeout: 2000,
        autoplayHoverPause: true,
        animateIn: 'slideInRight',
        animateOut: 'slideOutLeft',
        loop: true,
        margin: 0,
        items: 1,
        nav: true,
        dots: false,
        autoHeight: true,
        center: true,
        responsiveClass: true,
        navText: ["<svg class=\"icon-banner-arrow left\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 8 18\" width=\"8\" height=\"18\">\n" +
        "  <path fill=\"#FFFFFF\" d=\"M7.55.978A.705.705 0 007.413.01a.663.663 0 00-.945.142L.019 9.06l6.433 8.558a.664.664 0 00.947.127.704.704 0 00.121-.972L1.713 9.046z\" style=\"fill: rgb(255, 255, 255);\"></path>\n" +
        "</svg>", "<svg class=\"icon-banner-arrow right\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 8 18\" width=\"8\" height=\"18\">\n" +
        "  <path fill=\"#FFFFFF\" d=\"M7.55.978A.705.705 0 007.413.01a.663.663 0 00-.945.142L.019 9.06l6.433 8.558a.664.664 0 00.947.127.704.704 0 00.121-.972L1.713 9.046z\" style=\"fill: rgb(255, 255, 255);\"></path>\n" +
        "</svg>"]

    });

    $('.main_slider_home').owlCarousel({
        autoplay: true,
        autoplayHoverPause: true,
        animateOut: 'fadeOut',
        loop: true,
        margin: 0,
        items: 1,
        nav: true,
        dots: true,
        video: true,
        lazyLoad: true,
        videoWidth: false,
        videoHeight: false,
        autoHeight: true,
        center: true,
        responsiveClass: true
    });

    $('.category_item_home_owl').owlCarousel({
        autoplay: false,
        autoplayHoverPause: false,
        animateOut: 'fadeOut',
        loop: false,
        margin: 0,
        items: 4,
        nav: false,
        dots: false,
        video: true,
        lazyLoad: true,
        videoWidth: false,
        videoHeight: false,
        autoHeight: true,
        center: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 2
            },
            640: {
                items: 2
            },
            650: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    });

    $(".backdrop").click(function () {
        var i = $(this).hide().index('.backdrop');
        players[i].playVideo();
    });

    $("#frm_welcome_modal").validate({
        rules: {
            welcome_email: {
                required: true
            }
        },
        messages: {
            welcome_email: {
                required: 'Please enter your email.'
            }
        },
        submitHandler: function (form) {
            $('#frm_welcome_modal .loading_form').addClass('active');
            $.ajax({
                type: 'POST',
                url: site + '/ajax/welcome-modal',
                dataType: 'json',
                data: $('form#frm_welcome_modal').serialize(),
                success: function (result) {
                    if (result == 'success') {
                        $('#frm_welcome_modal .loading_form').removeClass('active');
                        $('#welcomeModal .welcome_step_1').addClass("active");
                        $('#welcomeModal .welcome_step_2').addClass("active");
                        $('#welcomeModal #frm_welcome_error').removeClass("active");
                        $('#welcomeModal #frm_welcome_error').html("");
                    } else {
                        $('#frm_welcome_modal .loading_form').removeClass('active');
                        $('#welcomeModal #frm_welcome_error').addClass("active");
                        $('#welcomeModal #frm_welcome_error').html("Opps! Something error. May be you already get this code.");
                    }
                }
            });
            return false;
        }
    });

    var url_home = 'http://olaben.com/';
    //ajax load product-size
    $('.list_size input[type="checkbox"]').on('click', function () {
        var size = [];
        $('.list_size input[type="checkbox"]:checked').each(function (i) {
            size[i] = $(this).val();
        });
        var cate_slug = $('.cate-slug').val();
        var data = {
            'size': size,
            'cate_slug': cate_slug
        };
        $.ajax({
            url: site + '/ajax/get-cate-on-size',
            type: "GET",
            data: data,
            success: function (result) {
                $(".list_theme_category").html(result);
            }
        });
    });

    $("#backtop-btn").click(function (e) {
        e.preventDefault();
        $("body,html").animate({scrollTop: 0}, 500);
    });

    //validation form customer
    $.validator.addMethod(
        "regex",
        function (value, element, regexp) {
            var check = false;
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        }, ""
    );

    $("#customer-register").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            first_name: "required",
            last_name: "required",
            email: "required",
            password: "required",
            password_confirmation: "required",
            birthday_month: {required: true, regex: '^(((0)[0-9])|((1)[0-2]))$'},
            birthday_day: {required: true, regex: '^([0-2][0-9]|(3)[0-1])$'},
            birthday_year: {required: true, regex: '^([0-9]{4})$'},
            phone: "required",
            address: "required"
        },
        messages: {
            first_name: "Enter your First Name",
            last_name: "Enter your Last Name",
            email: "Enter your E-mail",
            password: "Enter your Password",
            password_confirmation: "Enter your confirm password",
            birthday_month: {required: "Enter the month", regex: "Month [01-12]"},
            birthday_day: {required: "Enter the date", regex: "Date [01-31]"},
            birthday_year: {required: "Enter the year", regex: "Year 1950 to now"},
            phone: "Enter your phone number",
            address: "Enter your address",
        },
    });
    $(window).scroll(function () {
        $(this).scrollTop() > 100 ? $("#back-top").fadeIn() : $("#back-top").fadeOut().fadeOut()
    }), $("html, body").on("click", "#back-top", function () {
        return $("html, body").animate({scrollTop: 0}, 600), !1;
    });

    $(window).on("load resize scroll", function (e) {
        fnScrollFixed();
    });
    $(window).on('load resize', function () {

        var vs_980 = window.matchMedia("only screen and (max-width: 980px)");
        if (vs_980.matches) {
            //$('.main-header').addClass('fixed-header');
            $(window).on('scroll', function () {
                headerStyle();
            });
        } else {
            $(window).on('scroll', function () {
                headerStyle();
            });
        }
    });

    $('span.toc_toggle a').click(function (event) {
        event.preventDefault();
        $(this).toggleClass('active');
        $('ul.toc_list').toggle();
    });
    $('ul.toc_list li a').click(function (event) {
        var href_link = $(this).attr('href');
        $('body,html').animate({
            scrollTop: $(href_link).offset().top
        }, 800);
        $('header#header').removeClass('scrolled');
        event.preventDefault();
        return false;
    });


    $("#menu_btn").delegate("a#show_menu_hover", "click", function (e) {
        e.preventDefault();
        if ($("#sidr-right-menu").hasClass("right")) {

            $('#sidr-right-menu').removeClass('right');
            $('#page').removeClass('right');
            $('header#branding').removeClass('icon_menu_fixed');

        } else {

            $('#sidr-right-menu').addClass('right');
            $('#page').addClass('right');
            $('header#branding').addClass('icon_menu_fixed');

        }
    });

    $("#primary-menu").delegate(".plus,.prevent", "click", function (e) {
        e.preventDefault();
        if ($(this).hasClass("minus")) {
            $(this).parent().find('ul').slideUp();
            $(this).removeClass('minus');
        } else {
            $(this).addClass('minus');
            $(this).parent().find('ul').slideDown();

        }
    });

    var $megamenu_options = {
        activeClass: 'open',
        fadeInDuration: 150,
        fadeOutDuration: 'normal',
        hoverTimeout: 450
    };
    $(window).on('load resize', function () {
        var vs_767 = window.matchMedia("only screen and (max-width: 767px)");
        if (vs_767.matches) {
            $('ul#primary-menu>li>ul').removeClass('mega_menu');
            $('ul#primary-menu>li>ul').removeClass('animated');
            $('ul#primary-menu>li>ul').removeClass('fadeOutDown');
            //$('#primary-menu').Megadropdown($megamenu_options);
        } else {
            $('ul#primary-menu>li>ul').addClass('mega_menu');
            $('ul#primary-menu>li>ul').addClass('animated');
            $('#primary-menu').Megadropdown($megamenu_options);
        }
    });

    $('#slt_countries').on('change', function () {
        if ($('#slt_countries').val() == '') {
            return;
        } else {
            let country_id = $('#slt_countries').val()
            var data_type = {
                '_token': getMetaContentByName("_token"),
                'country': country_id
            };
            $.ajax({
                url: site + '/get-states',
                type: 'GET',
                data: data_type,
                dataType: 'JSON',
                beforeSend: function () {
                },
                success: function (result) {
                    if (country_id == 240) {
                        $('.ward-control').addClass('active');
                    } else {
                        $('.ward-control').removeClass('active');
                    }
                    let html = '<option value="">Select State</option>';
                    if (result.success) {
                        let i;
                        let data = result.data;
                        for(i = 0; i < data.length; i++) {
                            html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        }
                        $('#slt_states').html(html);
                    }
                },
                complete: function () {
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        }
    });

    $('#slt_states').on('change', function () {
        if ($('#slt_states').val() == '') {
            return;
        } else {
            let country_id = $('#slt_countries').val();
            var data_type = {
                '_token': getMetaContentByName("_token"),
                'state': $('#slt_states').val()
            };

            if (country_id == 240) {
                $.ajax({
                    url: site + '/get-districts',
                    type: 'GET',
                    data: data_type,
                    dataType: 'JSON',
                    beforeSend: function () {
                    },
                    success: function (result) {
                        let html = '<option value="">Select City</option>';
                        if (result.success) {
                            let i;
                            let data = result.data;
                            for(i = 0; i < data.length; i++) {
                                html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                            }
                            $('#slt_cities').html(html);
                        }
                    },
                    complete: function () {
                    },
                    error: function (errorThrown) {
                        console.log(errorThrown);
                    }
                });
            } else {
                $.ajax({
                    url: site + '/get-cities',
                    type: 'GET',
                    data: data_type,
                    dataType: 'JSON',
                    beforeSend: function () {
                    },
                    success: function (result) {
                        let html = '<option value="">Select City</option>';
                        if (result.success) {
                            let i;
                            let data = result.data;
                            for(i = 0; i < data.length; i++) {
                                html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                            }
                            $('#slt_cities').html(html);
                        }
                    },
                    complete: function () {
                    },
                    error: function (errorThrown) {
                        console.log(errorThrown);
                    }
                });
            }
        }
    });
    $('#slt_cities').on('change', function () {
        if ($('#slt_cities').val() == '') {
            return;
        } else if ($('#slt_countries').val() == 240) {
            var data_type = {
                '_token': getMetaContentByName("_token"),
                'district': $('#slt_cities').val()
            };
            $.ajax({
                url: site + '/get-wards',
                type: 'GET',
                data: data_type,
                dataType: 'JSON',
                beforeSend: function () {
                },
                success: function (result) {
                    let html = '<option value="">Select Ward</option>';
                    if (result.success) {
                        let i;
                        let data = result.data;
                        for(i = 0; i < data.length; i++) {
                            html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        }
                        $('#slt_wards').html(html);
                    }
                },
                complete: function () {
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        }
    });

    $('#slt_countries').select2();
    $('#slt_states').select2();
    $('#slt_cities').select2();
    $('#slt_wards').select2();

    $('#type_shipping').on('change', function () {
        if ($('#type_shipping').val() == '') {
            return;
        } else {
            var arr = [];
            var data_type = {
                '_token': getMetaContentByName("_token"),
                'data': $('#type_shipping').val()
            };
            $.ajax({
                url: site + '/ajax/get-fee-shipping',
                type: 'POST',
                data: data_type,
                dataType: 'html',
                beforeSend: function () {
                },
                success: function (data) {
                    arr = JSON.parse(data);
                    if (isFreeShip == 1) {
                        $('#shipping-fee').html('Miễn phí giao hàng');
                        $('#shipping_fee').val(0);
                    } else {
                        $('#shipping-fee').html(arr['fee_html']);
                        $('#shipping_fee').val(arr['fee']);
                        $('span.woocommerce-Price-amount').html(arr['cart_total']);
                    }
                },
                complete: function () {
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        }
    });

    $('.toggle-menu').jPushMenu({closeOnClickLink: false});
    $(".flexnav").flexNav({'animationSpeed': 'fast'});

    checkSizeQuantitySingle();

    $('#check_out_frm #slt_countries').on('change', function () {
        if ($('#check_out_frm #slt_countries').val() == '') {
            return;
        } else {
            var data_type = {
                '_token': getMetaContentByName("_token"),
                'data': $('#check_out_frm #slt_countries').val(),
                'current_lang': current_lang
            };
            $.ajax({
                url: site + '/ajax/get-price-shipping',
                type: 'POST',
                data: data_type,
                dataType: 'html',
                beforeSend: function () {
                },
                success: function (result) {
                    var arr = JSON.parse(result);
                    $('#delivery_charges').html(arr['shipping_fee_html']);
                    $('#delivery_ip').val(arr['shipping_fee']);
                    $('#price_total_cart').html(arr['total']);
                    $('#price_tax').html(arr['tax_html']);
                },
                complete: function () {
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        }
    });

    $('.qty-down').click(function (e) {
        if ($(this).parent().find('.qty-val').val() > 1) {
            var id_product = $(this).parent().find('.qty-val').attr("data-id-pro");
            var newQty = parseInt($(this).parent().find('.qty-val').val()) - 1;
            $(this).parent().find('.qty-val').val(newQty);
        }

        var sendInfo = {
            '_token': $('input[name=_token]').val(),
            'qty': newQty,
            'id_product': id_product,
            'shipping_fee': $('#delivery_ip').val(),
            'country': $('#check_out_frm #slt_countries').val(),
        };
        var arr = [];
        $.ajax({
            type: 'POST',
            url: site + '/ajax-update-cart',
            data: sendInfo,
            success: function (result) {
                arr = JSON.parse(result);
                $('span[data-total-id="' + id_product + '"]').html(arr['new_price']);
                $('#price_subtotal').html(arr['subtotal']);
                $('#price_total_cart').html(arr['total']);
                $('#price_tax').html(arr['tax']);
                if (arr['shipping_fee_html'] != "") {
                    $('#delivery_charges').html(arr['shipping_fee_html']);
                }
                $('#delivery_ip').val(arr['shipping_fee']);
            }
        });
        setTimeout(function () {
            $.ajax({
                url: site + "/ajax/ajax_load_cart",
                data: {
                    'lang': current_lang
                },
                type: "GET",
                datatype: 'html',
                success: function (result) {
                    $('.shopping-cart').html(result);
                },
                error: function () {
                }
            });
        }, 500);
        e.preventDefault();
    });
    $('.qty-up').click(function (e) {
        var id_product = $(this).parent().find('.qty-val').attr("data-id-pro");
        var newQty = parseInt($(this).parent().find('.qty-val').val()) + 1;
        $(this).parent().find('.qty-val').val(newQty);
        var sendInfo = {
            '_token': $('input[name=_token]').val(),
            'qty': newQty,
            'id_product': id_product,
            'shipping_fee': $('#delivery_ip').val(),
            'current_lang': current_lang,
            'country': $('#check_out_frm #slt_countries').val(),
        };
        $.ajax({
            type: 'POST',
            url: site + '/ajax-update-cart',
            data: sendInfo,
            success: function (result) {
                arr = JSON.parse(result);
                $('span[data-total-id="' + id_product + '"]').html(arr['new_price']);
                $('#price_subtotal').html(arr['subtotal']);
                $('#price_total_cart').html(arr['total']);
                $('#price_tax').html(arr['tax']);
                if (arr['shipping_fee_html'] != "") {
                    $('#delivery_charges').html(arr['shipping_fee_html']);
                }
                $('#delivery_ip').val(arr['shipping_fee']);
            }
        });
        setTimeout(function () {
            $.ajax({
                url: site + "/ajax/ajax_load_cart",
                data: {
                    'lang': current_lang
                },
                type: "GET",
                datatype: 'html',
                success: function (result) {
                    $('.shopping-cart').html(result);
                },
                error: function () {
                }
            });
        }, 500);
        e.preventDefault();
    });


    $(window).on('mousewheel DOMMouseScroll', function (e) {
        var direction = (function () {
            var delta = (e.type === 'DOMMouseScroll' ?
                e.originalEvent.detail * -40 :
                e.originalEvent.wheelDelta);
            return delta > 0 ? 0 : 1;
        }());
        if (direction === 1) {
            if ($('header#site_header.header_modify').length > 0) {
                //$('header#site_header').removeClass('transf');
            } else {
                $('header#site_header').addClass('header_modify');

            }
        }
        if (direction === 0) {
            //scroll up
            if ($('header#site_header.header_modify').length > 0) {
                $('header#site_header').removeClass('header_modify');
            } else {
                //not working

            }
        }

    });

});

function fnSetSearchValue(type, value) {
    var url = location.protocol + '//' + location.host + location.pathname;
    url = url + "?";

    var urlParams = new URLSearchParams(window.location.search);

    var allParams = urlParams.toString();

    var page = "";
    if (getUrlParameter("page") != "") {
        page = "page=" + getUrlParameter("page");

        allParams = allParams.replace("&" + page, "");

        allParams = allParams.replace(page, "");
    }

    if (getUrlParameter(type) != "") {
        allParams = allParams.replace(getUrlParameter(type), value);
    } else {
        if (allParams == "") {
            allParams = type + "=" + value;
        } else {
            allParams = allParams + "&" + type + "=" + value;
        }
    }
    var newUrl = url + allParams;
    window.location.href = newUrl + '&page=1';
}

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};

function Load_Releated_Cate_Theme($slug, $page, e) {
    //history.pushState(null, null, '#' + id);
    $ = jQuery;
    e.preventDefault();
    var sendInfo = {
        '_token': $('input[name=_token]').val(),
        'category': $slug,
        'page': $page,
        'load': 'auto'
    };
    $.ajax({
        url: site + '/ajax/load-theme',
        type: 'POST',
        data: sendInfo,
        dataType: 'html',
        beforeSend: function () {
            if ($page > 1) {
                $('body,html').animate({
                    scrollTop: $('#row_releated_theme_category').offset().top - 40
                }, 800);
            }
            $('#row_releated_theme_category').html('<p class="tai">Đang tải dữ liệu..</p></p><div id="loader"></div>');
        },
        success: function (data) {
            $('#row_releated_theme_category').html(data);
        },
        complete: function () {
            $(".html5lightbox").html5lightbox();
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}

function Load_Releated_Cate_post($slug, $slug1, $page, e) {
    var $ = jQuery;
    e.preventDefault();
    $.ajax({
        type: 'post',
        url: site + '/ajax/load-post',
        data: {
            '_token': $('input[name=_token]').val(),
            'category_current': $slug,
            'category_parent': $slug1,
            'page': $page,
            'load': 'auto'
        },
        dataType: "html",
        beforeSend: function () {
            if ($page > 1) {
                $('body,html').animate({
                    scrollTop: $('#row_releated_theme_category').offset().top - 40
                }, 800);
            }
            $('#row_releated_theme_category').html('<p class="tai">Đang tải dữ liệu..</p></p><div id="loader"></div>');
        },
        success: function (data) {
            $('#row_releated_theme_category').html(data);
            //return false;
        },
        complete: function () {
            $(".html5lightbox").html5lightbox();
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
    return false;
}

function addQuantityDetail(quan) {
    var crQuan = $("#quantity").val() * 1;
    var newQuan = crQuan + quan;
    if (newQuan <= 0) {
        newQuan = 1;
    }
    $("#quantity").val(newQuan);
    $('#btn_cart_primary').attr('data-quantity', newQuan);
}

function fnScrollFixed() {
    var $ = jQuery;

    var $scrollingDiv = $("#fixed_content_detail");
    var w = $(".main-container").width();
    var h = $(window).innerHeight() - $(".header3").height();
    var containerw = $(".container").width();
    var delta = (w - containerw) / 2;
    var contentwidth = $("#fixed_content_detail").width() + 30;
    var ctheight = $(".main-container").height();
    var le = $("#singleProductImg").width() + delta;
    var $scrollingDiv = $("#fixed_content_detail");
    //console.log('width:'+$scrollingDiv.width());
    if (window.innerWidth > 767) {
        if ($('#endfixed').length > 0) {
            var y = $(window).scrollTop(),
                maxY = $('#endfixed').offset().top,
                scrollHeight = $scrollingDiv.height();
            //console.log('Max:'+maxY+'Scroll:'+y+'height:'+scrollHeight);
            //debugger;
            if ($(window).scrollTop() > 80) {
                if ((y + scrollHeight) >= maxY) {
                    //console.log('hieht:'+scrollHeight);
                    $('#fixed_content_detail').css({
                        top: maxY - (y + scrollHeight),
                        position: 'fixed',
                        width: $scrollingDiv.width()
                    });
                } else {
                    $('#fixed_content_detail').css({top: 95, position: 'fixed', width: $scrollingDiv.width()});
                }
            } else {
                $('#fixed_content_detail').css({position: 'static', width: '90%'});
            }
            if (y < (maxY - scrollHeight - 230)) {
                if (($(window).scrollTop()) > 0) {
                    //$scrollingDiv.css({"marginTop": (($(window).scrollTop()) - 70) + "px"});
                    //$scrollingDiv
                    //.stop()
                    //.animate({"marginTop": (($(window).scrollTop()) - 70) + "px"}, "slow" );
                } else {
                    //$scrollingDiv.css({"marginTop": (($(window).scrollTop())) + "px"});
                    //$scrollingDiv
                    // .stop()
                    // .animate({"marginTop": (($(window).scrollTop())) + "px"}, "slow" );
                }
            }
        }
    }
    return true;
}

function removeCartItem(product_id) {
    (function ($) {
        var sendInfo = {
            '_token': getMetaContentByName("_token"),
            'product_id': product_id,
        };
        $.ajax({
            url: site + '/remove-cart-item',
            type: "POST",
            data: sendInfo,
            datatype: 'JSON',
            cache: false,
            success: function (response) {
                if (response.success == true) {
                    Swal.fire(
                        'Success',
                        response.message,
                        'success'
                    ).then((result) => {
                        location.reload(true);
                    })
                } else {
                    Swal.fire(
                        'Oops...',
                        response.message,
                        'error'
                    );
                }
            }
        });
    })(jQuery);
}

function reloadCart() {
    (function ($) {
        $.ajax({
            url: site + '/ajax/reload-cart',
            type: "GET",
            datatype: 'HTML',
            cache: false,
            success: function (html) {
                $('.shopping-cart').html(html);
            }
        });
    })(jQuery);
}

function append_menu(menu_class) {
    if ($('ul#primary_menu_top ' + menu_class).length > 0) {
        if ($('#template_menu_page ' + menu_class).length > 0) {
            var content_menu = $('#template_menu_page ' + menu_class).html();
            $('#primary_menu_top ' + menu_class).html(content_menu);
            return true;
        }
    }
}

function headerStyle() {
    var $ = jQuery;
    if ($('.main-header').length) {
        var windowpos = $(window).scrollTop();
        if (windowpos >= 100) {
            //$('.main-header').addClass('fixed-header');
            $('#back-top').fadeIn(300);
        } else {
            //$('.main-header').removeClass('fixed-header');
            $('#back-top').fadeOut(300);
        }
    }
}

function addToWishList(product_id) {
    (function ($) {
        if (!islogin) {
            $('#myModal').modal('show');
            return false;
        }
        var sendInfo = {
            '_token': getMetaContentByName("_token"),
            'product_id': product_id,
        };
        $.ajax({
            url: site + '/add-to-wishlist',
            type: "POST",
            data: sendInfo,
            cache: false,
            success: function (result) {
                if (result) {
                    alert('Success add to wishlist.');
                    location.reload(true);
                } else {
                    alert('Product already in wishlist.');
                }
            }
        });
        return false;
    })(jQuery);
}

function postRating(id_product) {
    (function ($) {
        if (!islogin) {
            $('#myModal').modal('show');
            return false;
        }
        var rating = $('input[name="ratings[1]"]:checked').val();
        if (isEmpty(rating)) {
            return false;
        }
        var sendInfo = {
            '_token': getMetaContentByName("_token"),
            'id_product': id_product,
            'rating': rating,
        };
        $.ajax({
            url: site + '/customer/post-reviews',
            type: "POST",
            data: sendInfo,
            cache: false,
            success: function (html) {
                if (html == 1) {
                    alert('Thank you for rating.');
                    location.reload(true);
                } else {
                    alert('You have already rated this product.');
                }
            }
        });
        return false;
    })(jQuery);
}

function more_reviews(media_id, current_page, next_page) {
    (function ($) {
        var sendInfo = {
            '_token': getMetaContentByName("_token"),
            'media_id': media_id,
            'current_page': current_page,
            'next_page': next_page
        };
        $.ajax({
            url: site + '/ajax/more-reviews',
            type: "POST",
            data: sendInfo,
            cache: false,
            success: function (html) {
                if (html) {
                    $(".paging").remove();
                    $("#reviews").append(html);
                } else{
                    alert('Sorry, unexpected error. Please try again later.');
                }
            }
        });
        return false;
    })(jQuery);
}

function checkout_login() {
    (function ($) {
        var email = $('input[name="lg_email"]').val();
        var password = $('input[name="lg_password"]').val();

        if (isEmpty(email) || isEmpty(password)) {
            var error_text = "<p>Email & Password is required.</p>";
            $('.error-login').html(error_text);
            return false;
        }
        var sendInfo = {
            '_token': getMetaContentByName("_token"),
            'email': email,
            'password': password,
        };
        $.ajax({
            url: site + '/ajax/checkout-login',
            type: "POST",
            data: sendInfo,
            cache: false,
            success: function (html) {
                if (html == 1) {
                    location.reload(true);
                } else {
                    var error_text = "<p>Email or Password is incorrect.</p>";
                    $('.error-login').html(error_text);
                }
            }
        });
        return false;
    })(jQuery);
}

function check_code_discount() {
    (function ($) {
        var country = $('#slt_countries').val();
        var user_mail = $('.form_user_cart input[name="email"]').val();
        var user_phone = $('.form_user_cart input[name="phone"]').val();
        if (country == "" || user_mail == "" || user_phone == "") {
            $('#voucherAndCouponsErrorsContainer').html('Please choose country, email, phone first.');
            $('#voucherAndCouponsErrorsContainer').addClass('active');
            return false;
        } else {
            $('#voucherAndCouponsErrorsContainer').removeClass('active');
            var arr = [];
            var code_discount = $('#voucherInput').val();
            if (code_discount !== '') {
                var sendInfo = {
                    '_token': getMetaContentByName("_token"),
                    'code_discount': code_discount,
                    'current_lang': current_lang,
                    'email': user_mail,
                    'phone': user_phone,
                    'country': country
                };
                $.ajax({
                    url: site + '/ajax/check-discount-code',
                    type: "POST",
                    data: sendInfo,
                    cache: false,
                    success: function (html) {
                        if (html == 0) {
                            $('#voucherAndCouponsErrorsContainer').html('The discount code is invalid or has expired');
                            $('#voucherAndCouponsErrorsContainer').addClass('active');
                            $('#voucherAndCouponsSuccessContainer').html('');
                            $('#voucherAndCouponsSuccessContainer').removeClass('active');
                        } else {
                            arr = JSON.parse(html);
                            $('#voucherAndCouponsErrorsContainer').html('');
                            if (arr['error_type_code'] == 'error') {
                                $('#voucherAndCouponsErrorsContainer').html('Code only applies to select items. Keep shopping!');
                                $('#voucherAndCouponsErrorsContainer').addClass('active');
                                $('#voucherAndCouponsSuccessContainer').html('');
                                $('#voucherAndCouponsSuccessContainer').removeClass('active');
                                $('#BillingSummaryTotalPrice').html(arr['price_discount']);
                                $('#BillingSummaryTax').html(arr['tax']);
                            } else if (arr['error_already_use'] == 1) {
                                $('#voucherAndCouponsErrorsContainer').html('You already use this code.');
                                $('#voucherAndCouponsErrorsContainer').addClass('active');
                                $('#voucherAndCouponsSuccessContainer').html('');
                                $('#voucherAndCouponsSuccessContainer').removeClass('active');
                            } else {
                                $('#voucherAndCouponsSuccessContainer').html('Discount code has been applied. You get ' + arr['discount'] + ' off');
                                $('#voucherAndCouponsSuccessContainer').addClass('active');
                                $('#voucherAndCouponsErrorsContainer').html('');
                                $('#voucherAndCouponsErrorsContainer').removeClass('active');
                                $('#BillingSummaryTotalPrice').html(arr['price_discount']);
                                $('#BillingSummaryTax').html(arr['tax']);
                                $('#BillingSummaryShipping').html(arr['delivery']);
                            }
                        }
                    }
                });
            } else {
                $('#voucherAndCouponsErrorsContainer').html('Please enter coupon code');
                $('#voucherAndCouponsErrorsContainer').addClass('active');
                $('#voucherAndCouponsSuccessContainer').html('');
                $('#voucherAndCouponsSuccessContainer').removeClass('active');
            }
        }
    })(jQuery);
}

function see_matching_product(element) {
    (function ($) {
        if ($(element).next().hasClass('active')) {
            $(element).next().removeClass('active');
        } else {
            $(element).next().addClass('active');
        }
    })(jQuery);
}

function close_matching(element) {
    (function ($) {
        $(element).closest(".show_up_matching").removeClass('active');
    })(jQuery);
}

function PopUpLoadGallerySingle(id_theme, variable_id) {
    (function ($) {
        var sendInfo = {
            '_token': getMetaContentByName("_token"),
            'id_theme': id_theme,
            'id_variable': variable_id
        };
        $.ajax({
            url: site + '/ajax/popuploadGalery',
            type: "POST",
            data: sendInfo,
            dataType: 'html',
            cache: false,
            success: function (html) {
                if (html != '') {
                    $('#quick__slider_' + id_theme).html(html);
                    $('.popup_gallery_lookbok').owlCarousel({
                        autoplay: true,
                        autoplayHoverPause: true,
                        animateOut: 'fadeOut',
                        loop: true,
                        margin: 0,
                        items: 1,
                        nav: true,
                        dots: true,
                        video: true,
                        lazyLoad: true,
                        videoWidth: true,
                        videoHeight: true,
                        autoHeight: true,
                        center: true,
                        responsiveClass: true
                    });
                }
            }
        });
    })(jQuery);
}

function sacombankResendOTP(transactionID) {
    (function ($) {
        var sendInfo = {
            '_token': getMetaContentByName("_token"),
            'TransactionID': transactionID
        };
        $.ajax({
            url: site + '/sacombank/resend-otp',
            type: "POST",
            data: sendInfo,
            dataType: 'html',
            cache: false,
            success: function (result) {
                if(result.success) {
                    $('#result-otp').addClass('success');
                    $('#result-otp').removeClass('error');
                } else {
                    $('#result-otp').removeClass('success');
                    $('#result-otp').addClass('error');
                }
                $('#result-otp').html(result.message);
            }
        });
    })(jQuery);
}

function getMetaContentByName(name, content) {
    var content = (content == null) ? 'content' : content;
    return document.querySelector("meta[name='" + name + "']").getAttribute(content);
}

function isEmpty(obj) {
    for (var key in obj) {
        if (obj.hasOwnProperty(key))
            return false;
    }
    return true;
}

function closeCart() {
    $('.my-card').removeClass('cart_show');
}

function checkSizeQuantitySingle() {
    var val_color = $('.choose_color li.choose_variable-item_li input[type=radio]').attr('variableid');
    $('#fixed_content_detail_parent .choose_size li.choose_variable-item_li input[type=radio]').each(function () {
        var id_size_variable = $(this).attr('variableid');
        if ($("#fixed_content_detail_parent #quantity_variable_query_" + id_size_variable + "_" + val_color).val() == 0 || $("#fixed_content_detail_parent  #quantity_variable_query_" + id_size_variable + "_" + val_color).val() == "") {
            $(this).addClass('sold-out');
        } else {
            $(this).removeClass('sold-out');
        }
    });
    if ($('.quick-view-popup').length > 0) {
        $('.quick-view-popup').each(function () {
            var e = $(this);
            var val_color_popup = e.find('.choose_color li.choose_variable-item_li input[type=radio]').attr('variableid');
            e.find('.choose_size li.choose_variable-item_li input[type=radio]').each(function () {
                var id_size_variable = $(this).attr('variableid');
                if (e.find("#quantity_variable_query_" + id_size_variable + "_" + val_color_popup).val() == 0 || e.find("#quantity_variable_query_" + id_size_variable + "_" + val_color_popup).val() == "") {
                    $(this).addClass('sold-out');
                } else {
                    $(this).removeClass('sold-out');
                }
            });
        });
    }
}

function checkSizeQuantitySingleOnChange(val_color) {
    $('#fixed_content_detail_parent .choose_size li.choose_variable-item_li input[type=radio]').each(function () {
        var id_size_variable = $(this).attr('variableid');
        if ($("#fixed_content_detail_parent #quantity_variable_query_" + id_size_variable + "_" + val_color).val() == 0 || $("#fixed_content_detail_parent  #quantity_variable_query_" + id_size_variable + "_" + val_color).val() == "") {
            $(this).addClass('sold-out');
        } else {
            $(this).removeClass('sold-out');
        }
    });
}

function formatNumber(nStr, decSeperate, groupSeperate) {
    nStr += '';
    x = nStr.split(decSeperate);
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
    }
    return x1 + x2;
}

// module product slider home page
function homeProductSlider() {
    jQuery('.home_product_new_item').each(function () {
        var homeProductSlider = jQuery(this);
        if (jQuery(this).find('.product-item').length > 5) {
            homeProductSlider.on('changed.owl.carousel initialized.owl.carousel', function (e) {
                if (e.page.index === 0 || e.page.index === -1) {
                    jQuery(this).find('.owl-prev').addClass('disabled');
                } else if (e.page.index + 1 === e.page.count) {
                    jQuery(this).find('.owl-next').addClass('disabled');
                } else {
                    jQuery(this).find('.owl-prev, .owl-next').removeClass('disabled');
                }
            });
            homeProductSlider.addClass('owl-carousel').owlCarousel({
                nav: true,
                margin: 24,
                items: 5,
                slideBy: 5,
                navText: ['', ''],
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                        stagePadding: 30,
                        margin: 10,
                        loop: true,
                        nav: false
                    },
                    500: {
                        items: 2,
                        stagePadding: 80,
                        margin: 10,
                        loop: true,
                        nav: false
                    },
                    800: {
                        items: 3,
                        nav: false
                    },
                    900: {
                        items: 4,
                        nav: false
                    },
                    1200: {
                        items: 5,
                        nav: true,
                        loop: false
                    },
                    1500: {
                        items: 6,
                        nav: true,
                        loop: false
                    }
                }
            });
        }

    });

}

// module product slider home page
function ChildProductSlider() {
    jQuery('.child_product_new_item').each(function () {
        var ChildProductSlider = jQuery(this);
        if (jQuery(this).find('.product-item').length > 0) {
            ChildProductSlider.on('changed.owl.carousel initialized.owl.carousel', function (e) {
                if (e.page.index === 0 || e.page.index === -1) {
                    jQuery(this).find('.owl-prev').addClass('disabled');
                } else if (e.page.index + 1 === e.page.count) {
                    jQuery(this).find('.owl-next').addClass('disabled');
                } else {
                    jQuery(this).find('.owl-prev, .owl-next').removeClass('disabled');
                }
            });
            ChildProductSlider.addClass('owl-carousel').owlCarousel({
                nav: true,
                margin: 24,
                items: 5,
                slideBy: 5,
                navText: ['', ''],
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                        stagePadding: 30,
                        margin: 10,
                        loop: false,
                        nav: false
                    },
                    500: {
                        items: 2,
                        stagePadding: 80,
                        margin: 10,
                        loop: false,
                        nav: false
                    },
                    800: {
                        items: 3,
                        nav: false
                    },
                    900: {
                        items: 4,
                        nav: false
                    },
                    1200: {
                        items: 5,
                        nav: true,
                        loop: false
                    },
                    1500: {
                        items: 6,
                        nav: true,
                        loop: false
                    }
                }
            })
        }

    });

}

// module product slider home page
function LookboxProductSlider() {
    jQuery('.lookbox_product_new_item').each(function () {
        var LookboxProductSlider = jQuery(this);
        if (jQuery(this).find('.product-item').length > 0) {
            LookboxProductSlider.on('changed.owl.carousel initialized.owl.carousel', function (e) {
                if (e.page.index === 0 || e.page.index === -1) {
                    jQuery(this).find('.owl-prev').addClass('disabled');
                } else if (e.page.index + 1 === e.page.count) {
                    jQuery(this).find('.owl-next').addClass('disabled');
                } else {
                    jQuery(this).find('.owl-prev, .owl-next').removeClass('disabled');
                }
            });
            LookboxProductSlider.addClass('owl-carousel').owlCarousel({
                nav: true,
                margin: 15,
                items: 4,
                slideBy: 4,
                navText: ['', ''],
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                        stagePadding: 30,
                        margin: 10,
                        loop: true,
                        nav: false
                    },
                    500: {
                        items: 2,
                        stagePadding: 80,
                        margin: 10,
                        loop: true,
                        nav: false
                    },
                    800: {
                        items: 2,
                        nav: false
                    },
                    900: {
                        items: 3,
                        nav: false
                    },
                    1200: {
                        items: 3,
                        nav: true,
                        loop: false
                    },
                    1500: {
                        items: 4,
                        nav: true,
                        loop: false
                    }
                }
            })
        }

    });

}

function createVideo(video) {
    var youtubeScriptId = "youtube-api";
    var youtubeScript = document.getElementById(youtubeScriptId);
    var videoId = video.getAttribute("data-video-id");

    if (youtubeScript === null) {
        var tag = document.createElement("script");
        var firstScript = document.getElementsByTagName("script")[0];

        tag.src = "https://www.youtube.com/iframe_api";
        tag.id = youtubeScriptId;
        firstScript.parentNode.insertBefore(tag, firstScript);
    }

    window.onYouTubeIframeAPIReady = function () {
        window.player = new window.YT.Player(video, {
            videoId: videoId,
            playerVars: {
                autoplay: 1,
                modestbranding: 1,
                rel: 0
            }
        });
    };
}

var isIE = navigator.appVersion.match(/MSIE/) == "MSIE";
$.browser = {};
(function ($) {
    $.browser.msie = false;
    $.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        $.browser.msie = true;
        $.browser.version = RegExp.$1;
    }
})(jQuery);
var StuckBlock = function (stuckBlock, outerBlock, devMode) {
    var __this = this;
    this.mode = 'none';
    this.vpDirect = 0;
    this.vpOpposite = 0;
    this.outerBlock = outerBlock;
    this.outerBlock.css({'position': 'relative'});
    this.stuckBlock = stuckBlock;
    this.absolutePosition = 0;
    this.devMode = devMode;
    this.stuckDisable = false;
    this.checkStopWork = function () {
        if (!this.stuckBlock.length) {
            this.stuckDisable = true;
            return false
        }
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
            this.stuckBlock.addClass('iosStuck');
            this.stuckDisable = true;
            return false;
        }
        return true;
    };
    this.init = function () {
        if (!this.checkStopWork()) {
            return false
        }
        var _this = this;
        if (typeof this.stuckCheckScroll === 'undefined') {
            jQuery(window).on('scroll', function () {
                _this.scrollHandle.call(__this);
            });
            this.stuckCheckScroll = true;
        }
        this.applyMode('none');
        if (this.devMode) {
            this.initState();
        }
    };
    this.initState = function () {
        jQuery('body').append('<div id="stuckState"></div>');
        this.stateBlock = jQuery('#stuckState');
        this.stateBlock.css({
            'position': 'fixed',
            'top': '90px',
            'left': '0',
            'padding': '10px',
            'background-color': 'rgba(0,0,0,.6',
            'color': '#fff',
            'font-size': '20px',
            'line-height': '30px',
            'width': '300px',
            'z-index': 1000
        });
    };
    this.showState = function () {
        this.stateBlock.html('' +
            '<span>Direct: ' + this.scrollDirect + '</span><br>' +
            '<span>Mode: ' + this.mode + '</span><br>' +
            '<span>Stuck Inner: ' + this.stuckInner + '</span><br>' +
            '<span>Stuck Height: ' + this.stuckBlock.height + '</span><br>' +
            '<span>Stuck Top: ' + this.stuckBlock.top + '</span><br>' +
            '<span>Outer Height: ' + this.outerBlock.height + '</span><br>' +
            '<span>Outer Top: ' + this.outerBlock.top + '</span><br>' +
            '<br>' +
            '<span>Vp Direct: ' + this.vpDirect + '</span><br>' +
            '<span>Vp Opposite: ' + this.vpOpposite + '</span><br>' +
            '<br>' +
            '<span>Shown Top: ' + this.shownRect.top + '</span><br>' +
            '<span>Shown Bottom: ' + this.shownRect.bottom + '</span><br>' +
            '<br>' +
            '<span>Outer Top: ' + this.outerBlock.top + '</span><br>' +
            '<span>Outer Bottom: ' + this.outerBlock.bottom + '</span><br>' +
            '<br>' +
            '<span>Absolute Pos: ' + this.absolutePosition + '</span><br>' +
            '<span>VP Max: ' + this.vpMax + '</span><br>' +
            '<span>Resized: ' + this.window.resized + '</span><br>' +
            ''
        );
    };
    this.applyStuckCss = function (position, top) {
        if (typeof position === 'undefined') {
            position = 'static';
        }
        if (typeof top === 'undefined' || top === 'auto') {
            top = 'auto';
        } else {
            top = top + 'px';
        }
        this.stuckBlock.css({
            'position': position,
            'top': top
        });
    };
    this.applyMode = function (mode) {
        this.mode = mode;
        switch (mode) {
            case 'none' :
                this.applyStuckCss('static', 'auto');
                break;
            case 'absolute':
                this.applyStuckCss('absolute', this.absolutePosition);
                break;
            case 'topFixed' :
                this.applyStuckCss('fixed', this.fixedTopPosition);
                break;
            case 'bottomFixed' :
                this.applyStuckCss('fixed', this.fixedBottomPosition);
                break;
        }
    };
    this.scrollHandle = function (changed) {
        var windowScrollNow = Math.floor(jQuery(window).scrollTop());

        this.window = {};

        var windowWidth = jQuery(window).width;
        window.resized = (this.window.width !== windowWidth);
        this.window.width = windowWidth;

        /* get shown rectangle */

        this.fixedTopElementsHeight = 108;

        this.window.height = jQuery(window).height();
        this.window.bottom = this.window.height + windowScrollNow;
        this.shownRect = {
            top: windowScrollNow + this.fixedTopElementsHeight,
            bottom: this.window.bottom,
            height: this.window.height - this.fixedTopElementsHeight
        };

        /* scroll direction */

        this.scrollDirect = (windowScrollNow >= this.windowBeforeScrollPosition);

        /* virtual position init */

        this.stuckBlock.top = Math.floor(this.stuckBlock.offset().top);
        this.stuckBlock.height = Math.floor(this.stuckBlock.outerHeight());
        this.stuckBlock.bottom = this.stuckBlock.top + this.stuckBlock.height;
        this.outerBlock.height = Math.floor(this.outerBlock.outerHeight());
        this.outerBlock.top = Math.floor(this.outerBlock.offset().top);
        this.outerBlock.bottom = this.outerBlock.top + this.outerBlock.height;
        this.outerBlock.shown = this.outerBlock.bottom - this.shownRect.top;
        this.stuckDisable = this.stuckBlock.height >= this.outerBlock.height;
        this.stuckInner = this.stuckBlock.height < this.shownRect.height;

        if (this.stuckDisable) {
            this.applyMode('none');
            return false;
        }

        this.vpMax = this.outerBlock.height - this.stuckBlock.height;
        this.vpDirect = windowScrollNow - this.outerBlock.top + this.fixedTopElementsHeight;
        this.vpOpposite = windowScrollNow - this.outerBlock.top + this.window.height - this.stuckBlock.height;

        if (this.vpDirect < 0) {
            this.vpDirect = 0;
        }
        if (this.vpOpposite < 0) {
            this.vpOpposite = 0;
        }
        if (this.vpDirect > this.vpMax) {
            this.vpDirect = this.vpMax;
        }
        if (this.vpOpposite > this.vpMax) {
            this.vpOpposite = this.vpMax;
        }

        /* calculate positions for topFixed and bottomFixed modes */

        this.fixedTopPosition = this.fixedTopElementsHeight;
        this.fixedBottomPosition = this.window.height - this.stuckBlock.height;

        if (changed) {
            if (this.absolutePosition > this.vpMax) {
                this.absolutePosition = this.vpMax;
                this.applyMode('absolute');
            }
            if (this.stuckBlock.bottom > this.shownRect.bottom) {
                this.applyMode('absolute');
            }
        }
        if (this.stuckInner) {
            if (this.scrollDirect) {
                if (this.mode === 'none') {
                    if (this.shownRect.top > this.outerBlock.top) {
                        this.applyMode('topFixed');
                    }
                }
                if (this.mode === 'absolute') {
                    if (this.outerBlock.shown > this.stuckBlock.height) {
                        this.applyMode('topFixed');
                    }
                }
                if (this.mode === 'topFixed') {
                    this.absolutePosition = this.vpDirect;
                    if (this.outerBlock.shown < this.stuckBlock.height) {
                        this.applyMode('absolute');
                    }
                }
            } else {
                if (this.mode === 'absolute') {
                    if (this.outerBlock.shown > this.stuckBlock.height) {
                        this.applyMode('topFixed');
                    }
                }
                if (this.mode === 'topFixed') {
                    this.absolutePosition = this.vpDirect;
                    if (this.shownRect.top < this.outerBlock.top) {
                        this.applyMode('none');
                    }
                }
            }
        } else {
            if (this.scrollDirect) {
                if (this.mode === 'none') {
                    if (this.stuckBlock.bottom < this.shownRect.bottom &&
                        this.shownRect.bottom < this.outerBlock.bottom) {
                        this.applyMode('bottomFixed');
                    }
                    if (this.outerBlock.top < this.shownRect.top) {
                        this.absolutePosition = this.vpDirect;
                        this.applyMode('topFixed');
                    }
                }
                if (this.mode === 'absolute') {
                    if (this.stuckBlock.bottom < this.shownRect.bottom) {
                        this.applyMode('bottomFixed');
                    }
                }
                if (this.mode === 'topFixed') {
                    this.applyMode('absolute');
                    if (this.stuckBlock.bottom < this.shownRect.bottom) {
                        this.applyMode('bottomFixed');
                    }
                }
                if (this.mode === 'bottomFixed') {
                    this.absolutePosition = this.vpOpposite;
                    if (this.shownRect.bottom > this.outerBlock.bottom) {
                        this.applyMode('absolute');
                    }
                }
            } else {
                if (this.mode === 'none') {

                }
                if (this.mode === 'absolute') {
                    if (this.stuckBlock.top > this.shownRect.top) {
                        this.applyMode('topFixed');
                    }
                }
                if (this.mode === 'topFixed') {
                    this.absolutePosition = this.vpDirect;
                    if (this.vpDirect === 0) {
                        this.applyMode('absolute');
                    }
                }
                if (this.mode === 'bottomFixed') {
                    this.applyMode('absolute');
                }
            }
        }
        if (this.devMode) {
            this.showState();
        }
        this.windowBeforeScrollPosition = windowScrollNow;

    };
};
var glob = {};
$(window).on('load resize', function () {
    if ($('.js_stuck_left').length > 0) {
        glob.pdpInfoStuck = new StuckBlock($('.js_stuck_left'), $('.js_pdp_topBox'));
        glob.pdpInfoStuck.init();
    }
    if ($('.js_stuck_thumbnail').length > 0) {
        glob.pdpThumbnailStuck = new StuckBlock($('.js_stuck_thumbnail'), $('.js_pdp_topBox'));
        glob.pdpThumbnailStuck.init();
    }
});

function AddReadMore() {
    var $ = jQuery;
    var carLmt = 280;
    var readMoreTxt = " ... See more";
    var readLessTxt = " Shortcut";
    //Traverse all selectors with this class and manupulate HTML part to show Read More
    $(".addReadMore").each(function () {
        if ($(this).find(".firstSec").length)
            return;
        var allstr = $(this).html();
        if (allstr.length > carLmt) {
            var firstSet = allstr.substring(0, carLmt);
            var secdHalf = allstr.substring(carLmt, allstr.length);
            var strtoadd = firstSet + "<span class='SecSec'>" + secdHalf + "</span><span class='readMore'  title='Hiện thị tất cả'>" + readMoreTxt + "</span><span class='readLess' title='Show all'>" + readLessTxt + "</span>";
            $(this).html(strtoadd);
        }
    });
    //Read More and Read Less Click Event binding
    $(document).on("click", ".readMore,.readLess", function () {
        $(this).closest(".addReadMore").toggleClass("showlesscontent showmorecontent");
    });
}

function parseDateString(dateString) {
    var matchers = [];
    matchers.push(/^[0-9]*$/.source);
    matchers.push(/([0-9]{1,2}\/){2}[0-9]{4}( [0-9]{1,2}(:[0-9]{2}){2})?/.source);
    matchers.push(/[0-9]{4}([\/\-][0-9]{1,2}){2}( [0-9]{1,2}(:[0-9]{2}){2})?/.source);
    matchers = new RegExp(matchers.join("|"));
    if (dateString instanceof Date) {
        return dateString;
    }
    if (String(dateString).match(matchers)) {
        if (String(dateString).match(/^[0-9]*$/)) {
            dateString = Number(dateString);
        }
        if (String(dateString).match(/\-/)) {
            dateString = String(dateString).replace(/\-/g, "/");
        }
        return new Date(dateString);
    } else {
        throw new Error("Couldn't cast `" + dateString + "` to a date object.");
    }
}

function number_format(number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
