<?php

function remove_src_wp_ver($dep)
{
    $dep->default_version = '';
}

function replace_assets()
{
    if (!is_admin()) {
        // WPの既存jQueryを無効化
        wp_deregister_script('jquery');
        // CDN版jQueryに変更
        // wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', [], null, true);
    }

    //ヘッダーのスクリプトを削除
    remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);

    //フッターにスクリプトを移動
    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);

    // emojiとembed無効化
    // if (
    //   is_front_page() ||
    //   is_page() ||
    //   is_archive()
    // ) {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_print_styles', 'print_emoji_styles');
    add_filter('emoji_svg_url', '__return_false');
    // }

    // generatorを非表示
    remove_action('wp_head', 'wp_generator');

    // EditURIを非表示
    remove_action('wp_head', 'rsd_link');

    // wlwmanifestを非表示
    remove_action('wp_head', 'wlwmanifest_link');

    // rel="shortlink"
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

    // WLW(Windows Live Writer) wlwmanifest.xml
    remove_action('wp_head', 'wlwmanifest_link');

    // RSD xmlrpc.php?rsd
    remove_action('wp_head', 'rsd_link');

    // .recentcommentsを消す
    global $wp_widget_factory;
    remove_action('wp_head', [$wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style']);

    // wpautop pタグなどの自動挿入無効
    if (is_page()) {
        remove_filter('the_excerpt', 'wpautop');
        remove_filter('the_content', 'wpautop');
    }
}
add_action('wp_enqueue_scripts', 'replace_assets', 102);

/**
 *  Robots.txt 出力に追記.
 */
//  function custom_robots_txt($output)
//  {
//      $public = get_option('blog_public');
//      $site_url = parse_url(site_url());
//      $path = (!empty($site_url['path'])) ? $site_url['path'] : '';
//      if ('0' != $public) {
//          $output .= 'Disallow: /archives/';
//      }

//      return $output;
//  }
//  add_filter('robots_txt', 'custom_robots_txt');

/**
 * wp_headになんか追加（gtagなど）
 */
function custom_adds_head()
{
    // oembedの修正用スタイル
    if (is_embed()) {
        echo '<style>html{background:transparent}body{min-height:200px}</style>';
    }
    // gtgテンプレート
    get_template_part('templates/analytics');
}
add_action('wp_head', 'custom_adds_head', 100);

/**
 * Yoest SEO：ogpのtwitter:image をデフォルトのロゴ画像に変更。
 */
function custom_custom_twitter_image($img)
{
    $page_id = get_the_ID();

    if (!has_post_thumbnail($page_id) || preg_match('/\[/', $img)) {
        return home_url(''); // 画像URL
    }

    return $img;
}
// add_filter('wpseo_twitter_image', 'custom_custom_twitter_image');

/**
 * W3 Total Cache : コメント出力を非表示.
 */
function custom_disable_w3tc_can_print_comment($w3tc_setting)
{
    if (!current_user_can('activate_plugins')) {
        return false;
    }
}
add_filter('w3tc_can_print_comment', 'custom_disable_w3tc_can_print_comment', 10, 1);

/**
 *  get_the_archive_title()で取得するタイトルから「アーカイブ:」などの余分なテキストを削除するフック.
 */
function custom_custom_archive_title($title)
{
    if (is_category()) {
        /* translators: Category archive title. 1: Category name */
        $title = sprintf(__('%s'), single_cat_title('', false));
    } elseif (is_tag()) {
        /* translators: Tag archive title. 1: Tag name */
        $title = sprintf(__('%sに関する記事'), single_tag_title('', false));
    } elseif (is_author()) {
        /* translators: Author archive title. 1: Author name */
        $title = sprintf(__('%s'), '<span class="vcard">'.get_the_author().'</span>');
    } elseif (is_year()) {
        /* translators: Yearly archive title. 1: Year */
        $title = sprintf(__('%sの記事'), get_the_date(_x('Y', 'yearly archives date format')));
    } elseif (is_month()) {
        /* translators: Monthly archive title. 1: Month name and year */
        $title = sprintf(__('%sの記事'), get_the_date(_x('F Y', 'monthly archives date format')));
    } elseif (is_day()) {
        /* translators: Daily archive title. 1: Date */
        $title = sprintf(__('%sの記事'), get_the_date(_x('F j, Y', 'daily archives date format')));
    } elseif (is_tax('post_format')) {
        if (is_tax('post_format', 'post-format-aside')) {
            $title = _x('Asides', 'post format archive title');
        } elseif (is_tax('post_format', 'post-format-gallery')) {
            $title = _x('Galleries', 'post format archive title');
        } elseif (is_tax('post_format', 'post-format-image')) {
            $title = _x('Images', 'post format archive title');
        } elseif (is_tax('post_format', 'post-format-video')) {
            $title = _x('Videos', 'post format archive title');
        } elseif (is_tax('post_format', 'post-format-quote')) {
            $title = _x('Quotes', 'post format archive title');
        } elseif (is_tax('post_format', 'post-format-link')) {
            $title = _x('Links', 'post format archive title');
        } elseif (is_tax('post_format', 'post-format-status')) {
            $title = _x('Statuses', 'post format archive title');
        } elseif (is_tax('post_format', 'post-format-audio')) {
            $title = _x('Audio', 'post format archive title');
        } elseif (is_tax('post_format', 'post-format-chat')) {
            $title = _x('Chats', 'post format archive title');
        }
    } elseif (is_post_type_archive()) {
        if (is_post_type_archive('partner')) {
            $title = 'PARTNERS';
        } else {
            /* translators: Post type archive title. 1: Post type name */
            $title = sprintf(__('%s'), post_type_archive_title('', false));
        }
    } elseif (is_tax()) {
        $tax = get_taxonomy(get_queried_object()->taxonomy);
        /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
        // $title = sprintf(__('%1$s: %2$s'), $tax->labels->singular_name, single_term_title('', false));
        $title = sprintf(__('%s'), single_term_title('', false));
    } else {
        $title = __('Archives');
    }

    return $title;
}
add_filter('get_the_archive_title', 'custom_custom_archive_title');

/**
 * 「続きを読む」の表記変更
 */
function new_excerpt_more($more)
{
    global $post;
    // return '<a class="moreTag" href="'. get_permalink($post->ID) . '"> … 続きを読む</a>';
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * カスタムフィールドを検索対象に含める
 * 参考：http://wpcj.net/1363
 */
function posts_search_custom_fields($orig_search, $query)
{
    if ($query->is_search() && $query->is_main_query() && ! is_admin()) {
        global $wpdb;
        $q = $query->query_vars;
        $n = ! empty($q['exact']) ? '' : '%';
        $searchand = '';

        foreach ($q['search_terms'] as $term) {
            $include = '-' !== substr($term, 0, 1);
            if ($include) {
                $like_op  = 'LIKE';
                $andor_op = 'OR';
            } else {
                $like_op  = 'NOT LIKE';
                $andor_op = 'AND';
                $term     = substr($term, 1);
            }
            $like = $n . $wpdb->esc_like($term) . $n;
            // カスタムフィールド用の検索条件を追加します。
            $search .= $wpdb->prepare("{$searchand}(($wpdb->posts.post_title $like_op %s) $andor_op ($wpdb->posts.post_content $like_op %s) $andor_op (custom.meta_value $like_op %s))", $like, $like, $like);
            $searchand = ' AND ';
        }
        if (! empty($search)) {
            $search = " AND ({$search}) ";
            if (! is_user_logged_in()) {
                $search .= " AND ($wpdb->posts.post_password = '') ";
            }
        }
        return $search;
    } else {
        return $orig_search;
    }
}
add_filter('posts_search', 'posts_search_custom_fields', 10, 2);

/**
 * カスタムフィールド検索用のJOINを行う。
 */
function posts_join_custom_fields($join, $query)
{
    if ($query->is_search() && $query->is_main_query() && ! is_admin()) {
        // group_concat()したmeta_valueをJOINすることでレコードの重複を除きつつ検索しやすくします。
        global $wpdb;
        $join .= " INNER JOIN ( ";
        $join .= " SELECT post_id, group_concat( meta_value separator ' ') AS meta_value FROM $wpdb->postmeta ";
        // $join .= " WHERE meta_key IN ( 'test' ) ";
        $join .= " GROUP BY post_id ";
        $join .= " ) AS custom ON ($wpdb->posts.ID = custom.post_id) ";
    }
    return $join;
}
add_filter('posts_join', 'posts_join_custom_fields', 10, 2);

/**
 * 投稿内figureタグのwidthインラインスタイルを除去
 */
add_filter('img_caption_shortcode_width', '__return_false');

/**
 * 投稿 iframeのレスポンシブ対応
 *
 * @param [type] $the_content
 * @return void
 */
function wrap_iframe_in_div($the_content)
{
    if (is_singular()) {
        $the_content = preg_replace('/<iframe/i', '<div class="embedResponsive embedResponsive--16by9"><iframe', $the_content);
        $the_content = preg_replace('/<\/iframe>/i', '</iframe></div>', $the_content);
    }
    return $the_content;
}
add_filter('the_content', 'wrap_iframe_in_div');

/**
 * 抜粋の文字数変更
 */
function custom_excerpt_length($length)
{
    return 32;
}
add_filter('excerpt_length', 'custom_excerpt_length');

/**
 * 抜粋の省略文字数変更
 */
function custom_excerpt_more($more)
{
    return '…';
}
add_filter('excerpt_more', 'custom_excerpt_more');

/**
 * 画像にLazyloadクラス付与
 */
function add_post_img_lazyloading($content)
{
    $re_content = preg_replace('/(<img[^>]*)\s+class="([^"]*)"/', '$1 class="$2 lazyload"', $content);
    return $re_content;
}
// add_filter('the_content', 'add_post_img_lazyloading');

/**
 * Yoastのmetaにキーワードタグを追加
 */
function yoast_add_force_keyword_tag($keywords)
{
    echo '<meta name="keywords" content="">'."\n";
}
// add_filter('wpseo_head', 'yoast_add_force_keyword_tag');

/**
 * カスタムメニューのハッシュリンク書き換え
 * トップページ以外でhome_urlを追記
 */
function change_menu($items)
{
    if (!is_front_page()) {
        foreach ($items as $item) {
            if (preg_match("/^#/", $item->url) && !preg_match("/^#0/", $item->url)) {
                $item->url = home_url('/'.$item->url);
            }
        }
    }

    return $items;
}
// add_filter('wp_nav_menu_objects', 'change_menu');
