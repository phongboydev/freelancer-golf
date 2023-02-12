<?php
ob_start();
$xml_file='<?xml version="1.0" encoding="utf-8"?>';
$xml_file.= '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd">
         <sitemap>
             <loc>'.$url_map.'static.xml</loc>
             <lastmod>'.$datetime_format_php.'</lastmod>
         </sitemap>
         <sitemap>
             <loc>'.$url_map.'page.xml</loc>
             <lastmod>'.$datetime_format_php.'</lastmod>
         </sitemap>
         <sitemap>
             <loc>'.$url_map.'theme.xml</loc>
             <lastmod>'.$datetime_format_php.'</lastmod>
         </sitemap>
         <sitemap>
             <loc>'.$url_map.'post.xml</loc>
             <lastmod>'.$datetime_format_php.'</lastmod>
         </sitemap>
         <sitemap>
             <loc>'.$url_map.'cat_post.xml</loc>
             <lastmod>'.$datetime_format_php.'</lastmod>
         </sitemap>
         <sitemap>
             <loc>'.$url_map.'cat_theme.xml</loc>
             <lastmod>'.$datetime_format_php.'</lastmod>
         </sitemap>'."\n".'</sitemapindex>';
header('Content-type: text/xml');
echo $xml_file;
ob_flush();
?>