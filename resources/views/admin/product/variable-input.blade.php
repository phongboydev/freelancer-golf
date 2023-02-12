{{-- Variable --}}
<?php
use App\Models\Variable_Product;
use App\Constants\BaseConstants;
$list_parent_variable = Variable_Product::where('parent', 0)->get();
?>
@if($group_variable_product == [] || $group_variable_product == '')
    <div class="form-group">
        <label for="">Chọn biến thể sản phẩm</label>
        <select name="parent_variable[]" id="parent_variable" class="form-control" multiple="multiple" onchange="generateSelectVariableChild(this)">
            <option value="">Chọn biến thể cha</option>
            @foreach($list_parent_variable as $parent_variable)
                <option value="{{ $parent_variable->name }}">{{ $parent_variable->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <div id="loading-select-variable" class="text-center mt-2" style="display: none">
            <img src="{{ asset('img/loading.gif') }}" alt="">
        </div>
        <div id="generate_select_variable_child"></div>
    </div>
    <div class="form-group">
        <div id="loading-table-variable" class="text-center mt-2" style="display: none">
            <img src="{{ asset('img/loading.gif') }}" alt="">
        </div>
        <div id="table_variable_generate"></div>
    </div>
@else
    <?php
    $parent = isset($group_variable_product->parent) ? $group_variable_product->parent : [];
    $variable_generate_items = isset($group_variable_product->variable_option) ? $group_variable_product->variable_option : [];
    $count_variable_generate_items = count($variable_generate_items);
    $parent_name = [];
    $variable_parent_gallery = '';
    $count_variable_parent_gallery = 0;
    $count_variable_parent_group_related = 0;
    $variable_parent_icon = '';
    $count_variable_parent_icon = 0;
    $group_parent_variable_slug = '';
    foreach ($parent as $key_parent => $item_parent) {
        if ($group_parent_variable_slug == '') {
            $group_parent_variable_slug .= $key_parent;
        } else {
            $group_parent_variable_slug .= ',' . $key_parent;
        }

        foreach ($list_parent_variable as $item) {
            if ($item->slug == $key_parent) {
                array_push($parent_name, $item->name);
            }
        }

        //count variable parent gallery
        foreach ($item_parent as $item_parent_child) {
            if (isset($item_parent_child->gallery)) {
                if (count($item_parent_child->gallery) > 0) {
                    if ($variable_parent_gallery == '') {
                        $variable_parent_gallery .= $key_parent;
                    } else {
                        $variable_parent_gallery .= ',' . $key_parent;
                    }
                    $count_variable_parent_gallery++;
                    break;
                }
            }
        }

        $variable_parent_group_related = '';
        //count variable parent group related
        foreach ($item_parent as $item_parent_child) {
            if (isset($item_parent_child->group_related)) {
                if (count($item_parent_child->group_related) > 0) {
                    if ($variable_parent_group_related == '') {
                        $variable_parent_group_related .= $key_parent;
                    } else {
                        $variable_parent_group_related .= ',' . $key_parent;
                    }
                    $count_variable_parent_group_related++;
                    break;
                }
            }
        }

        //count variable parent icon
        foreach ($item_parent as $item_parent_child) {
            if (isset($item_parent_child->icon)) {
                if ($item_parent_child->icon != '') {
                    if ($variable_parent_icon == '') {
                        $variable_parent_icon .= $key_parent;
                    } else {
                        $variable_parent_icon .= ',' . $key_parent;
                    }
                    $count_variable_parent_icon++;
                    break;
                }
            }
        }
    }
    $count_variable_parent = count($parent_name);
    ?>
    <div class="form-group">
        <label for="">Chọn biến thể sản phẩm</label>
        <select name="parent_variable[]" id="parent_variable" class="form-control" multiple="multiple" onchange="generateSelectVariableChild(this)">
            <option value="">Chọn biến thể cha</option>
            @foreach($list_parent_variable as $parent_variable)
                <option value="{{ $parent_variable->name }}"
                        @if(in_array($parent_variable->name, $parent_name)) selected @endif
                >
                    {{ $parent_variable->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <div id="loading-select-variable" class="text-center mt-2" style="display: none">
            <img src="{{ asset('img/loading.gif') }}" alt="">
        </div>
        <div id="generate_select_variable_child">
            <input type="hidden" name="count_variable_parent" id="count_variable_parent" value="{{ $count_variable_parent }}">
            <div class="row">
                <?php $k = 0; ?>
                @for($i = 0; $i < $count_variable_parent; $i++)
                    <?php
                    $k++;
                    $parent_slug = \Illuminate\Support\Str::slug($parent_name[$i]);
                    $data_child = $parent->{$parent_slug};
                    $child_name_arr = [];
                    foreach ($data_child as $key_child => $data_item_child) {
                        array_push($child_name_arr, $key_child);
                    }
                    ?>
                    <div class="col-md-6 mt-2 select-variable">
                        <label for="variable_child_{{ $k }}">Chọn {{ $parent_name[$i] }}</label>
                        <select name="variable_child_{{ $k }}[]" id="variable_child_{{ $k }}" data-parent-slug="{!! $parent_slug !!}"
                                onchange="generateTableVariable()" multiple class="form-control {!! $parent_slug !!}">
                            <?php
                            $data_parent = Variable_Product::where('name', $parent_name[$i])->first();
                            if ($data_parent) {
                                $list_variable_child = Variable_Product::where('parent', $data_parent->id)
                                    ->orderBy('name', 'ASC')
                                    ->get();

                                foreach ($child_name_arr as $child) {
                                    foreach ($list_variable_child as $variable_child) {
                                        if ($variable_child->name == $child) {
                                            echo '<option value="' . $variable_child->name . '" selected>' . $variable_child->name . '</option>';
                                            break;
                                        }
                                    }
                                }

                                foreach ($list_variable_child as $variable_child) {
                                    if (!in_array($variable_child->name, $child_name_arr)) {
                                        echo '<option value="' . $variable_child->name . '">' . $variable_child->name . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                        <script>
                            jQuery(document).ready(function ($){
                                $('#variable_child_{{ $k }}').select2();
                            });
                        </script>
                    </div>
                @endfor
            </div>


            {{-- Create variable gallery --}}
            <input type="hidden" name="variable_parent_gallery" id="variable_parent_gallery" value="{{ $variable_parent_gallery }}">
            <div class="form-row align-items-center mb-4 mt-4">
                <div class="col">
                    <b>Chọn biến thể cần tạo Gallery (Cần chọn các biến thể con trước khi tạo Gallery)</b>
                </div>
                <div class="col">
                    <select name="slt_variable_album" id="slt_variable_album" class="form-control valid" aria-invalid="false">
                        <option value="">Chọn biến thể</option>
                        @foreach($list_parent_variable as $parent_variable)
                            @if(in_array($parent_variable->name, $parent_name))
                                <option value="{{ $parent_variable->slug }}">
                                    {{ $parent_variable->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" class="btn btn-success" onclick="generateVariableGallery()">Tạo</a>
                </div>
            </div>
            <div id="generate-gallery-result">
                <?php
                $explode_variable_parent_gallery = explode(',', $variable_parent_gallery);
                ?>
                @for($i = 1; $i <= $count_variable_parent_gallery; $i++)
                    <?php
                    $key_parent = $explode_variable_parent_gallery[$i -1];
                    $count_item_gallery = count((array)$parent->{$key_parent});
                    $data_gallery = (array)$parent->{$key_parent};
                    ?>
                    <div class="box-variable-gallery box-variable-gallery-{{ $i }} mt-2 box-variable-gallery-{{ $key_parent }}"
                         data-count-gallery-parent="{{ $i }}">
                        <input type="hidden" id="count_item_gallery_{{ $i }}" value="{{ $count_item_gallery }}" name="count_item_gallery_{{ $i }}">
                        <?php $check_gallery_process = []; ?>
                        @for($j = 1; $j <= $count_item_gallery; $j++)
                            <?php
                            $gallery_items = [];
                            foreach ($data_gallery as $data_gallery_key => $data_gallery_item) {
                                if (count($check_gallery_process) > 0) {
                                    if (!in_array($data_gallery_key, $check_gallery_process)) {
                                        $key_gallery_child = $data_gallery_key;
                                        $gallery_items = (isset($data_gallery_item->gallery)) ? $data_gallery_item->gallery : [];
                                        array_push($check_gallery_process, $data_gallery_key);
                                        break;
                                    }
                                } else {
                                    $key_gallery_child = $data_gallery_key;
                                    $gallery_items = (isset($data_gallery_item->gallery)) ? $data_gallery_item->gallery : [];
                                    array_push($check_gallery_process, $data_gallery_key);
                                    break;
                                }
                            }
                            $count_gallery_variable = count($gallery_items);
                            $key_gallery_child_slug = \Illuminate\Support\Str::slug($key_gallery_child);
                            ?>
                            <div class="mt-2 box-item-count box-item-{{ $i }}-{{ $j }} box-item-{{ $key_gallery_child_slug }}"
                                 data-box-item="{{ $key_gallery_child }}">
                                <div class="form-row align-items-center">
                                    <div class="col"><b>{{ $key_gallery_child }}</b></div>
                                    <div class="col">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#variableGalleryModal_{{ $i }}_{{ $j }}">
                                            Gallery
                                        </button>
                                    </div>
                                    <div class="col">
                                        <a href="javascript:void(0)" class="btn btn-danger"
                                           onclick="deleteVariableGallery({{ $i }}, {{ $j }})">
                                            Xóa
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade variable-gallery-modal" id="variableGalleryModal_{{ $i }}_{{ $j }}"
                                 tabindex="-1" role="dialog" aria-labelledby="variableGalleryLabel_{{ $i }}_{{ $j }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="variableGalleryLabel_{{ $i }}_{{ $j }}">
                                                Gallery biến thể
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input id="count_gallery_variable_{{ $i }}_{{ $j }}" type="hidden"
                                                   value="{{ $count_gallery_variable }}" name="count_gallery_variable_{{ $i }}_{{ $j }}">
                                            <input id="name_gallery_variable_{{ $i }}_{{ $j }}" type="hidden"
                                                   value="{{ $key_gallery_child }}" name="name_gallery_variable_{{ $i }}_{{ $j }}">
                                            <input id="slug_parent_variable_{{ $i }}_{{ $j }}" type="hidden"
                                                   value="{{ $key_parent }}" name="slug_parent_variable_{{ $i }}_{{ $j }}">
                                            <div id="add_item_images_{{ $i }}_{{ $j }}">
                                                @for($n = 1; $n <= $count_gallery_variable; $n++)
                                                    <div class="form-row image-gallery-group" id="image-gallery-group-{{ $i }}-{{ $j }}-{{ $n }}">
                                                        <div class="form-group col-md-3">
                                                            <div class="image-variable-demo">
                                                                <img src="{{ asset('images/product/'. $gallery_items[$n-1]) }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-7">
                                                            <input class="form-control image_file_name" type="text" name="variable_image_name_{{ $i }}_{{ $j }}_{{ $n }}"
                                                                   value="{{ $gallery_items[$n-1] }}">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <a href="javascript:void(0)" class="btn btn-danger action-img-variable"
                                                               onclick="deleteVariableImageItem({{ $i }}, {{ $j }}, {{ $n }})">
                                                                Xóa
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                            <script type="text/javascript">
                                                jQuery(document).ready(function($){
                                                    $("#add_item_images_{{ $i }}_{{ $j }}").sortable({
                                                        stop: function(event, ui){
                                                            var cnt = 1;
                                                            $(this).children('.image-gallery-group').each(function(){
                                                                $(this).find('input.image_file_name').attr('name','variable_image_name_{{ $i }}_{{ $j }}_' + cnt);
                                                                $(this).find('a.action-img-variable').attr('onclick','deleteVariableImageItem({{ $i }},{{ $j }},' + cnt + ')');
                                                                $(this).attr('id', 'image-gallery-group-{{ $i }}-{{ $j }}-' + cnt);
                                                                cnt++;
                                                            });
                                                        }
                                                    });
                                                });
                                            </script>
                                            <div class="form-group text-center mt-2">
                                                <label for="variable_image_upload_{{ $i }}_{{ $j }}" class="ml-1">
                                                    <input type="file" class="variable_upload_file" id="variable_image_upload_{{ $i }}_{{ $j }}"
                                                           name="variable_image_upload_{{ $i }}_{{ $j }}[]" multiple=""
                                                           onchange="generateGalleryVariable(this, {{ $i }}, {{ $j }}, {{ $n - 1 }})">
                                                    <span class="btn btn-success">Thêm ảnh (Giữ Ctrl để chọn nhiều ảnh)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @endfor
                <input type="hidden" name="count_gallery_parent" id="count_gallery_parent" value="{{ $count_variable_parent_gallery }}">
            </div>
            {{-- End create variable gallery --}}

            {{-- Create variable icon --}}
            <input type="hidden" name="variable_parent_icon" id="variable_parent_icon" value="{{ $variable_parent_icon }}">
            <div class="form-row align-items-center mb-4 mt-4">
                <div class="col">
                    <b>Chọn biến thể cần tạo Icon</b>
                </div>
                <div class="col">
                    <select name="slt_variable_icon" id="slt_variable_icon" class="form-control valid" aria-invalid="false">
                        <option value="">Chọn biến thể</option>
                        @foreach($list_parent_variable as $parent_variable)
                            @if(in_array($parent_variable->name, $parent_name))
                                <option value="{{ $parent_variable->slug }}">
                                    {{ $parent_variable->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" class="btn btn-success" onclick="generateVariableIcon()">Tạo</a>
                </div>
            </div>
            <div id="generate-icon-result">
                <?php
                $explode_variable_parent_icon = explode(',', $variable_parent_icon);
                ?>
                @for($i = 1; $i <= $count_variable_parent_icon; $i++)
                    <?php
                    $key_parent = $explode_variable_parent_icon[$i -1];
                    $count_item_icon = count((array)$parent->{$key_parent});
                    $data_icon = (array)$parent->{$key_parent};
                    ?>
                    <div class="box-variable-gallery box-variable-icon-{{ $i }} mt-2 box-variable-icon-{{ $key_parent }}"
                         data-count-icon-parent="{{ $i }}">
                        <input type="hidden" id="count_item_icon_{{ $i }}" value="{{ $count_item_icon }}" name="count_item_icon_{{ $i }}">
                        <?php $check_icon_process = []; ?>
                        @for($j = 1; $j <= $count_item_icon; $j++)
                            <?php
                            $icon_item = '';
                            foreach ($data_icon as $data_icon_key => $data_icon_item) {
                                if (isset($data_icon_item->icon)) {
                                    if (count($check_icon_process) > 0) {
                                        if (!in_array($data_icon_key, $check_icon_process)) {
                                            $key_icon_child = $data_icon_key;
                                            $icon_item = $data_icon_item->icon;
                                            array_push($check_icon_process, $key_icon_child);
                                            break;
                                        }
                                    } else {
                                        $key_icon_child = $data_icon_key;
                                        $icon_item = $data_icon_item->icon;
                                        array_push($check_icon_process, $key_icon_child);
                                        break;
                                    }
                                }
                            }
                            $key_icon_child_slug = \Illuminate\Support\Str::slug($key_icon_child);
                            ?>
                            <div class="mt-2 box-item-count box-item-{{ $i }}-{{ $j }} box-item-{{ $key_icon_child_slug }}"
                                 data-box-item="{{ $key_icon_child }}">
                                <input id="name_icon_variable_{{ $i }}_{{ $j }}" type="hidden"
                                       value="{{ $key_icon_child }}" name="name_icon_variable_{{ $i }}_{{ $j }}">
                                <input id="slug_icon_parent_variable_{{ $i }}_{{ $j }}" type="hidden"
                                       value="{{ $key_parent }}" name="slug_icon_parent_variable_{{ $i }}_{{ $j }}">
                                <div class="form-row align-items-center">
                                    <div class="col"><b>{{ $key_icon_child }}</b></div>
                                    <div class="col text-center">
                                        @if($icon_item != '')
                                            <img src="{{ asset('images/product/icon/' . $icon_item) }}" alt="" style="width: 80px">
                                        @else
                                            <img src="{{ asset('img/default-150x150.png') }}" alt="" style="width: 80px">
                                        @endif
                                    </div>
                                    <div class="col">
                                        <div class="form-inline">
                                            <input type="text" name="icon_variable_name_{{ $i }}_{{ $j }}"
                                                   class="form-control" style="width: 90%"
                                                   placeholder="Icon ảnh biến thể (60x60px)"
                                                   value="{{ $icon_item }}"
                                                   id="icon_variable_name_{{ $i }}_{{ $j }}">
                                            <label for="upload_icon_variable_{{ $i }}_{{ $j }}" class="pl-2">
                                                <input type="file" name="upload_icon_variable_{{ $i }}_{{ $j }}"
                                                       id="upload_icon_variable_{{ $i }}_{{ $j }}"
                                                       onchange="fakeValueIconVariable({{ $i }}, {{ $j }})"
                                                       style="display: none"
                                                >
                                                <span style="font-size: 20px;"><i class="fas fa-file-upload"></i></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @endfor
                <input type="hidden" name="count_icon_parent" id="count_icon_parent" value="{{ $count_variable_parent_icon }}">
            </div>
            {{-- End create variable gallery --}}
        </div>
    </div>
    <div class="form-group">
        <div id="loading-table-variable" class="text-center mt-2" style="display: none;">
            <img src="{{ asset('img/loading.gif') }}" alt="">
        </div>
        <div id="table_variable_generate">
            <div class="text-right mb-2">
                <a href="javascript:void(0)" class="btn btn-danger" onclick="autoAddPriceToVariable()">Đồng giá và tên toàn bộ</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <td scope="col"><b>Tên sản phẩm</b></td>
                        <td scope="col"><b>Mã sản phẩm (SKU)</b></td>
                        <td scope="col"><b>Số lượng</b></td>
                        @foreach($list_parent_variable as $parent_variable)
                            @if(in_array($parent_variable->name, $parent_name))
                                <td scope="col"><b>{{ $parent_variable->name }}</b></td>
                            @endif
                        @endforeach
                        <td scope="col"><b>Sắp xếp</b></td>
                        <td scope="col"><b>Giá gốc</b></td>
                        <td scope="col"><b>Giá khuyến mãi</b></td>
                        <td scope="col"><b>Ảnh đại diện</b></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count_item = 0;
                    ?>
                    @for($t = 0; $t < $count_variable_generate_items; $t++)
                        <?php
                        $count_item++;
                        ?>
                        @if (isset($variable_generate_items[$t]->{'variable_option' . $count_item}))
                            <?php
                            $data_variable = $variable_generate_items[$t]->{'variable_option' . $count_item};
                            $html_option_variable = '';
                            $data_variable_html = '';
                            foreach($list_parent_variable as $parent_variable) {
                                if(in_array($parent_variable->name, $parent_name)) {
                                    $html_option_variable .= '<td>
                                        <input type="hidden" name="variable_name_' . $parent_variable->slug . '_' . $count_item . '"
                                               value="' . $data_variable->{$parent_variable->slug} . '">
                                        ' . $data_variable->{$parent_variable->slug} . '
                                    </td>';
                                    if ($data_variable_html == '') {
                                        $data_variable_html .= $data_variable->{$parent_variable->slug};
                                    } else {
                                        $data_variable_html .= '/' . $data_variable->{$parent_variable->slug};
                                    }
                                }
                            }
                            ?>
                            <tr data-variable="{!! $data_variable_html !!}">
                                <td>
                                    <input type="text" class="form-control product_name_variable" onkeyup="generateSlug({{ $count_item }})"
                                           name="product_name_variable_{{ $count_item }}" placeholder="Tên sản phẩm"
                                           value="{{ $data_variable->product_name }}">
                                    <div style="font-size:14px; color:#060;">
                                        <span id="count_char_title_{{ $count_item }}" style="color:#F00; font-weight:bold;">0</span> ký tự, Max <b>70</b> ký tự
                                    </div>
                                    <?php
                                    $product_slug = (isset($data_variable->product_slug)) ? $data_variable->product_slug : \Illuminate\Support\Str::slug($data_variable->product_name);
                                    ?>
                                    <input type="text" class="form-control product_slug_variable mt-1"
                                           name="product_slug_variable_{{ $count_item }}" placeholder="Slug"
                                           value="{{ $product_slug }}">
                                    <p class="mt-1 font-weight-bold" style="color: #0a53be">
                                        @if ($id > 0 && $status == BaseConstants::ACTIVE)
                                            <a href="{{ route('single.detail', array($slug_cat, $product_slug)) }}" target="_blank">
                                                Xem thử
                                            </a>
                                        @else
                                            <a href="{{ route('admin.draftDetails', array($slug_cat, $product_slug)) }}" target="_blank">
                                                Xem thử
                                            </a>
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <?php
                                    $product_sku = (isset($data_variable->product_sku)) ? $data_variable->product_sku : '';
                                    ?>
                                    <input type="text" class="form-control"
                                           name="product_variable_sku_{{ $count_item }}" placeholder="Mã sản phẩm"
                                           value="{{ $product_sku }}">
                                </td>
                                <td>
                                    <?php
                                    $product_in_stock = (isset($data_variable->product_stock)) ? $data_variable->product_stock : '';
                                    ?>
                                    <input type="text" class="form-control"
                                           name="product_stock_variable_{{ $count_item }}" placeholder="Số lượng sản phẩm"
                                           value="{{ $product_in_stock }}">
                                </td>
                                {!! $html_option_variable !!}
                                <td>
                                    <?php
                                    $product_sort = (isset($data_variable->sort)) ? $data_variable->sort : '';
                                    ?>
                                    <input type="text" class="form-control sort_variable"
                                           name="sort_variable_{{ $count_item }}" placeholder="Sắp xếp"
                                           value="{{ $product_sort }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control price_origin_variable"
                                           name="price_origin_variable_{{ $count_item }}" placeholder="Giá gốc"
                                           value="{{ $data_variable->price_origin }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control price_promotion_variable"
                                           name="price_promotion_variable_{{ $count_item }}" placeholder="Giá khuyến mãi"
                                           value="{{ $data_variable->price_promotion }}">
                                </td>
                                <td>
                                    <?php
                                    $product_thumbnail = (isset($data_variable->thumbnail)) ? $data_variable->thumbnail : '';
                                    ?>
                                    <div class="form-inline">
                                        @if($product_thumbnail != '')
                                            <a href="{{ asset('images/product/' . $product_thumbnail) }}" target="_blank" style="width: 20%">
                                                <img src="{{ asset('images/product/' . $product_thumbnail) }}" id="thumbnail_variable_demo_{{ $count_item }}" alt="" class="pr-2">
                                            </a>
                                        @else
                                            <img src="{{ asset('img/default-150x150.png') }}" alt="" id="thumbnail_variable_demo_{{ $count_item }}" class="pr-2" style="width: 20%">
                                        @endif
                                        <input type="text" class="form-control" id="product_thumbnail_link_variable_{{ $count_item }}" style="width: 60%"
                                               name="product_thumbnail_link_variable_{{ $count_item }}"
                                               value="{{ $product_thumbnail }}">
                                        <label for="product_thumbnail_variable_{{ $count_item }}" class="pl-2">
                                            <input type="file" onchange="fakeValueThumbnailVariable({{ $count_item }})" style="display: none"
                                                   name="product_thumbnail_variable_{{ $count_item }}" id="product_thumbnail_variable_{{ $count_item }}">
                                            <span style="font-size: 20px;"><i class="fas fa-file-upload"></i></span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endfor
                    </tbody>
                </table>
            </div>
            <input type="hidden" name="group_parent_variable_slug" id="group_parent_variable_slug" value="{{ $group_parent_variable_slug }}">
            <input type="hidden" name="count_item_variable_generate" id="count_item_variable_generate" value="{{ $count_variable_generate_items }}">
        </div>
    </div>
@endif
{{-- End variable --}}
