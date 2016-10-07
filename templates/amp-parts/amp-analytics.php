<?php
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' ); // Array of All Options
$ampanalytics = $wpdev_mobile_options['amp_google_analytics_tracking_id_19'];

if(!empty($ampanalytics)) { ?>
<amp-analytics type="googleanalytics" id="wpdev-amp-analytics">
<script type="application/json">
{
  "vars": {
    "account": "<?php echo $ampanalytics; ?>"
  },
  "triggers": {
    "trackPageview": {
      "on": "visible",
      "request": "pageview"
    }
  }
}
</script>
</amp-analytics>
<?php } else {
  // Not enabled. Don't run.
} ?>
