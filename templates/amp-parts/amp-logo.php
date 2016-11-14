<?php
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' );
$logoimage = $wpdev_mobile_options['logo_image_0']; ?>

<nav class="title-bar">
  <div>
    <a href="<?php echo get_bloginfo('url'); ?>">
      <?php if(!empty($logoimage)) { ?>
      <amp-img src="<?php echo $logoimage; ?>" width=600 height=60></amp-img>
      <?php } else { ?>
      <h1 class="article-site-title"><?php echo get_bloginfo('name'); ?></h1>
      <?php } ?>
    </a>
  </div>
</nav>
<hr/>
