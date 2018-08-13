<?php

namespace Lib\Custom\Util;

use Roots\Sage\Assets;

/**
 * ファイルの更新日時を取得
 * @param string $filename [ファイルパス]
 * @return string [更新日時文字列]
 */
function filedate($filename)
{
    $filetime_string = null;
    if (file_exists($filename)) {
        $filetime_string = date('YmdHis', filemtime($filename));
    }

    return $filetime_string;
}

/**
 * 画像 URI のみ取得関数.
 *
 * @param string $size 取得するWP画像サイズ
 * @param bool $bg_style trueはbackground-imageインラインスタイル込みでURIを返す。falseはURIのみ
 * @return string URI
 */
function img_src($image_id = null, $size = 'middle', $bg_style = true)
{
    $image_path = null;
    $image_id = (!$image_id && has_post_thumbnail()) ? get_post_thumbnail_id() : $image_id;

    if ($image_id) {
        $image_url = wp_get_attachment_image_src($image_id, true);
        $image_path = $image_url[0];
    } else {
        // $image_path = Assets\asset_path('/images/thumbnail.png');
        $image_path = '';
    }

    return ($bg_style) ? bg_uri($image_path) : $image_path;
}

/**
 * background-image のインラインスタイルの文字列をesc_urlして返す関数.
 *
 * @param string $uri ソースパス
 *
 * @return string インラインスタイル
 */
function bg_uri($uri = null)
{
    if ($uri) {
        return 'style="background-image:url('.esc_url($uri).')"';
    }
}

/**
 * モバイル判定.
 */
function is_mobile()
{
    $useragents = array(
        'iPhone', // iPhone
        'iPad', // iPad
        'iPod', // iPod touch
        'Android.*Mobile', // 1.5+ Android *** Only mobile
        'Windows.*Phone', // *** Windows Phone
        'dream', // Pre 1.5 Android
        'CUPCAKE', // 1.5+ Android
        'blackberry9500', // Storm
        'blackberry9530', // Storm
        'blackberry9520', // Storm v2
        'blackberry9550', // Storm v2
        'blackberry9800', // Torch
        'webOS', // Palm Pre Experimental
        'incognito', // Other iPhone browser
        'webmate', // Other iPhone browser
    );
    $pattern = '/'.implode('|', $useragents).'/i';

    return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}

/**
 * htmlタグのホワイトリスト.
 */
function allowed_html()
{
    return  array(
      'a' => array(
        'href' => array(),
        'target' => array()),
      'br' => array(),
      'span' => array(),
      'strong' => array(),
      'small' => array(),
      'i' => array(),
      'p' => array(),
    );
}

/**
 * [new]バッジの表示判定.
 */
function new_badge_display()
{
    $news_badge_display_date = get_option('news_badge_display_date', '');
    $days = ($news_badge_display_date && $news_badge_display_date !== '') ? $news_badge_display_date : 15;
    $days_int = ($days - 1) * 86400;
    $dayago = time() - get_the_time('U');
    $badge = ($dayago < $days_int) ? '<span class="badge">New</span>' : '';

    return $badge;
}

/**
 * 投稿から一定期間内の新着記事にNewバッジを表示する
 */
function new_badge()
{
    $days = 15;
    $today = date_i18n('U');
    $entry = get_the_time('U');
    $total = date('U', ($today - $entry)) / 86400;
    if ($days > $total) {
        echo '<span class="label label-danger">NEW</span>';
    }
}

/**
 * アイキャッチを取得・無い場合はダミー画像のimgタグを返す
 */
function custom_get_thumbnail($id = null, $size = 'medium', $attr = array())
{
    return (has_post_thumbnail($id))
                  ? get_the_post_thumbnail($id, $size, $attr)
                  : '<img src="'.Assets\asset_path('images/darmmy.png').'" alt="">';
}

/**
 * Responsive Image Helper Function.
 *
 * @param string $image_id   the id of the image (from ACF or similar)
 * @param string $image_size the size of the thumbnail image or custom image size
 * @param string $max_width  the max width this image will be shown to build the sizes attribute
 */
function acf_srcset_img($image_id, $image_size, $max_width)
{

    // check the image ID is not blank
    if ($image_id != '') {
        // set the default src image size
        $image_src = wp_get_attachment_image_url($image_id, $image_size);

        // set the srcset with various image sizes
        $image_srcset = wp_get_attachment_image_srcset($image_id, $image_size);

        // generate the markup for the responsive image
        return 'src="'.$image_src.'" srcset="'.$image_srcset.'" sizes="(max-width: '.$max_width.') 100vw, '.$max_width.'"';
    }
}

/**
 * アイキャッチ画像をsrcset属性付きで取得.
 */
function get_cover_fit_img($class = '', $image_size = 'thumb-2000', $max_width = '2000px')
{
    if (is_category() && function_exists('get_field')) {
        // カテゴリページの場合はACFの値から取得
        global $cat;
        $category_thumbnail_id = get_field('category_thumbnail_id', 'category_'.$cat);
        $css_class = ($class !== '') ? 'class="'.esc_html($class).'" ' : '';

        echo '<img '. $css_class . acf_srcset_img($category_thumbnail_id, $image_size, $max_width) .'alt="" />';
    } elseif (has_post_thumbnail()) {
        the_post_thumbnail();
    }
}

/**
 * ページIDからスラッグ名を取得
 */
function get_page_slug($page_id)
{
    $str = get_post($page_id);
    return $str->post_name;
}

/**
 * 親ページスラッグを取得
 */
function get_parent_slug()
{
    global $post;
    if ($post->post_parent) {
        $post_data = get_post($post->post_parent);
        return $post_data->post_name;
    }
}

/**
 * プラグインがアクティベートされているかを判別
 * 参考： https://firegoby.jp/archives/5237
 */
function check_plugin_activate($plugin)
{
    if (function_exists('is_plugin_active')) {
        return is_plugin_active($plugin);
    } else {
        return in_array(
            $plugin,
            get_option('active_plugins')
        );
    }
}

/**
 * 指定したパスのSVGをインラインで描画
 */
function embed_inline_svg($filepath = '')
{
    $svg_asset_path = Assets\asset_path($filepath, true);

    if (file_exists($svg_asset_path)) {
        return file_get_contents($svg_asset_path);
    }
}

/**
 * 指定文字数で省略表示
 */
function trunk_text($text, $text_length = 35)
{
    $trunk_text = $text;
    if (strlen($text) > $text_length) {
        $trunk_text =  mb_substr($text, 0, $text_length) . ' …';
    }

    return $trunk_text;
}

/**
 * カテゴリ一覧リンク出力
 */
function get_category_list($categories = [], $separator = ' ')
{
    $output = '';
    if ($categories) {
        foreach ($categories as $category) {
            $output .= '<li><a class="content__category" href="'
                          . get_category_link($category->term_id) . '" title="'
                          . esc_attr(sprintf(__("View all posts in %s"), $category->name))
                          . '"><span>' . $category->cat_name . '</span></a></li>' . $separator;
        }
        return trim($output, $separator);
    }
}

/**
 * タグ一覧リンク出力
 */
function get_tag_list($tags = [], $separator = ' ')
{
    $output = '';
    if ($tags) {
        foreach ($tags as $tag) {
            $output .= '<li><a class="content__tag" href="' . get_tag_link($tag->term_id) . '" title="'
                        . esc_attr(sprintf(__("View all posts in %s"), $tag->name))
                        . '"><span>#' . $tag->name . '</span></a></li>' . $separator;
        }
        return trim($output, $separator);
    }
}

/**
 * カスタムページネーション出力
 *
 * custom pagination with bootstrap .pagination class
 * source: http://www.ordinarycoder.com/paginate_links-class-ul-li-bootstrap/
 */
function custom_pagination($echo = true)
{
    global $wp_query;

    $big = 999999999; // need an unlikely integer

    $pages = paginate_links(
        array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'type'  => 'array',
            'prev_next'   => true,
            'prev_text'    => __('&laquo;'),
            'next_text'    => __('&raquo;'),
        )
    );

    if (is_array($pages)) {
        $paged = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');

        $pagination = '<ul class="pagination">';

        foreach ($pages as $page) {
            $pagination .= "<li>$page</li>";
        }

        $pagination .= '</ul>';

        if ($echo) {
            echo $pagination;
        } else {
            return $pagination;
        }
    }
}

/**
 * 現在のページ数の取得
 */
function show_page_number()
{
    global $wp_query;

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $max_page = $wp_query->max_num_pages;
    return  $paged;
}

/**
 * 総ページ数の取得
 */
function max_show_page_number()
{
    global $wp_query;

    $max_page = $wp_query->max_num_pages;
    return (int) $max_page;
}

/**
 * 指定日の残日数を取得
 *
 * @param string $date
 * @return $diff
 */
function get_days_left($date = '2018/08/31 00:00:00', $now = 'now')
{
    date_default_timezone_set('Asia/Tokyo');

    $datetime = new \DateTime($date);
    $current  = new \DateTime($now);
    $diff = $current->diff($datetime);

    return $diff;
}

/**
 * 指定期間の日にち配列生成
 *
 * @param [type] $startUnixTime 開始日時
 * @param [type] $endUnixTime 終了日時
 * @return void
 */
function get_date_list($startUnixTime = '2018/07/21 00:00:00', $endUnixTime = '2018/08/31 23:59:59')
{
    $period = [];
    $diff = (strtotime($endUnixTime) - strtotime($startUnixTime)) / (60 * 60 * 24);

    for ($i = 0; $i <= $diff; $i++) {
        $period[] = date('Y-m-d', strtotime($startUnixTime . '+' . $i . 'days'));
    }

    return $period;
}

/**
 * 整形した日時を返す
 */

function get_format_date_array($date_value)
{
    $format_date = [];
    if ($date_value) {
        $format_date['year']     = strftime('%b', strtotime($date_value)); // 月(英語)
        $format_date['month']    = ltrim(strftime('%m', strtotime($date_value)), "0"); // 月（数字）
        $format_date['day']      = strftime('%d', strtotime($date_value)); // 日
        $format_date['day_week'] = strftime('%a', strtotime($date_value)); // 曜日（英語3文字）
    }

    return $format_date;
}

function get_format_date($date_value)
{
    $date_html = null;
    $current_date = get_format_date_array($date_value);

    if (!empty($current_date)) {
        $b = $current_date['year'] ?: '';
        $m = $current_date['month'] ?: '';
        $d = $current_date['day'] ?: '';
        $date_html = $m .'/'. $d . ' ' . $b;
    }

    return $date_html;
}

/**
 * ACF プラグインアクティブ判定
 *
 * @return boolean
 */
function is_acf()
{
    return (function_exists('get_field'));
}
