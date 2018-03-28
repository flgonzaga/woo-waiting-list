<?php
/**
 * WOOWL
 *
 * Dashboard Template
 *
 * @author   Fabio Gonzaga
 * @package woo-waiting-list
 * @since    1.0
 */
if ( ! defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly.
}

/** WordPress Administration Bootstrap */
require_once( ABSPATH . 'wp-load.php' );
require_once( ABSPATH . 'wp-admin/admin.php' );
require_once( ABSPATH . 'wp-admin/admin-header.php' );

global $wpdb;

$querystr = "
	SELECT DISTINCT $wpdb->postmeta.meta_key 
	FROM $wpdb->postmeta
	ORDER BY $wpdb->postmeta.meta_key ASC
";

$meta_keys = $wpdb->get_results($querystr, OBJECT);

?>
<div class="wrap">
    
	<h1><?php echo 'WOO ' . __( 'Waiting List', 'woowl' ); ?></h1>

	<div class="welcome-panel">
		
		<form method="post" action="options.php">
			<?php settings_fields( 'woowl_option_group' ); ?>
    		<?php //do_settings_sections( 'woowl-page-options' ); ?>

			<h4><?php echo __('Show form when product is out of stock?', 'woowl'); ?></h4>
    		<input type="radio" name="woowl_fields[out_of_stock]" value="y" <?php if (($this->options['out_of_stock'] == 'y') || (empty($this->options['out_of_stock']))) echo 'checked'; ?> > <label><?php echo __('Yes', 'woowl'); ?></label>
    		<input type="radio" name="woowl_fields[out_of_stock]" value="n" <?php if ($this->options['out_of_stock'] == 'n') echo 'checked'; ?> > <label><?php echo __('No', 'woowl'); ?></label>
			<hr>
    		<h4><?php echo __('Show form using a custom code in my product page?', 'woowl'); ?></h4>
    		<p><?php echo __('When selected "Yes" the form view happens as soon as you add the shortcode to your page, being free to add display conditions or not directly in the code of your product page.', 'woowl'); ?></p>
    		<input type="radio" name="woowl_fields[custom_code]" value="y" <?php if ($this->options['custom_code'] == 'y') echo 'checked'; ?> > <label><?php echo __('Yes', 'woowl'); ?></label>
    		<input type="radio" name="woowl_fields[custom_code]" value="n" <?php if (($this->options['custom_code'] == 'n') || (empty($this->options['custom_code']))) echo 'checked'; ?> > <label><?php echo __('No', 'woowl'); ?></label>
			
			<?php /* ?>
			<h3><?php echo __('Custom conditions:', 'woowl'); ?></h3>
			<table class="wp-list-table widefat striped posts">
				<tr>
					<td><?php echo __('Field', 'woowl'); ?></td>
					<td><?php echo __('Condition', 'woowl'); ?></td>
					<td><?php echo __('Value', 'woowl'); ?></td>
				</tr>
				<tr>
					<td>
						<?php if ($meta_keys) : ?>
							<select name="woowl_fields[postmeta_field]">
								<option value=""></option>
								<?php foreach ($meta_keys as $key) : ?>
								<option><?php echo $key->meta_key; ?></option>
								<?php endforeach; ?>
							</select>
						<?php endif; ?>
					</td>
					<td>
						<select name="woowl_fields[operator]">
							<option value=""></option>
							<option value="equal"><?php echo __('Equal', 'woowl'); ?></option>
					       	<option value="totallyEqual"><?php echo __('Totally equal', 'woowl'); ?></option>
					       	<option value="notEqual"><?php echo __('Not equal', 'woowl'); ?></option>
					       	<option value="greaterThan"><?php echo __('Greater than', 'woowl'); ?></option>
					        <option value="lessThan"><?php echo __('Less than', 'woowl'); ?></option>					
					    </select>
					</td>
					<td>
						<input type="text" name="woowl_fields[value]" id="">
					</td>
				</tr>
			</table>
			<?php */ ?>
			<?php submit_button(); ?>
		</form>
	</div><!-- /.welcome-panel -->
	
	<h4><?php echo __('Usage', 'woowl'); ?></h4>
	<p><?php echo __('Add this shortcode in your product page:', 'woowl'); ?></p>
	<blockquote>
		echo do_shortcode('[woo_waiting_list product_id=' . $product->get_id() . ']');
	</blockquote>
	
</div><!-- /.wrap -->
<?php include( ABSPATH . 'wp-admin/admin-footer.php' );