<?php ob_start();
$xml_file = '';
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:xhtml="http://www.w3.org/1999/xhtml">';
$img_thumb = "";
$link_cat = "";
$categories_parent = "";
?>
@foreach ($categories as $category)
    <?php
    if (isset($category->thumbnail) && $category->thumbnail != ''):
        $img_thumb = $url_map . "/images/category/" . $category->thumbnail;
    else:
        $img_thumb = asset(Helpers::get_setting('seo_image'));
    endif;
    if (isset($category->categoryParent) && $category->categoryParent > 0):
        $id_parent = (int)$category->categoryParent;
        $categories_parent = DB::table('categories')
            ->where('status', 0)
            ->where('categoryID', $id_parent)
            ->select('categorySlug')
            ->first();
        if ($categories_parent):
            $link_cat = route("single.detail", array($categories_parent->categorySlug, $category->categorySlug));
        endif;
    else:
        $link_cat = route("category.list", $category->categorySlug);
    endif;
    $xml_file .= '
<url>
    <loc>' . $link_cat . '</loc>
    <image:image>
        <image:loc>' . $img_thumb . '</image:loc>
        <image:caption>' . $category->thumbnail_alt . '</image:caption>
        <image:license>' . $url_map . '</image:license>
        <image:family_friendly>yes</image:family_friendly>
    </image:image>
        <lastmod>' . date("Y-m-d", strtotime($category->updated)) . '</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
</url>';
    ?>
@endforeach
<?php
$xml_file .= '</urlset>';
header('Content-type: text/xml');
echo $xml_file;
ob_flush();
?>
