<?php
/**
Grab Plugin Variables
**/
if(wpdev_file_check() == '1') {
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' ); // Array of All Options
$numberposts = $wpdev_mobile_options['number_of_posts_20']; // Number of Posts
$enablefacebookads = $wpdev_mobile_options['enable_facebook_ads_21']; // Enable Facebook Ads
$facebook_ad1 = $wpdev_mobile_options['facebook_ad_placement_id_1_22']; // Facebook Ad Placement ID 1
$facebook_ad2 = $wpdev_mobile_options['facebook_ad_placement_id_2_23']; // Facebook Ad Placement ID 2
$facebook_ad3 = $wpdev_mobile_options['facebook_ad_placement_id_3_24']; // Facebook Ad Placement ID 3
$facebook_ad4 = $wpdev_mobile_options['facebook_ad_placement_id_4_25']; // Facebook Ad Placement ID 4
$enablerecirculate = $wpdev_mobile_options['enable_facebook_ads_recirculation_21']; // Enable Facebook Recirculation Ad
$facebook_recirc = $wpdev_mobile_options['facebook_ad_placement_id_4_recirculation_25']; // Facebook Recirculation Ad Placement
$enableanalytics = $wpdev_mobile_options['enable_google_analytics_tracking_26']; // Enable Google Analytics Tracking
$fbiaanalytics_id = $wpdev_mobile_options['fbia_google_analytics_tracking_id_27']; // FBIA Google Analytics Tracking ID
$analyticsgroup = $wpdev_mobile_options['enable_google_analytics_group_tracking_28']; // Enable Google Analytics Group Tracking
$additionaltrack = $wpdev_mobile_options['additional_analytics_tracking_code_28']; // Additional tracking codes
}

/**
Setup Plugin Variables
**/
// Create Post Number
if(!empty($numberposts)) {
  $postnums = $numberposts;
} else {
  $postnums = 10;
}

// Create Ad array
$fbadid = array();
if(!empty($facebook_ad1)) {
  $fbadid[] = $facebook_ad1;
}
if (!empty($facebook_ad2)) {
  $fbadid[] = $facebook_ad2;
}
if (!empty($facebook_ad3)) {
  $fbadid[] = $facebook_ad3;
}
if (!empty($facebook_ad4)) {
  $fbadid[] = $facebook_ad4;
}

header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<?php if(wpdev_file_check() == '1') { do_action('rss2_ns'); } ?>
<channel>
        <title><?php bloginfo_rss('name'); ?></title>
        <link><?php bloginfo_rss('url'); ?></link>
        <description><?php bloginfo_rss('description'); ?></description>
        <lastBuildDate><?php echo date('Y-m-d') . 'T' . date('G:i:s+00:00'); ?></lastBuildDate>
        <language>en</language>
        <?php // WP_Query arguments
          $args = array (
          	'post_type'              => array( 'post' ),
          	'post_status'            => array( 'publish' ),
          	'nopaging'               => false,
          	'posts_per_page'         => $postnums,
          	'ignore_sticky_posts'    => true,
          	'order'                  => 'DESC',
          	'orderby'                => 'date',
          	'meta_query'             => array(
          		array(
          			'key'       => 'wpdev_mobile_articles_instant_articles',
          			'value'     => 'instant-articles',
          		),
          	),
          );

          // The Query
          $instantarticles = new WP_Query( $args );

          // The Loop
          if ( $instantarticles->have_posts() && wpdev_file_check() == '1' ) {
          	while ( $instantarticles->have_posts() ) {
          		$instantarticles->the_post();
                global $more;
                $more = -1;
                
                  // Setup Items
                  $gettitle = get_the_title();
                  $title = apply_filters( 'the_title_rss', $gettitle );
                  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                  include plugin_dir_path(__FILE__) . 'fb-parts/fb-content-clean.php';
                  ?>
                  <item>
                        <title><?php echo $title; ?></title>
                        <link><?php echo get_permalink(); ?></link>
                        <guid><?php echo wp_get_shortlink(); ?></guid>
                        <pubDate><?php echo get_the_date('Y-m-d') . 'T' . get_the_date('G:i:s+00:00'); ?></pubDate>
                        <author><?php echo get_the_author(); ?></author>
                        <description><?php echo get_the_post_thumbnail(); ?>
                        <?php echo wp_trim_words( $cleanedcontent, 40, '...' ); ?>
                        </description>
                        <content:encoded>
                          <![CDATA[
                            <!doctype html>
                                <html lang="en" prefix="op: http://media.facebook.com/op#">
                                <head>
                                  <meta charset="utf-8">
                                  <link rel="canonical" href="<?php echo get_permalink(); ?>">
                                  <meta property="op:markup_version" content="v1.0">
                                  <meta property="fb:use_automatic_ad_placement" content="enable=true ad_density=default">
                                  <?php if(!empty($enablerecirculate)) { ?>
                                  <meta property="fb:op-recirculation-ads" content="placement_id=<?php echo $facebook_recirc; ?>">
                                  <?php } ?>
                                </head>
                                <body>
                                    <article>
                                        <header>
                                            <!-- title -->
                                                        <h1><?php echo $title; ?></h1>

                                            <!-- publication date/time -->
                                                        <time class="op-published" datetime="<?php echo get_the_date('Y-m-d') . 'T' . get_the_date('G:i:s+00:00'); ?>"><?php echo get_the_date('M j, Y, g:i a'); ?></time>

                                                    <!-- modification date/time -->
                                                        <time class="op-modified" datetime="<?php echo get_the_modified_date('Y-m-d') . 'T' . get_the_modified_date('G:i:s+00:00'); ?>"><?php echo get_the_modified_date('M j, Y, g:i a'); ?></time>

                                                    <!-- author(s) -->
                                                <address>
                                                    <a><?php echo get_the_author(); ?></a>
                                                    <?php echo get_the_author_meta('description'); ?>
                                                </address>

                                                    <!-- cover -->
                                                        <figure>
                                                    <img src="<?php echo $image[0]; ?>" />
                                                </figure>
                                            <?php if(!empty($enablefacebookads) && !empty($fbadid)) { ?>
                                                    <!-- Advertisements -->
                                                <section class="op-ad-template">
                                                  <?php if(!empty($fbadid)) {
                                                      $count = 0;
                                                      foreach($fbadid as $fbid) {
                                                        $count++;
                                                        if($count == 1) {
                                                          $addefault = ' op-ad-default';
                                                        } else {
                                                          $addefault = '';
                                                        } ?>

                                                        <figure class="op-ad<?php echo $addefault; ?>">
                                                            <iframe width="300" height="250" style="border:0; margin:0;" src="https://www.facebook.com/adnw_request?placement=<?php echo $fbid; ?>&adtype=banner300x250"></iframe>
                                                        </figure>

                                                      <?php }
                                                    }?>
                                                </section>
                                              <?php } ?>
                                            </header>
                                        <!-- body -->
                                        <?php echo $cleanedcontent; ?>
                                        <?php if(!empty($enableanalytics) && !empty($fbiaanalytics_id) || !empty($additionaltrack) && !empty($enableanalytics)) { ?>
                                          <!-- Analytics -->
                                            <figure class="op-tracker">
                                                <iframe>
                                                  <?php if(!empty($enableanalytics) && !empty($fbiaanalytics_id)) { ?>
                                                  <script>
                                                    var shareURL = ia_document.shareURL;
                                                    var srcURL = shareURL.replace(/(http.*?\?)(.*utm_source=)((.*?)(\&.*)|(.*))/g, '$4');
                                                    var medURL = shareURL.replace(/(http.*?\?)(.*utm_medium=)((.*?)(\&.*)|(.*))/g, '$4');
                                                    var camURL = shareURL.replace(/(http.*?\?)(.*utm_campaign=)((.*?)(\&.*)|(.*))/g, '$4');
                                                    var terURL = shareURL.replace(/(http.*?\?)(.*utm_term=)((.*?)(\&.*)|(.*))/g, '$4');
                                                    var conURL = shareURL.replace(/(http.*?\?)(.*utm_content=)((.*?)(\&.*)|(.*))/g, '$4');

                                                    // Check replaces
                                                    if(srcURL == 'undefined' || srcURL == 'null' || srcURL == '') {
                                                        fixsrcURL = shareURL.replace(/(http.*?\?)(.*utm_source=)((.*?)(\&.*)|(.*))/g, '$6');
                                                    } else {
                                                        fixsrcURL = srcURL;
                                                    }
                                                    if(medURL == 'undefined' || medURL == 'null' || medURL == '') {
                                                        fixmedURL = shareURL.replace(/(http.*?\?)(.*utm_medium=)((.*?)(\&.*)|(.*))/g, '$6');
                                                    } else {
                                                        fixmedURL = medURL;
                                                    }
                                                    if(camURL == 'undefined' || camURL == 'null' || camURL == '') {
                                                        fixcamURL = shareURL.replace(/(http.*?\?)(.*utm_campaign=)((.*?)(\&.*)|(.*))/g, '$6');
                                                    } else {
                                                        fixcamURL = camURL;
                                                    }
                                                    if(terURL == 'undefined' || terURL == 'null' || terURL == '') {
                                                        fixterURL = shareURL.replace(/(http.*?\?)(.*utm_term=)((.*?)(\&.*)|(.*))/g, '$6');
                                                    } else {
                                                        fixterURL = terURL;
                                                    }
                                                    if(conURL == 'undefined' || conURL == 'null' || conURL == '') {
                                                        fixconURL = shareURL.replace(/(http.*?\?)(.*utm_content=)((.*?)(\&.*)|(.*))/g, '$6');
                                                    } else {
                                                        fixconURL = conURL;
                                                    }

                                                    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                                                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                                                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                                                    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

                                                    ga('create', '<?php echo $fbiaanalytics_id; ?>', 'auto');
                                                    <?php if(!empty($analyticsgroup)) { ?>
                                                    ga('set', 'contentGroup1', 'Instant Articles');
                                                    <?php } ?>
                                                    // Output GA
                                                    if(fixsrcURL !== 'undefined' && fixsrcURL !== 'null' && fixsrcURL !== '' && fixsrcURL.indexOf('http') !== 0) {
                                                    ga('set', 'campaignSource', fixsrcURL);
                                                    }

                                                    if(fixmedURL !== 'undefined' && fixmedURL !== 'null' && fixmedURL !== '' && fixmedURL.indexOf('http') !== 0) {
                                                    ga('set', 'campaignMedium', fixmedURL);
                                                    }

                                                    if(fixcamURL !== 'undefined' && fixcamURL !== 'null' && fixcamURL !== '' && fixcamURL.indexOf('http') !== 0) {
                                                    ga('set', 'campaignName', fixcamURL);
                                                    }

                                                    if(fixterURL !== 'undefined' && fixterURL !== 'null' && fixterURL !== '' && fixterURL.indexOf('http') !== 0) {
                                                    ga('set', 'campaignKeyword', fixterURL);
                                                    }

                                                    if(fixconURL !== 'undefined' && fixconURL !== 'null' && fixconURL !== '' && fixconURL.indexOf('http') !== 0) {
                                                    ga('set', 'campaignContent', fixconURL);
                                                    }

                                                    ga('send', 'pageview');
                                                  </script>
                                                  <?php } ?>
                                                  <?php if(!empty($additionaltrack)) { ?>
                                                    <?php echo $additionaltrack; ?>
                                                  <?php } ?>
                                                </iframe>
                                            </figure>
                                            <?php } ?>
                                            <footer>
                                              <small>Copyright <?php echo date('Y'); ?> <a href="<?php echo get_bloginfo('url'); ?>"><?php echo get_bloginfo('name'); ?></a></small>
                                            </footer>
                                    </article>
                                </body>
                            </html>            ]]>
                      </content:encoded>
                </item>
          	<?php }
          } else {
          	echo 'No posts.';
          }

          // Restore original Post Data
          wp_reset_postdata(); ?>
</channel>
</rss>