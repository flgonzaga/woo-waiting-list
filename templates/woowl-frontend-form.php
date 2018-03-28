<?php
/**
 * WOOWL Template: frontend-form
 *
 * @package  WOO Waiting List
 * @since    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="woowl_notify_me">
	<form method="get">
		<input type="hidden" name="woowl_referrer" value="<?php the_permalink(); ?>">
		<input type="hidden" name="woowl_action" value="woowl_waiting_list">
		<input type="checkbox" name="woowl_notify_me" value="1" onchange="this.form.submit();" <?php if ( $woowl_is_checked ) echo 'checked'; ?>> <label><?php echo __('Notify me when available.', 'woowl'); ?></label>
	</form>
</div>
<!-- /.woowl_notify_me -->