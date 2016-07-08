<?php
/*
 * Integration with WooCommerce plugin
 * https://wordpress.org/plugins/woocommerce/
 *
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( !class_exists( 'CP_WooCommerce_Integration' ) ) {
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

		class CP_WooCommerce_Integration {

			function __construct() {
				add_action( 'add_meta_boxes', array( &$this, 'add_post_parent_metaboxe' ) );
				add_action( 'woocommerce_process_product_meta_simple', array( &$this, 'woo_save_post' ), 999 );
				add_action( 'fieldpress_general_options_page', array( &$this, 'add_woocommerce_general_option' ) );
				add_action( 'fieldpress_update_settings', array( &$this, 'save_woocommerce_general_option' ), 10, 2 );
				add_action( 'woocommerce_order_details_after_order_table', array( &$this, 'show_field_message_woocommerce_order_details_after_order_table' ), 10, 2 );
				add_filter( 'woocommerce_cart_item_name', array( &$this, 'change_cp_item_name' ), 10, 3 );
				add_filter( 'woocommerce_order_item_name', array( &$this, 'change_cp_order_item_name' ), 10, 2 );
			}

			function add_post_parent_metaboxe() {
				add_meta_box( 'cp_woo_post_parent', __( 'Parent Field Trip', 'cp' ), array( &$this, 'cp_woo_post_parent' ), 'product', 'side', 'default' );
			}

			function woo_save_post() {
				global $post;
				if ( $post->post_type == 'product' ) {
					if ( isset( $_POST[ 'parent_field' ] ) && !empty( $_POST[ 'parent_field' ] ) ) {
						wp_update_post( array( 'ID' => $post->ID, 'post_parent' => (int) $_POST[ 'parent_field' ] ) );
					}
				}
			}

			function cp_woo_post_parent() {
				global $post;
				if ( isset( $post->ID ) ) {
					?>
					<input type="text" name="parent_field" value="<?php echo esc_attr( wp_get_post_parent_id( $post->ID ) ); ?>" />
					<?php
				}
			}

			function show_field_message_woocommerce_order_details_after_order_table( $order ) {
				global $fieldpress;


				$order_details		 = new WC_Order( $order->id );
				$order_items		 = $order_details->get_items();
				$purchased_field	 = false;

				foreach ( $order_items as $order_item ) {
					$field_id = wp_get_post_parent_id( $order_item[ 'product_id' ] );
					if ( $field_id && get_post_type( $field_id ) == 'field' ) {
						$purchased_field = true;
					}
				}

				if ( $purchased_field ) {
					?>
					<h2 class="cp_woo_header"><?php _e( 'Field Trip', 'cp' ); ?></h2>
					<p class="cp_woo_thanks"><?php _e( 'Thank you for signing up for the field trip. We hope you enjoy your experience.' ); ?></p>
					<?php
					if ( is_user_logged_in() && $order->post_status == 'wc-completed' ) {
						?>
						<p class="cp_woo_dashboard_link">
							<?php printf( __( 'You can find the field trip in your <a href="%s">Dashboard</a>', 'cp' ), $fieldpress->get_student_dashboard_slug( true ) ) ?>
						</p>
						<hr />
						<?php
					}
				}
			}

			function change_cp_item_name( $title, $cart_item, $cart_item_key ) {
				$field_id = wp_get_post_parent_id( $cart_item[ 'product_id' ] );
				if ( $field_id && get_post_type( $field_id ) == 'field' ) {
					return get_the_title( $field_id );
				}
				return $title;
			}

			function change_cp_order_item_name( $name, $item ) {
				$product_id	 = isset( $item[ 'item_meta' ][ '_product_id' ] ) ? $item[ 'item_meta' ][ '_product_id' ] : '';
				$product_id	 = $product_id[ 0 ];
				if ( is_numeric( $product_id ) ) {
					$field_id = wp_get_post_parent_id( $product_id );
					if ( $field_id && get_post_type( $field_id ) == 'field' ) {
						return get_the_title( $field_id );
					}
				}
				return $name;
			}

			function add_woocommerce_general_option() {
				?>
				<div class="postbox">
					<h3 class="hndle" style='cursor:auto;'><span><?php _e( 'WooCommerce Integration', 'cp' ); ?></span></h3>

					<div class="inside">
						<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row"><?php _e( 'Use WooCommerce to sell field trips', 'cp' ); ?></th>
									<td>
										<a class="help-icon" href="javascript:;"></a>

										<div class="tooltip">
											<div class="tooltip-before"></div>
											<div class="tooltip-button">&times;</div>
											<div class="tooltip-content">
												<?php _e( 'If checked, WooCommerce will be use instead of the MarketPress for selling fields', 'cp' ) ?>
											</div>
										</div>
										<input type='checkbox' name='option_use_woo' <?php echo( ( get_option( 'use_woo', 0 ) ) ? 'checked' : '' ); ?> />
									</td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php _e( 'Redirect WooCommerce product post to a parent field post', 'cp' ); ?></th>
									<td>
										<a class="help-icon" href="javascript:;"></a>

										<div class="tooltip">
											<div class="tooltip-before"></div>
											<div class="tooltip-button">&times;</div>
											<div class="tooltip-content">
												<?php _e( 'If checked, visitors who try to access WooCommerce single post will be automatically redirected to a parent field single post.', 'cp' ) ?>
											</div>
										</div>
										<input type='checkbox' name='option_redirect_woo_to_field' <?php echo( ( get_option( 'redirect_woo_to_field', 0 ) ) ? 'checked' : '' ); ?> />
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
			}

			function save_woocommerce_general_option( $tab, $post ) {
				if ( $tab == 'general' ) {
					if ( isset( $post[ 'option_use_woo' ] ) ) {
						update_option( 'use_woo', 1 );
					} else {
						update_option( 'use_woo', 0 );
					}

					if ( isset( $post[ 'option_redirect_woo_to_field' ] ) ) {
						update_option( 'redirect_woo_to_field', 1 );
					} else {
						update_option( 'redirect_woo_to_field', 0 );
					}
				}
			}

			public static function woo_product_id( $field_id = false ) {
				$args = array(
					'posts_per_page' => 1,
					'post_type'		 => 'product',
					'post_parent'	 => $field_id,
					'post_status'	 => 'publish',
					'fields'		 => 'ids',
				);

				$products = get_posts( $args );

				if ( isset( $products[ 0 ] ) ) {
					return (int) $products[ 0 ];
				} else {
					return false;
				}
			}

			public static function add_product_to_cart( $product_id ) {
				global $woocommerce;
				$found = false;

				//check if product already in cart
				if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
					foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
						$_product	 = $values[ 'data' ];
						if ( $_product->id == $product_id )
							$found		 = true;
					}
					// if product not found, add it
					if ( !$found )
						$woocommerce->cart->add_to_cart( $product_id );
				} else {
					// if no products in cart, add it
					$woocommerce->cart->add_to_cart( $product_id );
				}
			}

		}

		$cp_woo = new CP_WooCommerce_Integration();
	}
}