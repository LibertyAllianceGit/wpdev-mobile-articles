<?php
$sharefb = plugin_dir_url( __FILE__ ) . 'images/wpdev-article-facebookshare.jpg';
$sharetw = plugin_dir_url( __FILE__ ) . 'images/wpdev-article-twittershare.jpg';
$shareem = plugin_dir_url( __FILE__ ) . 'images/wpdev-article-emailshare.jpg'; ?>
<div class="article-share">
  <ul class="article-share-container">
    <li><a class="article-share-button facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>" target="_blank"><amp-img src="<?php echo $sharefb; ?>" width=46 height=30></amp-img></a></li>
    <li><a class="article-share-button twitter" href="http://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>%20-%20<?php echo urlencode(wp_get_shortlink()); ?>%20via%20%40NAME" target="_blank"><amp-img src="<?php echo $sharetw; ?>" width=46 height=30></amp-img></a></li>
    <li><a class="article-share-button email" href="mailto:?&subject=<?php echo get_the_title(); ?>&body=Check%20out%20this%20article%20from%20<?php echo get_bloginfo('name'); ?>%3A%20<?php echo urlencode(get_permalink()); ?>" target="_blank"><amp-img src="<?php echo $shareem; ?>" width=46 height=30></amp-img></a></li>
  </ul>
</div>
