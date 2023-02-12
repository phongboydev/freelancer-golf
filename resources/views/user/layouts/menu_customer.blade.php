<div class="tit-menu-cus">MY ACCOUNT</div>
<ul class="menu-cus menu_siderbar_custom_view">
	@if(Auth::check())
	<li><a href="{{ route('user.dashboard') }}">Account Details</a></li>

    <?php
    $referrals = \App\Models\Referral::where('user_id', Auth::user()->id)
        ->where('status', \App\Constants\BaseConstants::ACTIVE)
        ->first();
    ?>
	@if($referrals)
		<li class="muti"><a class="@if(Route::currentRouteName() =='user.affiliate') active @endif" href="{{ route('user.affiliate') }}">Affiliate marketing</a>
			<ul>
				<li><a class="@if(Route::currentRouteName() == 'user.affiliate') active @endif" href="{{ route('user.affiliate') }}">Affiliate Details</a></li>
				<li><a class="@if(Route::currentRouteName() == 'user.affiliateProducts') active @endif" href="{{ route('user.affiliateProducts') }}">Marketing products</a></li>
				<li><a class="@if(Route::currentRouteName() == 'user.affiliateHistories') active @endif" href="{{ route('user.affiliateHistories') }}">History Payment</a></li>
			</ul>
		</li>
	@endif

	<li><a class="@if(Route::currentRouteName() == 'user.orders') active @endif" href="{{ route('user.orders') }}">Order & Return</a></li>
	<li><a class="@if(Route::currentRouteName() == 'user.wishList') active @endif" href="{{ route('user.wishList') }}">Favorites list</a></li>
	<li><a class="@if(Route::currentRouteName() == 'user.reviews') active @endif" href="{{ route('user.reviews') }}">List of reviews</a></li>
	<li><a href="#">Gift Cards</a></li>
	<li><a class="@if(Route::currentRouteName() == 'user.changePasswordForm') active @endif" href="{{ route('user.changePasswordForm') }}">Change Password</a></li>
	<li><a href="{{route('user.logout')}}">Log out</a></li>
	@else
		<li><a href="{{ route('user.loginForm') }}">Log in</a></li>
	@endif
</ul>
