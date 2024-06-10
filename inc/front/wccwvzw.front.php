<?php
/**
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @subpackage Customer Who Viewed This Item Also Viewed Using Woocommerce
 * @since 2.4
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Track product views.
 */
add_action( 'template_redirect', 'wccwvzw_custom_track_product_view', 20 );
function wccwvzw_custom_track_product_view() {
	if ( ! is_singular( 'product' )) {
		return;
	}

	global $post;

	if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) )
		$viewed_products = array();
	else
		$viewed_products = (array) explode( '|', wp_unslash($_COOKIE['woocommerce_recently_viewed']) ); //phpcs:ignore

	if ( ! in_array( $post->ID, $viewed_products ) ) {
		$viewed_products[] = $post->ID;
	}

	if ( sizeof( $viewed_products ) > 15 ) {
		array_shift( $viewed_products );
	}

	// Store for session only
	wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
 /**
* Add related products to options
*/
add_action('woocommerce_after_single_product', 'wccwvzw_customer_who_viewed_relation_product_options_woocommerce');
function wccwvzw_customer_who_viewed_relation_product_options_woocommerce()
{
	// Get WooCommerce Global
	global $woocommerce;
	global $post;

	$customer_also_viewed = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash($_COOKIE['woocommerce_recently_viewed']) ) : array();  //phpcs:ignore

	if(($key = array_search($post->ID, $customer_also_viewed)) !== false) { unset($customer_also_viewed[$key] ); }

	if(!empty($customer_also_viewed))
	{
			foreach($customer_also_viewed as $viewed)
			{
				$option = 'customer_also_viewed_'.$viewed;
				$option_value = get_option($option);
				$option_value = explode(',', $option_value);
				if(!in_array($post->ID,$option_value))
				{
					$option_value[] = $post->ID;
				}
				$option_value = (count($option_value) > 1) ? implode(',', $option_value) : $post->ID;
				update_option($option, $option_value);
			}
	}
}
/**
 *
 * @global type $woocommerce
 * @global type $post
 */
add_action("woocommerce_after_single_product", "wccwvzw_customer_who_viewed_also_viewed_this_item_woocommerce");
function wccwvzw_customer_who_viewed_also_viewed_this_item_woocommerce( $atts, $content = null ) 
{
	$per_page = get_option( 'total_items_display' );
	$plugin_title = get_option( 'customer_who_viewed_title' );
	$category_filter = get_option( 'category_filter' );
	$show_image_filter = get_option( 'show_image_filter' );
	$show_price_filter = get_option( 'show_price_filter' );
	$show_addtocart_filter = get_option( 'show_addtocart_filter' );
	$product_order = get_option( 'product_order' );
	// Get WooCommerce Global
	global $woocommerce;
	global $post;
	// Get recently viewed product data using get_option

	$customer_also_viewed = get_option('customer_also_viewed_'.$post->ID);
	if(!empty($customer_also_viewed))
	{
		$customer_also_viewed = explode(',',$customer_also_viewed);
		$customer_also_viewed = array_reverse($customer_also_viewed);

		//Skip same product on product page from the list
		if(($key = array_search($post->ID, $customer_also_viewed)) !== false) { unset($customer_also_viewed[$key] ); }

		$per_page = ($per_page == "")? $per_page = 5 : $per_page;
		$plugin_title = ($plugin_title == "")? $plugin_title = 'Customer Who Viewed This Item Also Viewed' : $plugin_title;

		// Create the object
		ob_start();

		$categories = get_the_terms( $post->ID, 'product_cat' );

		// Create query arguments array

		$query_args = array(
			'posts_per_page' => $per_page,
			'no_found_rows'  => 1,
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'post__in'       => $customer_also_viewed
		);

		$query_args['orderby'] = ($product_order == '') ? 'ID(ID, explode('.implode(",",$customer_also_viewed).'))' : $product_order;

		//Executes if category filter applied on product page
		if($category_filter == 1 && !empty($categories)) {
		$category_slug = '';
		foreach ($categories as $category) {
			if($category->parent == 0){
					$category_slug = $category->slug;
			}
		}
		if($category_slug != '') {
			$query_args['product_cat'] = $category_slug;
		}
	}

	// Add meta_query to query args
	$query_args['meta_query'] = array();

	// Check products stock status
	$query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();

	// Create a new query
	$products = new WP_Query($query_args);

	// If query return results
	if ( !$products->have_posts() ) {
			// If no data, quit
			exit;
	}
	else { //Displays title ?>
		<section class="related products customer_also_viewed_wrapper">
			<h2><?php esc_html_e( $plugin_title, 'woocommerce' ) ?></h2>
				<?php // Start the loop
				$count = 1;
				woocommerce_product_loop_start();
				while ( $products->have_posts() ) : $product = $products->the_post(); ?>
					<li <?php wc_product_class( '', $product ); ?>>
							<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
								<?php if($show_image_filter == 1) { do_action( 'woocommerce_before_shop_loop_item_title' ); } ?>
									<h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
								<?php if($show_price_filter == 1){ do_action( 'woocommerce_after_shop_loop_item_title' ); } ?>
							</a>
						<?php if($show_addtocart_filter == 1) { do_action( 'woocommerce_after_shop_loop_item' ); } ?>
					</li>
				<?php endwhile; ?>
				<?php woocommerce_product_loop_end(); ?>
		</section>
		<?php }
		wp_reset_postdata();
	}
}