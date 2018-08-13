<?php
/**
 * 旧IE向けアラート
 */

global $is_IE;

if ($is_IE) :
?>
<!--[if IE]>
  <style> .ie-alert-warning { z-index:9999; posion: absolute; top:0; left:0; width:100%; font-weight: bold; padding: 15px; color: #856404; background-color: #fff3cd; border-color: #ffeeba; font-size: 18px; } </style>
  <div class="ie-alert-warning">
    <?php _e('お使いのブラウザーは、必要な機能に対応していないため適切にサイトが表示されない可能性があります。より安全な最新のブラウザーをご利用ください。', 'sage'); ?>
  </div>
<![endif]-->
<?php
endif;
