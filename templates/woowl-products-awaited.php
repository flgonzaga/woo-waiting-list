<?php
/**
 * WOOWL
 *
 * Products Awaited
 *
 * @author   Fabio Gonzaga
 * @package woo-waiting-list
 * @since    1.1
 */
if ( ! defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly.
}
?>
<div class="wrap">
    
	<h1><?php echo 'WOO ' . __( 'Products Awaited', 'woowl' ); ?></h1>

	<div class="welcome-panel">
	
		<?php 
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array(
			'post_type'			=> 'product',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'ASC',
			'paged'				=> $paged,
			'meta_query' => array (
						        'relation' => 'AND',
							    array (
							        'key' 		=> 'woowl_waiting_list', //The field to check.
							        'value' 	=> '', //The value of the field.
							        'compare' 	=> '!=', //Conditional statement used on the value.
							    ),
							    array (
							        'key' 		=> 'woowl_waiting_list', //The field to check.
							        'value' 	=> '[]', //The value of the field.
							        'compare' 	=> '!=', //Conditional statement used on the value.
							    ),
							),
			);

		$product_cat = filter_input(INPUT_GET, 'product_cat');
		if ($product_cat)
		{
			$args['product_cat'] = $product_cat;
		}

		// The Query
		$the_query = new WP_Query( $args );
		$max_num_pages = $the_query->max_num_pages;

		?>
		<form method="get">
			<input type="hidden" name="post_type" value="product">
			<input type="hidden" name="page" value="woowl_awaited">
			<div class="alignleft actions">
				<?php 
					wc_product_dropdown_categories( array(
						'hierarchical'       => 1,
						'show_uncategorized' => 0,
						'show_count'         => 0
					) );
				?>
				<input name="filter_action" id="post-query-submit" class="button" value="Filtrar" type="submit">		
			</div>
			<br class="clear">
			<br class="clear">
		</form>

		<?php if ( $the_query->have_posts() ) : ?>
			<table class="wp-list-table widefat fixed striped posts">
				<tr>
					<th><?php echo __('Product', 'woowl'); ?></th>
					<th><?php echo __('Customers Waiting', 'woowl'); ?></th>
					<th><?php echo __('Open', 'woowl'); ?></th>
				</tr>

				<?php while ($the_query->have_posts()) : ?>
					<?php 
						$the_query->the_post();
						$product 		= wc_get_product( get_the_ID() );
						$woowl 			= new WooWaitingList( $product );
						$woowl_cf_array = $woowl->woowl_get_list( $product );
					?>
					<tr>
						<td><?php echo get_the_title(); ?></td>
						<td><?php echo count( $woowl_cf_array ); ?></td>
						<td>
							<a href="<?php echo get_edit_post_link( get_the_ID() ); ?>" class="button button-primary button-large"><?php echo __('Open', 'woowl'); ?></a>
						</td>
					</tr>
				<?php endwhile; ?>








				<?php wp_reset_postdata(); ?>
			</table>
		<?php endif; ?>
		<br>
	</div>

</div>