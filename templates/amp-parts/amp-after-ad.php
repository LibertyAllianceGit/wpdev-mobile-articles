<?php
// Setup Ads
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' ); // Array of All Options
$dfp_ad2_grab = $wpdev_mobile_options['dfp_ad_2_13']; // DFP Ad 2
$dfp_ad3_grab = $wpdev_mobile_options['dfp_ad_3_14']; // DFP Ad 3
$adsense_ad1_grab = $wpdev_mobile_options['adsense_ad_1_15']; // AdSense Ad 1
$adsense_ad2_grab = $wpdev_mobile_options['adsense_ad_2_16']; // AdSense Ad 2
$adsense_ad3_grab = $wpdev_mobile_options['adsense_ad_3_17']; // AdSense Ad 3

// Parse Ads - DFP
if(!empty($dfp_ad2_grab)) {
$dfp_ad2 = explode(',',$dfp_ad2_grab);
$dfpad2_out = '<amp-ad width=' . $dfp_ad2[0] . ' height=' . $dfp_ad2[1] . ' type="doubleclick" data-slot="' . $dfp_ad2[2] . '"></amp-ad>';
}
if(!empty($dfp_ad3_grab)) {
$dfp_ad3 = explode(',',$dfp_ad3_grab);
$dfpad3_out = '<amp-ad width=' . $dfp_ad3[0] . ' height=' . $dfp_ad3[1] . ' type="doubleclick" data-slot="' . $dfp_ad3[2] . '"></amp-ad>';
}
// Parse Ads - AdSense
if(!empty($adsense_ad1_grab)) {
$adsense_ad1 = explode(',',$adsense_ad1_grab);
$adsensead1_out = '<amp-ad width=' . $adsense_ad1[0] . ' height=' . $adsense_ad1[1] . ' type="adsense" data-ad-client="' . $adsense_ad1[2] . '" data-ad-slot="' . $adsense_ad1[3] . '"></amp-ad>';
}
if(!empty($adsense_ad2_grab)) {
$adsense_ad2 = explode(',',$adsense_ad2_grab);
$adsensead2_out = '<amp-ad width=' . $adsense_ad2[0] . ' height=' . $adsense_ad2[1] . ' type="adsense" data-ad-client="' . $adsense_ad2[2] . '" data-ad-slot="' . $adsense_ad2[3] . '"></amp-ad>';
}
if(!empty($adsense_ad3_grab)) {
$adsense_ad3 = explode(',',$adsense_ad3_grab);
$adsensead3_out = '<amp-ad width=' . $adsense_ad3[0] . ' height=' . $adsense_ad3[1] . ' type="adsense" data-ad-client="' . $adsense_ad3[2] . '" data-ad-slot="' . $adsense_ad3[3] . '"></amp-ad>';
}

if ($i < 15 && !empty($adsense_ad3)) { echo $adsense_ad3; }
elseif ($i < 12 && !empty($dfpad3_out)) { echo $dfpad3_out; }
elseif ($i < 9 && !empty($adsense_ad2)) { echo $adsense_ad2; }
elseif ($i < 6 && !empty($dfpad2_out)) { echo $dfpad2_out; }
elseif ($i < 3 && !empty($adsense_ad1)) { echo $adsense_ad1; } ?>
