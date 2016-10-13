<?php
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' );
$taboola_ad_grab = $wpdev_mobile_options['taboola_ad_18']; // RevContent Ad

if(!empty($taboola_ad_grab)) {
  $taboolaadsplit = explode(",", $taboola_ad_grab);
  $taboolaadout = '<amp-embed width=' . $taboolaadsplit[0] . ' height=' . $taboolaadsplit[1] . ' type=taboola layout=responsive data-publisher="' . $taboolaadsplit[2] . '" data-mode="' . $taboolaadsplit[3] . '" data-placement="' . $taboolaadsplit[4] . '" data-article="' . $taboolaadsplit[5] . '"></amp-embed>'; ?>
  <div class="article-ad">
    <?php echo $taboolaadout; ?>
  </div>
  <?php
} else {
  // No ad.
} ?>
