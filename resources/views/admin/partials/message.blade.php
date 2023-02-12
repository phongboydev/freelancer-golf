@if(Session::has('success_msg'))
<div class="col-xs-12">
	<div class="alert alert-success alert-dismissible fade in" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		{{ Session::get('success_msg') }}
	</div>
</div>

@elseif(Session::has('fail_msg'))
<div class="col-xs-12">
	<div class="alert alert-danger alert-dismissible fade in" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		{{ Session::get('fail_msg') }}
	</div>
</div>
@endif