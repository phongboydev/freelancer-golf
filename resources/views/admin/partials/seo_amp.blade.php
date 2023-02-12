<title>{{ $seo['title'] }}</title>
<meta name="keywords" content="{{ $seo['keywords'] }}" />
<meta name="description" content="{{ $seo['description'] }}" />
<!--Facebook Seo-->
<meta property="og:title" content="{{ $seo['og_title'] }}" />
<meta property="og:description" content="{{ $seo['og_description'] }}" />
<meta property="og:url" content="{{ $seo['og_url'] }}" />
<meta property="og:image" content="{{ $seo['og_img'] }}" />
<link rel="canonical" href="{{ $seo['current_url'] }}" />
<!--End-->