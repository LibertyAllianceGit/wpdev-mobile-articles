<?php
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' ); // Array of All Options
$footerarticles = $wpdev_mobile_options['footer_articles_section_6'];

if(empty($footerarticles) || $footerarticles == 'relatedarticles') {
  $sectiontitle = 'Related';
} else {
  $sectiontitle = 'Recent';
}

wp_reset_postdata();
if(empty($footerarticles) || $footerarticles == 'relatedarticles') {
    $args = array (
      'post_type'              => array( 'post' ),
      'post_status'            => array( 'publish' ),
      'cat'                    => array( $postcats ),
      'posts_per_page'         => '5',
      'ignore_sticky_posts'    => true,
    );
  } else {
    $args = array (
      'post_type'              => array( 'post' ),
      'post_status'            => array( 'publish' ),
      'posts_per_page'         => '5',
      'ignore_sticky_posts'    => true,
    );
  }

  // The Query
  $related = new WP_Query( $args );

  // The Loop
  if ( $related->have_posts() ) { ?>
    <div class="article-related">
      <div class="article-600">
        <h2 class="article-related-section"><?php echo $sectiontitle; ?> Articles on <?php echo get_bloginfo('name'); ?></h2>
  <?php	while ( $related->have_posts() ) {
      $related->the_post();
      $relatedimg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'wpdev_mobile_amp' );
      if(empty($relatedimg)) {
        $ampimg = '';
        $ampimgclass = 'full';
      } else {
        $ampimg = '<div class="article-related-single-img"><a href="' . get_permalink() . '" target="_blank"><amp-img src="' . $relatedimg[0] . '" width=600 height=350 layout="responsive"></amp-img></a></div>';
        $ampimgclass = 'half';
      }
      echo '<div class="article-related-single ' . $ampimgclass . '">';
      echo $ampimg;
      echo '<div class="article-related-single-title"><h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3></div>';
      echo '</div class="article-related-single">';
    } ?>
    </div>
  </div>
  <?php
  } else {
    // no posts found
  }
// Restore original Post Data
wp_reset_postdata(); ?>
