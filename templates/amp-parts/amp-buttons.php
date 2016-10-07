<?php
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' ); // Array of All Options
$enablesubscribe = $wpdev_mobile_options['enable_subscribe_button_for_amp_4']; // Enable Subscribe Button for AMP
$subscribelink = $wpdev_mobile_options['subscribe_button_link_for_amp_5']; // Subscribe Button Link for AMP

if(!empty($enablesubscribe) && !empty($subscribelink)) { ?>
<!-- Subscribe Button -->
<p><a href="<?php echo $subscribelink; ?>" class="article-fullexperience" target="_blank">Subscribe to <?php echo get_bloginfo('name'); ?> Now</a></p>
<?php } else { ?>
<!-- Full Experience Button -->
<p><a href="<?php echo $url; ?>" class="article-fullexperience" target="_blank">View Full Experience</a></p>
<?php } ?>
