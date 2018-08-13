<?php
/**
 * ---------------
 * 管理画面カスタマイズ関連フック.
 * ---------------
 */

use Roots\Sage\Assets;

/**
 * 管理画面：TinyMCEの自動整形無効化.
 */
function custom_override_mce_options($init_array)
{
    global $allowedposttags;

    $init_array['valid_elements'] = '*[*]';
    $init_array['extended_valid_elements'] = '*[*]';
    $init_array['valid_children'] = '+a['.implode('|', array_keys($allowedposttags)).']';
    $init_array['indent'] = false;
    $init_array['wpautop'] = false;
    $init_array['force_p_newlines'] = false;

    return $init_array;
}

add_filter('tiny_mce_before_init', 'custom_override_mce_options');

/**
 *  ログインページのロゴ変更.
 */
function custom_login_style()
{
    $logo = Assets\asset_path(''); // ロゴ画像のパス
    if ($logo) :
    ?>
    <style type="text/css">
      body.login #login h1 a {
        background-image: none, url(<?= $logo ?>) !important;
        background-size: contain;
        width: 80%;
        height: 60px;
        margin-bottom: 0;
      }
    </style>
    <?php
    endif;
}
add_action('login_enqueue_scripts', 'custom_login_style');

/**
 * ログインページロゴのリンク先を指定.
 */
function custom_login_logo_url()
{
    return get_bloginfo('url');
}
add_filter('login_headerurl', 'custom_login_logo_url');

/**
 * ログインページロゴのtitle変更.
 */
function custom_login_logo_tit()
{
    return get_option('blogname');
}
add_filter('login_headertitle', 'custom_login_logo_tit');

/**
 * 管理画面の不要メニューを管理者権限以外は非表示
 */
function remove_menus()
{
    global $menu;

    if (!current_user_can('administrator')) {
        // unset($menu[2]);  // ダッシュボード
        // unset($menu[4]);  // メニューの線1
        // unset($menu[5]);  // 投稿
        // unset($menu[10]); // メディア
        // unset($menu[15]); // リンク
        // unset($menu[20]); // ページ
        unset($menu[25]); // コメント
        // unset($menu[59]); // メニューの線2
        // unset($menu[60]); // テーマ
        // unset($menu[65]); // プラグイン
        // unset($menu[70]); // プロフィール
        unset($menu[75]); // ツール
        // unset($menu[80]); // 設定
        // unset($menu[90]); // メニューの線3
    }
}
add_action('admin_menu', 'remove_menus');

/**
 * Move yoast seo boxes to bottom of post/page
 *
 * @return void
 */
function yoasttobottom()
{
    return 'low';
}
add_filter('wpseo_metabox_prio', 'yoasttobottom');


/**
 * remove ewww plugin notice
 *
 * @return void
 */
function remove_ewww_notice()
{
    if (!current_user_can('administrator')) :
        ?>
        <style type="text/css">
          #ewww-image-optimizer-notice-php53 { display: none; }
        </style>
        <?php
    endif;
}
add_action("admin_head", 'remove_ewww_notice');


/**
 * 管理画面：特定の固定ページ編集時にビジュアルエディタ無効
 */
function disable_visual_editor_in_page()
{
    global $typenow;
    $post_id = $_GET['post'];
    $target_post = array(
        // 無効にする固定ページのID
        '17', // 団体概要
        '22', // 事業内容
        '24', // 維持会員
        '26', // 読書に関わる関連情報
        '32', // 役員名簿
        '469', // 事業報告
        '1839', // 維持会員募集のご案内
     );

    if ($typenow == 'page' && in_array($post_id, $target_post, true)) {
        add_filter('user_can_richedit', 'disable_visual_editor_filter');
    }
}
function disable_visual_editor_filter()
{
    return false;
}
// add_action('load-post.php', 'disable_visual_editor_in_page');
// add_action('load-post-new.php', 'disable_visual_editor_in_page');

