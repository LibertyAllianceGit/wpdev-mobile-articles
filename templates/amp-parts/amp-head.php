<?php
$wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' ); // Array of All Options
$logoimage = $wpdev_mobile_options['logo_image_0'];
$headerfont = $wpdev_mobile_options['header_font_1']; // Header Font
$bodyfont = $wpdev_mobile_options['body_font_2']; // Body Font
$highlightcolor = $wpdev_mobile_options['highlight_color_3']; // Highlight Color
$ampanalyticsscript = $wpdev_mobile_options['amp_google_analytics_tracking_id_19'];
$adsenseauto = $wpdev_mobile_options['clientid_adsense_autoplacement_for_amp_4'];

if(!empty($highlightcolor)) {
  $color = $highlightcolor;
} else {
  $color = '#333333';
}

if($headerfont == 'breeserif') {
  $headfont = 'Bree+Serif';
  $headfontcss = 'font-family: \'Bree Serif\', serif;';
} elseif($headerfont == 'lato') {
  $headfont = 'Lato:400,700';
  $headfontcss = 'font-family: \'Lato\', sans-serif;';
} elseif($headerfont == 'lora') {
  $headfont = 'Lora:400,700';
  $headfontcss = 'font-family: \'Lora\', serif;';
} elseif($headerfont == 'merriweather') {
  $headfont = 'Merriweather:400,700';
  $headfontcss = 'font-family: \'Merriweather\', serif;';
} elseif($headerfont == 'montserrat') {
  $headfont = 'Montserrat:400,700';
  $headfontcss = 'font-family: \'Montserrat\', sans-serif;';
} elseif($headerfont == 'notosans') {
  $headfont = 'Noto+Sans:400,700';
  $headfontcss = 'font-family: \'Noto Sans\', sans-serif;';
} elseif($headerfont == 'notoserif') {
  $headfont = 'Noto+Serif:400,700';
  $headfontcss = 'font-family: \'Noto Serif\', serif;';
} elseif($headerfont == 'opensans') {
  $headfont = 'Open+Sans:400,700';
  $headfontcss = 'font-family: \'Open Sans\', sans-serif;';
} elseif($headerfont == 'oswald') {
  $headfont = 'Oswald:400,700';
  $headfontcss = 'font-family: \'Oswald\', sans-serif;';
} elseif($headerfont == 'playfair') {
  $headfont = 'Playfair+Display:400,700';
  $headfontcss = 'font-family: \'Playfair Display\', serif;';
} elseif($headerfont == 'raleway') {
  $headfont = 'Raleway:400,700';
  $headfontcss = 'font-family: \'Raleway\', sans-serif;';
} elseif($headerfont == 'roboto') {
  $headfont = 'Roboto:400,700';
  $headfontcss = 'font-family: \'Roboto\', sans-serif;';
} elseif($headerfont == 'robotoslab') {
  $headfont = 'Roboto+Slab:400,700';
  $headfontcss = 'font-family: \'Roboto Slab\', serif;';
} elseif($headerfont == 'slabo') {
  $headfont = 'Slabo+27px';
  $headfontcss = 'font-family: \'Slabo\', serif;';
} elseif($headerfont == 'ubuntu') {
  $headfont = 'Ubuntu:400,700';
  $headfontcss = 'font-family: \'Ubuntu\', sans-serif;';
} elseif($headerfont == 'vollkorn') {
  $headfont = 'Vollkorn:400,700';
  $headfontcss = 'font-family: \'Vollkorn\', serif;';
}
if($headerfont == $bodyfont) {
  $headfontoutput = '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . $headfont . '">';
  $bodyfontoutput = '';
  $headcssoutput = 'body{' . $headfontcss . '}';
  $bodycssoutput = '';
} else {
  if($bodyfont == 'breeserif') {
    $headfont = 'Bree+Serif';
    $bodfontcss = 'font-family: \'Bree Serif\', serif;';
  } elseif($bodyfont == 'lato') {
    $bodfont = 'Lato:400,700';
    $bodfontcss = 'font-family: \'Lato\', sans-serif;';
  } elseif($bodyfont == 'lora') {
    $bodfont = 'Lora:400,700';
    $bodfontcss = 'font-family: \'Lora\', serif;';
  } elseif($bodyfont == 'merriweather') {
    $bodfont = 'Merriweather:400,700';
    $bodfontcss = 'font-family: \'Merriweather\', serif;';
  } elseif($bodyfont == 'montserrat') {
    $bodfont = 'Montserrat:400,700';
    $bodfontcss = 'font-family: \'Montserrat\', sans-serif;';
  } elseif($bodyfont == 'notosans') {
    $bodfont = 'Noto+Sans:400,700';
    $bodfontcss = 'font-family: \'Noto Sans\', sans-serif;';
  } elseif($bodyfont == 'notoserif') {
    $bodfont = 'Noto+Serif:400,700';
    $bodfontcss = 'font-family: \'Noto Serif\', serif;';
  } elseif($bodyfont == 'opensans') {
    $bodfont = 'Open+Sans:400,700';
    $bodfontcss = 'font-family: \'Open Sans\', sans-serif;';
  } elseif($bodyfont == 'oswald') {
    $bodfont = 'Oswald:400,700';
    $bodfontcss = 'font-family: \'Oswald\', sans-serif;';
  } elseif($bodyfont == 'playfair') {
    $bodfont = 'Playfair+Display:400,700';
    $bodfontcss = 'font-family: \'Playfair Display\', serif;';
  } elseif($bodyfont == 'raleway') {
    $bodfont = 'Raleway:400,700';
    $bodfontcss = 'font-family: \'Raleway\', sans-serif;';
  } elseif($bodyfont == 'roboto') {
    $bodfont = 'Roboto:400,700';
    $bodfontcss = 'font-family: \'Roboto\', sans-serif;';
  } elseif($bodyfont == 'robotoslab') {
    $bodfont = 'Roboto+Slab:400,700';
    $bodfontcss = 'font-family: \'Roboto Slab\', serif;';
  } elseif($bodyfont == 'slabo') {
    $bodfont = 'Slabo+27px';
    $bodfontcss = 'font-family: \'Slabo\', serif;';
  } elseif($bodyfont == 'ubuntu') {
    $bodfont = 'Ubuntu:400,700';
    $bodfontcss = 'font-family: \'Ubuntu\', sans-serif;';
  } elseif($bodyfont == 'vollkorn') {
    $bodfont = 'Vollkorn:400,700';
    $bodfontcss = 'font-family: \'Vollkorn\', serif;';
  }
  $headfontoutput = '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . $headfont . '">';
  $bodyfontoutput = '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . $bodfont . '">';
  $headcssoutput = 'h1,h2,h3,h4,h5,h6{' . $headfontcss . '}';
  $bodycssoutput = 'body{' . $bodfontcss . '}';
}
?>
<meta charset="utf-8">
<title><?php echo $title; ?></title>
<link rel="canonical" href="<?php echo $url; ?>" />
<?php echo $headfontoutput; ?>
<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo $url; ?>"
  },
  "headline": "<?php echo $title; ?>",
  "image": {
    "@type": "ImageObject",
    "url": "<?php echo $image[0]; ?>",
    "height": 406,
    "width": 696
  },
  "datePublished": "<?php echo $postpub; ?>",
  "dateModified": "<?php echo $postmod; ?>",
  "author": {
    "@type": "Person",
    "name": "<?php echo $author; ?>"
  },
  "publisher": {
    "@type": "Organization",
    "name": "<?php echo $site; ?>",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo $logoimage; ?>",
      "width": 600,
      "height": 60
    }
  },
  "description": "<?php echo $excerpt; ?>"
}
</script>
<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
<style amp-custom><?php echo $bodycssoutput . $headcssoutput; ?>div.article-600{max-width: 600px; margin: 0 auto; width: auto; padding: 0 10px;}nav.title-bar {text-align:center; padding: 0.5rem 0;}ul.article-share-container li {display: inline-block;} ul.article-share-container {padding: 0;}.article-title h1 {margin-bottom: 0;line-height: 2rem;}figcaption.wp-caption-text {text-align: center;font-size: 0.8rem;color: #7e7e7e;margin-top: -0.9rem;}p.wpdev-article-meta {font-size: 0.9rem;font-weight: bold;}p.wpdev-article-meta span {color: #a4a4a4;}p.wpdev-article-meta a {text-decoration: none;color: <?php echo $color; ?>;}.article-footer {background: #333;color: #fff;font-size: 0.9rem;text-align: center;padding: 1.5rem 0;}.article-content {margin-bottom: 2rem;}.article-footer a {color: <?php echo $color; ?>;text-decoration: none;font-weight: bold;}p.article-footer-menu a {padding: 0 0.5rem;text-transform: uppercase; font-size: 1rem;}blockquote {background: #f5f5f5;margin: 0.5rem 0;padding: 0.5rem 2rem 1rem;border-left: 1px solid <?php echo $color; ?>;}h3.article-share-head {margin: 0;display: inline-block;vertical-align: sub;margin-right: 0.5rem;color: <?php echo $color; ?>;}.article-share-bottom ul.article-share-container {margin: 0;display: inline-block;vertical-align: text-top;}.article-share.article-share-bottom {background: whitesmoke;padding: 1rem 1rem 0.5rem 1rem;margin: 1rem 0;border-left: 1px solid <?php echo $color; ?>;}.article-ad {text-align: center;}h1.article-site-title {font-size: 2rem;text-decoration: inherit;color: <?php echo $color; ?>;}a.article-fullexperience {border: 1px solid <?php echo $color; ?>;color: #000;text-decoration: none;font-weight: bold;font-size: 1.1rem;width: 100%;display: block;text-align: center;padding: 0.6rem 0;}.article-related {background: #f5f5f5;padding: 1.5rem;}.article-related-single.half .article-related-single-img {display: inline-block;width: 31%;margin-right: 1rem;}.article-related-single.half {display: block;position: relative;}.article-related-single.half .article-related-single-title {display: inline-block;width: 61%;vertical-align: top;}.article-related-single-title h3 {margin: 0;}.article-related-single {background: #fff;margin: 0.5rem 0;padding: 1rem;}.article-related-single-title a {text-decoration: none;color: <?php echo $color; ?>;font-weight: bold;}h2.article-related-section {margin: 0;}</style>
<script async src="https://cdn.ampproject.org/v0.js"></script>
<script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>
<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
<?php if(!empty($ampanalyticsscript)) { ?>
<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
<?php }
      if(!empty($adsenseauto)) { ?>
<script async custom-element="amp-auto-ads" src="https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js"></script>    
<?php }?>
<script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>
<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
