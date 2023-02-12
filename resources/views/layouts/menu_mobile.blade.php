<?php
$public_Mobilemenu = Menu::getByName('Mobile');
?>
@if($public_Mobilemenu)
    <div class="logo_menu_mobile">
        <a href="{{route('index')}}">
            <img src="{{asset('img/logo_new.png')}}" alt="{!! Helpers::get_setting('site_name') !!}"/>
        </a>
    </div>
    {!! WebService::ListMenuCateMobileRender() !!}
    <ul id="primary_menu-mobile-id" class="menu-item-view clear nav flexnav" itemscope
        itemtype="http://schema.org/SiteNavigationElement">
        @foreach($public_Mobilemenu as $menu_mobile)
            <li class="item sub_menu">
                <a itemprop="url" class="item_link with_icon"
                   href="{{ $menu_mobile['link'] }}">{{ $menu_mobile['label'] }}</a>
                @if( $menu_mobile['child'] )
                    <ul class="sub-menu">
                        @foreach( $menu_mobile['child'] as $child_mobile )
                            <li class="">
                                <a href="{{ $child_mobile['link'] }}">{{ $child_mobile['label'] }}</a>
                                @if( $child_mobile['child'] )
                                    <ul class="menu-item has-sub">
                                        @foreach( $child_mobile['child'] as $child_mobile_lv3 )
                                            <li class="sub-menu-item level3 {{ $child_mobile_lv3['class'] }}">
                                                <a target="{{ $child_mobile_lv3['target'] }}"
                                                   rel="{{ $child_mobile_lv3['rel'] }}"
                                                   href="{{ $child_mobile_lv3['link'] }}">{{ $child_mobile_lv3['label'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul><!-- /.sub-menu -->
                @endif
            </li>
        @endforeach
        @if(Auth::check())
            <li class="item sub_menu">
                <a href="{{ route('user.dashboard') }}"><i class="fa fa-user" aria-hidden="true"></i>
                    Hi! {{Auth::user()->first_name}} {{Auth::user()->last_name}}
                </a>
                <ul class="menu-item has-sub">
                    <li><a href="{{route('user.profile')}}">Account information</a></li>
                    <li><a href="{{route('user.orders')}}">Order & Return</a></li>
                    <li><a href="{{route('user.wishList')}}">Favorites list</a></li>
                    <li><a href="{{route('user.reviews')}}">List of reviews</a></li>
                    <li><a href="{{route('user.changePasswordForm')}}">Change Password</a></li>
                    <li><a href="{{route('user.logout')}}">Log out</a></li>
                </ul>
            </li>
        @endif
        @if(!Auth::check())
            <li class="menu-item">
                <a title="Sign In" href="{{ route('user.loginForm') }}" class="bg-title-users">
                    <i class="fa fa-user" aria-hidden="true"></i> Login
                </a>
            </li>
            <li class="menu-item bg-title-users">
                <a title="Register" href="{{ route('user.registerForm') }}" class="bg-title-users">Register</a>
            </li>
        @endif
        <li class="menu-item">
            <a title="cart" href="{{ route('viewOrder') }}" class="view_order_mobile_mb">View Order</a>
        </li>
    </ul><!-- /.menu -->
@endif
