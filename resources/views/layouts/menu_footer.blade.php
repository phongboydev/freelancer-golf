<?php $footer_menus = Menu::getByName('footer_support'); ?>
@if($footer_menus)
    <ul id="footer_menu" class="mn-foot " itemscope itemtype="http://schema.org/SiteNavigationElement">
        @foreach($footer_menus as $footer_menu)
        <li class="menu-item {{ $footer_menu['class'] }}">
            @if($footer_menu['link'] !='')
                <a href="{{ $footer_menu['link'] }}" target="{{ $footer_menu['target'] }}" rel="{{ $footer_menu['rel'] }}">{!! $footer_menu['label'] !!}</a>
            @else
                <h4>{!! $footer_menu['label'] !!}</h4>
            @endif
            @if( $footer_menu['child'] )
                <ul class="menu-item has-sub">
                    @foreach( $footer_menu['child'] as $footer_menu_child )
                        <li class="sub-menu-item level5 {{ $footer_menu_child['class'] }}">
                            <a target="{{ $footer_menu_child['target'] }}" rel="{{ $footer_menu_child['rel'] }}" href="{{ $footer_menu_child['link'] }}">
                                {!! $footer_menu_child['label'] !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
        @endforeach
    </ul><!-- /.menu -->
@endif
