<?php
/**
 * WOOWL
 *
 * Functions
 *
 * @author   Fabio Gonzaga
 * @since    1.0
 */
if ( ! defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly.
}

include WOOWL_PATH . 'inc/class-dynamic-comparisons.php';
include WOOWL_PATH . 'inc/class-woowl.php';
include WOOWL_PATH . 'inc/class-woowl-options.php';

$label = __( 'Enable Logging', 'woowl' );
$description = __( 'Enable the logging of errors.', 'woowl' );

if ( defined( 'WC_LOG_DIR' ) ) 
{
	$log_url = add_query_arg( 'tab', 'logs', add_query_arg( 'page', 'wc-status', admin_url( 'admin.php' ) ) );
	$log_key = 'woo-waiting-list-' . sanitize_file_name( wp_hash( 'woo-waiting-list' ) ) . '-log';
	$log_url = add_query_arg( 'log_file', $log_key, $log_url );

	$label .= ' | ' . sprintf( __( '%1$sView Log%2$s', 'woowl' ), '<a href="' . esc_url( $log_url ) . '">', '</a>' );
}

$form_fields['wc_woowl_debug'] = array(
	'title'       => __( 'Debug Log', 'woowl' ),
	'label'       => $label,
	'description' => $description,
	'type'        => 'checkbox',
	'default'     => 'no'
);

/**
* Register plugin menu
*/
if ( ! function_exists('woowl_register_plugin_menu') ) 
{
	function woowl_register_plugin_menu() 
	{
		add_submenu_page( 'edit.php?post_type=product', __('Products Awaited', 'woowl'), __('Products Awaited', 'woowl'), 'publish_posts', 'woowl_awaited', 'woowl_products_awaited' );
	}
	add_action( 'admin_menu', 'woowl_register_plugin_menu' );
}

/**
* Create Plugin Dashboard
*/
if ( ! function_exists('woowl_products_awaited') ) 
{
	function woowl_products_awaited()
	{
		include ( WOOWL_PATH . 'templates/woowl-products-awaited.php' );
	}
}

/**
* Using plugin by Shortcode method
*/
if ( ! function_exists('woowl_load_class') ) 
{
	function woowl_load_class($args)
	{
		$product = wc_get_product($args['product_id']);
		$woowl = new WooWaitingList($product);

		return $woowl->woowl_render();
	}
	add_shortcode( 'woo_waiting_list', 'woowl_load_class' );
}

/* 
* Save de custom fields
*/
if ( ! function_exists('woowl_save_cf') ) 
{
	function woowl_save_cf($product, $custom_field, $value) 
	{
		
	    $woowl_cf = get_post_meta( $product->get_id(), $custom_field, TRUE );

	    if ( $woowl_cf ) 
	    {
	        update_post_meta( $product->get_id(), $custom_field, $value );
	    } 
	    else 
	    {
	        add_post_meta($product->get_id(), $custom_field, $value, true);
	    }

	}
	//add_action( 'save_post', 'woowl_save_cf' );
}

/**
* Call Metabox for product Post Type
*/
if ( ! function_exists('woowl_call_product_mbx') ) 
{
	function woowl_call_product_mbx() {
	    add_meta_box( 'woowl_product', 'WOO Waiting List', 'woowl_product_meta_box', 'product', 'advanced', 'high' );    
	}
	add_action( 'add_meta_boxes', 'woowl_call_product_mbx' );
}

/*
* Generate metabox to product Post Type
*/
if ( ! function_exists('woowl_product_meta_box') ) 
{
	function woowl_product_meta_box($post, $metabox) 
	{
	    include( WOOWL_PATH . 'templates/woowl-product-metabox.php' );
	}
}