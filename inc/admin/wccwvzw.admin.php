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
 * Handles admin functionality
 */
add_filter( 'plugin_action_links_' . WCCWVZW_PLUGIN_BASENAME, 'wccwvzw_add_action_links' );
function wccwvzw_add_action_links ( $links ) {
	$settingslinks = array(
		 '<a href="' . admin_url( 'admin.php?page=customer-also-viewed-settings' ) . '">'. __( 'Settings', 'customer-who-viewed-this-item-also-viewed-using-woocommerce') .'</a>',
		 );
		return array_merge( $settingslinks, $links );
}

register_activation_hook (WCCWVZW_FILE, 'wccwvzw_activation_check');
function wccwvzw_activation_check() {
	if( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		wp_die( __( '<b>Warning</b> : Install/Activate Woocommerce to activate "WooCommerce - Customer Who Viewed This Item Also Viewed" plugin', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ) ); //phpcs:ignore
	}
}
/**
 * Set up submenu under woocommerce main menu at admin side
 */
//Set up menu under woocommerce
add_action('admin_menu', 'wccwvzw_customer_also_viewed_setup_menu');
function wccwvzw_customer_also_viewed_setup_menu(){
				add_submenu_page( 'woocommerce', 'Customer Also Viewed Settings', 'Customer Also Viewed Settings', 'manage_options', 'customer-also-viewed-settings', 'wccwvzw_customer_also_viewed_init');
}
/**
 * Initialize the plugin and display all options at admin side
 */
function wccwvzw_customer_also_viewed_init(){
?>
	<h1><?php echo esc_html_e( 'Customer Who Viewed This Item Also Viewed Using Woocommerce', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ); ?></h1>
	<form method="post" action="options.php">
		<?php settings_fields( 'customer-also-viewed-settings' ); ?>
		<?php do_settings_sections( 'customer-also-viewed-settings' ); ?>
		<table class="form-table">
				<tr valign="top">
						<th scope="row"><?php echo esc_html_e( 'Title to be displayed', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ).':'; ?></th>
						<td><input type="text" name="customer_who_viewed_title" value="<?php echo esc_attr(get_option( 'customer_who_viewed_title' )); ?>"maxlength="100"/>&nbsp;&nbsp;&nbsp;<?php echo esc_html_e( 'NOTE: you can wright 100 words displayed Title ', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ); ?></td></td>
						
				</tr>
				<tr valign="top">
						<th scope="row"><?php echo esc_html_e( 'Number of items to be displayed', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ).':'; ?></th>
						<td><input type="text" name="total_items_display" value="<?php echo esc_attr(get_option( 'total_items_display' )); ?>"/>&nbsp;&nbsp;&nbsp;<?php echo esc_html_e( 'NOTE: You cannot display items more than 10', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ); ?></td>
			 </tr>
				<tr valign="top">
						<th scope="row"><?php echo esc_html_e( 'Add category filter', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ).':'; ?></th>
						<td>
								<input type="checkbox" name="category_filter" value="1" <?php echo (get_option( 'category_filter' ) == 1) ? 'checked': '';?>/>
						</td>
				</tr>
				<tr valign="top">
						<th scope="row"><?php echo esc_html_e( 'Show product image', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ).':'; ?></th>
						<td>
								<input type="checkbox" name="show_image_filter" value="1" <?php echo (get_option( 'show_image_filter' ) == 1) ? 'checked': '';?>/>
						</td>
				</tr>
				<tr valign="top">
						<th scope="row"><?php echo esc_html_e( 'Show product price', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ).':'; ?></th>
						<td>
								<input type="checkbox" name="show_price_filter" value="1" <?php echo (get_option( 'show_price_filter' ) == 1) ? 'checked': '';?>/>
						</td>
				</tr>
				 <tr valign="top">
						<th scope="row"><?php echo esc_html_e( 'Show add to cart button', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ).':'; ?></th>
						<td>
								<input type="checkbox" name="show_addtocart_filter" value="1" <?php echo (get_option( 'show_addtocart_filter' ) == 1) ? 'checked': '';?>/>
						</td>
				</tr>
				<tr valign="top">
						<th scope="row"><?php echo esc_html_e( 'Order by', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ).':'; ?></th>
						<td>
								<select name = "product_order">
										<option value="" ><?php echo esc_html_e( 'Recent', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ); ?></option>
										<option value="rand" <?php echo (get_option( 'product_order' ) == 'rand') ? 'selected': '';?>><?php echo esc_html_e( 'Random', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ); ?></option>
								</select>
						</td>
				</tr>
		</table>
		<?php submit_button(); ?>
		</div>
	</form>
<?php
}
/**
* Admin footer scripts
*/
add_action('admin_footer', 'wccwvzw_validate_number_of_items');

function wccwvzw_validate_number_of_items(){
	$script = "<script>
				jQuery('input[name=total_items_display]').change(function() {
				var val = Math.abs(parseInt(this.value, 10) || 1);
				this.value = val > 10 ? 3 : val;
			});
	</script>";
	echo $script; //phpcs:ignore
}

/**
 * Registers all the setting options
 */
add_action( 'admin_init', 'wccwvzw_customer_who_viewed_register_mysettings' );
function wccwvzw_customer_who_viewed_register_mysettings() {
	register_setting( 'customer-also-viewed-settings', 'customer_who_viewed_title' );
	register_setting( 'customer-also-viewed-settings', 'total_items_display' );
	register_setting( 'customer-also-viewed-settings', 'category_filter' );
	register_setting( 'customer-also-viewed-settings', 'show_image_filter' );
	register_setting( 'customer-also-viewed-settings', 'show_price_filter' );
	register_setting( 'customer-also-viewed-settings', 'show_addtocart_filter' );
	register_setting( 'customer-also-viewed-settings', 'product_order' );
}
