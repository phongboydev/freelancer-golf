@include('layouts.header')
    <?php $segment_check=""; ?>
    <main id="customer-dashboard" class="custom_details_view_container clear">
    	<div class="container">
    		<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-12 siderbar_user_right">
    				<div class="clear customer-dashboard-content">
			            @yield('content')
			        </div>
    			</div>
				<div class="col-lg-3 col-md-3 col-sm-12 siderbar_user_left">
					<div class="customer-menu clear">
						@include('user.layouts.menu_customer')
					</div>
				</div>
    		</div>
    	</div>
    </main>
@include('layouts.footer')
