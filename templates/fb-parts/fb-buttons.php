<?php
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' ); // Array of All Options
$subscribelink = $wpdev_mobile_options['subscribe_button_link_for_fbia_30']; // Subscribe Button Link for FBIA
if(!empty($subscribelink)) { ?>
<!-- Subscribe Button -->
<p><a href="<?php echo $subscribelink; ?>" target="_blank" style="border: 1px solid black; color: #000; text-decoration: none; font-weight: bold; font-size: 1rem; width: 100%; display: block; text-align: center; padding: 0.6rem;">Subscribe to <?php echo get_bloginfo('name'); ?> Now!</a></p>
<?php } ?>
