<?php
use App\Models\Variable_Product;

$group_variable_product = $product->group_variable_product;
$group_variable_product = json_decode($group_variable_product, true);
?>
@if (count($group_variable_product))
    <?php
        $variable_options = $group_variable_product['variable_option'];
        $variable_parents = $group_variable_product['parent'];
        $count_parent = 1;
        $key_parent_slug = '';
        $variable_data = json_decode($product->variable_data, true);
    ?>
    <div class="group-product-variable" @if(isset($variable_option_child)) data-variable-option="{{ json_encode($variable_options) }}" @endif>
        @if(!isset($variable_option_child))
            <script type="application/javascript">
                var variable_options = {!! json_encode($variable_options) !!};
                var variable_gallery = {!! json_encode($variable_parents) !!};
            </script>
        @endif
        @foreach($variable_parents as $key_parent => $variable_parent)
                <?php
                $data_parent = Variable_Product::where('slug', $key_parent)
                    ->first();
                ?>
            @if($data_parent)
                    <?php
                    if ($key_parent_slug == '') {
                        $key_parent_slug .= $key_parent;
                    } else {
                        $key_parent_slug .= ',' . $key_parent;
                    }
                    $get_list_variable = Variable_Product::where('parent', $data_parent->id)
                        ->get();
                    ?>
                <div class="item-variable parent-{{ $key_parent }}">
                    <div class="variable-title">
                        <b>{{ $data_parent->name }}:</b> <span id="variable_name_active_{{ $key_parent }}">{{ $variable_data[$key_parent] }}</span>
                    </div>
                    <input type="hidden" name="parent_key_{{ $count_parent }}"
                           value="{{ $key_parent }}" id="parent_key_{{ $count_parent }}">
                    <ul class="select-variable select-variable-{{ $count_parent }}" id="select-variable-{{ $key_parent }}" data-attribute="{{ $key_parent }}">
                        @foreach($variable_parent as $key_child => $data_variable)
                            @if($get_list_variable != '')
                                @foreach($get_list_variable as $variable_item)
                                    @if($variable_item->name == $key_child && isset($data_variable->icon) && $data_variable->icon != '')
                                        <li class="type-icon-img">
                                        <span class="st-custom-attribute
                                              @if(isset($variable_data[$key_parent]) && $variable_data[$key_parent] == $key_child) active firstLoadClick @endif"
                                              data-value="{{ $key_child }}"
                                              @once id="first_variable_{{ $key_parent }}" @endonce
                                              data-parent="{{ $key_parent }}">
                                            <img src="{{ asset('images/product/icon/thumbs/150x225/' . $data_variable->icon) }}" alt="">
                                        </span>
                                        </li>
                                    @elseif($variable_item->name == $key_child)
                                        <li class="type-label">
                                        <span class="st-custom-attribute
                                              @if(isset($variable_data[$key_parent]) && $variable_data[$key_parent] == $key_child) active firstLoadClick @endif"
                                              data-value="{{ $key_child }}"
                                              @once id="first_variable_{{ $key_parent }}" @endonce
                                              data-parent="{{ $key_parent }}">
                                            {{ $key_child }}
                                        </span>
                                        </li>
                                    @endif
                                @endforeach
                            @else
                                <li class="type-label">
                                <span class="st-custom-attribute
                                      @if(isset($variable_data[$key_parent]) && $variable_data[$key_parent] == $key_child) active firstLoadClick @endif"
                                      data-value="{{ $key_child }}"
                                      @once id="first_variable_{{ $key_parent }}" @endonce
                                      data-parent="{{ $key_parent }}">
                                    {{ $key_child }}
                                </span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <input name="attribute_{{ $key_parent }}" id="{{ $key_parent }}"
                           value="@if(isset($variable_data[$key_parent]) && $variable_data[$key_parent] != ''){!! $variable_data[$key_parent] !!}@endif"
                           type="hidden">
                </div>
                    <?php $count_parent++; ?>
            @endif
        @endforeach
        <input type="hidden" id="key_parent_slug" value="{{ $key_parent_slug }}" name="key_parent_slug">
    </div>
    <div class="reset-choose-variable mt-2 mb-2">
        <a href="javascript:void(0)" class="btn btn-reset-option" onclick="resetVariableOption()">
            Chọn lại
        </a>
    </div>
@endif
