<?php
/**
 *  Googleアナリティクスコード
 */

use Lib\Custom\Util;

?>
<?php if (!is_user_logged_in() || !is_admin_bar_showing()) : ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-*********-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag() {
    dataLayer.push(arguments);
  }

  gtag('js', new Date());
  gtag('config', 'UA-*********-1');

<?php if (Util\check_plugin_activate('contact-form-7/wp-contact-form-7.php')) : // Contact Form 7 送信イベントを送信するイベントリスナー ?>
  document.addEventListener('wpcf7mailsent', function(event) {
      gtag('event', location.href, {'event_category': 'フォーム送信','event_label': 'お問い合わせ'});
  }, false );
<?php endif; ?>
</script>
<?php endif; ?>
