<?php
// Grab Options
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' ); // Array of All Options
$footerhome = $wpdev_mobile_options['footer_home_link_7']; // Footer - Home Link
$footerabout = $wpdev_mobile_options['footer_about_link_8']; // Footer - About Link
$footercontact = $wpdev_mobile_options['footer_contact_link_9']; // Footer - Contact Link
$footerprivacy = $wpdev_mobile_options['footer_privacy_policy_link_10']; // Footer - Privacy Policy Link
$footeradvertising = $wpdev_mobile_options['footer_advertising_link_11']; // Footer - Advertising Link

// Create Output
if(!empty($footerhome)) {
  $output .= '<a href="' . $footerhome . '" target="_blank">Home</a>';
}
if(!empty($footerabout)) {
  $output .= '<a href="' . $footerabout . '" target="_blank">About</a>';
}
if(!empty($footercontact)) {
  $output .= '<a href="' . $footercontact . '" target="_blank">Contact</a>';
}
if(!empty($footerprivacy)) {
  $output .= '<a href="' . $footerprivacy . '" target="_blank">Privacy Policy</a>';
}
if(!empty($footeradvertising)) {
  $output .= '<a href="' . $footeradvertising . '" target="_blank">Advertising</a>';
} ?>
<div class="article-footer">
  <div class="article-600">
    <?php if(!empty($output)) { ?>
    <p class="article-footer-menu">
      <?php echo $output; ?>
    </p>
    <?php } ?>
    <p class="article-copyright">Copyright &copy; <?php echo date('Y'); ?> <a href="<?php echo get_bloginfo('url'); ?>"><?php echo get_bloginfo('name'); ?></a>. All Rights Reserved. Proudly Built by <a target="_blank" href="//wpdevelopers.com">WPDevelopers</a>.</p>
  </div>
</div>
