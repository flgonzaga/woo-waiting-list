<?php
/**
 * LMS Visualy
 *
 * Question - Template
 *
 * @author   Fabio Gonzaga
 * @since    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$product 		= wc_get_product($post->ID);
$woowl 			= new WooWaitingList($product);

/**
* Actions
*/
$woowl_action 	= filter_input(INPUT_GET, 'woowl_action');
$woowl_email	= filter_input(INPUT_GET, 'woowl_email');
// Remove single
if ($woowl_action == 'woowl_remove_single')
{
	$woowl->woowl_remove_from_list($product, get_user_by('email', $woowl_email));
}

// Send email for all
if ($woowl_action == 'woowl_send_email')
{
	//Form data
	$subject 	= __($product->get_name() . ' is avaliable now!', 'woowl');

	$body = __('Hello', 'woowl') . ',<br>';
	$body .= sprintf(__('This is an up-to-date message to inform you that the product "%1$s" is already available in our stock.', 'woowl'), $product->get_name()) . '<br /><br />';
	$body .= __('You are receiving this notification because your email address has been added to the waiting list for this product. If you do not know if you have registered, we ask that you disregard this message.', 'woowl') . '<br />';

	$woowl_cf_array = $woowl->woowl_get_list($product);
	if (is_array($woowl_cf_array) && !empty($woowl_cf_array))
	{
		$counter = 0;
		$headers = array('From: ' . get_option( 'admin_email' ));
		add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
		foreach ($woowl_cf_array as $key) {
			$woowl_get_user = get_user_by('email', $key);
			if ($woowl_get_user)
			{
				$send = wp_mail($woowl_get_user->user_email, $subject, $body, $headers);
				if ($send)
				{
					$counter++;
				}
			}
		}
		remove_filter('wp_mail_content_type', 'set_html_content_type');
		echo '<script>alert("' . __($counter . ' sent emails from ' . count($woowl_cf_array), 'woowl') . '");</script>';
	}
	
}

// Clear list
if ($woowl_action == 'woowl_clear_list')
{
	$woowl->woowl_clear_list($product);
	echo '<script>alert("' . __('List deleted successfully.', 'woowl') . '");</script>';
}	

$woowl_cf_array = $woowl->woowl_get_list($product);

?>
<link rel="stylesheet" type="text/css" href="">
<?php if ($woowl_cf_array) : ?>
<table class="wp-list-table widefat striped posts">
    <thead>
        <th>
            <?php echo __('Customers waiting this product', 'woowl'); ?>
        </th>
        <th>
        	<a href="<?php echo get_edit_post_link() . '&woowl_action=woowl_send_email'; ?>" class="button button-primary button-large" onclick="return confirm('<?php echo __('Do you confirm send emails to all the clients on this list?','woowl') ?>');" ><?php echo __('Send e-mail for all', 'woowl'); ?></a>
        	
        	<a href="<?php echo get_edit_post_link() . '&woowl_action=woowl_clear_list'; ?>" class="button button-large" onclick="return confirm('<?php echo __('Do you confirm the deletion of all emails from the waiting list below?','woowl') ?>');" > [ X ] <?php echo __('Clear list', 'woowl'); ?></a>
        </th>
    </thead>
    <tbody>
    		<?php foreach ($woowl_cf_array as $key) : ?>
	        <tr>
	            <td>
	                <?php echo $key; ?>
	            </td>
	            <td>
	            	<a href="<?php echo get_edit_post_link() . '&woowl_action=woowl_remove_single&woowl_email=' . $key; ?>" class="button delete" onclick="return confirm('<?php echo __('Do you confirm that you remove this client from the waiting list?', 'woowl'); ?>')"> [ X ] <?php echo __('Remove from list', 'woowl'); ?></a>
	            </td>
	        </tr>
    		<?php endforeach; ?>
    </tbody>
</table>
<?php else : ?>
	<?php echo __('No one on the waiting list for this product at this time.', 'woowl'); ?>
<?php endif; ?>