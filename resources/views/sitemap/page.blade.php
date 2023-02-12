<?php ob_start();
$xml_file='';
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:xhtml="http://www.w3.org/1999/xhtml">';
$img_thumb="";
?>
@foreach ($data_customers as $post)
<?php
if(isset($post->thubnail) && $post->thubnail !=''):
    $img_thumb=$url_map."/img/uploads/".$post->thubnail;
else:
    $img_thumb=asset(Helpers::get_setting('seo_image'));
endif;

$xml_file .='
<url>
    <loc>'.route("category.list",array($post->slug)).'</loc>
    <image:image>
        <image:loc>'.$img_thumb.'</image:loc>
        <image:caption>'.$post->thubnail_alt.'</image:caption>
        <image:license>'.$url_map.'</image:license>
        <image:family_friendly>yes</image:family_friendly>
    </image:image>
        <lastmod>'.date("Y-m-d", strtotime($post->updated)).'</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
</url>';
?>
@endforeach
<?php
$xml_file .='</urlset>';
header('Content-type: text/xml');
echo $xml_file;
ob_flush();
?>
