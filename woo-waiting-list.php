<?php
/*
* Plugin Name: WOO Waiting List
* Plugin URI: http://gist.github.com/flgonzaga/woo-waiting-list
* Description: Add Waiting List to WooCommerce
* Author: Fabio Gonzaga
* Author URI: http://gist.github.com/flgonzaga
* Version: 1.0.1
* Text Domain: woowl
* 
* WC requires at least: 3.3
* WC tested up to: 3.3
* 
*/

if ( ! defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly.
}

define( 'WOOWL_PATH', plugin_dir_path( __FILE__ ) );

function woowl_init()
{
    $woowl_rel_path = basename( dirname( __FILE__ ) ) . '/languages'; /* Relative to WP_PLUGIN_DIR */
    load_plugin_textdomain( 'woowl', false, $woowl_rel_path );
}
add_action('plugins_loaded', 'woowl_init');

/**
 * Detect plugin. For use in Admin area only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
* Check if WooCommerce is active
*/
if ( !is_plugin_active( 'woocommerce/woocommerce.php' )  ) 
{
	deactivate_plugins( plugin_basename( __FILE__ ), true );

    function woowl_admin_notice__error() {
        $class = 'notice notice-error';
        $message = __( 'WooCommerce is required. Woo Waiting List has deactived temporarily.', 'woowl' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }
    add_action( 'admin_notices', 'woowl_admin_notice__error' );
}


require_once 'inc/woowl-functions.php';