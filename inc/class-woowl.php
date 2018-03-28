<?php
/**
 * WOOWL Core Class
 *
 * @package  WOO Waiting List
 * @since    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WooWaitingList
{
	use DynamicComparisons;

	/**
	* WC Product
	*/
	protected $product;

	/**
	* @param $product WC Product
	* @return $this WC Product
	*/
	public function __construct($product)
	{
		return $this->product = $product;
	}

	/**
	* Check if product is out of stock
	* @return boolean
	*/
	private function woowl_is_out_of_stock()
	{
		if ( $this->product->get_stock_status() == 'outofstock' )
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	/**
	* @param post_meta_key string The post_meta field name
	* @param operator string The Operator @see DynamicComparisons trait
	* @param value_to_compare string The value to compare with value of post_meta
	* @return boolean
	*/
	private function woowl_is_post_meta($post_meta_key, $operator, $value_to_compare)
	{
		
		$post_meta_value = get_post_meta($this->product->get_id(), $post_meta_key, TRUE);

		$this->verbose_mode = true; //Enable verbose mode 
		$result = $this->is($post_meta_value, $operator, $value_to_compare);
		
		return $result;
	}

	/**
	* @param WC_Product $product
	* @return array The array with users/customers waiting this product
	*/
	public function woowl_get_list($product)
	{
		$woowl_cf_array = json_decode(get_post_meta($product->get_id(), 'woowl_waiting_list', TRUE));
		if (is_null($woowl_cf_array))
		{
			$woowl_cf_array = array();
		}
		return $woowl_cf_array;
	}

	/**
	* @param WC_Product $product
	* @param WP_User $user The current user
	* @return boolean TRUE if the user is on the list, or FALSE otherwise.
	*/
	public function woowl_verify($product, $current_user)
	{
		$woowl_cf_array = $this->woowl_get_list($product);
		if (in_array($current_user->user_email, $woowl_cf_array))
		{
			//This user exists
			return true;
		} 
		else 
		{
			//This user not found
			return false;
		}
	}

	/**
	* Clear the field, used only for test mode
	* @param WC_Product $product
	*/
	public function woowl_clear_list($product)
	{
		woowl_save_cf($product, 'woowl_waiting_list', '[]');
	}

	/**
	* @param WC_Product $product
	* @param WP_User $user The current user
	* @return boolean True if save is successfully
	*/
	public function woowl_add_to_list($product, $current_user)
	{
		$woowl_cf_array = $this->woowl_get_list($product);
		
		if ($current_user->ID != 0)
		{
			array_push($woowl_cf_array, $current_user->user_email);
			woowl_save_cf($product, 'woowl_waiting_list', json_encode($woowl_cf_array));
			// print_r($this->woowl_get_list($product)); // Console log
		}
		else
		{
			echo __('<span style="color:red;">You must be logged.</span>', 'woowl');
			return false;
		}
	}

	/**
	* @param WC_Product $product
	* @param WP_User $user The current user
	* @return boolean True if save is successfully
	*/
	public function woowl_remove_from_list($product, $current_user)
	{
		$woowl_cf_array = $this->woowl_get_list($product);

		foreach ($woowl_cf_array as $key) 
		{
			if ( ($key = array_search($current_user->user_email, $woowl_cf_array)) !== false )
			{
				unset($woowl_cf_array[$key]);
			}
		}
		woowl_save_cf($product, 'woowl_waiting_list', json_encode($woowl_cf_array));
		// print_r($this->woowl_get_list($product)); // Console log
	}

	/**
	* @return the form render in frontend
	*/
	public function woowl_render()
	{
		// return $this->woowl_is_post_meta('_regular_price', 'lessThan', 1.25);
		$woowl_display_form	= false;
		$product 			= $this->product;
		$current_user 		= wp_get_current_user();

		$woowl_is_checked 	= $this->woowl_verify($product, $current_user);
		$woowl_notify_me 	= filter_input(INPUT_GET, 'woowl_notify_me');
		$woowl_action 		= filter_input(INPUT_GET, 'woowl_action');

		if ( $woowl_action == 'woowl_waiting_list' )
		{
			if (!$woowl_is_checked)
			{
				$this->woowl_add_to_list($product, $current_user);
			} 
			else
			{
				$this->woowl_remove_from_list($product, $current_user);
			}
		}
		$woowl_is_checked = $this->woowl_verify($product, $current_user); // Check if has changed

		// Conditions to render the form:
		$woowl_options = get_option( 'woowl_fields' );

		if (($woowl_options['out_of_stock'] == 'y') || (empty($woowl_options['out_of_stock'])))
			$woowl_display_form = $this->woowl_is_out_of_stock();

		if ($woowl_options['custom_code'] == 'y')
			$woowl_display_form = true;

		if ($woowl_display_form)
		{
			include WOOWL_PATH . 'templates/woowl-frontend-form.php';
		}
	}

}