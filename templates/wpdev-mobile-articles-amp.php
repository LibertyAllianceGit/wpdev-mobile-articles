<?php
// Post Material Setup
$postid = $post->ID;
$url = get_permalink($postid);
$shorturl = wp_get_shortlink($postid);
$title = $post->post_title;
$date = $post->post_date;
$postcats = wp_get_post_categories($postid);
$postdate = get_the_date('F j, Y', $postid);
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'wpdev_mobile_amp' );
$authid = $post->post_author;
$author = get_the_author_meta('display_name', $authid);
$authorlink = get_author_posts_url($authid);
$sharefb = plugin_dir_url( __FILE__ ) . 'images/wpdev-article-facebookshare.jpg';
$sharetw = plugin_dir_url( __FILE__ ) . 'images/wpdev-article-twittershare.jpg';
$shareem = plugin_dir_url( __FILE__ ) . 'images/wpdev-article-emailshare.jpg';

// Content Cleaning
include plugin_dir_path(__FILE__) . 'amp-parts/amp-content-clean.php'; ?>
<!-- START AMP HTML -->
<!doctype html>
<html amp lang="en">
  <head>
    <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-head.php'; ?>
  </head>
  <body>
    <!-- Article Header -->
    <div class="article-600">
      <!-- Site Logo -->
      <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-logo.php'; ?>
      <!-- Article Title -->
      <div class="article-title">
        <h1><?php echo $title; ?></h1>
      </div>
      <!-- Share Buttons -->
      <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-share-top.php'; ?>
    </div>
    <!-- Article Image -->
    <?php if(!empty($image)) { ?>
    <div class="article-image">
      <amp-img src="<?php echo $image[0]; ?>" width=600 height=350 layout="responsive"></amp-img>
    </div>
    <?php } ?>
    <!-- Article Body -->
    <div class="article-600">
      <div class="article-content">
        <p class="wpdev-article-meta"><?php echo $postdate; ?> <span>|</span> <a href="<?php echo $authorlink; ?>" target="_blank"><?php echo $author; ?></a></p>
        <hr/>
        <!-- Content -->
        <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-content.php'; ?>
        <!-- Share Buttons -->
        <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-share-bottom.php'; ?>
        <!-- After Share/Article Ad -->
        <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-after-ad.php'; ?>
        <!-- Subscribe & Full Experience Buttons -->
        <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-buttons.php'; ?>
        <!-- RevContent Ad -->
        <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-revcontent.php'; ?>
        <!-- Taboola Ad -->
        <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-taboola.php'; ?>
      </div>
    </div>
    <!-- Article Related -->
    <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-related-recent-trending.php'; ?>
    <!-- Article Footer -->
    <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-footer.php'; ?>
    <!-- Google Analytics -->
    <?php include plugin_dir_path(__FILE__) . 'amp-parts/amp-analytics.php'; ?>
  </body>
</html>
