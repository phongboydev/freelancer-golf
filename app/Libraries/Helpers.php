<?php

namespace App\Libraries;

use App\Constants\BaseConstants;
use App\Models\BusinessSetting;
use App\Models\Currency;
use App\Models\Discount_code;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use App\Models\Setting;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Variable_Product;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use GeoIP;
use Illuminate\Support\Str;

class Helpers
{
    public static function flagCurrency($price, $currency)
    {
        if (isset($_COOKIE['currency_cookie']) && $_COOKIE['currency_cookie'] != '') {
            $currency_cookie = json_decode($_COOKIE['currency_cookie']);
            if (isset($currency_cookie[2]) && isset($currency_cookie[3]) && $currency_cookie[2] != '' && $currency_cookie[3] != '') {
                $rate = floatval($currency_cookie[2]);
                $price = $price * $rate;
                $symbol = $currency_cookie[3];
                return $symbol . number_format($price);
            }
        } else {
            return $currency . number_format($price);
        }
        return null;
    }

    public static function format_timer($time_in_seconds)
    {
        $time_in_seconds = ceil($time_in_seconds);
        // Check for 0
        if ($time_in_seconds == 0) {
            return 'Less than a second';
        }
        // Days
        $days = floor($time_in_seconds / (60 * 60 * 24));
        $time_in_seconds -= $days * (60 * 60 * 24);
        // Hours
        $hours = floor($time_in_seconds / (60 * 60));
        $time_in_seconds -= $hours * (60 * 60);
        // Minutes
        $minutes = floor($time_in_seconds / 60);
        $time_in_seconds -= $minutes * 60;
        // Seconds
        $seconds = floor($time_in_seconds);
        // Format for return
        $return = '';
        if ($days > 0) {
            $return .= $days . ' ngày ';
        }
        if ($hours > 0) {
            $return .= $hours . ' giờ ';
        }
        if ($minutes > 0) {
            $return .= $minutes . ' phút ';
        }
        if ($seconds > 0) {
            $return .= $seconds . ' giây ';
        }
        $return = trim($return);
        return $return;
    }

    public static function parse_youtubeID($url)
    {
        $pattern = '#^(?:https?://)?';    # Optional URL scheme. Either http or https.
        $pattern .= '(?:www\.)?';         #  Optional www subdomain.
        $pattern .= '(?:';                #  Group host alternatives:
        $pattern .= 'youtu\.be/';       #    Either youtu.be,
        $pattern .= '|youtube\.com';    #    or youtube.com
        $pattern .= '(?:';              #    Group path alternatives:
        $pattern .= '/embed/';        #      Either /embed/,
        $pattern .= '|/v/';           #      or /v/,
        $pattern .= '|/watch\?v=';    #      or /watch?v=,
        $pattern .= '|/watch\?.+&v='; #      or /watch?other_param&v=
        $pattern .= ')';                #    End path alternatives.
        $pattern .= ')';                  #  End host alternatives.
        $pattern .= '([\w-]{11})';        # 11 characters (Length of Youtube video ids).
        $pattern .= '(?:.+)?$#x';         # Optional other ending URL parameters.
        preg_match($pattern, $url, $matches);
        return (isset($matches[1])) ? $matches[1] : false;
    }

    public static function get_setting($key, $default = null, $lang = false)
    {
        $settings = Cache::remember(
            'business_settings',
            86400,
            function () {
                return BusinessSetting::all();
            }
        );

        if ($lang == false) {
            $setting = $settings->where('type', $key)->first();
        } else {
            $setting = $settings->where('type', $key)->where('lang', $lang)->first();
            $setting = !$setting ? $settings->where('type', $key)->first() : $setting;
        }
        return $setting == null ? $default : $setting->value;
    }

    public static function get_permalink_category_by_id($id)
    {
        $category = ProductCategory::where('id', $id)
            ->first();
        $link = "";
        if ($category) {
            $link = route('category.list', $category->slug);
        }
        return $link;
    }

    public static function getPriceProductStock($product_id)
    {
        $product = ProductStock::with(
            [
                'product' => function ($query) {
                    $query->select(
                        'id',
                        'start_event',
                        'end_event',
                        'store_status'
                    );
                }
            ]
        )
            ->where('id', $product_id)
            ->first();
        if ($product) {
            if (($product->price_promotion < $product->price_origin) && $product->price_promotion > 0) {
                $product->final_price = $product->price_promotion;
            } else {
                $product->final_price = $product->price_origin;
            }
            return $product;
        } else {
            return null;
        }
    }

    public static function getCartTotal($cart)
    {
        $cart_total = 0;
        foreach ($cart as $cart_item) {
            $product = Helpers::getPriceProductStock($cart_item['product_id']);
            if ($product) {
                $price = $product->final_price;
                $quantity = $cart_item['quantity'];
                $cart_total += $quantity * $price;
            }
        }
        return $cart_total;
    }

    public static function getFinalPriceWithDiscountCode($discountCodeString, $cartTotal) {
        $finalPrice = $cartTotal;
        $today = date('Y-m-d H:i:s');
        $discountCode = Discount_code::where('code', $discountCodeString)
            ->where('expired', '>=', $today)
            ->where('start_date', '=<', $today)
            ->where('status', BaseConstants::ACTIVE)
            ->first();
        if ($discountCode) {
            if ($discountCode->percent > 0) {
                $finalPrice = $cartTotal - ($cartTotal * $discountCode->percent / 100);
            } else {
                $finalPrice = $cartTotal - $discountCode->fixed_price;
            }

            return [
                'discountCode' => $discountCode,
                'finalPrice' => $finalPrice
            ];
        }
        return [
            'discountCode' => '',
            'finalPrice' => $finalPrice
        ];
    }

    public static function remove_query_params(array $params = [])
    {
        $url = url()->current();
        $query = request()->query();
        foreach($params as $param) {
            unset($query[$param]);
        }
        return $query ? $url . '?' . http_build_query($query) : $url;
    }

    public static function add_query_params(array $params = [])
    {
        $query = array_merge(request()->query(), $params);
        return url()->current() . '?' . http_build_query($query);
    }

    public static function arrayPaginator($array, $request)
    {
        $page = $request->get('page', 1);
        $perPage = self::get_option_minhnn('total-item-in-category');
        $offset = ($page * $perPage) - $perPage;
        return new LengthAwarePaginator(
            array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    public static function getProductStockById($id)
    {
        return ProductStock::with(
            [
                'product' => function ($query) {
                    $query->select('*');
                }
            ]
        )
            ->where('id', $id)
            ->first();
    }

    public static function get_product_by_id($id)
    {
        return Product::where('id', $id)->first();
    }

    public static function get_title_variable_theme_by_id($id)
    {
        $variables = Variable_Product::where('id', $id)->first();
        if ($variables) {
            return $variables->name;
        } else {
            return null;
        }
    }

    public static function get_permalink_by_id($id)
    {
        $product = ProductCategory::join(
            'join_category_product',
            'product_categories.id',
            'join_category_product.category_id'
        )
            ->join('product_stocks', 'product_stocks.product_id', 'join_category_product.product_id')
            ->where('product_stocks.id', $id)
            ->first(['product_stocks.slug', 'product_categories.slug as categorySlug']);
        if ($product) {
            return route('single.detail', [$product->categorySlug, $product->slug]);
        }
        return "javascript:void(0)";
    }

    public static function getCategoryProductSlug($id)
    {
        $product = ProductCategory::join(
            'join_category_product',
            'product_categories.id',
            'join_category_product.category_id'
        )
            ->join('products', 'join_category_product.product_id', 'products.id')
            ->where('products.id', $id)
            ->first(['product_categories.slug']);
        if ($product) {
            return $product->slug;
        }
        return "";
    }

    public static function auto_code_discount()
    {
        $rand1 = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
        $rand2 = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
        $rand3 = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
        return strtoupper($rand1 . '-' . $rand2 . '-' . $rand3);
    }

    public static function generateCodeOrder()
    {
        $strtime_conver = strtotime(date('d-m-Y H:i:s'));
        $strtime = substr($strtime_conver, -4);
        $rand = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
        $string_rand = "ORDER-" . $strtime . $rand;
        return $string_rand;
    }

    public static function msg_move_page($msg, $url = "back", $isExit = 1)
    {
        if ($msg) {
            echo "<script language='javascript'>alert('" . $msg . "');</script>";
        }
        if ($url) {
            switch ($url) {
                case "home" :
                    echo "<script>location.href='/'</script>";
                    break;
                case "back" :
                    echo "<script language='javascript'>history.go(-1);</script>";
                    break;
                case "close" :
                    echo "<script language='javascript'>self.close();</script>";
                    break;
                case "reload" :
                    echo "<script language='javascript'>document.location.reload();</script>";
                    break;
                case "top_opener_reload" :
                    echo "<script language='javascript'>top.opener.document.location.reload();</script>";
                    break;
                case "top_url" :
                    echo "<Script language='javascript'>top.document.location.href = '" . $url . "'</script>";
                    break;
                case "parent_reload" :
                    echo "<script language='javascript'>parent.document.location.reload();</Script>";
                    break;
                case "not":
                    echo "<script language='javascript'>alert('" . $msg . "');</script>";
                    break;
                default :
                    echo "<script language='javascript'>document.location.replace('" . $url . "');</script>";
                    break;
            }
        }
        if ($isExit) {
            exit();
        }
    }

    //Refresh url
    function move_page($url)
    {
        echo "<meta http-equiv=\"refresh\" content=\"0; url=$url\">";
        exit;
    }

    public static function formatMoney($number, $fractional = false)
    {
        if ($fractional) {
            $number = sprintf('%.2f', $number);
        }
        while (true) {
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
            if ($replaced != $number) {
                $number = $replaced;
            } else {
                break;
            }
        }
        return $number;
    }

    public static function get_option_minhnn($variable)
    {
        if (Cache::has('theme_option')) {
            $List = Cache::get('theme_option');
        } else {
            $List = Setting::orderBy('updated', 'desc')->first();
            Cache::forever('theme_option', $List);
        }
        if ($List):
            $array_option_autos = unserialize($List['value_setting']);
            $str = "";
            if (!empty($array_option_autos)):
                $count = count($array_option_autos);
                for ($i = 0; $i < $count; $i++):
                    $label_text = ($array_option_autos[$i]['group_tdr']['tdr_name'] != '') ? $array_option_autos[$i]['group_tdr']['tdr_name'] : '';
                    $option_value = ($array_option_autos[$i]['group_tdr']['tdr_value'] != '') ? $array_option_autos[$i]['group_tdr']['tdr_value'] : '';
                    if ($label_text == $variable):
                        $str = stripslashes(stripslashes(base64_decode($option_value)));
                    endif;
                endfor;
            endif;
            return $str;
            //echo $array_option_autos[$variable];
        endif;
    }

    public static function getThumbnail($path, $img_path, $width, $height, $type = "fit")
    {
        return app('App\Http\Controllers\ImageController')->getImageThumbnail($path, $img_path, $width, $height, $type);
    }

    /*Code tạo mục lục*/
    public static function TableOfContents($content, $toc_title = "Mục lục")
    {
        $items = $css_classes = $anchor = '';
        $custom_toc_position = strpos($content, '<!--TOC-->');
        $find = $replace = array();
        $items = self::extract_headings($find, $replace, $content);
        if ($items) {
            $css_classes = "toc_wrap";
            $css_classes .= ' toc_wrap';
            $css_classes .= ' toc_light_blue';
            $css_classes = trim($css_classes);
            $html = '<div id="toc_container" class="' . $css_classes . '">';
            $html .= '<p class="toc_title">' . htmlentities(
                    $toc_title,
                    ENT_COMPAT,
                    'UTF-8'
                ) . ' <span class="toc_toggle">[<a href="#">hide</a>]</span></p>';
            $html .= '<ul class="toc_list">' . $items . '</ul></div>' . "\n";
            if ($custom_toc_position !== false) {
                $find[] = '<!--TOC-->';
                $replace[] = $html;
                $content = self::mb_find_replace($find, $replace, $content);
            } else {
                $replace[0] = $html . $replace[0];
                $content = self::mb_find_replace($find, $replace, $content);
            }
        }
        $content = str_replace('<!--TOC-->', '', $content);
        return $content;
    }

    public static function mb_find_replace(&$find = false, &$replace = false, &$string = '')
    {
        if (is_array($find) && is_array($replace) && $string) {
            // check if multibyte strings are supported
            if (function_exists('mb_strpos')) {
                for ($i = 0; $i < count($find); $i++) {
                    $string =
                        mb_substr($string, 0, mb_strpos($string, $find[$i])) .    // everything befor $find
                        $replace[$i] .                                              // its replacement
                        mb_substr(
                            $string,
                            mb_strpos($string, $find[$i]) + mb_strlen($find[$i])
                        )  // everything after $find
                    ;
                }
            } else {
                for ($i = 0; $i < count($find); $i++) {
                    $string = substr_replace(
                        $string,
                        $replace[$i],
                        strpos($string, $find[$i]),
                        strlen($find[$i])
                    );
                }
            }
        }

        return $string;
    }

    public static function extract_headings(&$find, &$replace, $content = '')
    {
        $matches = array();
        $anchor = '';
        $items = false;

        $collision_collector = array();

        if (is_array($find) && is_array($replace) && $content) {
            if (preg_match_all('/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER)) {
                $new_matches = array();
                for ($i = 0; $i < count($matches); $i++) {
                    if (trim(strip_tags($matches[$i][0])) != false) {
                        $new_matches[] = $matches[$i];
                    }
                }
                if (count($matches) != count($new_matches)) {
                    $matches = $new_matches;
                }
                if (count($matches) >= 1) {
                    for ($i = 0; $i < count($matches); $i++) {
                        // get anchor and add to find and replace arrays
                        $anchor = self::url_anchor_target($matches[$i][0]);
                        $find[] = $matches[$i][0];
                        $replace[] = str_replace(
                            array(
                                $matches[$i][1],                // start of heading
                                '</h' . $matches[$i][2] . '>'   // end of heading
                            ),
                            array(
                                $matches[$i][1] . '<span id="' . $anchor . '">',
                                '</span></h' . $matches[$i][2] . '>'
                            ),
                            $matches[$i][0]
                        );
                    }
                    $items = self::build_hierarchy($matches);
                }
            }
        }
        return $items;
    }

    public static function build_hierarchy(&$matches)
    {
        $current_depth = 100;   // headings can't be larger than h6 but 100 as a default to be sure
        $html = '';
        $numbered_items = array();
        $numbered_items_min = null;
        $collision_collector = array();
        for ($i = 0; $i < count($matches); $i++) {
            if ($current_depth > $matches[$i][2]) {
                $current_depth = (int)$matches[$i][2];
            }
        }

        $numbered_items[$current_depth] = 0;
        $numbered_items_min = $current_depth;

        for ($i = 0; $i < count($matches); $i++) {
            if ($current_depth == (int)$matches[$i][2]) {
                $html .= '<li>';
            }

            // start lists
            if ($current_depth != (int)$matches[$i][2]) {
                for ($current_depth; $current_depth < (int)$matches[$i][2]; $current_depth++) {
                    $numbered_items[$current_depth + 1] = 0;
                    $html .= '<ul><li>';
                }
            }
            // list item
            if (in_array($matches[$i][2], array(1, 2, 3, 4, 5, 6))) {
                $html .= '<a href="#' . self::url_anchor_target($matches[$i][0]) . '">';
                //if ( $this->options['ordered_list'] ) {
                // attach leading numbers when lower in hierarchy
                $html .= '<span class="toc_number toc_depth_' . ($current_depth - $numbered_items_min + 1) . '">';
                for ($j = $numbered_items_min; $j < $current_depth; $j++) {
                    $number = ($numbered_items[$j]) ? $numbered_items[$j] : 0;
                    $html .= $number . '.';
                }

                $html .= ($numbered_items[$current_depth] + 1) . '</span> ';
                $numbered_items[$current_depth]++;
                //}
                $html .= strip_tags($matches[$i][0]) . '</a>';
            }
            // end lists
            if ($i != count($matches) - 1) {
                if ($current_depth > (int)$matches[$i + 1][2]) {
                    for ($current_depth; $current_depth > (int)$matches[$i + 1][2]; $current_depth--) {
                        $html .= '</li></ul>';
                        $numbered_items[$current_depth] = 0;
                    }
                }

                if ($current_depth == (int)@$matches[$i + 1][2]) {
                    $html .= '</li>';
                }
            } else {
                // this is the last item, make sure we close off all tags
                for ($current_depth; $current_depth >= $numbered_items_min; $current_depth--) {
                    $html .= '</li>';
                    if ($current_depth != $numbered_items_min) {
                        $html .= '</ul>';
                    }
                }
            }
        }
        return $html;
    }

    private static function url_anchor_target($title)
    {
        $return = false;

        if ($title) {
            $return = trim(strip_tags($title));
            // convert accented characters to ASCII
            $return = self::remove_accents($return);
            // replace newlines with spaces (eg when headings are split over multiple lines)
            $return = str_replace(array("\r", "\n", "\n\r", "\r\n"), ' ', $return);
            // remove &amp;
            $return = str_replace('&amp;', '', $return);
            // remove non alphanumeric chars
            $return = preg_replace('/[^a-zA-Z0-9 \-_]*/', '', $return);

            // convert spaces to _
            $return = str_replace(
                array('  ', ' '),
                '_',
                $return
            );
            // remove trailing - and _
            $return = rtrim($return, '-_');

            if (!$return) {
                $return = 'i';
            }
        }
        $collision_collector[$return] = 1;
        return $return;
    }

    public static function getExtension($str)
    {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    public static function remove_accents($string)
    {
        if (!preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }

        if (self::seems_utf8($string)) {
            $chars = array(
                // Decompositions for Latin-1 Supplement
                'ª' => 'a',
                'º' => 'o',
                'À' => 'A',
                'Á' => 'A',
                'Â' => 'A',
                'Ã' => 'A',
                'Ä' => 'A',
                'Å' => 'A',
                'Æ' => 'AE',
                'Ç' => 'C',
                'È' => 'E',
                'É' => 'E',
                'Ê' => 'E',
                'Ë' => 'E',
                'Ì' => 'I',
                'Í' => 'I',
                'Î' => 'I',
                'Ï' => 'I',
                'Ð' => 'D',
                'Ñ' => 'N',
                'Ò' => 'O',
                'Ó' => 'O',
                'Ô' => 'O',
                'Õ' => 'O',
                'Ö' => 'O',
                'Ù' => 'U',
                'Ú' => 'U',
                'Û' => 'U',
                'Ü' => 'U',
                'Ý' => 'Y',
                'Þ' => 'TH',
                'ß' => 's',
                'à' => 'a',
                'á' => 'a',
                'â' => 'a',
                'ã' => 'a',
                'ä' => 'a',
                'å' => 'a',
                'æ' => 'ae',
                'ç' => 'c',
                'è' => 'e',
                'é' => 'e',
                'ê' => 'e',
                'ë' => 'e',
                'ì' => 'i',
                'í' => 'i',
                'î' => 'i',
                'ï' => 'i',
                'ð' => 'd',
                'ñ' => 'n',
                'ò' => 'o',
                'ó' => 'o',
                'ô' => 'o',
                'õ' => 'o',
                'ö' => 'o',
                'ø' => 'o',
                'ù' => 'u',
                'ú' => 'u',
                'û' => 'u',
                'ü' => 'u',
                'ý' => 'y',
                'þ' => 'th',
                'ÿ' => 'y',
                'Ø' => 'O',
                // Decompositions for Latin Extended-A
                'Ā' => 'A',
                'ā' => 'a',
                'Ă' => 'A',
                'ă' => 'a',
                'Ą' => 'A',
                'ą' => 'a',
                'Ć' => 'C',
                'ć' => 'c',
                'Ĉ' => 'C',
                'ĉ' => 'c',
                'Ċ' => 'C',
                'ċ' => 'c',
                'Č' => 'C',
                'č' => 'c',
                'Ď' => 'D',
                'ď' => 'd',
                'Đ' => 'D',
                'đ' => 'd',
                'Ē' => 'E',
                'ē' => 'e',
                'Ĕ' => 'E',
                'ĕ' => 'e',
                'Ė' => 'E',
                'ė' => 'e',
                'Ę' => 'E',
                'ę' => 'e',
                'Ě' => 'E',
                'ě' => 'e',
                'Ĝ' => 'G',
                'ĝ' => 'g',
                'Ğ' => 'G',
                'ğ' => 'g',
                'Ġ' => 'G',
                'ġ' => 'g',
                'Ģ' => 'G',
                'ģ' => 'g',
                'Ĥ' => 'H',
                'ĥ' => 'h',
                'Ħ' => 'H',
                'ħ' => 'h',
                'Ĩ' => 'I',
                'ĩ' => 'i',
                'Ī' => 'I',
                'ī' => 'i',
                'Ĭ' => 'I',
                'ĭ' => 'i',
                'Į' => 'I',
                'į' => 'i',
                'İ' => 'I',
                'ı' => 'i',
                'Ĳ' => 'IJ',
                'ĳ' => 'ij',
                'Ĵ' => 'J',
                'ĵ' => 'j',
                'Ķ' => 'K',
                'ķ' => 'k',
                'ĸ' => 'k',
                'Ĺ' => 'L',
                'ĺ' => 'l',
                'Ļ' => 'L',
                'ļ' => 'l',
                'Ľ' => 'L',
                'ľ' => 'l',
                'Ŀ' => 'L',
                'ŀ' => 'l',
                'Ł' => 'L',
                'ł' => 'l',
                'Ń' => 'N',
                'ń' => 'n',
                'Ņ' => 'N',
                'ņ' => 'n',
                'Ň' => 'N',
                'ň' => 'n',
                'ŉ' => 'n',
                'Ŋ' => 'N',
                'ŋ' => 'n',
                'Ō' => 'O',
                'ō' => 'o',
                'Ŏ' => 'O',
                'ŏ' => 'o',
                'Ő' => 'O',
                'ő' => 'o',
                'Œ' => 'OE',
                'œ' => 'oe',
                'Ŕ' => 'R',
                'ŕ' => 'r',
                'Ŗ' => 'R',
                'ŗ' => 'r',
                'Ř' => 'R',
                'ř' => 'r',
                'Ś' => 'S',
                'ś' => 's',
                'Ŝ' => 'S',
                'ŝ' => 's',
                'Ş' => 'S',
                'ş' => 's',
                'Š' => 'S',
                'š' => 's',
                'Ţ' => 'T',
                'ţ' => 't',
                'Ť' => 'T',
                'ť' => 't',
                'Ŧ' => 'T',
                'ŧ' => 't',
                'Ũ' => 'U',
                'ũ' => 'u',
                'Ū' => 'U',
                'ū' => 'u',
                'Ŭ' => 'U',
                'ŭ' => 'u',
                'Ů' => 'U',
                'ů' => 'u',
                'Ű' => 'U',
                'ű' => 'u',
                'Ų' => 'U',
                'ų' => 'u',
                'Ŵ' => 'W',
                'ŵ' => 'w',
                'Ŷ' => 'Y',
                'ŷ' => 'y',
                'Ÿ' => 'Y',
                'Ź' => 'Z',
                'ź' => 'z',
                'Ż' => 'Z',
                'ż' => 'z',
                'Ž' => 'Z',
                'ž' => 'z',
                'ſ' => 's',
                // Decompositions for Latin Extended-B
                'Ș' => 'S',
                'ș' => 's',
                'Ț' => 'T',
                'ț' => 't',
                // Euro Sign
                '€' => 'E',
                // GBP (Pound) Sign
                '£' => '',
                // Vowels with diacritic (Vietnamese)
                // unmarked
                'Ơ' => 'O',
                'ơ' => 'o',
                'Ư' => 'U',
                'ư' => 'u',
                // grave accent
                'Ầ' => 'A',
                'ầ' => 'a',
                'Ằ' => 'A',
                'ằ' => 'a',
                'Ề' => 'E',
                'ề' => 'e',
                'Ồ' => 'O',
                'ồ' => 'o',
                'Ờ' => 'O',
                'ờ' => 'o',
                'Ừ' => 'U',
                'ừ' => 'u',
                'Ỳ' => 'Y',
                'ỳ' => 'y',
                // hook
                'Ả' => 'A',
                'ả' => 'a',
                'Ẩ' => 'A',
                'ẩ' => 'a',
                'Ẳ' => 'A',
                'ẳ' => 'a',
                'Ẻ' => 'E',
                'ẻ' => 'e',
                'Ể' => 'E',
                'ể' => 'e',
                'Ỉ' => 'I',
                'ỉ' => 'i',
                'Ỏ' => 'O',
                'ỏ' => 'o',
                'Ổ' => 'O',
                'ổ' => 'o',
                'Ở' => 'O',
                'ở' => 'o',
                'Ủ' => 'U',
                'ủ' => 'u',
                'Ử' => 'U',
                'ử' => 'u',
                'Ỷ' => 'Y',
                'ỷ' => 'y',
                // tilde
                'Ẫ' => 'A',
                'ẫ' => 'a',
                'Ẵ' => 'A',
                'ẵ' => 'a',
                'Ẽ' => 'E',
                'ẽ' => 'e',
                'Ễ' => 'E',
                'ễ' => 'e',
                'Ỗ' => 'O',
                'ỗ' => 'o',
                'Ỡ' => 'O',
                'ỡ' => 'o',
                'Ữ' => 'U',
                'ữ' => 'u',
                'Ỹ' => 'Y',
                'ỹ' => 'y',
                // acute accent
                'Ấ' => 'A',
                'ấ' => 'a',
                'Ắ' => 'A',
                'ắ' => 'a',
                'Ế' => 'E',
                'ế' => 'e',
                'Ố' => 'O',
                'ố' => 'o',
                'Ớ' => 'O',
                'ớ' => 'o',
                'Ứ' => 'U',
                'ứ' => 'u',
                // dot below
                'Ạ' => 'A',
                'ạ' => 'a',
                'Ậ' => 'A',
                'ậ' => 'a',
                'Ặ' => 'A',
                'ặ' => 'a',
                'Ẹ' => 'E',
                'ẹ' => 'e',
                'Ệ' => 'E',
                'ệ' => 'e',
                'Ị' => 'I',
                'ị' => 'i',
                'Ọ' => 'O',
                'ọ' => 'o',
                'Ộ' => 'O',
                'ộ' => 'o',
                'Ợ' => 'O',
                'ợ' => 'o',
                'Ụ' => 'U',
                'ụ' => 'u',
                'Ự' => 'U',
                'ự' => 'u',
                'Ỵ' => 'Y',
                'ỵ' => 'y',
                // Vowels with diacritic (Chinese, Hanyu Pinyin)
                'ɑ' => 'a',
                // macron
                'Ǖ' => 'U',
                'ǖ' => 'u',
                // acute accent
                'Ǘ' => 'U',
                'ǘ' => 'u',
                // caron
                'Ǎ' => 'A',
                'ǎ' => 'a',
                'Ǐ' => 'I',
                'ǐ' => 'i',
                'Ǒ' => 'O',
                'ǒ' => 'o',
                'Ǔ' => 'U',
                'ǔ' => 'u',
                'Ǚ' => 'U',
                'ǚ' => 'u',
                // grave accent
                'Ǜ' => 'U',
                'ǜ' => 'u',
            );
            $string = strtr($string, $chars);
        } else {
            $chars = array();
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
                . "\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
                . "\xc3\xc4\xc5\xc7\xc8\xc9\xca"
                . "\xcb\xcc\xcd\xce\xcf\xd1\xd2"
                . "\xd3\xd4\xd5\xd6\xd8\xd9\xda"
                . "\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
                . "\xe4\xe5\xe7\xe8\xe9\xea\xeb"
                . "\xec\xed\xee\xef\xf1\xf2\xf3"
                . "\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
                . "\xfc\xfd\xff";

            $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

            $string = strtr($string, $chars['in'], $chars['out']);
            $double_chars = array();
            $double_chars['in'] = array("\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe");
            $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
            $string = str_replace($double_chars['in'], $double_chars['out'], $string);
        }

        return $string;
    }

    public static function seems_utf8($str)
    {
        self::mbstring_binary_safe_encoding();
        $length = strlen($str);
        self::reset_mbstring_encoding();
        for ($i = 0; $i < $length; $i++) {
            $c = ord($str[$i]);
            if ($c < 0x80) {
                $n = 0;
            } // 0bbbbbbb
            elseif (($c & 0xE0) == 0xC0) {
                $n = 1;
            } // 110bbbbb
            elseif (($c & 0xF0) == 0xE0) {
                $n = 2;
            } // 1110bbbb
            elseif (($c & 0xF8) == 0xF0) {
                $n = 3;
            } // 11110bbb
            elseif (($c & 0xFC) == 0xF8) {
                $n = 4;
            } // 111110bb
            elseif (($c & 0xFE) == 0xFC) {
                $n = 5;
            } // 1111110b
            else {
                return false;
            } // Does not match any model
            for ($j = 0; $j < $n; $j++) { // n bytes matching 10bbbbbb follow ?
                if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) {
                    return false;
                }
            }
        }
        return true;
    }

    public static function mbstring_binary_safe_encoding($reset = false)
    {
        static $encodings = array();
        static $overloaded = null;

        if (is_null($overloaded)) {
            $overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2);
        }

        if (false === $overloaded) {
            return;
        }

        if (!$reset) {
            $encoding = mb_internal_encoding();
            array_push($encodings, $encoding);
            mb_internal_encoding('ISO-8859-1');
        }

        if ($reset && $encodings) {
            $encoding = array_pop($encodings);
            mb_internal_encoding($encoding);
        }
    }

    public static function reset_mbstring_encoding()
    {
        self::mbstring_binary_safe_encoding(true);
    }


    /*AMP*/
    public static function remove_spaces($string)
    {
        $string = preg_replace("/\s{2,}/", " ", $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace('@CHARSET "UTF-8";', "", $string);
        $string = str_replace(', ', ",", $string);
        return $string;
    }

    public static function remove_css_comments($css)
    {
        $file = preg_replace("/(\/\*[\w\'\s\r\n\*\+\,\"\-\.]*\*\/)/", "", $css);
        return $file;
    }

    public static function minify_css($cssFilePath)
    {
        $allCss = array();
        if (empty($cssFilePath)):
            echo "No CSS was added";
            exit; // maybe you will have a better error handler
        else:
            $file = file_get_contents($cssFilePath);
            //dd($file);
            $file = self::remove_spaces($file);
            $file = self::remove_css_comments($file);
            $allCss[] = $file;
        endif;
        return implode("\n", $allCss);
    }

    public static function ampify($html = '')
    {
        $reg_img = "/<\s*img\s+[^>]*>/is";
        $img_get_src = '/src=\s*"[^\"]*\"/is';
        preg_match_all($reg_img, $html, $match_img, PREG_PATTERN_ORDER);
        $url_img = "";
        $width = 0;
        $height = 0;
        $url_images_end = "";
        $image_url_content = "";
        //print_r($match_img[0]);
        $m = 0;
        for ($m = 0; $m < count($match_img[0]); $m++):
            //$result_image=preg_replace("/src=\s*|\s*\"/is","",$match_img[0][$m]);
            $image_url_content = $match_img[0][$m];
            preg_match_all($img_get_src, $match_img[0][$m], $url_image, PREG_PATTERN_ORDER);
            $result_image = preg_replace("/src=\s*|\s*\"/is", "", $url_image[0][0]);
            $string_kandc = "https";
            $string_kandc1 = "http";
            $pos = strpos($result_image, $string_kandc);
            $pos1 = strpos($result_image, $string_kandc1);
            if ($pos === false || $pos1 === false):
                $url_img = url('/') . $result_image;
            //echo $url_img;
            else:
                $url_img = $result_image;
            endif;
            //echo $url_img;
            list($width, $height, $type, $attr) = @getimagesize($url_img);
            if ($width == 0 || $height == 0):
                $url_images_end = '';
            else:
                $url_images_end = '<amp-img src="' . $url_img . '" width="' . $width . '" height="' . $height . '" layout="responsive"></amp-img>';
            endif;
            //echo $url_images_end."<br/>";
            //echo $image_url_content;
            $html = str_replace($image_url_content, $url_images_end, $html);
        endfor;

        $html = str_ireplace(
            array('<img', '<video', '/video>', '<audio', '/audio>'),
            array('<amp-img', '<amp-video', '/amp-video>', '<amp-audio', '/amp-audio>'),
            $html
        );

        $patterns = array(
            '/style="[^"]*"/',
            '/value-form="[^"]*"/',
            '/duration="[^"]*"/',
            '/new="[^"]*"/',
            '/roman="[^"]*"/',
            '/times="[^"]*"/',
            '/type="[^"]*"/'
        );
        $str_replate = array(
            'times;',
            'times new roman",',
            'font-size: 14pt;"',
            'Times New Roman","serif";"',
            'mso-bidi-font-family: "Times New Roman";',
            'color: #010000;"',
            'Times New Roman";',
            ''
        );

        $html = preg_replace($patterns, ' ', $html);
        $html = str_replace($str_replate, ' ', $html);
        $html = preg_replace("/<!--(.*?)-->/is", "", $html);
        $html = str_replace("<p></p>", " ", $html);
        $html = str_replace("&nbsp;", " ", $html);
        //$html=preg_replace("/<\s*(\/)?tr[^\w^>]*[^>]*>/is","</p><p>",$html);
        //Remove tag <br> by </p><p>
        $html = preg_replace("/<\s*br\s*(\/)?[^>]*>/is", "</p><p>", $html);
        //Remove tag <td> by "  "
        //$html=preg_replace("/<\s*(\/)?td[^\w^>]*[^>]*>/is","  ",$html);
        //Remove all tag by empty
        $html = preg_replace(
            "/<\s*(\/)?\s*(hr|!-*|meta|select|option|span|div|font|tbody|col|colgroup|input)[^\w^>]*[^>]*(\/)?>/is",
            "",
            $html
        );
        $html = preg_replace("/<\s*(\/)?\s*i>/is", "", $html);
        $html = preg_replace("/<\s*col\s*(\/)?[^>]*\s*>/is", "", $html);
        //Remove <script> tag
        $html = preg_replace('/<script[^>]*>[^<]*<\/script>/is', '', $html);
        $html = preg_replace('/<script[^>]*>[^<]*<![[]CDATA[^<]*<\/script>/is', '', $html);
        //Thêm mỗi thẻ </p> lẻ thẻ </p><p>, <p> lẻ thẻ </p><p>
        $html = preg_replace("/(<\s*p[^>^\w]*[^>]*>\s*)+/is", "</p><p>", $html);
        $html = preg_replace("/(<\s*\/\s*p[^>^\w]*[^>]*>\s*)+/is", "</p><p>", $html);
        $html = preg_replace("/(<\s*p[^>^\w]*[^>]*>\s*)+/is", "<p>", $html);
        $html = preg_replace("/(<\s*\/\s*p[^>^\w]*[^>]*>\s*)+/is", "</p>", $html);
        $html = preg_replace(
            "/<\s*\/\s*p[^>^\w]*>\s*<\s*p[^>^\w]*>(\s*|[&]nbsp;)<\s*\/\s*p[^>^\w]*>\s*<\s*p[^>^\w]*>/is",
            "</p><p>",
            $html
        );
        $html = preg_replace("/\/><p><\/p>/is", "/><p>", $html);
        $html = preg_replace("/(<\s*p[^>^\w]*[^>]*>\s*)+/is", "<p>", $html);
        $html = preg_replace("/(<\s*\/\s*p[^>^\w]*[^>]*>\s*)+/is", "</p>", $html);

        $html = preg_replace('/<a[^>]*href="([^"]*)"[^>]*>/i', '<a href="$1">', $html);

        $html = str_replace("https://www.youtube.com/embed/", "", $html);
        $html = str_replace("//www.youtube.com/embed/", "", $html);
        $html = preg_replace(
            '/<iframe\s+.*?\s+src=(".*?").*?<\/iframe>/',
            '<amp-youtube
            data-videoid=$1
            layout="responsive"
            width="480" height="270"></amp-youtube>',
            $html
        );


        //$html=str_replace("<p></p>"," ",$html);
        //$html=str_replace("<p> </p>"," ",$html);
        //$html=str_replace("<div></div>"," ",$html);
        //$html=str_replace("<div> </div>"," ",$html);
        //$html=preg_replace("/'/is",'"',$html);
        //$html=preg_replace("/\s+/is",' ',$html);

        preg_match("/<\/p>\s*[^<]/is", $html, $match_p);
        if (count($match_p) > 0):
            $match_p_replace = str_replace("</p>", "</p><p>", $match_p[0]);
            $html = str_replace($match_p[0], $match_p_replace, $html);
        endif;

        $html = str_replace('javascript:void(0);', '#', $html);
        $html = str_replace('javascript:void();', '#', $html);
        # Add closing tags to amp-img custom element
        $html = preg_replace('/<amp-img(.*?)>/', '<amp-img$1></amp-img>', $html);


        # Whitelist of HTML tags allowed by AMP
        $html = strip_tags(
            $html,
            '<h1><h2><h3><h4><h5><h6><a><i><span><b><strong><p><ul><ol><li><blockquote><q><cite><ins><del><strong><em><code><pre><svg><table><thead><tbody><tfoot><th><tr><td><dl><dt><dd><article><section><header><footer><aside><figure><time><abbr><div><span><hr><small><br><img><amp-img><amp-audio><amp-video><amp-ad><amp-anim><amp-carousel><amp-fit-rext><amp-image-lightbox><amp-instagram><amp-lightbox><amp-twitter><amp-youtube>'
        );
        return $html;
    }

    public static function ampifySimple($html = '')
    {
        $reg_img = "/<\s*img\s+[^>]*>/is";
        $img_get_src = '/src=\s*"[^\"]*\"/is';
        preg_match_all($reg_img, $html, $match_img, PREG_PATTERN_ORDER);
        $url_img = "";
        $width = 0;
        $height = 0;
        $url_images_end = "";
        $image_url_content = "";
        //print_r($match_img[0]);
        $m = 0;
        for ($m = 0; $m < count($match_img[0]); $m++):
            //$result_image=preg_replace("/src=\s*|\s*\"/is","",$match_img[0][$m]);
            $image_url_content = $match_img[0][$m];
            preg_match_all($img_get_src, $match_img[0][$m], $url_image, PREG_PATTERN_ORDER);
            $result_image = preg_replace("/src=\s*|\s*\"/is", "", $url_image[0][0]);
            $string_kandc = "https";
            $string_kandc1 = "http";
            $pos = strpos($result_image, $string_kandc);
            $pos1 = strpos($result_image, $string_kandc1);
            if ($pos === false || $pos1 === false):
                $url_img = url('/') . $result_image;
            //echo $url_img;
            else:
                $url_img = $result_image;
            endif;
            //echo $url_img;
            list($width, $height, $type, $attr) = @getimagesize($url_img);
            if ($width == 0 || $height == 0):
                $url_images_end = '';
            else:
                $url_images_end = '<amp-img src="' . $url_img . '" width="' . $width . '" height="' . $height . '" layout="responsive"></amp-img>';
            endif;
            //echo $url_images_end."<br/>";
            //echo $image_url_content;
            $html = str_replace($image_url_content, $url_images_end, $html);
        endfor;

        $html = str_ireplace(
            array('<img', '<video', '/video>', '<audio', '/audio>'),
            array('<amp-img', '<amp-video', '/amp-video>', '<amp-audio', '/amp-audio>'),
            $html
        );

        $patterns = array(
            '/style="[^"]*"/',
            '/value-form="[^"]*"/',
            '/duration="[^"]*"/',
            '/new="[^"]*"/',
            '/roman="[^"]*"/',
            '/times="[^"]*"/',
            '/type="[^"]*"/'
        );
        $str_replate = array(
            'times;',
            'times new roman",',
            'font-size: 14pt;"',
            'Times New Roman","serif";"',
            'mso-bidi-font-family: "Times New Roman";',
            'color: #010000;"',
            'Times New Roman";',
            ''
        );

        $html = preg_replace($patterns, ' ', $html);
        $html = str_replace($str_replate, ' ', $html);
        $html = preg_replace("/<!--(.*?)-->/is", "", $html);
        $html = str_replace("<p></p>", " ", $html);
        $html = str_replace("&nbsp;", " ", $html);
        //$html=preg_replace("/<\s*(\/)?tr[^\w^>]*[^>]*>/is","</p><p>",$html);
        //Remove tag <br> by </p><p>
        $html = preg_replace("/<\s*br\s*(\/)?[^>]*>/is", "</p><p>", $html);
        //Remove tag <td> by "  "
        //$html=preg_replace("/<\s*(\/)?td[^\w^>]*[^>]*>/is","  ",$html);
        //Remove all tag by empty
        $html = preg_replace(
            "/<\s*(\/)?\s*(hr|!-*|meta|select|option|span|font|tbody|col|colgroup|input)[^\w^>]*[^>]*(\/)?>/is",
            "",
            $html
        );
        $html = preg_replace("/<\s*(\/)?\s*i>/is", "", $html);
        $html = preg_replace("/<\s*col\s*(\/)?[^>]*\s*>/is", "", $html);
        //Remove <script> tag
        $html = preg_replace('/<script[^>]*>[^<]*<\/script>/is', '', $html);
        $html = preg_replace('/<script[^>]*>[^<]*<![[]CDATA[^<]*<\/script>/is', '', $html);
        //Thêm mỗi thẻ </p> lẻ thẻ </p><p>, <p> lẻ thẻ </p><p>
        $html = preg_replace("/(<\s*p[^>^\w]*[^>]*>\s*)+/is", "</p><p>", $html);
        $html = preg_replace("/(<\s*\/\s*p[^>^\w]*[^>]*>\s*)+/is", "</p><p>", $html);
        $html = preg_replace("/(<\s*p[^>^\w]*[^>]*>\s*)+/is", "<p>", $html);
        $html = preg_replace("/(<\s*\/\s*p[^>^\w]*[^>]*>\s*)+/is", "</p>", $html);
        $html = preg_replace(
            "/<\s*\/\s*p[^>^\w]*>\s*<\s*p[^>^\w]*>(\s*|[&]nbsp;)<\s*\/\s*p[^>^\w]*>\s*<\s*p[^>^\w]*>/is",
            "</p><p>",
            $html
        );
        $html = preg_replace("/\/><p><\/p>/is", "/><p>", $html);
        $html = preg_replace("/(<\s*p[^>^\w]*[^>]*>\s*)+/is", "<p>", $html);
        $html = preg_replace("/(<\s*\/\s*p[^>^\w]*[^>]*>\s*)+/is", "</p>", $html);

        $html = preg_replace('/<a[^>]*href="([^"]*)"[^>]*>/i', '<a href="$1">', $html);

        $html = str_replace("https://www.youtube.com/embed/", "", $html);
        $html = str_replace("//www.youtube.com/embed/", "", $html);
        $html = preg_replace(
            '/<iframe\s+.*?\s+src=(".*?").*?<\/iframe>/',
            '<amp-youtube
            data-videoid=$1
            layout="responsive"
            width="480" height="270"></amp-youtube>',
            $html
        );


        //$html=str_replace("<p></p>"," ",$html);
        //$html=str_replace("<p> </p>"," ",$html);
        //$html=str_replace("<div></div>"," ",$html);
        //$html=str_replace("<div> </div>"," ",$html);
        //$html=preg_replace("/'/is",'"',$html);
        //$html=preg_replace("/\s+/is",' ',$html);

        preg_match("/<\/p>\s*[^<]/is", $html, $match_p);
        if (count($match_p) > 0):
            $match_p_replace = str_replace("</p>", "</p><p>", $match_p[0]);
            $html = str_replace($match_p[0], $match_p_replace, $html);
        endif;

        $html = str_replace('javascript:void(0);', '#', $html);
        $html = str_replace('javascript:void();', '#', $html);
        # Add closing tags to amp-img custom element
        $html = preg_replace('/<amp-img(.*?)>/', '<amp-img$1></amp-img>', $html);


        # Whitelist of HTML tags allowed by AMP
        $html = strip_tags(
            $html,
            '<h1><h2><h3><h4><h5><h6><a><i><span><b><strong><p><ul><ol><li><blockquote><q><cite><ins><del><strong><em><code><pre><svg><table><thead><tbody><tfoot><th><tr><td><dl><dt><dd><article><section><header><footer><aside><figure><time><abbr><div><span><hr><small><br><img><amp-img><amp-audio><amp-video><amp-ad><amp-anim><amp-carousel><amp-fit-rext><amp-image-lightbox><amp-instagram><amp-lightbox><amp-twitter><amp-youtube>'
        );
        return $html;
    }
}
?>
