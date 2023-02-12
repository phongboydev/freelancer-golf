@include('customer.layouts.header')
<body>
@include('customer.modules.top-nav')
@include('customer.modules.left-nav')
	@yield('content')
@include('customer.layouts.footer')