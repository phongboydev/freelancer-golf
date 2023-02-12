$(document).ready(function() {
    $('.gallery_product').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
    });

    $('.gallery_product_nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.gallery_product',
        dots: false,
        arrows: false,
        centerMode: false,
        focusOnSelect: true
    });

    if (typeof key_option_data !== 'undefined' && key_option_data === '' && $('#is_combo').length == 0) {
        // Chọn tự động biến thể khi load trang
        $('.group-product-variable .item-variable').each(function() {
            $(this).find('.select-variable li span').each(function () {
                let data_parent = $(this).attr('data-parent');
                setTimeout(function(){
                    $('#first_variable_' + data_parent).trigger("click");
                }, 500);
                return false;
            });
        });
    }

    if (key_option_data !== '') {
        setTimeout(function(){
            $('.select-variable li span.firstLoadClick').trigger("click");
        }, 500);
    }

    // lấy ra các biến thể đang được chọn
    let key_parent_slug = $('.container_bienthe_group #key_parent_slug').val();
    if (key_parent_slug !== undefined && key_parent_slug !== '') {
        let arr_key_parent_slug = key_parent_slug.split(",");
        let m;
        let arr_option = [];
        for (m = 0; m < arr_key_parent_slug.length; m++) {
            let arr_item = {}
            arr_item[arr_key_parent_slug[m]] = $('input#' + arr_key_parent_slug[m]).val();
            arr_option.push(arr_item);
        }

        $('.select-variable li span.active').each(function () {
            let data_value = $(this).attr('data-value');
            let data_parent = $(this).attr('data-parent');
            // xử lý ẩn biến thể không có
            // data_value, data_parent
            let j;
            for (j = 0; j < variable_options.length; j++) {
                let option = variable_options[j];
                for (const [key, value] of Object.entries(option)) {
                    let i;
                    for (i = 0; i < arr_key_parent_slug.length; i++) {
                        if (arr_key_parent_slug[i] !== data_parent && option[key][data_parent] === data_value) {
                            if (option[key]['product_stock'] == 0 || option[key]['product_stock'] === '' || option[key]['price_promotion'] == 0) {
                                $('.select-variable li span[data-value="' + option[key][arr_key_parent_slug[i]] + '"]').addClass('sold-out');
                                $('#btn_sold_out').addClass('active');
                                $('.frm-sold-out').addClass('active');
                                $('#btn_cart_primary').hide();
                            } else {
                                $('.select-variable li span[data-value="' + option[key][arr_key_parent_slug[i]] + '"]').removeClass('sold-out');
                                $('.select-variable li span[data-value="' + option[key][arr_key_parent_slug[i]] + '"]').removeClass('sold-out');
                                $('#btn_sold_out').removeClass('active');
                                $('.frm-sold-out').removeClass('active');
                                $('#btn_cart_primary').show();
                            }
                        }

                    }
                }
            }
        })

        // choose variable
        let first_click = 0;
        if (typeof variable_options !== 'undefined' && variable_options.length > 0 && $('#is_combo').length == 0) {
            $('#fixed_content_detail_parent .select-variable li span').on('click', function () {
                $(this).parent().parent().find('li span').removeClass('active');
                $(this).addClass('active');
                let data_value = $(this).attr('data-value');
                let data_parent = $(this).attr('data-parent');
                $('#fixed_content_detail_parent #variable_name_active_' + data_parent).html(data_value);
                let parent_key = $(this).parent().parent().attr('data-attribute');
                // select value variable
                $('#fixed_content_detail_parent input#' + parent_key).val(data_value);
                for (const [key, value] of Object.entries(variable_gallery)) {
                    let gallery = variable_gallery[key];
                    for (const [key_child, value_child] of Object.entries(gallery)) {
                        let data_gallery = [];
                        if ('gallery' in gallery[key_child]) {
                            data_gallery = gallery[key_child]['gallery'];
                        }
                        if (data_value === key_child && data_gallery.length > 0) {
                            $('#singleProductImg .gallery_product').html('');
                            $('#singleProductImg .gallery_product_nav').html('');
                            $('#singleProductImg .gallery_product').removeClass('slick-initialized');
                            $('#singleProductImg .gallery_product').removeClass('slick-slider');
                            $('#singleProductImg .gallery_product_nav').removeClass('slick-initialized');
                            $('#singleProductImg .gallery_product_nav').removeClass('slick-slider');

                            let count_item_gallery;
                            let item_galleries = gallery[key_child].gallery;
                            let item_galleries_html = '';
                            let item_galleries_nav_html = '';
                            for (count_item_gallery = 0; count_item_gallery < item_galleries.length; count_item_gallery++) {
                                item_galleries_html += `<a href="${site}/images/product/${item_galleries[count_item_gallery]}"
                                data-fancybox="images-preview" class="gallery_product_item">
                                <img src="${site}/images/product/thumbs/450x450/${item_galleries[count_item_gallery]}">
                                </a>`;
                                item_galleries_nav_html += `<div class="gallery_product_nav_item">
                                <img src="${site}/images/product/thumbs/70x70/${item_galleries[count_item_gallery]}" alt=""/>
                              </div>`;
                            }
                            $('#singleProductImg .gallery_product').html(item_galleries_html);
                            $('#singleProductImg .gallery_product_nav').html(item_galleries_nav_html);


                            $('.gallery_product').slick({
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                arrows: false,
                                fade: true,
                            });

                            $('.gallery_product_nav').slick({
                                slidesToShow: 4,
                                slidesToScroll: 1,
                                asNavFor: '.gallery_product',
                                dots: false,
                                centerMode: false,
                                focusOnSelect: true,
                                arrows: false
                            });
                        }
                    }
                }

                // lấy ra các biến thể đang được chọn
                let key_parent_slug = $('#fixed_content_detail_parent #key_parent_slug').val();
                let arr_key_parent_slug = key_parent_slug.split(",");
                let m;
                let arr_option = [];
                for (m = 0; m < arr_key_parent_slug.length; m++) {
                    let arr_item = {}
                    arr_item[arr_key_parent_slug[m]] = $('#fixed_content_detail_parent input#' + arr_key_parent_slug[m]).val();
                    arr_option.push(arr_item);
                }

                // xử lý ẩn biến thể không có
                // data_value, data_parent
                let j;
                for (j = 0; j < variable_options.length; j++) {
                    let option = variable_options[j];
                    for (const [key, value] of Object.entries(option)) {
                        let i;
                        for (i = 0; i < arr_key_parent_slug.length; i++) {
                            if (arr_key_parent_slug[i] !== data_parent && option[key][data_parent] === data_value) {
                                if (option[key]['product_stock'] == 0 || option[key]['product_stock'] === '' || option[key]['price_promotion'] == 0) {
                                    $('#fixed_content_detail_parent .select-variable li span[data-value="' + option[key][arr_key_parent_slug[i]] + '"]').addClass('sold-out');
                                    $('#fixed_content_detail_parent #btn_sold_out').addClass('active');
                                    $('#fixed_content_detail_parent .frm-sold-out').addClass('active');
                                    $('#fixed_content_detail_parent #btn_cart_primary').hide();
                                } else {
                                    $('#fixed_content_detail_parent .select-variable li span[data-value="' + option[key][arr_key_parent_slug[i]] + '"]').removeClass('sold-out');
                                    $('#fixed_content_detail_parent #btn_sold_out').removeClass('active');
                                    $('#fixed_content_detail_parent .frm-sold-out').removeClass('active');
                                    $('#fixed_content_detail_parent #btn_cart_primary').show();
                                }
                            }

                        }
                    }
                }

                let key_option = '';
                let product_name = '';
                let product_slug = '';
                let product_stock = 0;
                let price_origin = '';
                let price_promotion = '';
                let product_sku = '';

                for (j = 0; j < variable_options.length; j++) {
                    let option = variable_options[j];
                    for (const [key, value] of Object.entries(option)) {
                        let i;
                        let check_temp = 0;
                        for (i = 0; i < arr_key_parent_slug.length; i++) {
                            let k;
                            for (k = 0; k < arr_option.length; k++) {
                                if (option[key][arr_key_parent_slug[i]] === arr_option[k][arr_key_parent_slug[i]]) {
                                    check_temp++;
                                    break;
                                }
                            }
                        }
                        if (check_temp === arr_key_parent_slug.length) {
                            key_option = key;
                            product_name = option[key]['product_name'];
                            product_slug = option[key]['product_slug'];
                            price_origin = option[key]['price_origin'];
                            product_stock = option[key]['product_stock'];
                            price_promotion = option[key]['price_promotion'];
                            if (option[key]['product_sku'] != null) {
                                product_sku = option[key]['product_sku'];
                            }
                        }
                    }
                }
                if (key_option !== '' && price_origin > 0 && price_promotion > 0) {
                    let price_origin_html = number_format(price_origin) + '<span>' + currency + '</span>';
                    if (is_promotion === 1) {
                        let percent_discount = (price_origin - price_promotion) / price_origin  * 100;
                        $('#percent_discount').html(parseInt(percent_discount) + '%');
                        $('span#price_origin').html(price_origin_html);
                        $('span#price_promotion').html(number_format(price_promotion) + '<sup>' + currency + '</sup>');
                        $('#tab_price').html(number_format(price_promotion) + '<sup>' + currency + '</sup>');
                    } else if (price_origin == 0) {
                        $('span#price_promotion').html('Contact');
                        $('span#price_origin').html('');
                    } else {
                        $('span#price_origin').html('');
                        $('span#price_promotion').html(price_origin_html);
                        $('#tab_price').html(price_origin_html);
                    }
                    $('#fixed_content_detail_parent #btn_cart_primary').show();
                } else if(key_option !== '') {
                    $('#fixed_content_detail_parent #btn_cart_primary').hide();
                }

                if (product_name !== '') {
                    $('#fixed_content_detail_parent #title_product_detail').html(product_name);
                }
                if (product_sku !== '') {
                    $('#fixed_content_detail_parent .product_sku').html(product_sku);
                }

                if (product_slug !== '') {
                    let current_url = window.location.href;
                    let arr_url = current_url.split("/");
                    let last_array = arr_url.length - 1;
                    let last_keyword_current_url = arr_url[last_array];
                    let new_url = current_url.replace(last_keyword_current_url, product_slug + '.html');
                    if (first_click > 1) {
                        if (data_parent === 'color') {
                            window.location.href = new_url;
                        }
                    } else {
                        first_click++;
                    }
                    history.pushState({}, product_name, new_url);
                    document.title = product_name;
                }

                $('#fixed_content_detail_parent #btn_cart_primary').attr('data-option', key_option);
            });

            $('#fixed_content_detail_parent .select-variable li span').hover(
                function () {
                    let data_value = $(this).attr('data-value');
                    let data_parent = $(this).attr('data-parent');
                    $('#fixed_content_detail_parent #variable_name_active_' + data_parent).html(data_value);
                },
                function () {
                    let data_parent = $(this).attr('data-parent');
                    let data_value;
                    if ($('#fixed_content_detail_parent #select-variable-' + data_parent + ' .st-custom-attribute.active').length > 0) {
                        data_value = $('#fixed_content_detail_parent #select-variable-' + data_parent + ' .st-custom-attribute.active').attr('data-value');
                    } else {
                        data_value = '';
                    }
                    $('#fixed_content_detail_parent #variable_name_active_' + data_parent).html(data_value);
                }
            );
        }

        $('.container_bienthe_pop_group .select-variable li span').on('click', function () {
            let modal_id = $(this).closest(".container_bienthe_pop_group").attr('data-id');
            let variable_options = $('#modal-content-' + modal_id + ' .group-product-variable').attr('data-variable-option');
            variable_options = JSON.parse(variable_options);
            $(this).parent().parent().find('li span').removeClass('active');
            $(this).addClass('active');
            let data_value = $(this).attr('data-value');
            let data_parent = $(this).attr('data-parent');
            $('#modal-content-' + modal_id + ' .container_bienthe_pop_group #variable_name_active_' + data_parent).html(data_value);
            let parent_key = $(this).parent().parent().attr('data-attribute');
            // select value variable
            $('#modal-content-' + modal_id + ' .container_bienthe_pop_group input#' + parent_key).val(data_value);

            // lấy ra các biến thể đang được chọn
            let key_parent_slug = $('#modal-content-' + modal_id + ' .container_bienthe_pop_group #key_parent_slug').val();
            let arr_key_parent_slug = key_parent_slug.split(",");
            let m;
            let arr_option = [];
            for (m = 0; m < arr_key_parent_slug.length; m++) {
                let arr_item = {}
                arr_item[arr_key_parent_slug[m]] = $('#modal-content-' + modal_id + ' .container_bienthe_pop_group input#' + arr_key_parent_slug[m]).val();
                arr_option.push(arr_item);
            }

            // xử lý ẩn biến thể không có
            // data_value, data_parent
            let j;
            for (j = 0; j < variable_options.length; j++) {
                let option = variable_options[j];
                for (const [key, value] of Object.entries(option)) {
                    let i;
                    for (i = 0; i < arr_key_parent_slug.length; i++) {
                        if (arr_key_parent_slug[i] !== data_parent && option[key][data_parent] === data_value) {
                            if (option[key]['product_stock'] == 0 || option[key]['product_stock'] === '' || option[key]['price_promotion'] == 0) {
                                $('#modal-content-' + modal_id + ' .select-variable li span[data-value="' + option[key][arr_key_parent_slug[i]] + '"]').addClass('sold-out');
                                $('#modal-content-' + modal_id + ' #btn_sold_out').addClass('active');
                                $('#modal-content-' + modal_id + ' .frm-sold-out').addClass('active');
                                $('#modal-content-' + modal_id + ' #btn_cart_primary').hide();
                            } else {
                                $('#modal-content-' + modal_id + ' .select-variable li span[data-value="' + option[key][arr_key_parent_slug[i]] + '"]').removeClass('sold-out');
                                $('#modal-content-' + modal_id + ' #btn_sold_out').removeClass('active');
                                $('#modal-content-' + modal_id + ' .frm-sold-out').removeClass('active');
                                $('#modal-content-' + modal_id + ' #btn_cart_primary').show();
                            }
                        }
                    }
                }
            }

            let key_option = '';
            let product_name = '';
            let product_slug = '';
            let product_stock = 0;
            let price_origin = '';
            let price_promotion = '';
            let product_sku = '';
            let product_thumbnail = '';

            for (j = 0; j < variable_options.length; j++) {
                let option = variable_options[j];
                for (const [key, value] of Object.entries(option)) {
                    let i;
                    let check_temp = 0;
                    for (i = 0; i < arr_key_parent_slug.length; i++) {
                        let k;
                        for (k = 0; k < arr_option.length; k++) {
                            if (option[key][arr_key_parent_slug[i]] === arr_option[k][arr_key_parent_slug[i]]) {
                                check_temp++;
                                break;
                            }
                        }
                    }
                    if (check_temp === arr_key_parent_slug.length) {
                        key_option = key;
                        product_name = option[key]['product_name'];
                        product_slug = option[key]['product_slug'];
                        price_origin = option[key]['price_origin'];
                        product_stock = option[key]['product_stock'];
                        price_promotion = option[key]['price_promotion'];
                        product_thumbnail = option[key]['thumbnail'];
                        if (option[key]['product_sku'] != null) {
                            product_sku = option[key]['product_sku'];
                        }
                    }
                }
            }
            if (key_option !== '' && price_origin > 0 && price_promotion > 0) {
                let price_origin_html = number_format(price_origin) + '<span>' + currency + '</span>';
                if (is_promotion === 1) {
                    let percent_discount = (price_origin - price_promotion) / price_origin  * 100;
                    $('#percent_discount').html(parseInt(percent_discount) + '%');
                    $('span#price_origin').html(price_origin_html);
                    $('span#price_promotion').html(number_format(price_promotion) + '<sup>' + currency + '</sup>');
                    $('#tab_price').html(number_format(price_promotion) + '<sup>' + currency + '</sup>');
                } else if (price_origin == 0) {
                    $('span#price_promotion').html('Contact');
                    $('span#price_origin').html('');
                } else {
                    $('span#price_origin').html('');
                    $('span#price_promotion').html(price_origin_html);
                    $('#tab_price').html(price_origin_html);
                }
                $('#modal-content-' + modal_id + ' #btn_cart_primary').show();
            } else if(key_option !== '') {
                $('#modal-content-' + modal_id + ' #btn_cart_primary').hide();
            }

            if (product_thumbnail !== '') {
                $('#modal-content-' + modal_id + ' .quick__slider img').attr('src', '/images/product/' + product_thumbnail);
            }

            if (product_name !== '') {
                $('#modal-content-' + modal_id + ' .title_product_detail').html(product_name);
            }

            if (product_sku !== '') {
                $('#modal-content-' + modal_id + ' .product_sku').html(product_sku);
            }

            $('#modal-content-' + modal_id + ' #btn_cart_primary').attr('data-option', key_option);
        });

        $('.container_bienthe_pop_group .select-variable li span').hover(
            function () {
                let data_value = $(this).attr('data-value');
                let data_parent = $(this).attr('data-parent');
                $('.container_bienthe_pop_group #variable_name_active_' + data_parent).html(data_value);
            },
            function () {
                let data_parent = $(this).attr('data-parent');
                let data_value;
                if ($('.container_bienthe_pop_group #select-variable-' + data_parent + ' .st-custom-attribute.active').length > 0) {
                    data_value = $('.container_bienthe_pop_group #select-variable-' + data_parent + ' .st-custom-attribute.active').attr('data-value');
                } else {
                    data_value = '';
                }
                $('.container_bienthe_pop_group #variable_name_active_' + data_parent).html(data_value);
            }
        );
    }
});

function buyNow() {
    (function ($) {
        trigger = true;
        $('#btn_cart_primary').trigger("click");
        setTimeout(function () {
            if (trigger) {
                window.location.href = site = '/cart';
            }
        }, 2000)
    })(jQuery);
}

function addToCart(e) {
    (function ($) {
        let option = $(e).attr('data-option');
        let parent_id = $(e).attr('data-product-parent');
        let product_id = $(e).attr('data-id');
        let quantity = $(e).attr('data-quantity');
        let sendInfo = {
            '_token': getMetaContentByName("_token"),
            'parent_id': parent_id,
            'quantity': quantity,
            'option': option,
            'product_id': product_id
        };
        $.ajax({
            url: site + '/add-to-cart',
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

function likeQuestion(id, product_id, parent_id) {
    (function ($) {
        if (!islogin) {
            $('#myModal').modal('show');
            return false;
        }
        var sendInfo = {
            '_token': getMetaContentByName("_token"),
            'product_id': product_id,
            'id': id,
            'parent_id': parent_id
        };
        $.ajax({
            url: site + '/ajax/like-question',
            type: "POST",
            data: sendInfo,
            datatype: 'JSON',
            cache: false,
            success: function (response) {
                if (response.success == true) {
                    $('#like_' + id).html(response.data.like);
                } else {
                    alert(response.message);
                }
            }
        });
        return false;
    })(jQuery);
}

function sendQA(product_id, parent_id) {
    (function ($) {
        let question_content = '';
        if (parent_id > 0) {
            question_content = $('.reply_content_' + parent_id).val();
        } else {
            question_content = $('#question_content').val();
        }

        var sendInfo = {
            '_token': getMetaContentByName("_token"),
            'product_id': product_id,
            'question_content': question_content,
            'parent_id': parent_id
        };

        $.ajax({
            url: site + '/send-question',
            type: "POST",
            data: sendInfo,
            datatype: 'JSON',
            cache: false,
            success: function (response) {
                if (response.success == true) {
                    if (parent_id > 0) {
                        addHtmlAnswer(response.data);
                        $('.reply_content_' + parent_id).val('');
                    } else {
                        addHtmlQuestion(response.data);
                        $('#question_content').val('');
                    }
                } else {
                    if (!islogin) {
                        $('#myModal').modal('show');
                    } else {
                        alert('Thao tác đã xãy ra lỗi. Vui lòng thử lại.');
                    }
                }
            }
        });
        return false;
    })(jQuery);
}

function addHtmlQuestion(question) {
    let created_at = getFormattedDate(question.created_at);
    var html = '<div class="item-question" data-id="' + question.id + '" data-product="' + question.product_id + '">' +
        '<div class="txt_color_1">' + question.name + ' - <span class="txt_999">' + created_at + '</span></div>' +
        '<div class="txt_quest"><strong>' + question.content + '</strong></div>' +
        '<div class="block_more_answear">' +
        '<a href="javascript:void(0);" onclick="likeQuestion(' + question.id + ', ' + question.product_id + ', ' + question.parent_id + ')" class="qa_like">Thích</a> ' +
        '<i class="far fa-thumbs-up"></i> ' +
        '<span class="number_like">' +
        '<span id="like_' + question.id + '" class="txt_number txt_color_1"> ' + question.user_like + ' </span>' +
        '</span>' +
        ' - <a href="javascript:void(0);" data-reply="' + question.name + '" class="qa_reply">Trả lời</a>' +
        '</div>' +
        '<div class="list_ans" id="list_ans_' + question.id +'">' +
        '<div class="view-more-ans more-ans-' + question.id +' hidden"></div>' +
        '<div class="input_ans_qa input_ans_qa_' + question.id + ' hidden">' +
        '<div class="block_book_quest flex">' +
        '<textarea placeholder="Nội dung câu trả lời của bạn" class="form-control reply_content reply_content_' + question.id + '"></textarea>' +
        '<button class="btn btn-question ml-2 mr-2 send-reply" onclick="sendQA(' + question.product_id + ', ' + question.id + ')">Gửi</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    $('.question-group').prepend(html);
}

function addHtmlAnswer(answer) {
    let created_at = getFormattedDate(answer.created_at);
    let html = '<div class="item_ans">' +
        '<div class="mb-1 txt_color_1">' +
        '<span class="' + (answer.user_id == 0 ? 'yoy_ans' : 'txt_color_1') + '">' + answer.name + '</span> - ' +
        '<span class="txt_999">' + created_at + '</span>' +
        '</div><div class="block_more_answear">' +
        '<div class="txt_answear txt_666">' + answer.content + '</div>' +
        '<a href="javascript:void(0);" onclick="likeQuestion(' + answer.id + ', ' + answer.product_id + ', ' + answer.parent_id + ')" class="qa_like">Thích</a> ' +
        '<i class="far fa-thumbs-up"></i> ' +
        '<span id="like_' + answer.id + '" class="txt_color_1 num_like">' + answer.user_like + '</span>' +
        ' - <a href="javascript:void(0);" class="qa_reply ans" data-reply="' + answer.name + '" >Trả lời</a>' +
        '</div></div>';
    if ($('#list_ans_' + answer.parent_id + ' .item_ans').length > 0) {
        $(html).insertAfter($('#list_ans_' + answer.parent_id).find('.item_ans').last());
    } else {
        $('#list_ans_' + answer.parent_id).prepend(html);
    }
    $('.input_ans_qa_' + answer.parent_id).addClass('hidden');
}

function resetVariableOption() {
    (function ($) {
        $('.select-variable li').show();
        $('.select-variable li span').removeClass('active');
        $('#btn_cart_primary').attr('data-option', '');
        let key_parent_slug = $('#key_parent_slug').val();
        let arr_key_parent_slug = key_parent_slug.split(",");
        let m;
        for (m = 0; m < arr_key_parent_slug.length; m++) {
            $('input#' + arr_key_parent_slug[m]).val('');
        }
    })(jQuery);
}


(function ($) {
    $(document).ready(function () {
        $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom()
    });

    function format(a) {
        for (var i = 1; i < arguments.length; i++) {
            a = a.replace('%' + (i - 1), arguments[i])
        }
        return a
    }

    function CloudZoom(g, i) {
        var j = $('img', g);
        var k;
        var l;
        var m = null;
        var n = null;
        var o = null;
        var p = null;
        var q = null;
        var r = null;
        var s;
        var t = 0;
        var u, ch;
        var v = 0;
        var z = 0;
        var A = 0;
        var B = 0;
        var C = 0;
        var D, my;
        var E = this, zw;
        setTimeout(function () {
            if (n === null) {
                var w = g.width();
                g.parent().append(format('<div style="width:%0px;position:absolute;top:75%;left:%1px;text-align:center" class="cloud-zoom-loading" >Loading...</div>', w / 3, (w / 2) - (w / 6))).find(':last').css('opacity', 0.5)
            }
        }, 200);
        var F = function () {
            if (r !== null) {
                r.remove();
                r = null
            }
        };
        this.removeBits = function () {
            if (o) {
                o.remove();
                o = null
            }
            if (p) {
                p.remove();
                p = null
            }
            if (q) {
                q.remove();
                q = null
            }
            F();
            $('.cloud-zoom-loading', g.parent()).remove()
        };
        this.destroy = function () {
            g.data('zoom', null);
            if (n) {
                n.unbind();
                n.remove();
                n = null
            }
            if (m) {
                m.remove();
                m = null
            }
            this.removeBits()
        };
        this.fadedOut = function () {
            if (m) {
                m.remove();
                m = null
            }
            this.removeBits()
        };
        this.controlLoop = function () {
            if (o) {
                var x = (D - j.offset().left - (u * 0.5)) >> 0;
                var y = (my - j.offset().top - (ch * 0.5)) >> 0;
                if (x < 0) {
                    x = 0
                } else if (x > (j.outerWidth() - u)) {
                    x = (j.outerWidth() - u)
                }
                if (y < 0) {
                    y = 0
                } else if (y > (j.outerHeight() - ch)) {
                    y = (j.outerHeight() - ch)
                }
                o.css({left: x, top: y});
                o.css('background-position', (-x) + 'px ' + (-y) + 'px');
                v = (((x) / j.outerWidth()) * s.width) >> 0;
                z = (((y) / j.outerHeight()) * s.height) >> 0;
                B += (v - B) / i.smoothMove;
                A += (z - A) / i.smoothMove;
                m.css('background-position', (-(B >> 0) + 'px ') + (-(A >> 0) + 'px'))
            }
            t = setTimeout(function () {
                E.controlLoop()
            }, 30)
        };
        this.init2 = function (a, b) {
            C++;
            if (b === 1) {
                s = a
            }
            if (C === 2) {
                this.init()
            }
        };
        this.init = function () {
            $('.cloud-zoom-loading', g.parent()).remove();
            n = g.parent().append(format("<div class='mousetrap' style='z-index:999;position:absolute;width:%0px;height:%1px;left:%2px;top:%3px;\'></div>", j.outerWidth(), j.outerHeight(), 0, 0)).find(':last');
            n.bind('mousemove', this, function (a) {
                D = a.pageX;
                my = a.pageY
            });
            n.bind('mouseleave', this, function (a) {
                clearTimeout(t);
                if (o) {
                    o.fadeOut(299)
                }
                if (p) {
                    p.fadeOut(299)
                }
                if (q) {
                    q.fadeOut(299)
                }
                m.fadeOut(300, function () {
                    E.fadedOut()
                });
            });
            n.bind('mouseenter', this, function (a) {
                D = a.pageX;
                my = a.pageY;
                zw = a.data;
                if (m) {
                    m.stop(true, false);
                    m.remove()
                }
                var b = i.adjustX, yPos = i.adjustY;
                var c = j.outerWidth();
                var d = j.outerHeight();
                var w = i.zoomWidth;
                var h = i.zoomHeight;
                if (i.zoomWidth == 'auto') {
                    w = c
                }
                if (i.zoomHeight == 'auto') {
                    h = d
                }
                var e = g.parent();
                switch (i.position) {
                    case'top':
                        yPos -= h;
                        break;
                    case'right':
                        b += c;
                        break;
                    case'bottom':
                        yPos += d;
                        break;
                    case'left':
                        b -= w;
                        break;
                    case'inside':
                        w = c;
                        h = d;
                        break;
                    default:
                        e = $('#' + i.position);
                        if (!e.length) {
                            e = g;
                            b += c;
                            yPos += d
                        } else {
                            w = e.innerWidth();
                            h = e.innerHeight()
                        }
                }
                m = e.append(format('<div id="cloud-zoom-big" class="cloud-zoom-big" style="display:none;position:absolute;left:%0px;top:%1px;width:%2px;height:%3px;background-image:url(\'%4\');z-index:99;"></div>', b, yPos, w, h, s.src)).find(':last');
                if (j.attr('title') && i.showTitle) {
                    m.append(format('<div class="cloud-zoom-title">%0</div>', j.attr('title'))).find(':last').css('opacity', i.titleOpacity)
                }
                if ($.browser.msie && $.browser.version < 7) {
                    r = $('<iframe frameborder="0" src="#"></iframe>').css({
                        position: "absolute",
                        left: b,
                        top: yPos,
                        zIndex: 99,
                        width: w,
                        height: h
                    }).insertBefore(m)
                }
                m.fadeIn(500);
                if (o) {
                    o.remove();
                    o = null
                }
                u = (j.outerWidth() / s.width) * m.width();
                ch = (j.outerHeight() / s.height) * m.height();
                o = g.append(format("<div class = 'cloud-zoom-lens' style='display:none;z-index:98;position:absolute;width:%0px;height:%1px;'></div>", u, ch)).find(':last');
                n.css('cursor', o.css('cursor'));
                var f = false;
                if (i.tint) {
                    o.css('background', 'url("' + j.attr('src') + '")');
                    p = g.append(format('<div style="display:none;position:absolute; left:0px; top:0px; width:%0px; height:%1px; background-color:%2;" />', j.outerWidth(), j.outerHeight(), i.tint)).find(':last');
                    p.css('opacity', i.tintOpacity);
                    f = true;
                    p.fadeIn(500)
                }
                if (i.softFocus) {
                    o.css('background', 'url("' + j.attr('src') + '")');
                    q = g.append(format('<div style="position:absolute;display:none;top:2px; left:2px; width:%0px; height:%1px;" />', j.outerWidth() - 2, j.outerHeight() - 2, i.tint)).find(':last');
                    q.css('background', 'url("' + j.attr('src') + '")');
                    q.css('opacity', 0.5);
                    f = true;
                    q.fadeIn(500)
                }
                if (!f) {
                    o.css('opacity', i.lensOpacity)
                }
                if (i.position !== 'inside') {
                    o.fadeIn(500)
                }
                zw.controlLoop();
                return
            })
        };
        k = new Image();
        $(k).load(function () {
            E.init2(this, 0)
        });
        k.src = j.attr('src');
        l = new Image();
        $(l).load(function () {
            E.init2(this, 1)
        });
        l.src = g.attr('href')
    }

    $.fn.CloudZoom = function (d) {
        try {
            document.execCommand("BackgroundImageCache", false, true)
        } catch (e) {
        }
        this.each(function () {
            var c, opts;
            eval('var  a = {' + $(this).attr('rel') + '}');
            c = a;
            if ($(this).is('.cloud-zoom')) {
                $(this).css({'position': 'relative', 'display': 'block'});
                $('img', $(this)).css({'display': 'block'});
                if ($(this).parent().attr('id') != 'wrap') {
                    $(this).wrap('<div id="wrap" style="top:0px;z-index:9999;position:relative;"></div>')
                }
                opts = $.extend({}, $.fn.CloudZoom.defaults, d);
                opts = $.extend({}, opts, c);
                $(this).data('zoom', new CloudZoom($(this), opts))
            } else if ($(this).is('.cloud-zoom-gallery')) {
                opts = $.extend({}, c, d);
                $(this).data('relOpts', opts);
                $(this).bind('click', $(this), function (a) {
                    var b = a.data.data('relOpts');
                    $('#' + b.useZoom).data('zoom').destroy();
                    $('#' + b.useZoom).attr('href', a.data.attr('href'));
                    $('#' + b.useZoom + ' img').attr('src', a.data.data('relOpts').smallImage);
                    $('#' + a.data.data('relOpts').useZoom).CloudZoom();
                    return false
                })
            }
        });
        return this
    };
    $.fn.CloudZoom.defaults = {
        zoomWidth: 'auto',
        zoomHeight: 'auto',
        position: 'right',
        tint: false,
        tintOpacity: 0.5,
        lensOpacity: 0.5,
        softFocus: false,
        smoothMove: 3,
        showTitle: true,
        titleOpacity: 0.5,
        adjustX: 0,
        adjustY: 0
    }
})(jQuery);
