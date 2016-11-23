<?php
/*
Plugin Name: WP Developers | Mobile Articles
Plugin URI: http://wpdevelopers.com
Description: Take advantage of Facebook's Instant Articles and Google's Accelerated Mobile Pages.
Version: 1.3.1
Author: Tyler Johnson
Author URI: http://tylerjohnsondesign.com/
Copyright: Tyler Johnson
Text Domain: wpdevfbmobile
Copyright 2016 WP Developers. All Rights Reserved.
*/

/**
Plugin Update
**/
require 'plugin-update-checker/plugin-update-checker.php';
$className = PucFactory::getLatestClassVersion('PucGitHubChecker');
$myUpdateChecker = new $className(
    'https://github.com/LibertyAllianceGit/wpdev-mobile-articles',
    __FILE__,
    'master'
);

/**
Plugin Activation & Deactivation
**/

// Create New RSS Feed for Instant Articles
function wpdev_mobile_articles__feed() {
    add_feed('instant', 'wpdev_mobile_articles__feed_template');
}
add_action('init', 'wpdev_mobile_articles__feed');

// Feed Template
function wpdev_mobile_articles__feed_template() {
    include (plugin_dir_path(__FILE__) . 'templates/wpdev-mobile-articles-fb-instant.php');
}

// On Activation Flush Permalinks
function wpdev_mobile_articles__activate() {
  // Trigger Feed Creation
  wpdev_mobile_articles__feed();

  // Add AMP Endpoint
  add_rewrite_endpoint('amp', EP_PERMALINK);

  // Trigger Permalink Flush
  flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'wpdev_mobile_articles__activate');

// On Deactivation Flush Permalinks
function wpdev_mobile_articles__deactivate() {
    // Trigger Permalink Flush
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'wpdev_mobile_articles__deactivate');


/**
Plugin Endpoint Creation
**/

// Create Query Variable for AMP Endpoint
function wpdev_mobile_articles_query_vars($vars) {
  $vars[] = "amp";
  return $vars;
}
add_filter('query_vars', 'wpdev_mobile_articles_query_vars');

// Create Template for AMP
function wpdev_mobile_articles_amp_template($templates = "") {
    global $wp_query;
    if(!isset($wp_query->query['amp']))
        return $templates;

    if(!is_array($templates) && !empty($templates)) {
        $templates = plugin_dir_path( __FILE__ ) . 'templates/wpdev-mobile-articles-amp.php';
    }
    elseif(empty($templates)) {
        $templates = plugin_dir_path( __FILE__ ) . 'templates/wpdev-mobile-articles-amp.php';
    }
    else {
        $new_template = locate_template(array("test.php"));
        if(!empty($new_template)) array_unshift($templates,$new_template);
    }
    return $templates;
}
add_filter( 'single_template', 'wpdev_mobile_articles_amp_template' );

// Add AMP Header Code
function wpdev_mobile_articles_amp_header() {
  if(is_singular('post')) {
    $postid = get_the_id();
    $getmeta = get_post_meta($postid, 'wpdev_mobile_articles_amp', true);
    if($getmeta == 'amp') {
      echo '<link rel="amphtml" href="' . get_permalink() . 'amp">
      ';
    } else {
      // Nothing. It's not enabled for Google AMP, so we won't let them know.
    }
  }
}
add_action('wp_head', 'wpdev_mobile_articles_amp_header', 2);

/**
Enqueue Plugin Files
**/
function wpdev_mobile_articles_files() {
        wp_enqueue_style( 'wpdev-mobile-articles-admin-css', plugin_dir_url(__FILE__) . 'inc/wpdev-mobile-articles-admin.css' );
        wp_enqueue_script( 'wpdev-mobile-articles-admin-js', plugin_dir_url(__FILE__) . 'inc/wpdev-mobile-articles-admin.js', array('jquery'), '1.0.0', true );
}
add_action('admin_enqueue_scripts', 'wpdev_mobile_articles_files', 20);


/**
Create Meta Box
**/

// Get Meta Data for Post
function wpdev_mobile_articles_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

// Add the Meta Box
function wpdev_mobile_articles_add_meta_box() {
	add_meta_box(
		'wpdev_mobile_articles-wpdev-mobile-articles',
		__( 'Mobile Articles', 'wpdev_mobile_articles' ),
		'wpdev_mobile_articles_html',
		'post',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wpdev_mobile_articles_add_meta_box' );

// Create Meta Box HTML
function wpdev_mobile_articles_html( $post) {
	wp_nonce_field( '_wpdev_mobile_articles_nonce', 'wpdev_mobile_articles_nonce' ); ?>

	<p>Enable the article for Facebook Instant Articles and/or Google Accelerated Mobile Pages.</p>
	<p>
		<input type="checkbox" name="wpdev_mobile_articles_instant_articles" id="wpdev_mobile_articles_instant_articles" value="instant-articles" <?php echo ( wpdev_mobile_articles_get_meta( 'wpdev_mobile_articles_instant_articles' ) === 'instant-articles' ) ? 'checked' : ''; ?>>
		<label for="wpdev_mobile_articles_instant_articles"><?php _e( 'Facebook Instant Articles', 'wpdev_mobile_articles' ); ?></label>	</p>	<p>

		<input type="checkbox" name="wpdev_mobile_articles_amp" id="wpdev_mobile_articles_amp" value="amp" <?php echo ( wpdev_mobile_articles_get_meta( 'wpdev_mobile_articles_amp' ) === 'amp' ) ? 'checked' : ''; ?>>
		<label for="wpdev_mobile_articles_amp"><?php _e( 'Google AMP', 'wpdev_mobile_articles' ); ?></label>	</p><?php
}

// Save Meta Box Information
function wpdev_mobile_articles_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['wpdev_mobile_articles_nonce'] ) || ! wp_verify_nonce( $_POST['wpdev_mobile_articles_nonce'], '_wpdev_mobile_articles_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['wpdev_mobile_articles_instant_articles'] ) )
		update_post_meta( $post_id, 'wpdev_mobile_articles_instant_articles', esc_attr( $_POST['wpdev_mobile_articles_instant_articles'] ) );
	else
		update_post_meta( $post_id, 'wpdev_mobile_articles_instant_articles', null );
	if ( isset( $_POST['wpdev_mobile_articles_amp'] ) )
		update_post_meta( $post_id, 'wpdev_mobile_articles_amp', esc_attr( $_POST['wpdev_mobile_articles_amp'] ) );
	else
		update_post_meta( $post_id, 'wpdev_mobile_articles_amp', null );
}
add_action( 'save_post', 'wpdev_mobile_articles_save' );


/**
Create Post List Column
**/
// ADD NEW COLUMN
function wpdev_mobile_columns_head($defaults) {
    $defaults['mobile'] = 'Mobile';
    return $defaults;
}
add_filter('manage_posts_columns', 'wpdev_mobile_columns_head');

// SHOW THE FEATURED IMAGE
function wpdev_mobile_columns_content($column_name, $post_ID) {
    if ($column_name == 'mobile') {
        $fbenabled = get_post_meta( $post_ID, 'wpdev_mobile_articles_instant_articles', true );
        $ampenabled = get_post_meta( $post_ID, 'wpdev_mobile_articles_amp', true );
        if($fbenabled == 'instant-articles' && $ampenabled == 'amp') {
          $fbclass = ' wpdev-mobile-facebook-active';
          $ampclass = ' wpdev-mobile-amp-active';
        } elseif($fbenabled == 'instant-article' && empty($ampenabled)) {
          $fbclass = ' wpdev-mobile-facebook-active';
          $ampclass = '';
        } elseif($ampenabled == 'instant-article' && empty($fbenabled)) {
          $fbclass = '';
          $ampclass = ' wpdev-mobile-amp-active';
        } else {
          $fbclass = '';
          $ampclass = '';
        }

        echo '<span class="wpdev-mobile-dot wpdev-mobile-facebook-dot' . $fbclass . '">&middot;</span><span class="wpdev-mobile-dot wpdev-mobile-amp-dot' . $ampclass . '">&middot;</span>';
    }
}
add_action('manage_posts_custom_column', 'wpdev_mobile_columns_content', 10, 2);

// Add Styles
function wpdev_mobile_column_width() {
    echo '<style type="text/css">';
    echo 'span.wpdev-mobile-dot{font-size:98px;color:rgba(51,51,51,.25);line-height:0;vertical-align:middle}.wpdev-mobile-facebook-active{color:#3b5998!important}.wpdev-mobile-amp-active{color:#0379C4!important}td.mobile.column-mobile,th#mobile{max-width:50px!important;min-width:51px!important;width:50px;text-align:center;overflow:hidden}@media screen and (max-width:782px){span.wpdev-mobile-dot{vertical-align:top}td.mobile.column-mobile,th#mobile{text-align:left}}';
    echo '</style>';
}
add_action('admin_head', 'wpdev_mobile_column_width');

/**
AMP Rendering
**/

// Set Crop to Work for Small Images
function wpdev_mobile_article_thumbnail_crop_fix($default, $orig_w, $orig_h, $new_w, $new_h, $crop){
    if (!$crop) return null; // let the wordpress default function handle this

    $aspect_ratio = $orig_w / $orig_h;
    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);

    $s_x = floor(($orig_w - $crop_w) / 2);
    $s_y = floor(($orig_h - $crop_h) / 2);

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}
add_filter('image_resize_dimensions', 'wpdev_mobile_article_thumbnail_crop_fix', 10, 6);

// Add AMP Image Size
add_image_size('wpdev_mobile_amp', 696, 406, true);

/**
Options Page
**/
class WPDevMobile {
	private $wpdev_mobile_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpdev_mobile_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'wpdev_mobile_page_init' ) );
	}

	public function wpdev_mobile_add_plugin_page() {
		add_menu_page(
			'WPDev Mobile', // page_title
			'WPDev Mobile', // menu_title
			'manage_options', // capability
			'wpdev-mobile', // menu_slug
			array( $this, 'wpdev_mobile_create_admin_page' ), // function
			'dashicons-smartphone', // icon_url
			100 // position
		);
	}

	public function wpdev_mobile_create_admin_page() {
		$this->wpdev_mobile_options = get_option( 'wpdev_mobile_option_name' ); ?>

		<div class="wrap wpdev-mobile-admin">
			<h2><img src="<?php echo plugin_dir_url(__FILE__) . 'inc/wpdevelopers-mobile-logo.png'; ?>" alt="WPDevelopers Mobile Articles"/></h2>
			<p>Easily publish, monetize, and manage your content for Google AMP and Facebook Instant Articles directly from WordPress, with support for Google Analytics. You can access your AMP articles by adding /amp at the end of any article URL. You can also access your Instant Articles RSS feed by going <a href="<?php echo get_bloginfo('url'); ?>/feed/instant" target="_blank">here</a>.</p>
      <button class="wpdev-button-amp">AMP Settings</button><button class="wpdev-button-fbia">FBIA Settings</button>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'wpdev_mobile_option_group' );
					do_settings_sections( 'wpdev-mobile-admin' );
					submit_button();
				?>
			</form>
      <p class="wpdev-mobile-copyright">Copyright &copy; <?php echo date('Y'); ?> <a href="//wpdevelopers.com" target="_blank">WPDevelopers</a>.</p>
		</div>
	<?php }

	public function wpdev_mobile_page_init() {
		register_setting(
			'wpdev_mobile_option_group', // option_group
			'wpdev_mobile_option_name', // option_name
			array( $this, 'wpdev_mobile_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'wpdev_mobile_amp_settings_section', // id
			'AMP Settings', // title
			array( $this, 'wpdev_mobile_section_info' ), // callback
			'wpdev-mobile-admin' // page
		);

		add_settings_field(
			'logo_image_0', // id
			'Logo Image', // title
			array( $this, 'logo_image_0_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'header_font_1', // id
			'Header Font', // title
			array( $this, 'header_font_1_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'body_font_2', // id
			'Body Font', // title
			array( $this, 'body_font_2_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'highlight_color_3', // id
			'Highlight Color', // title
			array( $this, 'highlight_color_3_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'enable_subscribe_button_for_amp_4', // id
			'Enable Subscribe Button for AMP', // title
			array( $this, 'enable_subscribe_button_for_amp_4_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'subscribe_button_link_for_amp_5', // id
			'Subscribe Button Link for AMP', // title
			array( $this, 'subscribe_button_link_for_amp_5_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'footer_articles_section_6', // id
			'Footer Articles Section', // title
			array( $this, 'footer_articles_section_6_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'footer_home_link_7', // id
			'Footer - Home Link', // title
			array( $this, 'footer_home_link_7_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'footer_about_link_8', // id
			'Footer - About Link', // title
			array( $this, 'footer_about_link_8_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'footer_contact_link_9', // id
			'Footer - Contact Link', // title
			array( $this, 'footer_contact_link_9_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'footer_privacy_policy_link_10', // id
			'Footer - Privacy Policy Link', // title
			array( $this, 'footer_privacy_policy_link_10_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'footer_advertising_link_11', // id
			'Footer - Advertising Link', // title
			array( $this, 'footer_advertising_link_11_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'dfp_ad_1_12', // id
			'DFP Ad 1', // title
			array( $this, 'dfp_ad_1_12_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'dfp_ad_2_13', // id
			'DFP Ad 2', // title
			array( $this, 'dfp_ad_2_13_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'dfp_ad_3_14', // id
			'DFP Ad 3', // title
			array( $this, 'dfp_ad_3_14_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'adsense_ad_1_15', // id
			'AdSense Ad 1', // title
			array( $this, 'adsense_ad_1_15_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'adsense_ad_2_16', // id
			'AdSense Ad 2', // title
			array( $this, 'adsense_ad_2_16_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'adsense_ad_3_17', // id
			'AdSense Ad 3', // title
			array( $this, 'adsense_ad_3_17_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'revcontent_ad_18', // id
			'RevContent Ad', // title
			array( $this, 'revcontent_ad_18_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

    add_settings_field(
			'taboola_ad_18', // id
			'Taboola Ad', // title
			array( $this, 'taboola_ad_18_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

		add_settings_field(
			'amp_google_analytics_tracking_id_19', // id
			'AMP Google Analytics Tracking ID', // title
			array( $this, 'amp_google_analytics_tracking_id_19_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_amp_settings_section' // section
		);

    add_settings_section(
			'wpdev_mobile_fbia_settings_section', // id
			'Facebook Settings', // title
			array( $this, 'wpdev_mobile_section_info' ), // callback
			'wpdev-mobile-admin' // page
		);

		add_settings_field(
			'number_of_posts_20', // id
			'Number of Posts', // title
			array( $this, 'number_of_posts_20_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'enable_facebook_ads_21', // id
			'Enable Facebook Ads', // title
			array( $this, 'enable_facebook_ads_21_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'facebook_ad_placement_id_1_22', // id
			'Facebook Ad Placement ID 1', // title
			array( $this, 'facebook_ad_placement_id_1_22_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'facebook_ad_placement_id_2_23', // id
			'Facebook Ad Placement ID 2', // title
			array( $this, 'facebook_ad_placement_id_2_23_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'facebook_ad_placement_id_3_24', // id
			'Facebook Ad Placement ID 3', // title
			array( $this, 'facebook_ad_placement_id_3_24_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'facebook_ad_placement_id_4_25', // id
			'Facebook Ad Placement ID 4', // title
			array( $this, 'facebook_ad_placement_id_4_25_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'enable_google_analytics_tracking_26', // id
			'Enable Google Analytics Tracking', // title
			array( $this, 'enable_google_analytics_tracking_26_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'fbia_google_analytics_tracking_id_27', // id
			'FBIA Google Analytics Tracking ID', // title
			array( $this, 'fbia_google_analytics_tracking_id_27_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'enable_google_analytics_group_tracking_28', // id
			'Enable Google Analytics Group Tracking', // title
			array( $this, 'enable_google_analytics_group_tracking_28_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'enable_subscribe_button_for_fbia_29', // id
			'Enable Subscribe Button for FBIA', // title
			array( $this, 'enable_subscribe_button_for_fbia_29_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);

		add_settings_field(
			'subscribe_button_link_for_fbia_30', // id
			'Subscribe Button Link for FBIA', // title
			array( $this, 'subscribe_button_link_for_fbia_30_callback' ), // callback
			'wpdev-mobile-admin', // page
			'wpdev_mobile_fbia_settings_section' // section
		);
	}

	public function wpdev_mobile_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['logo_image_0'] ) ) {
			$sanitary_values['logo_image_0'] = sanitize_text_field( $input['logo_image_0'] );
		}

		if ( isset( $input['header_font_1'] ) ) {
			$sanitary_values['header_font_1'] = $input['header_font_1'];
		}

		if ( isset( $input['body_font_2'] ) ) {
			$sanitary_values['body_font_2'] = $input['body_font_2'];
		}

		if ( isset( $input['highlight_color_3'] ) ) {
			$sanitary_values['highlight_color_3'] = sanitize_text_field( $input['highlight_color_3'] );
		}

		if ( isset( $input['enable_subscribe_button_for_amp_4'] ) ) {
			$sanitary_values['enable_subscribe_button_for_amp_4'] = $input['enable_subscribe_button_for_amp_4'];
		}

		if ( isset( $input['subscribe_button_link_for_amp_5'] ) ) {
			$sanitary_values['subscribe_button_link_for_amp_5'] = sanitize_text_field( $input['subscribe_button_link_for_amp_5'] );
		}

		if ( isset( $input['footer_articles_section_6'] ) ) {
			$sanitary_values['footer_articles_section_6'] = $input['footer_articles_section_6'];
		}

		if ( isset( $input['footer_home_link_7'] ) ) {
			$sanitary_values['footer_home_link_7'] = sanitize_text_field( $input['footer_home_link_7'] );
		}

		if ( isset( $input['footer_about_link_8'] ) ) {
			$sanitary_values['footer_about_link_8'] = sanitize_text_field( $input['footer_about_link_8'] );
		}

		if ( isset( $input['footer_contact_link_9'] ) ) {
			$sanitary_values['footer_contact_link_9'] = sanitize_text_field( $input['footer_contact_link_9'] );
		}

		if ( isset( $input['footer_privacy_policy_link_10'] ) ) {
			$sanitary_values['footer_privacy_policy_link_10'] = sanitize_text_field( $input['footer_privacy_policy_link_10'] );
		}

		if ( isset( $input['footer_advertising_link_11'] ) ) {
			$sanitary_values['footer_advertising_link_11'] = sanitize_text_field( $input['footer_advertising_link_11'] );
		}

		if ( isset( $input['dfp_ad_1_12'] ) ) {
			$sanitary_values['dfp_ad_1_12'] = sanitize_text_field( $input['dfp_ad_1_12'] );
		}

		if ( isset( $input['dfp_ad_2_13'] ) ) {
			$sanitary_values['dfp_ad_2_13'] = sanitize_text_field( $input['dfp_ad_2_13'] );
		}

		if ( isset( $input['dfp_ad_3_14'] ) ) {
			$sanitary_values['dfp_ad_3_14'] = sanitize_text_field( $input['dfp_ad_3_14'] );
		}

		if ( isset( $input['adsense_ad_1_15'] ) ) {
			$sanitary_values['adsense_ad_1_15'] = sanitize_text_field( $input['adsense_ad_1_15'] );
		}

		if ( isset( $input['adsense_ad_2_16'] ) ) {
			$sanitary_values['adsense_ad_2_16'] = sanitize_text_field( $input['adsense_ad_2_16'] );
		}

		if ( isset( $input['adsense_ad_3_17'] ) ) {
			$sanitary_values['adsense_ad_3_17'] = sanitize_text_field( $input['adsense_ad_3_17'] );
		}

		if ( isset( $input['revcontent_ad_18'] ) ) {
			$sanitary_values['revcontent_ad_18'] = sanitize_text_field( $input['revcontent_ad_18'] );
		}

    if ( isset( $input['taboola_ad_18'] ) ) {
			$sanitary_values['taboola_ad_18'] = sanitize_text_field( $input['taboola_ad_18'] );
		}

		if ( isset( $input['amp_google_analytics_tracking_id_19'] ) ) {
			$sanitary_values['amp_google_analytics_tracking_id_19'] = sanitize_text_field( $input['amp_google_analytics_tracking_id_19'] );
		}

		if ( isset( $input['number_of_posts_20'] ) ) {
			$sanitary_values['number_of_posts_20'] = sanitize_text_field( $input['number_of_posts_20'] );
		}

		if ( isset( $input['enable_facebook_ads_21'] ) ) {
			$sanitary_values['enable_facebook_ads_21'] = $input['enable_facebook_ads_21'];
		}

		if ( isset( $input['facebook_ad_placement_id_1_22'] ) ) {
			$sanitary_values['facebook_ad_placement_id_1_22'] = sanitize_text_field( $input['facebook_ad_placement_id_1_22'] );
		}

		if ( isset( $input['facebook_ad_placement_id_2_23'] ) ) {
			$sanitary_values['facebook_ad_placement_id_2_23'] = sanitize_text_field( $input['facebook_ad_placement_id_2_23'] );
		}

		if ( isset( $input['facebook_ad_placement_id_3_24'] ) ) {
			$sanitary_values['facebook_ad_placement_id_3_24'] = sanitize_text_field( $input['facebook_ad_placement_id_3_24'] );
		}

		if ( isset( $input['facebook_ad_placement_id_4_25'] ) ) {
			$sanitary_values['facebook_ad_placement_id_4_25'] = sanitize_text_field( $input['facebook_ad_placement_id_4_25'] );
		}

		if ( isset( $input['enable_google_analytics_tracking_26'] ) ) {
			$sanitary_values['enable_google_analytics_tracking_26'] = $input['enable_google_analytics_tracking_26'];
		}

		if ( isset( $input['fbia_google_analytics_tracking_id_27'] ) ) {
			$sanitary_values['fbia_google_analytics_tracking_id_27'] = sanitize_text_field( $input['fbia_google_analytics_tracking_id_27'] );
		}

		if ( isset( $input['enable_google_analytics_group_tracking_28'] ) ) {
			$sanitary_values['enable_google_analytics_group_tracking_28'] = $input['enable_google_analytics_group_tracking_28'];
		}

		if ( isset( $input['enable_subscribe_button_for_fbia_29'] ) ) {
			$sanitary_values['enable_subscribe_button_for_fbia_29'] = $input['enable_subscribe_button_for_fbia_29'];
		}

		if ( isset( $input['subscribe_button_link_for_fbia_30'] ) ) {
			$sanitary_values['subscribe_button_link_for_fbia_30'] = sanitize_text_field( $input['subscribe_button_link_for_fbia_30'] );
		}

		return $sanitary_values;
	}

	public function wpdev_mobile_section_info() {

	}

	public function logo_image_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[logo_image_0]" id="logo_image_0" value="%s"><label for="logo_image_0">Add the URL for the site logo. Logo size must be 60px high by 600px wide. This is a requirement by Google.</label>',
			isset( $this->wpdev_mobile_options['logo_image_0'] ) ? esc_attr( $this->wpdev_mobile_options['logo_image_0']) : ''
		);
	}

	public function header_font_1_callback() {
		?> <select name="wpdev_mobile_option_name[header_font_1]" id="header_font_1">
      <?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'montserrat') ? 'selected' : '' ; ?>
			<option value="montserrat" <?php echo $selected; ?>>Montserrat - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'breeserif') ? 'selected' : '' ; ?>
			<option value="breeserif" <?php echo $selected; ?>>Bree - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'lato') ? 'selected' : '' ; ?>
			<option value="lato" <?php echo $selected; ?>>Lato - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'lora') ? 'selected' : '' ; ?>
			<option value="lora" <?php echo $selected; ?>>Lora - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'merriweather') ? 'selected' : '' ; ?>
			<option value="merriweather" <?php echo $selected; ?>>Merriweather - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'notosans') ? 'selected' : '' ; ?>
			<option value="notosans" <?php echo $selected; ?>>Noto - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'notoserif') ? 'selected' : '' ; ?>
			<option value="notoserif" <?php echo $selected; ?>>Noto - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'opensans') ? 'selected' : '' ; ?>
			<option value="opensans" <?php echo $selected; ?>>Open - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'oswald') ? 'selected' : '' ; ?>
			<option value="oswald" <?php echo $selected; ?>>Oswald - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'playfair') ? 'selected' : '' ; ?>
			<option value="playfair" <?php echo $selected; ?>>Playfair Display - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'raleway') ? 'selected' : '' ; ?>
			<option value="raleway" <?php echo $selected; ?>>Raleway - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'roboto') ? 'selected' : '' ; ?>
			<option value="roboto" <?php echo $selected; ?>>Roboto - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'robotoslab') ? 'selected' : '' ; ?>
			<option value="robotoslab" <?php echo $selected; ?>>Roboto Slab - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'slabo') ? 'selected' : '' ; ?>
			<option value="slabo" <?php echo $selected; ?>>Slabo - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'ubuntu') ? 'selected' : '' ; ?>
			<option value="ubuntu" <?php echo $selected; ?>>Ubuntu - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['header_font_1'] ) && $this->wpdev_mobile_options['header_font_1'] === 'vollkorn') ? 'selected' : '' ; ?>
			<option value="vollkorn" <?php echo $selected; ?>>Vollkorn - Serif</option>
		</select><label for="header_font_1">Select a header font.</label> <?php
	}

	public function body_font_2_callback() {
		?> <select name="wpdev_mobile_option_name[body_font_2]" id="body_font_2">
      <?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'opensans') ? 'selected' : '' ; ?>
			<option value="opensans" <?php echo $selected; ?>>Open - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'breeserif') ? 'selected' : '' ; ?>
			<option value="breeserif" <?php echo $selected; ?>>Bree - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'lato') ? 'selected' : '' ; ?>
			<option value="lato" <?php echo $selected; ?>>Lato - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'lora') ? 'selected' : '' ; ?>
			<option value="lora" <?php echo $selected; ?>>Lora - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'merriweather') ? 'selected' : '' ; ?>
			<option value="merriweather" <?php echo $selected; ?>>Merriweather - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'montserrat') ? 'selected' : '' ; ?>
			<option value="montserrat" <?php echo $selected; ?>>Montserrat - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'notosans') ? 'selected' : '' ; ?>
			<option value="notosans" <?php echo $selected; ?>>Noto - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'notoserif') ? 'selected' : '' ; ?>
			<option value="notoserif" <?php echo $selected; ?>>Noto - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'oswald') ? 'selected' : '' ; ?>
			<option value="oswald" <?php echo $selected; ?>>Oswald - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'playfair') ? 'selected' : '' ; ?>
			<option value="playfair" <?php echo $selected; ?>>Playfair Display - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'raleway') ? 'selected' : '' ; ?>
			<option value="raleway" <?php echo $selected; ?>>Raleway - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'roboto') ? 'selected' : '' ; ?>
			<option value="roboto" <?php echo $selected; ?>>Roboto - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'robotoslab') ? 'selected' : '' ; ?>
			<option value="robotoslab" <?php echo $selected; ?>>Roboto Slab - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'slabo') ? 'selected' : '' ; ?>
			<option value="slabo" <?php echo $selected; ?>>Slabo - Serif</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'ubuntu') ? 'selected' : '' ; ?>
			<option value="ubuntu" <?php echo $selected; ?>>Ubuntu - Sans</option>
			<?php $selected = (isset( $this->wpdev_mobile_options['body_font_2'] ) && $this->wpdev_mobile_options['body_font_2'] === 'vollkorn') ? 'selected' : '' ; ?>
			<option value="vollkorn" <?php echo $selected; ?>>Vollkorn - Serif</option>
		</select><label for="body_font_2">Select a body font.</label> <?php
	}

	public function highlight_color_3_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[highlight_color_3]" id="highlight_color_3" value="%s"><label for="highlight_color_3">Enter a hex for the desired highlight color.</label>',
			isset( $this->wpdev_mobile_options['highlight_color_3'] ) ? esc_attr( $this->wpdev_mobile_options['highlight_color_3']) : ''
		);
	}

	public function enable_subscribe_button_for_amp_4_callback() {
		printf(
			'<input type="checkbox" name="wpdev_mobile_option_name[enable_subscribe_button_for_amp_4]" id="enable_subscribe_button_for_amp_4" value="enable_subscribe_button_for_amp_4" %s><label for="enable_subscribe_button_for_amp_4">Enable to add a subscribe button after the content, which will link to an email subscribe page. (If Disabled: Displays "View Full Experience" button instead)</label>',
			( isset( $this->wpdev_mobile_options['enable_subscribe_button_for_amp_4'] ) && $this->wpdev_mobile_options['enable_subscribe_button_for_amp_4'] === 'enable_subscribe_button_for_amp_4' ) ? 'checked' : ''
		);
	}

	public function subscribe_button_link_for_amp_5_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[subscribe_button_link_for_amp_5]" id="subscribe_button_link_for_amp_5" value="%s"><label for="subscribe_button_link_for_amp_5">Add the URL for subscribe page. If empty, button is disabled.</label>',
			isset( $this->wpdev_mobile_options['subscribe_button_link_for_amp_5'] ) ? esc_attr( $this->wpdev_mobile_options['subscribe_button_link_for_amp_5']) : ''
		);
	}

	public function footer_articles_section_6_callback() {
		?> <fieldset><?php $checked = ( isset( $this->wpdev_mobile_options['footer_articles_section_6'] ) && $this->wpdev_mobile_options['footer_articles_section_6'] === 'relatedarticles' ) ? 'checked' : '' ; ?>
		<label for="footer_articles_section_6-0"><input type="radio" name="wpdev_mobile_option_name[footer_articles_section_6]" id="footer_articles_section_6-0" value="relatedarticles" <?php echo $checked; ?>> Related Articles</label><br>
		<?php $checked = ( isset( $this->wpdev_mobile_options['footer_articles_section_6'] ) && $this->wpdev_mobile_options['footer_articles_section_6'] === 'recentarticles' ) ? 'checked' : '' ; ?>
		<label for="footer_articles_section_6-1"><input type="radio" name="wpdev_mobile_option_name[footer_articles_section_6]" id="footer_articles_section_6-1" value="recentarticles" <?php echo $checked; ?>> Recent Articles</label><?php
	}

	public function footer_home_link_7_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[footer_home_link_7]" id="footer_home_link_7" value="%s"><label for="footer_home_link_7">Add the site URL for a home link in the footer.</label>',
			isset( $this->wpdev_mobile_options['footer_home_link_7'] ) ? esc_attr( $this->wpdev_mobile_options['footer_home_link_7']) : ''
		);
	}

	public function footer_about_link_8_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[footer_about_link_8]" id="footer_about_link_8" value="%s"><label for="footer_about_link_8">Add the about page URL for an about page link in the footer.</label>',
			isset( $this->wpdev_mobile_options['footer_about_link_8'] ) ? esc_attr( $this->wpdev_mobile_options['footer_about_link_8']) : ''
		);
	}

	public function footer_contact_link_9_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[footer_contact_link_9]" id="footer_contact_link_9" value="%s"><label for="footer_contact_link_9">Add the contact page URL for a contact page link in the footer.</label>',
			isset( $this->wpdev_mobile_options['footer_contact_link_9'] ) ? esc_attr( $this->wpdev_mobile_options['footer_contact_link_9']) : ''
		);
	}

	public function footer_privacy_policy_link_10_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[footer_privacy_policy_link_10]" id="footer_privacy_policy_link_10" value="%s"><label for="footer_privacy_policy_link_10">Add the privacy policy page URL for a privacy policy page link in the footer.</label>',
			isset( $this->wpdev_mobile_options['footer_privacy_policy_link_10'] ) ? esc_attr( $this->wpdev_mobile_options['footer_privacy_policy_link_10']) : ''
		);
	}

	public function footer_advertising_link_11_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[footer_advertising_link_11]" id="footer_advertising_link_11" value="%s"><label for="footer_advertising_link_11">Add the advertising page URL for an advertising page link in the footer.</label>',
			isset( $this->wpdev_mobile_options['footer_advertising_link_11'] ) ? esc_attr( $this->wpdev_mobile_options['footer_advertising_link_11']) : ''
		);
	}

	public function dfp_ad_1_12_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="336,280,/4119129/mobile_ad_banner" name="wpdev_mobile_option_name[dfp_ad_1_12]" id="dfp_ad_1_12" value="%s"><label for="dfp_ad_1_12">Add the width, height, and ad slot for the DFP ad, separated by commas.</label>',
			isset( $this->wpdev_mobile_options['dfp_ad_1_12'] ) ? esc_attr( $this->wpdev_mobile_options['dfp_ad_1_12']) : ''
		);
	}

	public function dfp_ad_2_13_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="336,280,/4119129/mobile_ad_banner" name="wpdev_mobile_option_name[dfp_ad_2_13]" id="dfp_ad_2_13" value="%s"><label for="dfp_ad_2_13">Add the width, height, and ad slot for the DFP ad, separated by commas.</label>',
			isset( $this->wpdev_mobile_options['dfp_ad_2_13'] ) ? esc_attr( $this->wpdev_mobile_options['dfp_ad_2_13']) : ''
		);
	}

	public function dfp_ad_3_14_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="336,280,/4119129/mobile_ad_banner" name="wpdev_mobile_option_name[dfp_ad_3_14]" id="dfp_ad_3_14" value="%s"><label for="dfp_ad_3_14">Add the width, height, and ad slot for the DFP ad, separated by commas.</label>',
			isset( $this->wpdev_mobile_options['dfp_ad_3_14'] ) ? esc_attr( $this->wpdev_mobile_options['dfp_ad_3_14']) : ''
		);
	}

	public function adsense_ad_1_15_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="336,280,ca-pub-2005682797531342,7046626912" name="wpdev_mobile_option_name[adsense_ad_1_15]" id="adsense_ad_1_15" value="%s"><label for="adsense_ad_1_15">Add the width, height, publisher ID, and ad slot ID for the AdSense ad, separated by commas.</label>',
			isset( $this->wpdev_mobile_options['adsense_ad_1_15'] ) ? esc_attr( $this->wpdev_mobile_options['adsense_ad_1_15']) : ''
		);
	}

	public function adsense_ad_2_16_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="336,280,ca-pub-2005682797531342,7046626912" name="wpdev_mobile_option_name[adsense_ad_2_16]" id="adsense_ad_2_16" value="%s"><label for="adsense_ad_2_16">Add the width, height, publisher ID, and ad slot ID for the AdSense ad, separated by commas.</label>',
			isset( $this->wpdev_mobile_options['adsense_ad_2_16'] ) ? esc_attr( $this->wpdev_mobile_options['adsense_ad_2_16']) : ''
		);
	}

	public function adsense_ad_3_17_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="336,280,ca-pub-2005682797531342,7046626912" name="wpdev_mobile_option_name[adsense_ad_3_17]" id="adsense_ad_3_17" value="%s"><label for="adsense_ad_3_17">Add the width, height, publisher ID, and ad slot ID for the AdSense ad, separated by commas.</label>',
			isset( $this->wpdev_mobile_options['adsense_ad_3_17'] ) ? esc_attr( $this->wpdev_mobile_options['adsense_ad_3_17']) : ''
		);
	}

	public function revcontent_ad_18_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="320,420,rcjsload_2ff711,203" name="wpdev_mobile_option_name[revcontent_ad_18]" id="revcontent_ad_18" value="%s"><label for="revcontent_ad_18">Add the width, height, wrapper ID, and ad ID for the RevContent ad, separated by commas.</label>',
			isset( $this->wpdev_mobile_options['revcontent_ad_18'] ) ? esc_attr( $this->wpdev_mobile_options['revcontent_ad_18']) : ''
		);
	}

  public function taboola_ad_18_callback() {
		printf(
			'<input class="regular-text" type="text" placeholder="100,100,amp-demo,thumbnails-a,Responsive Example" name="wpdev_mobile_option_name[taboola_ad_18]" id="taboola_ad_18" value="%s"><label for="taboola_ad_18">Add the width, height, publisher ID, mode, and placement for the Taboola ad, separated by commas.</label>',
			isset( $this->wpdev_mobile_options['taboola_ad_18'] ) ? esc_attr( $this->wpdev_mobile_options['taboola_ad_18']) : ''
		);
	}

	public function amp_google_analytics_tracking_id_19_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[amp_google_analytics_tracking_id_19]" id="amp_google_analytics_tracking_id_19" value="%s"><label for="amp_google_analytics_tracking_id_19">Add the Google Analytics tracking ID.</label>',
			isset( $this->wpdev_mobile_options['amp_google_analytics_tracking_id_19'] ) ? esc_attr( $this->wpdev_mobile_options['amp_google_analytics_tracking_id_19']) : ''
		);
	}

	public function number_of_posts_20_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[number_of_posts_20]" id="number_of_posts_20" value="%s"><label for="number_of_posts_20">Define the amount of articles to output in the Instant Article RSS feed.</label>',
			isset( $this->wpdev_mobile_options['number_of_posts_20'] ) ? esc_attr( $this->wpdev_mobile_options['number_of_posts_20']) : ''
		);
	}

	public function enable_facebook_ads_21_callback() {
		printf(
			'<input type="checkbox" name="wpdev_mobile_option_name[enable_facebook_ads_21]" id="enable_facebook_ads_21" value="enable_facebook_ads_21" %s> <label for="enable_facebook_ads_21">Enable Facebook Audience Ad Placement. Ads will be automatically placed by Facebook.</label>',
			( isset( $this->wpdev_mobile_options['enable_facebook_ads_21'] ) && $this->wpdev_mobile_options['enable_facebook_ads_21'] === 'enable_facebook_ads_21' ) ? 'checked' : ''
		);
	}

	public function facebook_ad_placement_id_1_22_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[facebook_ad_placement_id_1_22]" id="facebook_ad_placement_id_1_22" value="%s"><label for="facebook_ad_placement_id_1_22">Input the Facebook Ad placement ID.</label>',
			isset( $this->wpdev_mobile_options['facebook_ad_placement_id_1_22'] ) ? esc_attr( $this->wpdev_mobile_options['facebook_ad_placement_id_1_22']) : ''
		);
	}

	public function facebook_ad_placement_id_2_23_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[facebook_ad_placement_id_2_23]" id="facebook_ad_placement_id_2_23" value="%s"><label for="facebook_ad_placement_id_2_23">Input the Facebook Ad placement ID.</label>',
			isset( $this->wpdev_mobile_options['facebook_ad_placement_id_2_23'] ) ? esc_attr( $this->wpdev_mobile_options['facebook_ad_placement_id_2_23']) : ''
		);
	}

	public function facebook_ad_placement_id_3_24_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[facebook_ad_placement_id_3_24]" id="facebook_ad_placement_id_3_24" value="%s"><label for="facebook_ad_placement_id_3_24">Input the Facebook Ad placement ID.</label>',
			isset( $this->wpdev_mobile_options['facebook_ad_placement_id_3_24'] ) ? esc_attr( $this->wpdev_mobile_options['facebook_ad_placement_id_3_24']) : ''
		);
	}

	public function facebook_ad_placement_id_4_25_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[facebook_ad_placement_id_4_25]" id="facebook_ad_placement_id_4_25" value="%s"><label for="facebook_ad_placement_id_4_25">Input the Facebook Ad placement ID.</label>',
			isset( $this->wpdev_mobile_options['facebook_ad_placement_id_4_25'] ) ? esc_attr( $this->wpdev_mobile_options['facebook_ad_placement_id_4_25']) : ''
		);
	}

	public function enable_google_analytics_tracking_26_callback() {
		printf(
			'<input type="checkbox" name="wpdev_mobile_option_name[enable_google_analytics_tracking_26]" id="enable_google_analytics_tracking_26" value="enable_google_analytics_tracking_26" %s> <label for="enable_google_analytics_tracking_26">Track Facebook Instant Article pageviews via Google Analytics</label>',
			( isset( $this->wpdev_mobile_options['enable_google_analytics_tracking_26'] ) && $this->wpdev_mobile_options['enable_google_analytics_tracking_26'] === 'enable_google_analytics_tracking_26' ) ? 'checked' : ''
		);
	}

	public function fbia_google_analytics_tracking_id_27_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[fbia_google_analytics_tracking_id_27]" id="fbia_google_analytics_tracking_id_27" value="%s"><label for="fbia_google_analytics_tracking_id_27">Enter the Google Analytics tracking ID.</label>',
			isset( $this->wpdev_mobile_options['fbia_google_analytics_tracking_id_27'] ) ? esc_attr( $this->wpdev_mobile_options['fbia_google_analytics_tracking_id_27']) : ''
		);
	}

	public function enable_google_analytics_group_tracking_28_callback() {
		printf(
			'<input type="checkbox" name="wpdev_mobile_option_name[enable_google_analytics_group_tracking_28]" id="enable_google_analytics_group_tracking_28" value="enable_google_analytics_group_tracking_28" %s> <label for="enable_google_analytics_group_tracking_28">For specific Facebook Instant Articles data in Google Analytics</label>',
			( isset( $this->wpdev_mobile_options['enable_google_analytics_group_tracking_28'] ) && $this->wpdev_mobile_options['enable_google_analytics_group_tracking_28'] === 'enable_google_analytics_group_tracking_28' ) ? 'checked' : ''
		);
	}

	public function enable_subscribe_button_for_fbia_29_callback() {
		printf(
			'<input type="checkbox" name="wpdev_mobile_option_name[enable_subscribe_button_for_fbia_29]" id="enable_subscribe_button_for_fbia_29" value="enable_subscribe_button_for_fbia_29" %s> <label for="enable_subscribe_button_for_fbia_29">For specific Facebook Instant Articles data in Google Analytics</label>',
			( isset( $this->wpdev_mobile_options['enable_subscribe_button_for_fbia_29'] ) && $this->wpdev_mobile_options['enable_subscribe_button_for_fbia_29'] === 'enable_subscribe_button_for_fbia_29' ) ? 'checked' : ''
		);
	}

	public function subscribe_button_link_for_fbia_30_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdev_mobile_option_name[subscribe_button_link_for_fbia_30]" id="subscribe_button_link_for_fbia_30" value="%s"><label for="subscribe_button_link_for_fbia_30">Enter the URL for the subscribe page. If empty, button will not display.</label>',
			isset( $this->wpdev_mobile_options['subscribe_button_link_for_fbia_30'] ) ? esc_attr( $this->wpdev_mobile_options['subscribe_button_link_for_fbia_30']) : ''
		);
	}

}
if ( is_admin() )
	$wpdev_mobile = new WPDevMobile();
