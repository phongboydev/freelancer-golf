<?php
$s_gallery_array_filter = array_filter($store_gallery);
if (count($s_gallery_array_filter) > 0 && !empty($s_gallery_array_filter)):
    $background_img = "";
    $thumbnail_img = "";
    $url_img = 'images/product';
    ?>
    <!-- *******************************************Gallery*********************************************************-->
<div class="gallery_product">
    @for($i=0; $i<count($s_gallery_array_filter); $i++)
        <?php
        if (!empty($s_gallery_array_filter[$i]) && $s_gallery_array_filter[$i] != "") {
            $thumbnail_single = Helpers::getThumbnail($url_img,$s_gallery_array_filter[$i], 450, 450, "resize");
            if (strpos($thumbnail_single, 'placehold') !== false) {
                $thumbnail_single = $url_img.$thumbnail_single;
            }
        } else {
            $thumbnail_single = "https://dummyimage.com/450x450/FFF/000";
        }
        ?>
        <a href="{{ asset('images/product/' . $s_gallery_array_filter[$i]) }}" data-fancybox="images-preview"
           class="gallery_product_item">
            <img src="{{ $thumbnail_single }}" alt="">
        </a>
    @endfor
</div>
<div class="gallery_product_nav">
        <?php
    for($i=0;$i<count($s_gallery_array_filter);$i++):
        if(!empty($s_gallery_array_filter[$i]) && $s_gallery_array_filter[$i] !=""):
            $thumbnail_img=Helpers::getThumbnail($url_img,$s_gallery_array_filter[$i], 70, 70, "resize");
            if(strpos($thumbnail_img, 'placehold') !== false):
                $thumbnail_img=$url_img.$thumbnail_img;
            endif;
        else:
            $thumbnail_img="https://dummyimage.com/70x70/000/fff";
        endif;
        ?>
    <div class="gallery_product_nav_item">
        <img src="{{ $thumbnail_img }}" alt=""/>
    </div>
    <?php endfor; ?>
</div>
<!-- *******************************************Gallery*********************************************************-->
<?php endif; ?>
