<?php

namespace Lib\Lib\Custom\Shortcode;

use Roots\Sage\Assets;
use Lib\Custom\Menu;

/*
 * テキストウィジェットでショートコードを有効化。
 */
add_filter('widget_text', 'do_shortcode');

/**
 * リンクボタン.
 * [button {url} {lavel}].
 */
function add_shortcode_btn_link($arg)
{
    $arg = shortcode_atts(array(
        'block'        => null, // ブロックサイズ
        'icon'         => null, // Material Iconを指定
        'label'        => null, // ボタン表示名
        'outline'      => null, // アウトラインボタン（true で白黒反転）
        'size'         => null, // サイズ（大: lg 小: sm 中: md or 指定しない）
        'sublabel'     => null, // サブボタン表示名
        'url'          => null, // リンク先 URL
        'home_url'     => null,  // home_urlを付与した相対URLを指定（重複指定した場合'url'を上書き）
        'upload_url'   => null,  // uploadディレクトリのurlを付与したURLを指定（重複指定した場合'url'を上書き）
        'category_url' => null  // カテゴリスラッグの指定でカテゴリリンクを表示（重複指定した場合'url'を上書き）
    ), $arg, 'button');

    $btn_label = ($arg['label']) ?  $arg['label'] : $arg['url'];
    $btn_mt_icon = ($arg['icon']) ? '<i class="material-icons">'.esc_html($arg['icon']).'</i>' : '';
    $btn_sublabel = ($arg['sublabel']) ? '<small>'.esc_html($arg['sublabel']).'</small>' : '';

    $btn_class = 'button';

    if ($arg['size'] === 'sm' || $arg['size'] === 's' || $arg['size'] === 'small') {
        $btn_class .= ' btn-sm';
    } elseif ($arg['size'] === 'lg' || $arg['size'] === 'l' || $arg['size'] === 'large') {
        $btn_class .= ' btn-lg';
    }

    if ($arg['block'] || $arg['block'] === true || $arg['block'] === 1 || $arg['block'] === 'on') {
        $btn_class .=' button--block';
    }

    if ($arg['outline']) {
        $btn_class .=' button--outline';
    }

    $btn_url = $arg['url'];

    if ($arg['home_url']) {
        $btn_url = home_url($arg['home_url']);
    }

    if ($arg['upload_url']) {
        $upload_dir = wp_upload_dir();
        $btn_url = $upload_dir['baseurl'] .'/'. $arg['upload_url'];
    }

    if ($arg['category_url']) {
        $cat = get_category_by_slug($arg['category_url']);
        $btn_url = (isset($cat->cat_ID)) ? get_category_link($cat->cat_ID) : $btn_url;
    }

    return '<a href="'.esc_url($btn_url).'" class="'.esc_html($btn_class).'">'
            .$btn_mt_icon
            .'<span>'.esc_html($btn_label).'</span>'
            .$btn_sublabel
            .'</a>';
}
add_shortcode('button', __NAMESPACE__.'\\add_shortcode_btn_link');

/**
 * wysiwygエディタのコメントアウト用ショートコード
 * サイトには表示されないコメントが入れられます。
 * 例： [comment ここにコメント].
 */
function ignore_shortcode($atts, $content = null)
{
    return;
}
add_shortcode('comment', __NAMESPACE__.'\\ignore_shortcode');

/**
 * アセットパス補完ショートコード
 */
function add_shortcode_theme_path($attr)
{
    $var = (isset($attr[0])) ? $attr[0] : '';

    return esc_url(Assets\asset_path($var));
}
add_shortcode('asset_path', __NAMESPACE__.'\\add_shortcode_theme_path');

/**
 * ホームURLパス.
 * [home_url].
 */
function add_shortcode_home_path($attr)
{
    $var = (isset($attr[0])) ? $attr[0] : '';

    return esc_url(home_url().'/'.$var);
}
add_shortcode('home_url', __NAMESPACE__.'\\add_shortcode_home_path');

/**
 * 親テーマのパス
 * [parent_theme_url].
 */
function add_shortcode_parent_theme($attr)
{
    $var = (isset($attr[0])) ? '/'.$attr[0] : '';

    return get_template_directory_uri().$var;
}
add_shortcode('parent_theme_url', __NAMESPACE__.'\\add_shortcode_parent_theme');

/**
 * 現在のテーマ(or子テーマ)のパス
 * [theme_url].
 */
function add_shortcode_child_theme($attr)
{
    $var = (isset($attr[0])) ? '/'.$attr[0] : '';

    return get_stylesheet_directory_uri().$var;
}
add_shortcode('theme_url', __NAMESPACE__.'\\add_shortcode_child_theme');

/**
 * [親テーマのアセットパス].
 */
function add_shortcode_assets_path($attr)
{
    $var = (isset($attr[0])) ? $attr[0] : '';

    return esc_url(Assets\asset_path($var));
}
add_shortcode('asset', __NAMESPACE__.'\\add_shortcode_assets_path');

/**
 * アップロードディレクトリのパス.
 */
function add_shortcode_upload_path($attr, $type = 'baseurl')
{
    $wp_upload_dir = wp_upload_dir();
    $path = ($type == 'url') ?  $wp_upload_dir['url'] : $wp_upload_dir['baseurl'];
    $var = (isset($attr[0])) ? $attr[0] : '';

    return esc_url($path.'/'.$var);
}
add_shortcode('upload', __NAMESPACE__.'\\add_shortcode_upload_path');

/**
 * カスタムメニュー出力.
 */
function single_page_custom_menu($atts, $content = null)
{
    extract(shortcode_atts(array(
        'echo' => true,
        'menu' => '',
    ), $atts));

    $menu = wp_nav_menu(array(
        'container' => false,
        'echo' => false,
        'menu' => $menu,
        'menu_class' => 'customMenuList',
    ));

    return '<nav>'."\n" .$menu."\n" .'</nav>';
}
add_shortcode('customenu', __NAMESPACE__.'\\single_page_custom_menu');

/**
 * コピー防止メールアドレス表示
 */
function add_shortcode_no_copy_mail($arg)
{
    $arg = shortcode_atts(array(
        'user' => null,
        'domain' => null,
    ), $arg, 'no_copy_mail');

    return '<span class="page__mail" data-user="'.$arg['user'].'" data-domain="'.$arg['domain'].'"></span>';
}
add_shortcode('no_copy_mail', __NAMESPACE__.'\\add_shortcode_no_copy_mail');
