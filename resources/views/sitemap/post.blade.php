<?php ob_start();
$xml_file='';
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:xhtml="http://www.w3.org/1999/xhtml">';
$img_thumb="";
$link_cat="";
$categories_parent="";
?>
@foreach ($datas as $data)
<?php
if(isset($data->thubnail) && $data->thubnail !=''):
    $img_thumb=$url_map."/images/article/".$data->thubnail;
else:
    $img_thumb=asset(Helpers::get_setting('seo_image'));
endif;
$link_cat = route("single.detail", [$data->categorySlug,$data->slug]);
$xml_file .='
<url>
    <loc>'.$link_cat.'</loc>
    <image:image>
        <image:loc>'.$img_thumb.'</image:loc>
        <image:caption>'.$data->thubnail_alt.'</image:caption>
        <image:license>'.$url_map.'</image:license>
        <image:family_friendly>yes</image:family_friendly>
    </image:image>
        <lastmod>'.date("Y-m-d", strtotime($data->updated)).'</lastmod>
        <changefreq>daily</changefreq>
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
