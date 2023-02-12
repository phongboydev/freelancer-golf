jQuery(document).ready(function($){
    $('#selectall').click(function() {
        var checkboxes = $('#table_index').find(':checkbox');
        if($(this).is(':checked')) {
            //checkboxes.attr('checked', 'checked');
            $(':checkbox').prop('checked',true);
        } else {
            //checkboxes.removeAttr('checked');
            $(':checkbox').prop('checked',false);
        }
    });

    //js upload avatar admin
    var readURL = function (input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".file-upload").on('change', function () {
        readURL(this);
    });

    $(".upload-button").on('click', function () {
        $(".file-upload").click();
    });
});

function select_all() {
    (function ($) {
        var checkboxes = $('#table_index').find(':checkbox').each(function(){
            if($(this).is(':checked')) {
                //checkboxes.attr('checked', 'checked');
                $(':checkbox').prop('checked',true);
            } else {
                //checkboxes.removeAttr('checked');
                $(':checkbox').prop('checked',false);
            }
        });
    })(jQuery);
}

function delete_id(type) {
    (function ($) {
        if (window.confirm('Bạn chắc chắn muốn xoá?')) {
            arr = new Array();
            var con = 0;
            $('input[name="seq_list[]"]:checked').each(function(){
                arr = $('input:checkbox').serializeArray();
                arr.push({ name: "_token", value: getMetaContentByName('csrf-token') });
                arr.push({ name: "type", value: type });
            }); //each
            $.ajax({
                type: "POST",
                url: admin_url+"/delete-id",
                data: arr ,//pass the array to the ajax call
                cache: false,
                beforeSend: function() {

                },
                success: function(result){
                    if (result == 1) {
                        location.reload();
                    } else {
                        alert('Bạn không có quyền xoá dữ liệu này.');
                    }
                }
            });//ajax
        }
    })(jQuery);
}

function actionReview(e, id, status) {
    (function ($) {
        $.ajax({
            type: "POST",
            url: admin_url + "/ajax/process-product-review",
            data: {
                "_token": getMetaContentByName('csrf-token'),
                "id": id,
                "status": status
            },
            dataType: "JSON",
            cache: false,
            beforeSend: function() {

            },
            success: function(result){
                if (result.success) {
                    location.reload();
                }
            }
        });//ajax
    })(jQuery);
}

function product_quick_update(product_id) {
    (function ($) {
        var origin_price=$('#origin-price-'+product_id).val();
        var promotion_price=$('#promotion-price-'+product_id).val();
        var start_event = $('#start-event-'+product_id).val();
        var end_event = $('#end-event-'+product_id).val();

        $.ajax({
            type: "POST",
            url: admin_url+"/ajax/product-quick-update",
            data: {
                '_token': getMetaContentByName('csrf-token'),
                'id' : product_id,
                'origin_price':origin_price,
                'promotion_price':promotion_price,
                'start_event': start_event,
                'end_event': end_event
            },
            dataType:"text",
            cache: false,
            beforeSend: function(){

            },
            success: function(status){
                $('#alert_'+product_id).html(status);
                $('#alert_'+product_id).show();
            }
        });//ajax
    })(jQuery);
}

function product_quick_update_option(product_id, type) {
    (function ($) {
        let value = 0;
        switch (type) {
            case 'arrival':
                if ($('#toggle-new_arrival-' + product_id + ':checkbox:checked').length > 0) {
                    value = 1;
                }
                break;
            case 'hot_deal':
                if ($('#toggle-hot_deal-' + product_id + ':checkbox:checked').length > 0) {
                    value = 1;
                }
                break;
            case 'propose':
                if ($('#toggle-propose-' + product_id + ':checkbox:checked').length > 0) {
                    value = 1;
                }
                break;
            default:
                if ($('#toggle-best_seller-' + product_id + ':checkbox:checked').length > 0) {
                    value = 1;
                }
                break;
        }
        $.ajax({
            type: "POST",
            url: admin_url + "/ajax/product-update-option",
            data: {
                '_token': getMetaContentByName('csrf-token'),
                'type' : type,
                'id': product_id,
                'value': value
            },
            dataType: "JSON",
            cache: false,
            beforeSend: function() {

            },
            success: function(result)
            {
                if (!result.success) {
                    alert('Product has update failed.');
                }
            }
        });
    })(jQuery);
}

function store_status_click(product_id) {
    (function ($) {
        if($('#toggle-store-status-'+product_id+':checkbox:checked').length > 0){
            var check = 1;
        }else{
            var check = 0;
        }
        $.ajax({
            type: "POST",
            url: admin_url+"/ajax/process_store_status",
            data: {
                '_token': getMetaContentByName('csrf-token'),
                'check' : check,
                'sid':product_id
            },
            dataType:"text",
            cache: false,
            beforeSend: function() {

            },
            success: function(status)
            {

            }
        });//ajax
    })(jQuery);
}

function generateSelectVariableChild(e) {
    (function ($) {
        if ($(e).val().length > 0) {
            $.ajax({
                type: "GET",
                url: admin_url + "/ajax/generate-select-variable-child",
                data: {
                    'variable_parents' : $(e).val()
                },
                dataType:"text",
                cache: false,
                beforeSend: function() {
                    $('#generate_select_variable_child').html('');
                    $('#loading-select-variable').show();
                },
                success: function(data)
                {
                    $('#generate_select_variable_child').html(data);
                    let i;
                    for (i = 1; i <= $(e).val().length; i++) {
                        $('#variable_child_' + i).select2();

                        $("select").select2({
                            tags: true
                        });

                        $("select").on("select2:select", function (evt) {
                            var element = evt.params.data.element;
                            var $element = $(element);

                            $element.detach();
                            $(this).append($element);
                            $(this).trigger("change");
                        });
                    }
                    $('#loading-select-variable').hide();
                }
            });//ajax
        } else {
            $('#table_variable_generate').html('');
        }
    })(jQuery);
}

function generateTableVariable() {
    (function ($) {
        if ($('#parent_variable').val().length > 0) {
            $('#loading-table-variable').show();
            var arr_parent_variable = $('#parent_variable').val();
            var count_variable_parent = $('#count_variable_parent').val();
            let table_variable_generate = $('#table_variable_generate').html();

            let i;
            let arr = [];
            let input_parent_variable = [];
            for (i = 1; i <= count_variable_parent; i++) {
                let parent_slug = $('#variable_child_' + i).attr('data-parent-slug');
                input_parent_variable.push(parent_slug);
                let data_variable_child = $('#variable_child_' + i).val();
                let arr_item = {
                    "parent_slug": parent_slug,
                    "data": data_variable_child
                };
                arr.push(arr_item);
                // xử lý xoá biển thể không tồn tại trong table
                let data_variable = '';
                if (data_variable_child.length == 0) {
                    $('#table_variable_generate table tbody').html('');
                    $('#count_item_variable_generate').val(0);
                } else {
                    $('#table_variable_generate table tbody tr').each(function () {
                        data_variable = $(this).attr('data-variable');
                        let arr_data_variable = data_variable.split('/');
                        let check_tr_isset = true;
                        for (let ii = 0; ii < arr_data_variable.length; ii++) {
                            for (let jj = 0; jj < data_variable_child.length; jj++) {
                                if (!arr_data_variable.includes(data_variable_child[jj])) {
                                    check_tr_isset = false
                                } else {
                                    check_tr_isset = true;
                                    break;
                                }
                            }
                        }
                        if (!check_tr_isset) {
                            $(this).remove();
                        }
                    });
                }
            }

            if (table_variable_generate !== '') {
                let parent_variable_name = '';
                for (i = 0; i < arr_parent_variable.length; i++) {
                    parent_variable_name += `<td scope="col"><b>${arr_parent_variable[i]}</b></td>`;
                }

                var table_thead = `<tr>
                        <td scope="col"><b>Tên sản phẩm</b></td>
                        <td scope="col"><b>Mã sản phẩm (SKU)</b></td>
                        <td scope="col"><b>Số lượng</b></td>
                        ${parent_variable_name}
                        <td scope="col"><b>Sắp xếp</b></td>
                        <td scope="col"><b>Giá gốc</b></td>
                        <td scope="col"><b>Giá khuyến mãi</b></td>
                        <td scope="col"><b>Ảnh đại diện</b></td>
                    </tr>`;
                $('#table_variable_generate table thead').html(table_thead);
                i = 0
                let count_variable_i = arr[i].data.length;
                let n;
                let count_item_generate = parseInt($('#count_item_variable_generate').val()) + 1;
                for (n = 0; n < count_variable_i; n++) {
                    let value_n = arr[i].data[n];
                    let j = i + 1;
                    if (j < count_variable_parent) {
                        let m;
                        let count_variable_j = arr[j].data.length;
                        for (m = 0; m < count_variable_j; m++) {
                            let value_m = arr[j].data[m];
                            let k = j + 1;
                            if (k < count_variable_parent) {
                                let o;
                                let count_variable_k = arr[k].data.length;
                                for (o = 0; o < count_variable_k; o++) {
                                    let value_o = arr[k].data[o];
                                    let l = k + 1;
                                    if (l < count_variable_parent) {
                                        let count_variable_l = arr[l].data.length;
                                        let p;
                                        for (p = 0; p < count_variable_l; p++) {
                                            let value_p = arr[l].data[p];
                                            let h = l + 1;
                                            if (h < count_variable_parent) {
                                                let count_variable_h = arr[h].data.length;
                                                let y;
                                                for (y = 0; p < count_variable_h; y++) {
                                                    let value_y = arr[h].data[y];

                                                    // kiểm tra data variable đã tồn tại hay chưa
                                                    const arr_variable = [value_n, value_m, value_o, value_p, value_y];
                                                    let check_variable = true;
                                                    let data_variable = '';
                                                    if ($('#table_variable_generate table tbody tr').length > 0) {
                                                        $('#table_variable_generate table tbody tr').each(function () {
                                                            data_variable = $(this).attr('data-variable');
                                                            let arr_data_variable = data_variable.split('/');
                                                            for (let ii = 0; ii < arr_data_variable.length; ii++) {
                                                                if (!arr_variable.includes(arr_data_variable[ii])) {
                                                                    check_variable = false;
                                                                    break;
                                                                } else {
                                                                    check_variable = true;
                                                                }
                                                            }
                                                            if (check_variable) {
                                                                return false;
                                                            }
                                                        });
                                                    } else {
                                                        check_variable = false;
                                                    }

                                                    if (!check_variable) {
                                                        $('#table_variable_generate table tbody').append(`
                                                            <tr data-variable="${value_n}/${value_m}/${value_o}/${value_p}/${value_y}">
                                                                <td>
                                                                    <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                                                    <div style="font-size:14px; color:#060;">
                                                                        <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                                                    </div>
                                                                    <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                                                </td>
                                                                <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                                                <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                                                <td>
                                                                    <input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="variable_name_${arr[j].parent_slug}_${count_item_generate}" value="${value_m}">${value_m}
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="variable_name_${arr[k].parent_slug}_${count_item_generate}" value="${value_o}">${value_o}
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="variable_name_${arr[l].parent_slug}_${count_item_generate}" value="${value_p}">${value_p}
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="variable_name_${arr[h].parent_slug}_${count_item_generate}" value="${value_y}">${value_y}
                                                                </td>
                                                                ${generateVariableInput(count_item_generate)}
                                                            </tr>
                                                        `);
                                                    }
                                                }
                                            } else {
                                                // kiểm tra data variable đã tồn tại hay chưa
                                                const arr_variable = [value_n, value_m, value_o, value_p];
                                                let check_variable = true;
                                                let data_variable = '';
                                                if ($('#table_variable_generate table tbody tr').length > 0) {
                                                    $('#table_variable_generate table tbody tr').each(function () {
                                                        data_variable = $(this).attr('data-variable');
                                                        let arr_data_variable = data_variable.split('/');
                                                        for (let ii = 0; ii < arr_data_variable.length; ii++) {
                                                            if (!arr_variable.includes(arr_data_variable[ii])) {
                                                                check_variable = false;
                                                                break;
                                                            } else {
                                                                check_variable = true;
                                                            }
                                                        }
                                                        if (check_variable) {
                                                            return false;
                                                        }
                                                    });
                                                } else {
                                                    check_variable = false;
                                                }

                                                if (!check_variable) {
                                                    $('#table_variable_generate table tbody').append(`
                                                        <tr data-variable="${value_n}/${value_m}/${value_o}/${value_p}">
                                                            <td>
                                                                <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                                                <div style="font-size:14px; color:#060;">
                                                                    <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                                                </div>
                                                                <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                                            </td>
                                                            <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                                            <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                                            <td>
                                                                <input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="variable_name_${arr[j].parent_slug}_${count_item_generate}" value="${value_m}">${value_m}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="variable_name_${arr[k].parent_slug}_${count_item_generate}" value="${value_o}">${value_o}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="variable_name_${arr[l].parent_slug}_${count_item_generate}" value="${value_p}">${value_p}
                                                            </td>
                                                            ${generateVariableInput(count_item_generate)}
                                                        </tr>
                                                    `);
                                                    count_item_generate++;
                                                }
                                            }
                                        }
                                    } else {
                                        // kiểm tra data variable đã tồn tại hay chưa
                                        const arr_variable = [value_n, value_m, value_o];
                                        let check_variable = true;
                                        let data_variable = '';
                                        if ($('#table_variable_generate table tbody tr').length > 0) {
                                            $('#table_variable_generate table tbody tr').each(function () {
                                                data_variable = $(this).attr('data-variable');
                                                let arr_data_variable = data_variable.split('/');
                                                for (let ii = 0; ii < arr_data_variable.length; ii++) {
                                                    if (!arr_variable.includes(arr_data_variable[ii])) {
                                                        check_variable = false;
                                                        break;
                                                    } else {
                                                        check_variable = true;
                                                    }
                                                }
                                                if (check_variable) {
                                                    return false;
                                                }
                                            });
                                        } else {
                                            check_variable = false;
                                        }

                                        if (!check_variable) {
                                            $('#table_variable_generate table tbody').append(`
                                                <tr data-variable="${value_n}/${value_m}/${value_o}">
                                                    <td>
                                                        <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                                        <div style="font-size:14px; color:#060;">
                                                            <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                                        </div>
                                                        <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                                    </td>
                                                    <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                                    <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                                    <td>
                                                        <input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="variable_name_${arr[j].parent_slug}_${count_item_generate}" value="${value_m}">${value_m}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="variable_name_${arr[k].parent_slug}_${count_item_generate}" value="${value_o}">${value_o}
                                                    </td>
                                                    ${generateVariableInput(count_item_generate)}
                                                </tr>
                                            `);
                                            count_item_generate++;
                                        }
                                    }
                                }
                            } else {
                                // kiểm tra data variable đã tồn tại hay chưa
                                const arr_variable = [value_n, value_m];
                                let check_variable = true;
                                let data_variable = '';
                                if ($('#table_variable_generate table tbody tr').length > 0) {
                                    $('#table_variable_generate table tbody tr').each(function () {
                                        data_variable = $(this).attr('data-variable');
                                        let arr_data_variable = data_variable.split('/');
                                        for (let ii = 0; ii < arr_data_variable.length; ii++) {
                                            if (!arr_variable.includes(arr_data_variable[ii])) {
                                                check_variable = false;
                                                break;
                                            } else {
                                                check_variable = true;
                                            }
                                        }
                                        if (check_variable) {
                                            return false;
                                        }
                                    });
                                } else {
                                    check_variable = false;
                                }

                                if (!check_variable) {
                                    $('#table_variable_generate table tbody').append(`
                                        <tr data-variable="${value_n}/${value_m}">
                                            <td>
                                                <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                                <div style="font-size:14px; color:#060;">
                                                    <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                                </div>
                                                <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                            </td>
                                            <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                            <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                            <td>
                                                <input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}
                                            </td>
                                            <td>
                                                <input type="hidden" name="variable_name_${arr[j].parent_slug}_${count_item_generate}" value="${value_m}">${value_m}
                                            </td>
                                            ${generateVariableInput(count_item_generate)}
                                        </tr>
                                    `);
                                    count_item_generate++;
                                }
                            }
                        }
                    } else {
                        // kiểm tra data variable đã tồn tại hay chưa
                        const arr_variable = [value_n];

                        let check_variable = true;
                        let data_variable = '';
                        if ($('#table_variable_generate table tbody tr').length > 0) {
                            $('#table_variable_generate table tbody tr').each(function () {
                                data_variable = $(this).attr('data-variable');
                                let arr_data_variable = data_variable.split('/');
                                for (let ii = 0; ii < arr_data_variable.length; ii++) {
                                    if (!arr_variable.includes(arr_data_variable[ii])) {
                                        check_variable = false;
                                        break;
                                    } else {
                                        check_variable = true;
                                    }
                                }
                                if (check_variable) {
                                    return false;
                                }
                            });
                        } else {
                            check_variable = false;
                        }

                        if (!check_variable) {
                            $('#table_variable_generate table tbody').append(`
                                <tr data-variable="${value_n}">
                                    <td>
                                        <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                        <div style="font-size:14px; color:#060;">
                                            <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                        </div>
                                        <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                    </td>
                                    <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                    <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                    <td><input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}</td>
                                    ${generateVariableInput(count_item_generate)}
                                </tr>
                            `);
                            count_item_generate++;
                        }
                    }
                }
                // $('#table_variable_generate table tbody').append(html_generate);
                let count_item_variable_generate = parseInt(count_item_generate) - 1;
                $('#group_parent_variable_slug').val(input_parent_variable);
                $('#count_item_variable_generate').val(count_item_variable_generate);
            } else {
                let parent_variable_name = '';
                for (i = 0; i < arr_parent_variable.length; i++) {
                    console.log(arr_parent_variable[i]);
                    parent_variable_name += `<td scope="col"><b>${arr_parent_variable[i]}</b></td>`;
                }
                var html_generate = `<div class="text-right mb-2">
                    <a href="javascript:void(0)" class="btn btn-danger" onclick="autoAddPriceToVariable()">Đồng giá toàn bộ</a>
                </div>`;
                    html_generate += `<div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <td scope="col"><b>Tên sản phẩm</b></td>
                                <td scope="col"><b>Mã sản phẩm (SKU)</b></td>
                                <td scope="col"><b>Số lượng</b></td>
                                ${parent_variable_name}
                                <td scope="col"><b>Sắp xếp</b></td>
                                <td scope="col"><b>Giá gốc</b></td>
                                <td scope="col"><b>Giá khuyến mãi</b></td>
                                <td scope="col"><b>Ảnh đại diện</b></td>
                            </tr>
                        </thead>
                        <tbody>
                `;

                i = 0
                let count_variable_i = arr[i].data.length;
                let n;
                let count_item_generate = 1;
                for (n = 0; n < count_variable_i; n++) {
                    let value_n = arr[i].data[n];
                    let j = i + 1;
                    if (j < count_variable_parent) {
                        let m;
                        let count_variable_j = arr[j].data.length;
                        for (m = 0; m < count_variable_j; m++) {
                            let value_m = arr[j].data[m];
                            let k = j + 1;
                            if (k < count_variable_parent) {
                                let o;
                                let count_variable_k = arr[k].data.length;
                                for (o = 0; o < count_variable_k; o++) {
                                    let value_o = arr[k].data[o];
                                    let l = k + 1;
                                    if (l < count_variable_parent) {
                                        let count_variable_l = arr[l].data.length;
                                        let p;
                                        for (p = 0; p < count_variable_l; p++) {
                                            let value_p = arr[l].data[p];
                                            let h = l + 1;
                                            if (h < count_variable_parent) {
                                                let count_variable_h = arr[h].data.length;
                                                let y;
                                                for (y = 0; p < count_variable_h; y++) {
                                                    let value_y = arr[h].data[y];
                                                    html_generate += `
                                                        <tr data-variable="${value_n}/${value_m}/${value_o}/${value_p}/${value_y}">
                                                            <td>
                                                                <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                                                <div style="font-size:14px; color:#060;">
                                                                    <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                                                </div>
                                                                <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                                            </td>
                                                            <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                                            <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                                            <td>
                                                                <input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="variable_name_${arr[j].parent_slug}_${count_item_generate}" value="${value_m}">${value_m}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="variable_name_${arr[k].parent_slug}_${count_item_generate}" value="${value_o}">${value_o}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="variable_name_${arr[l].parent_slug}_${count_item_generate}" value="${value_p}">${value_p}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="variable_name_${arr[h].parent_slug}_${count_item_generate}" value="${value_y}">${value_y}
                                                            </td>
                                                            ${generateVariableInput(count_item_generate)}
                                                        </tr>
                                                    `;
                                                }
                                            } else {
                                                html_generate += `
                                                    <tr data-variable="${value_n}/${value_m}/${value_o}/${value_p}">
                                                        <td>
                                                            <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                                            <div style="font-size:14px; color:#060;">
                                                                <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                                            </div>
                                                            <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                                        </td>
                                                        <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                                        <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                                        <td>
                                                            <input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="variable_name_${arr[j].parent_slug}_${count_item_generate}" value="${value_m}">${value_m}
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="variable_name_${arr[k].parent_slug}_${count_item_generate}" value="${value_o}">${value_o}
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="variable_name_${arr[l].parent_slug}_${count_item_generate}" value="${value_p}">${value_p}
                                                        </td>
                                                        ${generateVariableInput(count_item_generate)}
                                                    </tr>
                                                `;
                                                count_item_generate++;
                                            }
                                        }
                                    } else {
                                        html_generate += `
                                            <tr data-variable="${value_n}/${value_m}/${value_o}">
                                                <td>
                                                    <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                                    <div style="font-size:14px; color:#060;">
                                                        <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                                    </div>
                                                    <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                                </td>
                                                <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                                <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                                <td>
                                                    <input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="variable_name_${arr[j].parent_slug}_${count_item_generate}" value="${value_m}">${value_m}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="variable_name_${arr[k].parent_slug}_${count_item_generate}" value="${value_o}">${value_o}
                                                </td>
                                                ${generateVariableInput(count_item_generate)}
                                            </tr>
                                        `;
                                        count_item_generate++;
                                    }
                                }
                            } else {
                                html_generate += `
                                    <tr data-variable="${value_n}/${value_m}">
                                        <td>
                                            <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                            <div style="font-size:14px; color:#060;">
                                                <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                            </div>
                                            <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                        </td>
                                        <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                        <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                        <td>
                                            <input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}
                                        </td>
                                        <td>
                                            <input type="hidden" name="variable_name_${arr[j].parent_slug}_${count_item_generate}" value="${value_m}">${value_m}
                                        </td>
                                        ${generateVariableInput(count_item_generate)}
                                    </tr>
                                `;
                                count_item_generate++;
                            }
                        }
                    } else {
                        html_generate += `
                            <tr data-variable="${value_n}">
                                <td>
                                    <input type="text" class="form-control product_name_variable" onkeyup="generateSlug(${count_item_generate})" name="product_name_variable_${count_item_generate}" placeholder="Tên sản phẩm">
                                    <div style="font-size:14px; color:#060;">
                                        <span id="count_char_title_${count_item_generate}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                    </div>
                                    <input type="text" class="form-control product_slug_variable mt-1" name="product_slug_variable_${count_item_generate}" placeholder="Slug">
                                </td>
                                <td><input type="text" class="form-control" name="product_variable_sku_${count_item_generate}" placeholder="Mã sản phẩm"></td>
                                <td><input type="number" class="form-control" name="product_stock_variable_${count_item_generate}" placeholder="Số lượng sản phẩm"></td>
                                <td><input type="hidden" name="variable_name_${arr[i].parent_slug}_${count_item_generate}" value="${value_n}">${value_n}</td>
                                ${generateVariableInput(count_item_generate)}
                            </tr>
                        `;
                        count_item_generate++;
                    }
                }
                let count_item_variable_generate = parseInt(count_item_generate) - 1;
                html_generate += '</tbody></table></div>' +
                    '<input type="hidden" name="group_parent_variable_slug" id="group_parent_variable_slug" value="' + input_parent_variable + '">' +
                    '<input type="hidden" name="count_item_variable_generate" id="count_item_variable_generate" value="' + count_item_variable_generate + '">';
                $('#table_variable_generate').html(html_generate);
            }
            $('#loading-table-variable').hide();
        }
    })(jQuery);
}

function generateVariableGallery() {
    (function ($) {
        let count_gallery_parent = parseInt($('#count_gallery_parent').val()) + 1;
        let variable = $('#slt_variable_album').val();
        let variable_items = $('.select-variable .' + variable).val();
        let variable_parent_gallery = $('#variable_parent_gallery').val();
        if (variable_items.length > 0) {
            if($('.box-variable-gallery-' + variable).length > 0) {
                count_gallery_parent = $('.box-variable-gallery-' + variable).attr('data-count-gallery-parent');
                let i;
                let j = $('.box-variable-gallery-' + variable + ' .box-item-count').length;
                $('.box-variable-gallery-' + variable + ' .box-item-count').each(function () {
                    let box_item = $(this).attr('data-box-item');
                    let data_count_gallery_parent = $(this).attr('data-count-gallery-parent');
                    let data_count_child = $(this).attr('data-count-child');
                    if (!variable_items.includes(box_item)) {
                        $(this).remove();
                        $('#variableGalleryModal_' + data_count_gallery_parent + '_' + data_count_child).remove();
                    }
                });
                let box_variable_gallery = '';
                for (i = 0; i < variable_items.length; i++) {
                    let variable_item_slug = convertToSlug(variable_items[i]);
                    if ($('.box-variable-gallery-' + variable + ' .box-item-' + variable_item_slug).length === 0) {
                        j++;
                        box_variable_gallery += `<div class="mt-2 box-item-count box-item-${variable_item_slug}
                        box-item-${count_gallery_parent}-${j}" data-box-item="${variable_items[i]}"
                        data-count-child="${j}"
                        data-count-gallery-parent="${count_gallery_parent}">
                            <div class="form-row align-items-center">
                                <div class="col"><b>${variable_items[i]}</b></div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#variableGalleryModal_${count_gallery_parent}_${j}">
                                        Gallery
                                    </button>
                                </div>
                                <div class="col"><a href="javascript:void(0)" class="btn btn-danger" onclick="deleteVariableGallery(${count_gallery_parent}, ${j})">Xóa</a></div>
                            </div>
                            <div class="modal fade variable-gallery-modal" id="variableGalleryModal_${count_gallery_parent}_${j}" tabindex="-1" role="dialog"
                                aria-labelledby="variableGalleryLabel_${count_gallery_parent}_${j}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="variableGalleryLabel_${count_gallery_parent}_${j}">Gallery biến thể</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input id="count_gallery_variable_${count_gallery_parent}_${j}" type="hidden" value="0" name="count_gallery_variable_${count_gallery_parent}_${j}" />
                                            <input id="name_gallery_variable_${count_gallery_parent}_${j}" type="hidden" value="${variable_items[i]}" name="name_gallery_variable_${count_gallery_parent}_${j}" />
                                            <input id="slug_parent_variable_${count_gallery_parent}_${j}" type="hidden" value="${variable}" name="slug_parent_variable_${count_gallery_parent}_${j}" />
                                            <div id="add_item_images_${count_gallery_parent}_${j}" class="form-row"></div>
                                            <div class="form-group text-center mt-2">
                                                <label for="variable_image_upload_${count_gallery_parent}_${j}" class="ml-1">
                                                    <input type="file" class="variable_upload_file" id="variable_image_upload_${count_gallery_parent}_${j}"
                                                        name="variable_image_upload_${count_gallery_parent}_${j}[]" multiple onchange="generateGalleryVariable(this, ${count_gallery_parent}, ${j}, 0)">
                                                    <span class="btn btn-success">Thêm ảnh (Giữ Ctrl để chọn nhiều ảnh)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    }
                }
                $('#count_item_gallery_' + count_gallery_parent).val(j);
                $('.box-variable-gallery-' + variable).append(box_variable_gallery);
            } else {
                let box_variable_gallery = `<div class="box-variable-gallery box-variable-gallery-${variable}
                    box-variable-gallery-${count_gallery_parent} mt-2" data-count-gallery-parent="${count_gallery_parent}">
                    <input type="hidden" id="count_item_gallery_${count_gallery_parent}" value="${variable_items.length}"
                            name="count_item_gallery_${count_gallery_parent}">`;
                let i;
                let j = 1;
                for (i = 0; i < variable_items.length; i++) {
                    let variable_item_slug = convertToSlug(variable_items[i]);
                    box_variable_gallery += `<div class="mt-2 box-item-count box-item-${variable_item_slug}
                        box-item-${count_gallery_parent}-${j}" data-box-item="${variable_items[i]}">
                    <div class="form-row align-items-center">
                        <div class="col"><b>${variable_items[i]}</b></div>
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#variableGalleryModal_${count_gallery_parent}_${j}">
                                Gallery
                            </button>
                        </div>
                        <div class="col"><a href="javascript:void(0)" class="btn btn-danger" onclick="deleteVariableGallery(${count_gallery_parent}, ${j})">Xóa</a></div>
                    </div>
                    <div class="modal fade variable-gallery-modal" id="variableGalleryModal_${count_gallery_parent}_${j}" tabindex="-1" role="dialog"
                        aria-labelledby="variableGalleryLabel_${count_gallery_parent}_${j}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="variableGalleryLabel_${count_gallery_parent}_${j}">Gallery biến thể</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input id="count_gallery_variable_${count_gallery_parent}_${j}" type="hidden" value="0" name="count_gallery_variable_${count_gallery_parent}_${j}" />
                                    <input id="name_gallery_variable_${count_gallery_parent}_${j}" type="hidden" value="${variable_items[i]}" name="name_gallery_variable_${count_gallery_parent}_${j}" />
                                    <input id="slug_parent_variable_${count_gallery_parent}_${j}" type="hidden" value="${variable}" name="slug_parent_variable_${count_gallery_parent}_${j}" />
                                    <div id="add_item_images_${count_gallery_parent}_${j}" class="form-row"></div>
                                    <div class="form-group text-center mt-2">
                                        <label for="variable_image_upload_${count_gallery_parent}_${j}" class="ml-1">
                                            <input type="file" class="variable_upload_file" id="variable_image_upload_${count_gallery_parent}_${j}"
                                                name="variable_image_upload_${count_gallery_parent}_${j}[]" multiple onchange="generateGalleryVariable(this, ${count_gallery_parent}, ${j}, 0)">
                                            <span class="btn btn-success">Thêm ảnh (Giữ Ctrl để chọn nhiều ảnh)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
                    j++;
                }
                box_variable_gallery += `</div>`;
                let array_parent = [];
                if (variable_parent_gallery === '') {
                    array_parent.push(variable);
                    $('#variable_parent_gallery').val(array_parent);
                } else {
                    array_parent = [variable_parent_gallery];
                    array_parent.push(variable);
                    $('#variable_parent_gallery').val(array_parent);
                }
                $('#generate-gallery-result').prepend(box_variable_gallery);
                $('#count_gallery_parent').val(count_gallery_parent);
            }
        }
        return '';
    })(jQuery);
}

function generateVariableIcon() {
    (function ($) {
        let count_icon_parent = parseInt($('#count_icon_parent').val()) + 1;
        let variable = $('#slt_variable_icon').val();
        let variable_items = $('.select-variable .' + variable).val();
        let variable_parent_icon = $('#variable_parent_icon').val();
        let img_default = '/img/default-150x150.png';
        if (variable_items.length > 0) {
            if($('.box-variable-icon-' + variable).length > 0) {
                count_icon_parent = $('.box-variable-icon-' + variable).attr('data-count-icon-parent');
                let i;
                let j = $('.box-variable-icon-' + variable + ' .box-item-count').length;
                $('.box-variable-icon-' + variable + ' .box-item-count').each(function () {
                    let box_item = $(this).attr('data-box-item');
                    let data_count_icon_parent = $(this).attr('data-count-icon-parent');
                    let data_count_child = $(this).attr('data-count-child');
                    if (!variable_items.includes(box_item)) {
                        $(this).remove();
                    }
                });
                let box_variable_icon = '';
                for (i = 0; i < variable_items.length; i++) {
                    let variable_item_slug = convertToSlug(variable_items[i]);
                    if ($('.box-variable-icon-' + variable + ' .box-item-' + variable_item_slug).length === 0) {
                        j++;
                        box_variable_icon += `<div class="mt-2 box-item-count box-item-${variable_item_slug}
                        box-item-${count_icon_parent}-${j}" data-box-item="${variable_items[i]}"
                        data-count-child="${j}"
                        data-count-icon-parent="${count_icon_parent}">
                            <input id="name_icon_variable_${count_icon_parent}_${j}" type="hidden"
                                   value="${variable_items[i]}" name="name_icon_variable_${count_icon_parent}_${j}">
                            <input id="slug_icon_parent_variable_${count_icon_parent}_${j}" type="hidden"
                                   value="${variable}" name="slug_icon_parent_variable_${count_icon_parent}_${j}">
                            <div class="form-row align-items-center">
                                <div class="col"><b>${variable_items[i]}</b></div>
                                <div class="col text-center">
                                    <img src="${img_default}" alt="" style="width: 80px">
                                </div>
                                <div class="col">
                                    <div class="form-inline">
                                        <input type="text" name="icon_variable_name_${count_icon_parent}_${j}"
                                               class="form-control" style="width: 90%"
                                               placeholder="Icon ảnh biến thể (60x60px)"
                                               id="icon_variable_name_${count_icon_parent}_${j}">
                                        <label for="upload_icon_variable_${count_icon_parent}_${j}" class="pl-2">
                                            <input type="file" name="upload_icon_variable_${count_icon_parent}_${j}"
                                                   id="upload_icon_variable_${count_icon_parent}_${j}"
                                                   onchange="fakeValueIconVariable(${count_icon_parent}, ${j})"
                                                   style="display: none"
                                            >
                                            <span style="font-size: 20px;"><i class="fas fa-file-upload"></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    }
                }
                $('#count_item_icon_' + count_icon_parent).val(j);
                $('.box-variable-icon-' + variable).append(box_variable_icon);
            } else {
                let box_variable_icon = `<div class="box-variable-gallery box-variable-icon-${variable}
                    box-variable-icon-${count_icon_parent} mt-2" data-count-icon-parent="${count_icon_parent}">
                    <input type="hidden" id="count_item_icon_${count_icon_parent}" value="${variable_items.length}"
                            name="count_item_icon_${count_icon_parent}">`;
                let i;
                let j = 1;
                for (i = 0; i < variable_items.length; i++) {
                    let variable_item_slug = convertToSlug(variable_items[i]);
                    box_variable_icon += `<div class="mt-2 box-item-count box-item-${variable_item_slug}
                        box-item-${count_icon_parent}-${j}" data-box-item="${variable_items[i]}">
                        <input id="name_icon_variable_${count_icon_parent}_${j}" type="hidden"
                               value="${variable_items[i]}" name="name_icon_variable_${count_icon_parent}_${j}">
                        <input id="slug_icon_parent_variable_${count_icon_parent}_${j}" type="hidden"
                               value="${variable}" name="slug_icon_parent_variable_${count_icon_parent}_${j}">
                        <div class="form-row align-items-center">
                            <div class="col"><b>${variable_items[i]}</b></div>
                            <div class="col text-center">
                                <img src="${img_default}" alt="" style="width: 80px">
                            </div>
                            <div class="col">
                                <div class="form-inline">
                                    <input type="text" name="icon_variable_name_${count_icon_parent}_${j}"
                                           class="form-control" style="width: 90%"
                                           placeholder="Icon ảnh biến thể (60x60px)"
                                           id="icon_variable_name_${count_icon_parent}_${j}">
                                    <label for="upload_icon_variable_${count_icon_parent}_${j}" class="pl-2">
                                        <input type="file" name="upload_icon_variable_${count_icon_parent}_${j}"
                                               id="upload_icon_variable_${count_icon_parent}_${j}"
                                               onchange="fakeValueIconVariable(${count_icon_parent}, ${j})"
                                               style="display: none"
                                        >
                                        <span style="font-size: 20px;"><i class="fas fa-file-upload"></i></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    j++;
                }
                box_variable_icon += `</div>`;
                let array_parent = [];
                if (variable_parent_icon === '') {
                    array_parent.push(variable);
                    $('#variable_parent_icon').val(array_parent);
                } else {
                    array_parent = [variable_parent_icon];
                    array_parent.push(variable);
                    $('#variable_parent_icon').val(array_parent);
                }
                $('#generate-icon-result').prepend(box_variable_icon);
                $('#count_icon_parent').val(count_icon_parent);
            }
        }
        return '';
    })(jQuery);
}

function fakeValueThumbnailVariable(i) {
    (function ($) {
        let fake_value = $('#product_thumbnail_variable_' + i).val();
        $('#product_thumbnail_link_variable_' + i).val(fake_value);
        let input = document.getElementById('product_thumbnail_variable_' + i);
        var reader = new FileReader();
        reader.readAsDataURL(input.files[0]);
        reader.onload = function(){
            let img = document.getElementById('thumbnail_variable_demo_' + i);
            img.src = reader.result;
        };
    })(jQuery);
}

function fakeValueIconVariable(i, j) {
    (function ($) {
        let fake_value = $('#upload_icon_variable_' + i + '_' + j).val();
        $('#icon_variable_name_' + i + '_' + j).val(fake_value);
    })(jQuery);
}

function generateSlug(i) {
    (function ($) {
        let slug = $.slugify($('input[name="product_name_variable_' + i + '"]').val());
        $('input[name="product_slug_variable_' + i + '"]').val(slug);
        let charCount = $('input[name="product_name_variable_' + i + '"]').val().replace(/\s/g, '').length;
        $("#count_char_title_" + i).text(charCount);
    })(jQuery);
}

function generateVariableInput(count_item_generate) {
    let variable_input_html = '';
    variable_input_html = `
        <td><input type="text" class="form-control sort_variable" name="sort_variable_${count_item_generate}" placeholder="Sắp xếp"></td>
        <td><input type="text" class="form-control price_origin_variable" name="price_origin_variable_${count_item_generate}" placeholder="Giá gốc"></td>
        <td><input type="text" class="form-control price_promotion_variable" name="price_promotion_variable_${count_item_generate}" placeholder="Giá khuyến mãi"></td>
        <td>
            <div class="form-inline">
                <img src="/img/default-150x150.png" alt="" id="thumbnail_variable_demo_${count_item_generate}" class="pr-2" style="width: 20%">
                <input type="text" class="form-control" id="product_thumbnail_link_variable_${count_item_generate}" style="width: 60%"
                       name="product_thumbnail_link_variable_${count_item_generate}">
                <label for="product_thumbnail_variable_${count_item_generate}" class="pl-2">
                    <input type="file" onchange="fakeValueThumbnailVariable(${count_item_generate})" style="display: none"
                           name="product_thumbnail_variable_${count_item_generate}" id="product_thumbnail_variable_${count_item_generate}">
                    <span style="font-size: 20px;"><i class="fas fa-file-upload"></i></span>
                </label>
            </div>
        </td>
    `;
    return variable_input_html;
}

function deleteVariableGallery(count_gallery_parent, j) {
    (function ($) {
        $('#generate-gallery-result .box-item-' + count_gallery_parent + '-' + j).remove();
        $('#variableGalleryModal_' + count_gallery_parent + '_' + j).remove();
    })(jQuery);
}

function deleteVariableGroupRelated(countFavoriteCustomerGroupParent, j) {
    (function ($) {
        $('#generate-group-related-result .box-item-' + countFavoriteCustomerGroupParent + '-' + j).remove();
    })(jQuery);
}

function generateGalleryVariable(e, count_gallery_parent, k, count_item_current) {
    (function ($) {
        let count_image = 0;
        if (e.files && e.files[0]) {
            let add_item_gallery = "";
            $(e.files).each(function (i, file) {
                $('.create_item_image_variable').remove();
                let reader = new FileReader();
                reader.readAsDataURL(this);
                reader.onload = function (data) {
                    add_item_gallery = `
                    <div class="form-row image-gallery-group" id="image-gallery-group-${count_gallery_parent}-${k}-${count_item_current + i + 1}">
                        <div class="form-group col-md-3">
                            <div class="image-variable-demo">
                                <img src="${data.target.result}">
                            </div>
                        </div>
                        <div class="form-group col-md-7">
                            <input class="form-control image_file_name" type="text" value="${file.name}"
                                name="variable_image_name_demo_${count_gallery_parent}_${k}_${count_item_current + i + 1}">
                        </div>
                        <div class="form-group col-md-2">
                            <a href="javascript:void(0)" class="btn btn-danger action-img-variable"
                                onclick="deleteVariableImageItem(${count_gallery_parent}, ${k}, ${count_item_current + i + 1})">Xóa</a>
                        </div>
                    </div>`;
                    $('#add_item_images_' + count_gallery_parent + '_' + k).append(add_item_gallery);
                    $('#add_item_images_' + count_gallery_parent + '_' + k).sortable({
                        stop: function(event, ui){
                            var cnt = 1;
                            $(this).children('.image-gallery-group').each(function(){
                                $(this).find('input.image_file_name').attr('name','variable_image_name_demo_' + count_gallery_parent + '_' + k + '_' + cnt);
                                $(this).find('a.action-img-variable').attr('onclick','deleteVariableImageItem(' + count_gallery_parent + ',' + k + ',' + cnt + ')');
                                cnt++;
                            });
                        }
                    });
                }
                count_image++;
            });
            $('#count_gallery_variable_' + count_gallery_parent + '_' + k).val(count_image + count_item_current);
        }
    })(jQuery);
}

function deleteVariableImageItem(count_gallery_parent, k, i) {
    $('#image-gallery-group-' + count_gallery_parent + '-' + k + '-' + i).remove();
}

function autoAddPriceToVariable() {
    (function ($) {
        let post_title = $('#title').val();
        let price_origin = $('#price_origin').val();
        let price_promotion = $('#price_promotion').val();
        if (parseInt(price_origin) < parseInt(price_promotion)) {
            price_promotion = price_origin;
        }
        $('.product_name_variable').val(post_title);
        $('.price_origin_variable').val(price_origin);
        $('.price_promotion_variable').val(price_promotion);
    })(jQuery);
}

function autoFillNameVariableFile(e, count_item_generate) {
    $('#variable_image_name_' + count_item_generate).val($(e).val());
}

function addGroupCode(){
    (function ($) {
        var count_item_block = parseInt($('#count_group_discount').val()) + 1;
        var html_option = $('#js-get-option-product').html();
        var html_add_item_block = `<div class="box-discount">
            <a href="javascript:void(0)" class="delete-box" onclick="removeGroupCode(this)">x</a>
            <div class="font-weight-bold text-center title_group_box_discount">Option Discount ${count_item_block}</div>
            <p class="font-weight-bold my-2">Nhập <span style="color:red">MỘT trong HAI</span> loại giảm giá sau:</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="percent_${count_item_block}">Giảm theo phần trăm</label>
                        <input id="percent_${count_item_block}" type="text" name="percent_${count_item_block}" class="form-control"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="discount_money_${count_item_block}">Giảm theo giá trị cụ thể (USD)</label>
                        <input id="discount_money_${count_item_block}" type="text" name="discount_money_${count_item_block}" class="form-control"/>
                    </div>
                </div>
            </div>
            <h4 class="font-weight-bold">Chỉ chọn <span style="color: red">1 trong 2</span> loại áp dụng giảm giá sản phẩm. Nếu không nhập mã giảm giá sẽ áp dụng cho tất cả sản phẩm.</h4>
            <div class="form-group">
                <label for="except_products_${count_item_block}">Áp dụng cho tất cả sản phẩm ngoại trừ:</label>
                <select class="form-control" id="except_products_${count_item_block}"
                        name="except_products_${count_item_block}[]" multiple="multiple">
                    ${html_option}
                </select>
            </div>
            <div class="form-group">
                <label for="apply_products_${count_item_block}">Áp dụng cho các sản phẩm có trong list:</label>
                <select class="form-control" id="apply_products_${count_item_block}"
                        name="apply_products_${count_item_block}[]" multiple="multiple">
                    ${html_option}
                </select>
            </div>
        </div>`;
        $('#repeat_group_code').append(html_add_item_block);
        $('#count_group_discount').val(count_item_block);
        $('#apply_products_' + count_item_block).select2();
        $('#except_products_' + count_item_block).select2();
    })(jQuery);
}

function removeGroupCode(e) {
    (function ($) {
        $(e).parent().remove();
    })(jQuery);
}

function addMoreBlock(){
    (function ($) {
        var count_item_block = parseInt($('#count_block_occasion').val()) + 1;
        var html_option = $('#js-get-option-product').html();
        var html_add_item_block = `
                <div class="box-content">
                    <h3 class="mb-3">Block ${count_item_block}</h3>
                    <div class="form-group">
                        <label for="title_block_${count_item_block}">Title Block</label>
                        <input type="text" name="title_block_${count_item_block}" id="title_block_${count_item_block}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="subtitle_block_${count_item_block}">Subtitle Block</label>
                        <input type="text" name="subtitle_block_${count_item_block}" id="subtitle_block_${count_item_block}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description_block_${count_item_block}">Description Block</label>
                        <textarea name="description_block_${count_item_block}" id="description_block_${count_item_block}" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="upload_banner_block_${count_item_block}">Banner Block</label>
                        <input class="form-control" type="file" name="upload_banner_block_${count_item_block}" id="upload_banner_block_${count_item_block}">
                        <input type="hidden" name="text_banner_block_${count_item_block}" id="text_banner_block_${count_item_block}"/>
                    </div>
                    <div class="form-group">
                        <label for="slt_product_block_${count_item_block}">Select Products</label>
                        <select id="slt_product_block_${count_item_block}" name="slt_product_block_${count_item_block}[]" class="form-control" multiple="multiple">
                            ${html_option}
                        </select>
                    </div>
                </div>`;
        $('.multiple-content-occasion').append(html_add_item_block);
        $('#count_block_occasion').val(count_item_block);
        $('#slt_product_block_' + count_item_block).select2();

        CKEDITOR.replace('description_block_' + count_item_block,{
            width: '100%',
            resize_maxWidth: '100%',
            resize_minWidth: '100%',
            height:'300',
            filebrowserBrowseUrl: '/ckfinder/browser',
        });
        CKEDITOR.instances['description_block_' + count_item_block];
    })(jQuery);
}

function set_link_banner(elm){
    var fileCollection = new Array();
    var fn = $(elm).val();
    $(elm).parent().parent().find('.my_banner_block').val(fn);
    $(elm).parent().parent().find('.my_banner_block').attr("value",fn);
}

function loadFile(event){
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
}

function loadFileSlishow_pc(event){
    var output = document.getElementById('output_slishow_pc');
    output.src = URL.createObjectURL(event.target.files[0]);
}

function loadFileSlishow_mobile(event){
    var output = document.getElementById('output_slishow_mobile');
    output.src = URL.createObjectURL(event.target.files[0]);
}

function getMetaContentByName(name,content){
    var content = (content==null)?'content':content;
    return document.querySelector("meta[name='"+name+"']").getAttribute(content);
}

function number_format (number, decimals, dec_point, thousands_sep) {
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

function getFormattedDate(data) {
    var dt = new Date(data);
    return `${
        dt.getFullYear().toString().padStart(4, '0')}-${
        (dt.getMonth()+1).toString().padStart(2, '0')}-${
        dt.getDate().toString().padStart(2, '0')} ${
        dt.getHours().toString().padStart(2, '0')}:${
        dt.getMinutes().toString().padStart(2, '0')}:${
        dt.getSeconds().toString().padStart(2, '0')}`;
}

function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        ;
}
