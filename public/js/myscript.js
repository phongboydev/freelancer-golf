"use strict";
jQuery(document).ready(function($) {
    LookboxProductSlider();
    LookboxProductSliderMuti();
    search_layer_init();
    /*
    $('.center-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        //centerMode: false,
        arrows: true,
        dots: false,
        //speed: 300,
        //centerPadding: '20px',
        //autoplaySpeed: 5000,
        infinite: false,
        loop: true,
        autoplay: true,
        focusOnSelect: true,
        prevArrow: `<button type='button' class='slick-prev slick-arrow'><ion-icon name="arrow-back-outline"></ion-icon></button>`,
        nextArrow: `<button type='button' class='slick-next slick-arrow'><ion-icon name="arrow-forward-outline"></ion-icon></button>`,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
    });
*/
    // Brands List
    $('.brands-center-slider').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        centerMode: true,
        arrows: true,
        dots: false,
        speed: 300,
        infinite: true,
        autoplaySpeed: 5000,
        autoplay: false,
        prevArrow: `<button type='button' class='slick-prev slick-arrow'><ion-icon name="arrow-back-outline"></ion-icon></button>`,
        nextArrow: `<button type='button' class='slick-next slick-arrow'><ion-icon name="arrow-forward-outline"></ion-icon></button>`,

        responsive: [
            {
                breakpoint: 769,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 375,
                settings: {
                    centerPadding: '40px',
                    slidesToShow: 2
                }
            }
        ]
    });
});

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

function getMetaContentByName(name, content) {
    var content = (content == null) ? 'content' : content;
    return document.querySelector("meta[name='" + name + "']").getAttribute(content);
}
// module product slider home page
function LookboxProductSlider() {
    jQuery('.center-slider').each(function(){
        var LookboxProductSlider = jQuery(this);
        if(jQuery(this).find('.product-card').length>0){
            LookboxProductSlider.on('changed.owl.carousel initialized.owl.carousel', function(e) {
                if(e.page.index===0||e.page.index===-1){
                    jQuery(this).find('.owl-prev').addClass('disabled');
                }else if(e.page.index+1===e.page.count){
                    jQuery(this).find('.owl-next').addClass('disabled');
                }else{
                    jQuery(this).find('.owl-prev, .owl-next').removeClass('disabled');
                }
            });
            LookboxProductSlider.addClass('owl-carousel').owlCarousel({
                nav: true,
                margin: 15,
                items: 4,
                slideBy: 4,
                navText: ['<button type=\'button\' class=\'slick-prev slick-arrow\'><ion-icon name="arrow-back-outline"></ion-icon></button>', '<button type=\'button\' class=\'slick-next slick-arrow\'><ion-icon name="arrow-forward-outline"></ion-icon></button>'],
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                        
                    },
                    600:{
                        items:3,
                        
                    },
                    1000:{
                        items:4,
                    }
                }
            })
        }

    });

}

function LookboxProductSliderMuti() {
    jQuery('.tns-carousel-inner-container').each(function(){
        var LookboxProductSliderMuti = jQuery(this);
        if(jQuery(this).find('.product-card').length>0){
            LookboxProductSliderMuti.on('changed.owl.carousel initialized.owl.carousel', function(e) {
                if(e.page.index===0||e.page.index===-1){
                    jQuery(this).find('.owl-prev').addClass('disabled');
                }else if(e.page.index+1===e.page.count){
                    jQuery(this).find('.owl-next').addClass('disabled');
                }else{
                    jQuery(this).find('.owl-prev, .owl-next').removeClass('disabled');
                }
            });
            LookboxProductSliderMuti.addClass('owl-carousel').owlCarousel({
                nav: true,
                margin: 0,
                items: 4,
                margin: 15,
                slideBy: 4,
                navText: ['<button type="button" data-controls="prev" tabindex="-1" aria-controls="tns4"><i class="ci-arrow-left"></i></button>', '<button type="button" data-controls="next" tabindex="-1" aria-controls="tns4"><i class="ci-arrow-right"></i></button>'],
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                        
                    },
                    600:{
                        items:3,
                        
                    },
                    1000:{
                        items:4,
                    }
                }
            })
        }
    });
}
function search_layer_init() {
    var $btn = $('.btn_search');
    var $close = $('.btn_search_close');
    var $con = $('.search_layer');

    $btn.on('click', function (event) {
        $con.addClass('open');
        // $('.search_input').focus();

        event.preventDefault();
    });

    $close.on('click', function (event) {
        $con.removeClass('open');
        event.preventDefault();
    });
}