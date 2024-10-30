<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       Honeypot WooCommerce - WordPress AntiSpam
 * Description:       Adds honeypot anti-spam functionality
 * Version:           1.3.7
 * Author:            Camilo
 * License: GPLv3 or later
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Author URI:        https://camilowp.com/
 * Text Domain:       woo-antispam
 * Domain Path:       /languages
 * WC tested up to: 4.4.0
 */

if (!defined('ABSPATH')) {
    exit;
}


function hwwa_honeypot_load_textdomain()
{
    load_plugin_textdomain('woo-antispam', false, basename(dirname(__FILE__)) . '/languages');
}

add_action('init', 'hwwa_honeypot_load_textdomain');


/**
 * Honeypot comments
 */

function hwwa_add_honeypot()
{
  echo '<p class="hwwa_field"><textarea name="additional-comment"></textarea></p>';
}
add_action('comment_form_top', 'hwwa_add_honeypot');




/**
 * If the field is filled the comment is marked as spam
 */

function hwwa_check_honeypot($approved)
{
    return empty($_POST['additional-comment']) ? $approved : 'spam';
}
add_filter('pre_comment_approved', 'hwwa_check_honeypot');

/**
 * Hide field comments wordpress
*/

function hwwa_css_comments_wordpress() {
  if ( comments_open() ) {
?>
 <style type="text/css">
 .hwwa_field {
  display: none !important;
 }
 </style>
<?php }
}
add_action( 'wp_head', 'hwwa_css_comments_wordpress' );



/**
 * Honeypot Register WordPress
 *
 */

if ( get_option( 'users_can_register' ) ) {
   function hwwa_field_register_wordpress()
   {
       ?>
          <p class="hwwa_field">
          <label for="website_input_register"><?php _e('Website', 'woo-antispam'); ?> <span class="required"></label>
          <input autocomplete="off" type="text" class="input" name="website_input_register" id="website_input_register" value="<?php (isset($_POST['website_input_register'])); ?>" size="20" autocapitalize="off" />
          </p>
          <?php
   }

   add_action('register_form', 'hwwa_field_register_wordpress');

   function hwwa_validate_honeypot_register_wordpress()
   {
       if (isset($_POST['website_input_register']) && !empty($_POST['website_input_register'])) {
           wp_die(__('You filled out a form field that was created to stop spammers. Please go back and try again or contact the site administrator if you feel this was in error.', 'woo-antispam'), '', array( "response" => 200 ));
       }
   }

   add_action('user_register', 'hwwa_validate_honeypot_register_wordpress');
}


/**
 * Honeypot Login WordPress
 */

function hwwa_field_login_wordpress()
{
    ?>
       <p class="hwwa_field">
       <label for="website_input"><?php _e('Website', 'woo-antispam'); ?> <span class="required"></label>
       <input autocomplete="off" type="text"  class="input-text" name="website_input" id="website_input" value="<?php (isset($_POST['website_input'])); ?>" size="20" autocapitalize="off"/>
       </p>
       <?php
}

add_action('login_form', 'hwwa_field_login_wordpress');

function hwwa_validate_honeypot_login_wordpress()
{
    if (isset($_POST['website_input']) && !empty($_POST['website_input'])) {
        wp_die(__('You filled out a form field that was created to stop spammers. Please go back and try again or contact the site administrator if you feel this was in error.', 'woo-antispam'), '', array( "response" => 200 ));
    }
}

add_action('wp_login', 'hwwa_validate_honeypot_login_wordpress');
/**
 * Honeypot register woocommerce
 */

function hwwa_field_register_woo()
{
    ?>
           <p class="form-row form-row-wide hwwa_field_woo">
           <label  for="wc_website_inputt"><?php _e('Website', 'woo-antispam'); ?> <span class="required"></label>
           <input autocomplete="off" type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="wc_website_inputt" id="wc_website_inputt" value="<?php (isset($_POST['wc_website_inputt'])); ?>" />
           </p>
           <?php
}

add_action('woocommerce_register_form_start', 'hwwa_field_register_woo');

function hwwa_validate_honeypot_register($username, $email, $errors)
{
    if (isset($_POST['wc_website_inputt']) && !empty($_POST['wc_website_inputt'])) {
      wp_die(__('You filled out a form field that was created to stop spammers. Please go back and try again or contact the site administrator if you feel this was in error.', 'woo-antispam'), '', array( "response" => 200 ));
    }
};

add_action('woocommerce_register_post', 'hwwa_validate_honeypot_register', 10, 3);
/**
 * Honeypot login woocommerce
 */

function hwwa_field_login_woo()
{
    ?>
          <p class="form-row form-row-wide hwwa_field_woo">
          <label  for="wc_website_input"><?php _e('Website', 'woo-antispam'); ?> <span class="required"></label>
          <input  autocomplete="off" type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="wc_website_input" id="wc_website_input" value="<?php (isset($_POST['wc_website_input'])); ?>" />
          </p>
          <?php
}

add_action('woocommerce_login_form', 'hwwa_field_login_woo');

function hwwa_validate_honeypot_login()
{
    if (isset($_POST['wc_website_input']) && !empty($_POST['wc_website_input'])) {
        wp_die(__('You filled out a form field that was created to stop spammers. Please go back and try again or contact the site administrator if you feel this was in error.', 'woo-antispam'), '', array( "response" => 200 ));
    }
}

add_action('wp_login', 'hwwa_validate_honeypot_login');

/**
 * Hide the display none wordpress login
 */

 function hwwa_css_honeypot_login() {
 ?>
  <style type="text/css">
  .hwwa_field {
   display: none !important;
  }
  </style>
 <?php }

 add_action( 'login_enqueue_scripts', 'hwwa_css_honeypot_login' );



 /**
  * Hide the display none woocommerce login
  */

 add_action( 'woocommerce_login_form_start','hwwa_css_honeypot_login_woo' );

 function hwwa_css_honeypot_login_woo() {
   ?>
    <style type="text/css">
    .hwwa_field_woo {
     display: none !important;
    }
    </style>
   <?php }


/**
* Hide the display none woocommerce register
*/
add_action( 'woocommerce_register_form_start','hwwa_css_honeypot_register_woo' );

function hwwa_css_honeypot_register_woo() {
      ?>
       <style type="text/css">
       .hwwa_field_woo {
        display: none !important;
       }
     </style>
<?php }
