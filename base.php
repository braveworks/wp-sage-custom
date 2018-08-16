<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

/**
 * ベーステンプレート
 *
 * 参考：Theme Wrappers
 * http://scribu.net/wordpress/theme-wrappers.html
 *
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<?php get_template_part('templates/head'); ?>

<body <?php body_class(); ?>>
  <?php get_template_part('templates/ie-alert'); ?>
  <?php
    do_action('get_header');
    get_template_part('templates/header');
  ?>
  <div id="wrap" class="wrap" role="document">
    <div class="content">
      <main class="main">
        <?php include Wrapper\template_path(); ?>
      </main>
      <!-- /.main -->
      <?php if (Setup\display_sidebar()) : ?>
      <aside class="sidebar">
        <?php include Wrapper\sidebar_path(); ?>
      </aside>
      <!-- /.sidebar -->
      <?php endif; ?>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.wrap -->
  <?php
    do_action('get_footer');
    get_template_part('templates/footer');
    wp_footer();
  ?>
</body>

</html>
