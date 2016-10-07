<?php
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' );
$revcontent_ad_grab = $wpdev_mobile_options['revcontent_ad_18']; // RevContent Ad

if(!empty($revcontent_ad_grab)) {
  $revcontentad = explode(',', $revcontent_ad_grab);
  $revcontentout = '<amp-ad width=' . $revcontentad[0] . ' height=' . $revcontent[1] . ' type="revcontent" data-wrapper="' . $revcontent[2] . '" data-endpoint="trends.revcontent.com" data-id="' . $revcontent[3] . '"></amp-ad>'; ?>
   <div class="article-ad">
     <?php echo $revcontentout; ?>
   </div>
<?php
} else {
  // No ad.
} ?>
