<?php
$url_language_current_vi = "";
$url_current_check_vi = Request::url();
if (App::currentLocale() == 'vi'):
    $url_language_current_vi = str_replace("olaben.com/vi", "olaben.com/en", $url_current_check_vi);
    header("HTTP/1.1 301 Moved Permanently");
    header('Location:' . $url_language_current_vi);
endif;
/*
	ob_start();
	$url_current = Request::url();
	$url_www="";

	if (strpos($url_current, 'www') !== false):
		if(Request::server('HTTP_X_FORWARDED_PROTO')=='https'):
			//not working
			//echo $url_current."1";
		else:
			//echo $url_current."2";
			$url_www = str_replace('http://', 'https://', $url_current);
			header("HTTP/1.1 301 Moved Permanently");
			header('Location:'.$url_www);
		endif;
	else :
		if(Request::server('HTTP_X_FORWARDED_PROTO')=='https'):
			//echo $url_current."3";
			$url_www = str_replace("olaben.com", "www.olaben.com", $url_current);
			header("HTTP/1.1 301 Moved Permanently");
			header('Location:'.$url_www);
		else:
			//echo $url_current."4";
			$url_www = str_replace("olaben.com", "www.olaben.com", $url_current);
			$url_www = str_replace('http://', 'https://', $url_www);
			echo $url_www;
			header("HTTP/1.1 301 Moved Permanently");
			//Redirect::to($url_www);
			header('Location:'.$url_www, true, 301);
		endif;
	endif;
*/
?><?php
//$url_current = Request::url();
//$url_www = "";
//if (strpos($url_current, 'www') !== false) {
//    if (Request::secure()) {
//        //not working
//    } else {
//        $url_www = str_replace('http://', 'https://', $url_current);
//        header("HTTP/1.1 301 Moved Permanently");
//        header('Location:' . $url_www);
//    }
//} else {
//    if (Request::secure()) {
//        $url_www = str_replace("olaben.com", "www.olaben.com", $url_current);
//        header("HTTP/1.1 301 Moved Permanently");
//        header('Location:' . $url_www);
//    } else {
//        $url_www = str_replace("olaben.com", "www.olaben.com", $url_current);
//        $url_www = str_replace('http://', 'https://', $url_www);
//        header("HTTP/1.1 301 Moved Permanently");
//        header('Location:' . $url_www);
//    }
//}
?>
<title>{{ $seo['title'] }}</title>
<meta name="keywords" content="{{ $seo['keywords'] }}"/>
<meta name="description" content="{{ $seo['description'] }}"/>
<!--Facebook Seo-->
<meta property="og:title" content="{{ $seo['og_title'] }}"/>
<meta property="og:description" content="{{ $seo['og_description'] }}"/>
<meta property="og:url" content="{{ $seo['og_url'] }}"/>
<meta property="og:type" content="article"/>
<meta property="og:image" content="{{ $seo['og_img'] }}"/>
<link rel="canonical" href="{{ $seo['current_url'] }}"/>
@if($seo['current_url_amp'] !='')
    <?php /*<link rel="alternate" media="handheld" href="{{ $seo['current_url'] }}" />
<link rel="alternate" media="only screen and (max-width: 761px)" href="{{ $seo['current_url'] }}" />
<link rel="amphtml"  href="{{ $seo['current_url_amp'] }}" />
*/ ?>
@endif
<?php /*
<!--<link rel="alternate" media="handheld" href="{{ $seo['current_url'] }}" />
<link rel="alternate" media="only screen and (max-width: 761px)" href="{{ $seo['current_url'] }}" />-->
@if($seo['current_url_amp'] !='')
<!--<link rel="amphtml"  href="{{ $seo['current_url_amp'] }}" />-->
@endif
<!--End-->
*/ ?>
<link href='//fonts.googleapis.com' rel='dns-prefetch'/>
<link href='//ajax.googleapis.com' rel='dns-prefetch'/>
<link href='//apis.google.com' rel='dns-prefetch'/>
<link href='//connect.facebook.net' rel='dns-prefetch'/>
<link href='//www.facebook.com' rel='dns-prefetch'/>
<link href='//twitter.com' rel='dns-prefetch'/>
<link href='//www.google-analytics.com' rel='dns-prefetch'/>
<link href='//www.googletagservices.com' rel='dns-prefetch'/>
<link href='//pagead2.googlesyndication.com' rel='dns-prefetch'/>
<link href='//googleads.g.doubleclick.net' rel='dns-prefetch'/>
<link href='//static.xx.fbcdn.net' rel='dns-prefetch'/>
<link href='//platform.twitter.com' rel='dns-prefetch'/>
<link href='//syndication.twitter.com' rel='dns-prefetch'/>
<base href="{{ route('index') }}"/>
<meta name="robots" content="index,follow,noodp"/>
<meta name="author" content="{!! Helpers::get_setting('site_name') !!}"/>
<meta name="copyright"
      content="Copyright&copy;2020 {!! Helpers::get_setting('site_name') !!}.　All Right Reserved. Thiết kế web bởi thietkewebnhanh247.com"/>
<meta http-equiv="content-script-type" content="text/javascript"/>
<meta http-equiv="content-style-type" content="text/css"/>
<meta http-equiv="content-language" content="en"/>
<meta name="robots" content="notranslate"/>
<link rev="made" href="mailto:{!! Helpers::get_setting('email') !!}"/>
<meta name="distribution" content="global"/>
<meta name="rating" content="general"/>
<meta property="og:site_name" content="{!! Helpers::get_setting('site_name') !!}"/>
<link rel="index" href="{{ asset('/') }}"/>
<script type='application/ld+json'>
{
	"@context":"http:\/\/schema.org",
	"@type":"WebSite",
	"@id":"#website",
	"url":"{{ route('index') }}",
	"name":"{!! Helpers::get_setting('site_name') !!}",
	"alternateName":"{{Helpers::get_setting('seo_title')}}",
	"potentialAction":{"@type":"SearchAction","target":"{!! route('index') !!}/?s={search_term_string}","query-input":"required name=search_term_string"
	}
	}

</script>
<script type='application/ld+json'>
{
	"@context":"http:\/\/schema.org",
	"@type":"Organization",
	"url":"{{ route('index') }}",
	"foundingDate": "2019",
	"founders": [
	 {
	 "@type": "Person",
	 "name": "{!! Helpers::get_setting('site_name') !!}"
	 }],
	 "address": {
	 "@type": "PostalAddress",
	 "streetAddress": "Level 12, Miss Ao Dai Building, 21 Nguyen Trung Ngan Street",
	 "addressLocality": "Ben Nghe Ward",
	 "addressRegion": "District 1",
	 "postalCode": "700000",
	 "addressCountry": "Việt Nam"
	 },
	 "contactPoint": {
	 "@type": "ContactPoint",
	 "contactType": "Customer Support",
	 "telephone": "[+84899992088]",
	 "email": "{!! Helpers::get_setting('email') !!}"
	 },
	"sameAs":["https:\/\/www.facebook.com\/Olabenswear\/"],
	"@id":"#organization",
	"name":"{!! Helpers::get_setting('site_name') !!}",
	"logo":"{!! Helpers::get_setting('logo') !!}"
}

</script>
