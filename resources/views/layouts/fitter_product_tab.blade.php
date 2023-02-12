<div class="filter_product clear">
    <nav id="primary_nav_wrap" class="clear">
        <div class="ngrid-filter-category clear">
            <?php
            $list_current = "";
            if (isset($id_category) && isset($parent_category) && $id_category > 0) {
                if ($parent_category == 0) {
                    $category_parent = \App\Models\ProductCategory::where('id', $id_category)->first();
                    $category_child = \App\Models\ProductCategory::where('parent', $id_category)
                        ->where('status', \App\Constants\BaseConstants::ACTIVE)
                        ->get();
                    if ($category_parent) {
                        $slug_parent = $category_parent->slug;
                        $name_parent = $category_parent->name;
                        $list_current .= '<a class="active" href="' . route("category.list", $slug_parent) . '">All ' . $name_parent . '</a>';
                    }
                    if ($category_child) {
                        foreach ($category_child as $category_child_item) {
                            $name_child = $category_child_item->name;
                            $slug_child = $category_child_item->slug;
                            $list_current .= '<a href="' . route("category.list", $slug_child) . '">' . $name_child . '</a>';
                        }
                    }
                } else {
                    $category_parent = \App\Models\ProductCategory::where('id', $parent_category)->first();
                    $category_child = \App\Models\ProductCategory::where('parent', $parent_category)
                        ->where('status', \App\Constants\BaseConstants::ACTIVE)
                        ->get();
                    if ($category_parent) {
                        $slug_parent = $category_parent->slug;
                        $name_parent = $category_parent->name;
                        $list_current .= '<a href="' . route("category.list", $slug_parent) . '">All ' . $name_parent . '</a>';
                    }
                    if ($category_child) {
                        foreach ($category_child as $category_child_item) {
                            $slug_child = $category_child_item->slug;
                            $id_child = $category_child_item->id;

                            if ($id_child == $id_category) {
                                $active = "active";
                            } else {
                                $active = "";
                            }
                            $name_child = $category_child_item->name;
                            $list_current .= '<a class="' . $active . '" href="' . route("category.list", $slug_child) . '">' . $name_child . '</a>';
                        }
                    }
                }
            }
            ?>
            {!! $list_current !!}
        </div>

        @if(Request::get('size') || Request::get('color'))
            <div id="current_fitter_choise_sidebar" class="filter current_fitter_choise_sidebar clear">
                <div class="filter-current">
                    <strong class="block-subtitle filter-current-subtitle" role="heading" aria-level="2" data-count="1">Now
                        Shopping by</strong>
                    <ol class="items">
                        @if(Request::get('size'))
                            <li class="item">
                                <span class="filter-label">
                                    Size
                                </span>
                                <span class="filter-value">
                                    {!! WebService::variableGetNameByID(Request::get('size')) !!}
                                </span>
                                <a class="action remove"
                                   href="{!! Helpers::remove_query_params(['size',Request::get('size')]) !!}"
                                   title="Remove Size {!! WebService::variableGetNameByID(Request::get('size')) !!}">
                                    <span>Remove This Item</span>
                                </a>
                            </li>
                        @endif
                        @if(Request::get('color'))
                            <li class="item">
                                <span class="filter-label">
                                    Color
                                </span>
                                <span
                                    class="filter-value">{!! WebService::variableGetNameByID(Request::get('color')) !!}</span>
                                <a class="action remove"
                                   href="{!! Helpers::remove_query_params(['color', Request::get('color')]) !!}"
                                   title="Remove Colors {!! WebService::variableGetNameByID(Request::get('color')) !!}">
                                    <span>Remove This Item</span>
                                </a>
                            </li>
                        @endif
                    </ol>
                </div>
                <div class="block-actions filter-actions">
                    <a href="{!! Request::url() !!}" class="action clear filter-clear"><span>Clear All</span></a>
                </div>
            </div><!--#current_fitter_choise_sidebar-->
        @endif
        @if(!Request::get('size'))
            <div class="tab_sidebar_menu_product size_fitter_option clear">
                <div class="filter-options" id="narrow-by-list">
                    <div class="filter-options-item style_sizes" data-filter="style_sizes" data-title="Colors"
                         role="tablist">
                        <div class="wrapper_filters allow" data-role="collapsible" role="tab" data-collapsible="true"
                             aria-selected="true" aria-expanded="true">
                            <div class="filter-options-title">
                                <span class="title">Size</span>
                                <span class="count"></span>
                            </div>
                            <div class="filter-options-content clear">
                                {!! WebService::variableSlugBoxRender('size') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(!Request::get('color'))
            <div class="tab_sidebar_menu_product colors_fitter_option clear">
                <div class="filter-options" id="narrow-by-list">
                    <div class="filter-options-item style_colors" data-filter="style_colors" data-title="Colors"
                         role="tablist">
                        <div class="wrapper_filters allow" data-role="collapsible" role="tab" data-collapsible="true"
                             aria-selected="true" aria-expanded="true">
                            <div class="filter-options-title">
                                <span class="title">Color</span>
                                <span class="count"></span>
                            </div>
                            <div class="filter-options-content clear">
                                {!! WebService::variableSlugBoxRender('color') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </nav>
</div>
