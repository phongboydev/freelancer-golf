<?php
$primary_menus = Menu::getByName('primary_menu');
?>
@if($primary_menus)
    <ul id="primary_menu_top" class="navbar-nav" itemscope itemtype="http://schema.org/SiteNavigationElement">
        @foreach($primary_menus as $menu)
            <li class="nav-item {{ $menu['class'] }}">
                <a class="nav-link" target="{{ $menu['target'] }}" href="{{ $menu['link'] }}" rel="{{ $menu['rel'] }}">
                    {{ $menu['label'] }}
                </a>
            </li>
        @endforeach
    </ul><!-- /.menu -->
@endif
