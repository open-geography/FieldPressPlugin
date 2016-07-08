<?php
/**
 * This file defines the field trip class.
 *
 * @copyright Incsub (http://incsub.com/)
 *
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,
 * MA 02110-1301 USA
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Field' ) ) {

	/**
	 * This class defines the methods and properties of a Field in FieldPress.
	 *
	 * A Field object has all the required methods to manage the field trip custom
	 * post type, and the surrounding meta data used to create fields in FieldPress.
	 *
	 * A field is typically also the parent post for a Stop[].
	 *
	 * If creating a Field object outside of FieldPress make sure that FieldPress
	 * has already loaded. Hooking 'plugins_loaded' should do the trick.
	 *
	 * @todo Make sure we need !class_exists as it should be require_once() anyway.
	 *
	 * @since 1.0.0
	 * @package FieldPress
	 */
	class Field extends FieldPress_Object {

		var $id = '';
		var $output = 'OBJECT';
		var $field = array();
		var $details;
		var $data = array();

		public static $last_field_id = 0;
		public static $where_post_status = '';

		/**
		 * the field trip constructor.
		 *
		 * The constructor makes sure that it uses the WordPress Object cache to make
		 * subsequent access less resource heavy.
		 *
		 * Note: The actual post gets loaded in Field::$details;
		 *
		 * @param string $id
		 * @param string $output
		 */
		function __construct( $id = '', $output = 'OBJECT' ) {
			$this->id     = $id;
			$this->output = $output;

// Attempt to load from cache or create new cache object
			if ( ! self::load( self::TYPE_FIELD, $this->id, $this->details ) ) {

// Get the field trip
				$this->details = get_post( $this->id, $this->output );

// Initialize the field trip
				$this->init_field( $this->details );

// Cache the field trip object
				self::cache( self::TYPE_FIELD, $this->id, $this->details );
			};

			/**
			 * Perform action after a field object is created.
			 *
			 * @since 1.2.1
			 */
			do_action( 'fieldpress_field_init', $this );
		}

// PHP legacy constructor
		function Field( $id = '', $output = 'OBJECT' ) {
			$this->__construct( $id, $output );
		}

		/**
		 * Initialises a Field object.
		 *
		 * If there is no post title defined it will create a default. It also assigns additional
		 * metadata to the field trip object.
		 *
		 * @param $field
		 */
		function init_field( &$field ) {
			if ( ! empty( $field ) ) {
				if ( ! isset( $field->post_title ) || $field->post_title == '' ) {
					$field->post_title = __( 'Untitled', 'cp' );
				}
				if ( $field->post_status == 'private' || $field->post_status == 'draft' ) {
					$field->post_status = 'unpublished';
				}

				$field->allow_field_discussion = get_post_meta( $this->id, 'allow_field_discussion', true );
				$field->class_size              = get_post_meta( $this->id, 'class_size', true );
			}
		}

		/**
		 * Gets the actual WordPress post object for a Field.
		 *
		 * @return bool|null|WP_Post
		 */
		function get_field() {
			return ! empty( $this->details ) ? $this->details : false;
		}

		/**
		 * Renders the Field Trip Stop.
		 *
		 * Used in shortcodes on the front end to render the field trip hierarchy.
		 *
		 * @param string $try_title
		 * @param bool $show_try
		 * @param bool $hide_title
		 * @param bool $echo
		 */
		function field_stop_front( $try_title = '', $show_try = true, $hide_title = false, $echo = true ) {
			$show_stop    = $this->details->show_stop_boxes;
			$preview_stop = $this->details->preview_stop_boxes;

			$show_page    = $this->details->show_page_boxes;
			$preview_page = $this->details->preview_page_boxes;

			$stops = $this->get_stops();

			$content = '';

			if ( ! $echo ) {
				ob_start();
			}


			echo $hide_title ? '' : '<label>' . $this->details->post_title . '</label>';
			?>

			<ul class="tree">
			<li>
			<ul>
				<?php
				foreach ( $stops as $stop ) {
					$stop_id = $stop['post']->ID;
					$stop_post = $stop['post'];
					$stop_class = new Stop( $stop_id );
					$stop_pages = $stop_class->get_number_of_stop_pages();

//					$modules = Stop_Module::get_modules( $stop_id );

					if ( isset( $show_stop[ $stop_id ] ) && $show_stop[ $stop_id ] == 'on' && $stop_post->post_status == 'publish' ) {
						?>

						<li>

							<label for="stop_<?php echo $stop_id; ?>" class="field_stop_stop_label">
								<div class="tree-stop-left"><?php echo $stop_post->post_title; ?></div>
								<div class="tree-stop-right">

									<?php if ( $this->details->field_stop_time_display == 'on' ) { ?>
										<span><?php echo $stop_class->get_stop_time_estimation( $stop_id ); ?></span>
									<?php } ?>

									<?php
									if ( isset( $preview_stop[ $stop_id ] ) && $preview_stop[ $stop_id ] == 'on' ) {
										?>
										<a href="<?php echo Stop::get_permalink( $stop_id ); ?>?try"
										   class="preview_option"><?php
											if ( $try_title == '' ) {
												_e( 'Try Now', 'cp' );
											} else {
												echo $try_title;
											};
											?></a>
									<?php } ?>
								</div>
							</label>

							<ul>
								<?php
								for ( $i = 1; $i <= $stop_pages; $i ++ ) {
									if ( isset( $show_page[ $stop_id . '_' . $i ] ) && $show_page[ $stop_id . '_' . $i ] == 'on' ) {
										?>

										<li class="field_stop_page_li">
											<?php
											$pages_num  = 1;
											$page_title = $stop_class->get_stop_page_name( $i );
											?>

											<label for="page_<?php echo $stop_id . '_' . $i; ?>">
												<div class="tree-page-left">
													<?php echo( isset( $page_title ) && $page_title !== '' ? $page_title : __( 'Untitled Page', 'cp' ) ); ?>
												</div>
												<div class="tree-page-right">
													<?php if ( $this->details->field_stop_time_display == 'on' ) { ?>
														<span><?php echo $stop_class->get_stop_page_time_estimation( $stop_id, $i ); ?></span>
													<?php } ?>
													<?php
													if ( isset( $preview_page[ $stop_id . '_' . $i ] ) && $preview_page[ $stop_id . '_' . $i ] == 'on' ) {
														?>
														<a href="<?php echo Stop::get_permalink( $stop_id ); ?>page/<?php echo $i; ?>?try"
														   class="preview_option"><?php
															if ( $try_title == '' ) {
																_e( 'Try Now', 'cp' );
															} else {
																echo $try_title;
															};
															?></a>
													<?php } ?>

												</div>
											</label>

											<?php ?>
										</li>
										<?php
									}
								}//page visible
								?>

							</ul>
						</li>
						<?php
					}//stop visible

					if ( ! $echo ) {
						trim( ob_get_clean() );
					}
				}
				?>

			</ul>
			<?php
		}

		function is_open_ended() {

		}

		public static function get_stops_with_modules( $field_id, $force = false ) {

			$status = array( 'publish', 'draft' );

			// Force the cache clear first
			if( $force ) {
				self::kill( self::TYPE_STOP_MODULES_PERF, $field_id );
			}

			// Try cache first, else load stops and modules from DB
			if ( ! self::load( self::TYPE_STOP_MODULES_PERF, $field_id, $stops ) ) {

				self::$last_field_id = $field_id;
				$stops                = array();

				if ( ! array( $status ) ) {
					$status = array( $status );
				};


				$sql = 'AND ( ';
				foreach ( $status as $filter ) {
					$sql .= '%1$s.post_status = \'' . $filter . '\' OR ';
				}
				$sql = preg_replace( '/(OR.)$/', '', $sql );
				$sql .= ' )';

				self::$where_post_status = $sql;

				add_filter( 'posts_where', array( __CLASS__, 'filter_stop_module_where' ) );

				$post_args = array(
					'post_type'      => array(
						'stop',
						'module'
					),
					'post_parent'    => $field_id,
					'posts_per_page' => - 1,
					'order'          => 'ASC',
					'orderby'        => 'menu_order',
				);

				$query = new WP_Query( $post_args );

				foreach ( $query->posts as $post ) {
					$meta = get_post_meta( $post->ID );
					switch ( $post->post_type ) {
						case 'module':

							if ( ! isset( $stops[ $post->post_parent ] ) ) {
								$stops[ $post->post_parent ] = array(
									'post'    => array(),
									'modules' => array()
								);
							}
							$post->meta                                          = $meta;
							$post->menu_order                                    = $meta['module_order'][0];
							$stops[ $post->post_parent ]['modules'][ $post->ID ] = $post;

							break;

						case 'stop':

							if ( ! isset( $stops[ $post->ID ] ) ) {
								$stops[ $post->ID ] = array(
									'post'    => array(),
									'modules' => array()
								);
							}
							$post->meta                 = $meta;
							$post->menu_order           = $meta['stop_order'][0];
							$stops[ $post->ID ]['post'] = $post;

							break;
					}

				}

				$stops = FieldPress_Helper_Utility::sort_on_object_key( $stops, 'menu_order', true, 'post' );
				foreach ( $stops as $key => $stop ) {
					if ( ! empty( $stop['post'] ) ) {
						$stop['modules'] = FieldPress_Helper_Utility::sort_on_object_key( $stop['modules'], 'menu_order' );
					} else {
						unset( $stops[ $key ] );
					}
				}

				remove_filter( 'posts_where', array( __CLASS__, 'filter_stop_module_where' ) );

				// Cache the field trip object
				self::cache( self::TYPE_STOP_MODULES_PERF, $field_id, $stops );
			};

			if( ! current_user_can( 'manage_options' ) ) {
				return self::filter_stops( 'publish', $stops );
			} else {
				return $stops;
			}

		}

		public static function filter_stops( $status, $stops ) {

			foreach( $stops as $key => $stop ) {
				if( $stop['post']->post_status !== $status ) {
					unset( $stops[ $key ] );
				}
			}

			return $stops;
		}

		public static function get_stop( $stop_id, $field_id, $stop_only = false ) {
			$stops = self::get_stops_with_modules( $field_id );
			if( array_key_exists( $stop_id, $stops ) ) {
				if( $stop_only ) {
					return $stops[ $stop_id ]['post'];
				} else {
					return $stops[ $stop_id ];
				}
			} else {
				return false;
			}
		}

		public static function filter_stop_module_where( $sql ) {
			global $wpdb;

			$sql = 'AND ( %1$s.post_type = \'module\' AND %1$s.post_parent IN (SELECT ID FROM %1$s AS wpp WHERE wpp.post_type = \'stop\' AND wpp.post_parent = %2$d) OR (%1$s.post_type = \'stop\' AND %1$s.post_parent = %2$d ) ) ' . self::$where_post_status;
			$sql = $wpdb->prepare( $sql, $wpdb->posts, self::$last_field_id );

			return $sql;
		}


		static function get_field_featured_url( $field_id = false ) {
			if ( ! $field_id ) {
				return false;
			}

			$field = new Field( $field_id );

			if ( $field->details->featured_url !== '' ) {
				return $field->details->featured_url;
			} else {
				return false;
			}

			unset( $field );
		}

		static function get_field_thumbnail( $field_id = false ) {
			if ( ! $field_id ) {
				return false;
			}

			$thumb = get_post_thumbnail_id( $field_id );
			if ( $thumb !== '' ) {
				return $thumb;
			} else {
				self::get_field_featured_url( $field_id );
			}
		}

		static function has_field_video( $field_id = false ) {
			if ( ! $field_id ) {
				return false;
			}

			$field_video = get_post_meta( $field_id, 'field_video_url', true );

			if ( $field_video ) {
				return true;
			} else {
				return false;
			}
		}

		static function get_field_id_by_marketpress_product_id( $marketpress_product_id ) {

			$args = array(
				'post_type'      => 'field',
				'post_status'    => 'any',
				'meta_key'       => 'marketpress_product',
				'meta_value'     => $marketpress_product_id,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			);

			$post = get_posts( $args );

			if ( $post ) {
				return (int) $post[0];
			} else {
				return false;
			}
		}

		static function get_field_id_by_name( $slug ) {

			$args = array(
				'name'           => $slug,
				'post_type'      => 'field',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			);

			$post = get_posts( $args );

			if ( $post ) {
				return (int) $post[0];
			} else {
				return false;
			}
		}

		function mp_product_id( $field_id = false ) {
			$field_id     = $field_id ? $field_id : $this->id;
			$mp_product_id = (int) get_post_meta( $field_id, 'mp_product_id', true );

			if ( empty( $mp_product_id ) ) {
				$mp_product_id = (int) get_post_meta( $field_id, 'marketpress_product', true );
			}

			return get_post( $mp_product_id ) ? $mp_product_id : 0;
		}

		function update_mp_product( $field_id = false ) {

			$field_id            = $field_id ? $field_id : $this->id;
			$automatic_sku_number = 'CP-' . $field_id;

			if ( cp_use_woo() ) {
				$mp_product_id = CP_WooCommerce_Integration::woo_product_id( $field_id );
			} else {
				do_action( 'fieldpress_mp_update_product', $field_id );

				return true;

				//$mp_product_id = $this->mp_product_id( $field_id );
			}

			$post = array(
				'post_status'  => 'publish',
				'post_title'   => cp_filter_content( $this->details->post_title, true ),
				'post_type'    => 'product',
				'post_parent'  => $field_id,
				'post_content' => cp_filter_content( $this->details->post_content, true ),
			);

			// Add or Update a product if its a Paid Field Trip
			if ( isset( $_POST['meta_paid_field'] ) && 'on' == $_POST['meta_paid_field'] ) {

				if ( $mp_product_id ) {
					$post['ID'] = $mp_product_id; //If ID is set, wp_insert_post will do the UPDATE instead of insert
				}

				$post_id = wp_insert_post( $post );

				// Only works if the field trip actually has a thumbnail.
				set_post_thumbnail( $mp_product_id, get_post_thumbnail_id( $field_id ) );

				$automatic_sku = $_POST['meta_auto_sku'];

				if ( $automatic_sku == 'on' ) {
					$sku[0] = $automatic_sku_number;
				} else {
					$sku[0] = cp_filter_content( ( ! empty( $_POST['mp_sku'] ) ? $_POST['mp_sku'] : '' ), true );
				}

				if ( cp_use_woo() ) {
					update_post_meta( $this->id, 'woo_product_id', $post_id );
					update_post_meta( $this->id, 'woo_product', $post_id );

					$price      = cp_filter_content( ( ! empty( $_POST['mp_price'] ) ? $_POST['mp_price'] : 0 ), true );
					$sale_price = cp_filter_content( ( ! empty( $_POST['mp_sale_price'] ) ? $_POST['mp_sale_price'] : 0 ), true );

					update_post_meta( $post_id, '_virtual', 'yes' );
					update_post_meta( $post_id, '_sold_individually', 'yes' );
					update_post_meta( $post_id, '_sku', $sku[0] );
					update_post_meta( $post_id, '_regular_price', $price );
					update_post_meta( $post_id, '_visibility', 'visible' );

					if ( ! empty( $_POST['mp_is_sale'] ) ) {
						update_post_meta( $post_id, '_sale_price', $sale_price );
						update_post_meta( $post_id, '_price', $sale_price );
					} else {
						update_post_meta( $post_id, '_price', $price );
					}

					update_post_meta( $post_id, 'mp_is_sale', cp_filter_content( ( ! empty( $_POST['mp_is_sale'] ) ? $_POST['mp_is_sale'] : '' ), true ) );
					update_post_meta( $post_id, 'cp_field_id', $this->id );
				} else {
					//update_post_meta( $this->id, 'mp_product_id', $post_id );
					//update_post_meta( $this->id, 'marketpress_product', $post_id );
					//
					//$price		 = cp_filter_content( (!empty( $_POST[ 'mp_price' ] ) ? $_POST[ 'mp_price' ] : 0 ), true );
					//$sale_price	 = cp_filter_content( (!empty( $_POST[ 'mp_sale_price' ] ) ? $_POST[ 'mp_sale_price' ] : 0 ), true );
					//update_post_meta( $post_id, 'mp_sku', $sku );
					//update_post_meta( $post_id, 'mp_var_name', serialize( array() ) );
					//update_post_meta( $post_id, 'mp_price', $price );
					//update_post_meta( $post_id, 'mp_sale_price', $sale_price );
					//update_post_meta( $post_id, 'mp_is_sale', cp_filter_content( (!empty( $_POST[ 'mp_is_sale' ] ) ? $_POST[ 'mp_is_sale' ] : '' ), true ) );
					//update_post_meta( $post_id, 'mp_file', get_permalink( $this->id ) );
					//update_post_meta( $post_id, 'cp_field_id', $this->id );
				}
				// Remove product if its not a Paid Field Trip (clean up MarketPress products)
			} elseif ( isset( $_POST['meta_paid_field'] ) && 'off' == $_POST['meta_paid_field'] ) {
				if ( $mp_product_id && 0 != $mp_product_id ) {
					//if ( get_post_type( $mp_product_id ) == 'product' ) {
					//	wp_delete_post( $mp_product_id );
					//}
					if ( cp_use_woo() ) {
						delete_post_meta( $this->id, 'woo_product_id' );
						delete_post_meta( $this->id, 'woo_product' );
					} else {
						// Don't delete these anymore...
						//delete_post_meta( $this->id, 'mp_product_id' );
						//delete_post_meta( $this->id, 'marketpress_product' );
					}
				}
			}
		}

		function update_field() {
			global $user_id, $wpdb;

			$field = $this->get_field();

			$new_field = false;

			$post_status = empty( $this->data['status'] ) ? 'publish' : $this->data['status'];

			if ( $_POST['field_name'] != '' && $_POST['field_name'] != __( 'Untitled', 'cp' ) ) {
				if ( ! empty( $field->post_status ) && $field->post_status != 'publish' ) {
					$post_status = 'private';
				}
			} else {
				$post_status = 'draft';
			}

			$post = array(
				'post_author' => ! empty( $this->data['uid'] ) ? $this->data['uid'] : $user_id,
				// 'post_excerpt' => $_POST['field_excerpt'],
				// 'post_content' => $_POST['field_description'],
				'post_status' => $post_status,
				// 'post_title' => $_POST['field_name'],
				'post_type'   => 'field',
			);

			// If the field trip already exsists, avoid accidentally wiping out important fields.
			if ( $field ) {
				$post['post_excerpt'] = cp_filter_content( empty( $_POST['field_excerpt'] ) ? $field->post_excerpt : $_POST['field_excerpt'] );
				$post['post_content'] = cp_filter_content( empty( $_POST['field_description'] ) ? $field->post_content : $_POST['field_description'] );
				$post['post_title']   = cp_filter_content( ( empty( $_POST['field_name'] ) ? $field->post_title : $_POST['field_name'] ), true );
				if ( ! empty( $_POST['field_name'] ) ) {
					$post['post_name'] = wp_unique_post_slug( sanitize_title( $post['post_title'] ), $field->ID, 'publish', 'field', 0 );
				}
			} else {
				$new_field           = true;
				$post['post_excerpt'] = cp_filter_content( $_POST['field_excerpt'] );
				if ( isset( $_POST['field_description'] ) ) {
					$post['post_content'] = cp_filter_content( $_POST['field_description'] );
				}
				$post['post_title'] = cp_filter_content( $_POST['field_name'], true );
				$post['post_name']  = wp_unique_post_slug( sanitize_title( $post['post_title'] ), 0, 'publish', 'field', 0 );
			}

			if ( isset( $_POST['field_id'] ) ) {
				$post['ID'] = $_POST['field_id']; //If ID is set, wp_insert_post will do the UPDATE instead of insert
			}

			// Avoid ping backs
			$post['ping_status'] = 'closed';

			$post_id = wp_insert_post( apply_filters( 'fieldpress_pre_insert_post', $post ) );

			$field_order_exists = get_post_meta( $post_id, 'field_order', true );

			if ( empty( $field_order_exists ) ) {
				update_post_meta( $post_id, 'field_order', 0 );
			}

			// Clear cached object because we updated
			self::kill( self::TYPE_FIELD, $post_id );
			self::kill_related( self::TYPE_FIELD, $post_id );

			//Update post meta
			if ( $post_id != 0 ) {
				foreach ( $_POST as $key => $value ) {

					// Field Trip Category Fix
					if ( 'field_category' == $key ) {
						$_POST['meta_field_category'] = $_POST['field_category'];
					}

					if ( preg_match( "/meta_/i", $key ) ) {//every field name with prefix "meta_" will be saved as post meta automatically
						update_post_meta( $post_id, str_replace( 'meta_', '', $key ), cp_filter_content( $value ) );
					}

					if ( preg_match( "/mp_/i", $key ) ) {
						update_post_meta( $post_id, $key, cp_filter_content( $value ) );
					}

					if ( $key == 'field_category' || $key == 'meta_field_category' ) {
						if ( isset( $_POST['meta_field_category'] ) ) {
							update_post_meta( $post_id, 'field_category', cp_filter_content( $value ) );
							if ( is_array( $_POST['meta_field_category'] ) ) {
								$sanitized_array = array();
								foreach ( $_POST['meta_field_category'] as $cat_id ) {
									$sanitized_array[] = (int) $cat_id;
								}
								//wp_set_post_categories( $post_id, $sanitized_array );
								wp_set_object_terms( $post_id, $sanitized_array, 'field_category', false );
							} else {
								$cat = array( (int) $_POST['meta_field_category'] );
								if ( $cat ) {
									//wp_set_post_categories( $post_id, $cat );
									wp_set_object_terms( $post_id, $cat, 'field_category', false );
								}
							}
						} // meta_field_category
					}


					//Add featured image
					if ( ( 'meta_featured_url' == $key || '_thumbnail_id' == $key ) && ( ( isset( $_POST['_thumbnail_id'] ) && is_numeric( $_POST['_thumbnail_id'] ) ) || ( isset( $_POST['meta_featured_url'] ) && $_POST['meta_featured_url'] !== '' ) ) ) {

						$field_image_width  = get_option( 'field_image_width', 235 );
						$field_image_height = get_option( 'field_image_height', 225 );

						$upload_dir_info = wp_upload_dir();

						$fl = trailingslashit( $upload_dir_info['path'] ) . basename( $_POST['meta_featured_url'] );

						$image = wp_get_image_editor( $fl ); // Return an implementation that extends <tt>WP_Image_Editor</tt>

						if ( ! is_wp_error( $image ) ) {

							$image_size = $image->get_size();

							if ( ( $image_size['width'] < $field_image_width || $image_size['height'] < $field_image_height ) || ( $image_size['width'] == $field_image_width && $image_size['height'] == $field_image_height ) ) {
								update_post_meta( $post_id, '_thumbnail_id', cp_filter_content( $_POST['meta_featured_url'] ) );
							} else {
								$ext           = pathinfo( $fl, PATHINFO_EXTENSION );
								$new_file_name = str_replace( '.' . $ext, '-' . $field_image_width . 'x' . $field_image_height . '.' . $ext, basename( $_POST['meta_featured_url'] ) );
								$new_file_path = str_replace( basename( $_POST['meta_featured_url'] ), $new_file_name, $_POST['meta_featured_url'] );
								update_post_meta( $post_id, '_thumbnail_id', cp_filter_content( $new_file_path ) );
							}
						} else {
							update_post_meta( $post_id, '_thumbnail_id', cp_filter_content( $_POST['meta_featured_url'], true ) );
						}
					} else {
						//if ( isset( $_POST[ 'meta_featured_url' ] ) && $_POST[ 'meta_featured_url' ] == '' ) {
						//	update_post_meta( $post_id, '_thumbnail_id', '' );
						//}
					}

					//Add instructors
					if ( 'instructor' == $key && isset( $_POST['instructor'] ) ) {

						//Get last instructor ID array in order to compare with posted one
						$old_post_meta = get_post_meta( $post_id, 'instructors', false );

						if ( serialize( array( $_POST['instructor'] ) ) !== serialize( $old_post_meta ) || 0 == $_POST['instructor'] ) {//If instructors IDs don't match
							delete_post_meta( $post_id, 'instructors' );
							cp_delete_user_meta_by_key( 'field_' . $post_id );
						}

						if ( 0 != $_POST['instructor'] ) {

							update_post_meta( $post_id, 'instructors', cp_filter_content( $_POST['instructor'] ) ); //Save instructors for the field trip


							foreach ( $_POST['instructor'] as $instructor_id ) {
								$global_option = ! is_multisite();
								update_user_option( $instructor_id, 'field_' . $post_id, $post_id, $global_option ); //Link fields and instructors ( in order to avoid custom tables ) for easy MySql queries ( get instructor stats, his fields, etc. )
							}
						} // only add meta if array is sent
					}
				}

				if ( isset( $_POST['meta_paid_field'] ) ) {
					$this->update_mp_product( $post_id );
				}

				if ( $new_field ) {

					/**
					 * Perform action after field has been created.
					 *
					 * @since 1.2.1
					 */
					do_action( 'fieldpress_field_created', $post_id );
				} else {

					/**
					 * Perform action after field has been updated.
					 *
					 * @since 1.2.1
					 */
					do_action( 'fieldpress_field_updated', $post_id );
				}

				return $post_id;
			}
		}

		function delete_field( $force_delete = true ) {

			$force_delete = apply_filters( 'fieldpress_field_force_delete', $force_delete );

			/**
			 * Allow field deletion to be cancelled when filter returns true.
			 *
			 * @since 1.2.1
			 */
			if ( apply_filters( 'fieldpress_field_cancel_delete', false, $this->id ) ) {

				/**
				 * Perform actions if the deletion was cancelled.
				 *
				 * @since 1.2.1
				 */
				do_action( 'fieldpress_field_delete_cancelled', $this->id );

				return false;
			}

			// Get object before it gets destroyed
			$field = new Field( $this->id );

			// Clear cached object because we're deleting the object
			self::kill( self::TYPE_FIELD, $this->id );
			self::kill_related( self::TYPE_FIELD, $this->id );

			if ( get_post_type( $this->id ) == 'field' ) {
				wp_delete_post( $this->id, $force_delete ); //Whether to bypass trash and force deletion
			}
			/* Delete all usermeta associated to the field trip */
			cp_delete_user_meta_by_key( 'field_' . $this->id );
			cp_delete_user_meta_by_key( 'enrolled_field_date_' . $this->id );
			cp_delete_user_meta_by_key( 'enrolled_field_class_' . $this->id );
			cp_delete_user_meta_by_key( 'enrolled_field_group_' . $this->id );

			// Get list of stops from cached object
			$field_stops = Stop::get_stops_from_field( $this->id, 'any' );

			//Delete Field Trip Stops
			foreach ( $field_stops as $field_stop ) {
				$stop = new Stop( $field_stop );
				$stop->delete_stop( true );
			}

			//Delete Field Trip Discussions
			$discussion = new Discussion();
			$discussion->delete_discussion( true, $this->id );

			//Delete field notification
			$notification = new Notification();
			$notification->delete_notification( true, $this->id );

			/**
			 * Perform actions after a field is deleted.
			 *
			 * @var $field  the field trip object if the ID or post_ title is needed.
			 *
			 * @since 1.2.1
			 */
			do_action( 'fieldpress_field_deleted', $field );
		}

		function can_show_permalink() {
			$field = $this->get_field();
			if ( $field->post_status !== 'draft' ) {
				return true;
			} else {
				return false;
			}
		}

		static function get_field_instructors_ids( $field_id = false ) {
			if ( ! $field_id ) {
				return false;
			}

			$instructors     = get_post_meta( $field_id, 'instructors', true );
			$instructor_id_i = 0;
			if ( isset( $instructors ) && ! empty( $instructors ) ) {
				foreach ( $instructors as $instructor_id ) {
					$instructors[ $instructor_id_i ] = (int) $instructor_id; //make sure all are numeric values (it wasn't always the case, like for '1')
					if ( $instructor_id == 0 ) {
						unset( $instructors[ $instructor_id_i ] ); //remove zeros and empty values
					}

					$instructor_id_i ++;
				}
			}

			//re-index array
			return ! empty( $instructors ) ? $instructors : array();
		}

		static function get_field_students_ids( $field_id = false ) {
			if ( ! $field_id ) {
				return false;
			}

			$meta_key = '';
			if ( is_multisite() ) {
				$meta_key = $wpdb->prefix . 'enrolled_field_class_' . $field_id;
			} else {
				$meta_key = 'enrolled_field_class_' . $field_id;
			}

			$args = array(
				/* 'role' => 'student', */
				'meta_key' => $meta_key,
			);

			$wp_user_search = new WP_User_Query( $args );

			$student_id_i = 0;
			if ( isset( $wp_user_search ) ) {
				foreach ( $wp_user_search->results as $student ) {
					$students[ $student_id_i ] = (int) $student->ID; //make sure all are numeric values (it wasn't always the case, like for '1')
					$student_id_i ++;
				}
			}

			//re-index array
			return array_values( $students );
		}

		static function get_field_instructors( $field_id = false ) {
			global $wpdb;
			if ( ! $field_id ) {
				return false;
			}

			// Get instructor ID's to return
			$instructors = get_post_meta( $field_id, 'instructors', true );

			$args = array(
				'meta_key'     => 'field_' . $field_id,
				'meta_value'   => $field_id,
				'meta_compare' => '',
				'meta_query'   => array(),
				// Only include instructors, not students
				'include'      => $instructors,
				'orderby'      => 'display_name',
				'order'        => 'ASC',
				'offset'       => '',
				'search'       => '',
				'number'       => '',
				'count_total'  => false,
			);

			if ( is_multisite() ) {
				$args['blog_id']  = get_current_blog_id();
				$args['meta_key'] = $wpdb->prefix . 'field_' . $field_id;
			}

			return get_users( $args );
		}

		static function get_categories( $field_id = false ) {

			if ( ! $field_id ) {
				return false;
			}

			$field_category = get_post_meta( $field_id, 'field_category', true );

			if ( ! is_array( $field_category ) ) {
				$field_category = array( $field_category );
			}

			$args = array(
				'type'       => 'link_category',
				'hide_empty' => 0,
				'include'    => $field_category,
				'taxonomy'   => array( 'field_category' ),
			);

			return get_categories( $args );
		}

		function change_status( $post_status ) {
			$post = array(
				'ID'          => $this->id,
				'post_status' => $post_status,
			);
			// Update the post status
			wp_update_post( $post );

			// Clear cached object because we updated the object
			self::kill( self::TYPE_FIELD, $this->id );
			self::kill_related( self::TYPE_FIELD, $this->id );

			/**
			 * Perform actions when field status is changed.
			 *
			 * var $this->id  the field trip id
			 * var $post_status The new status
			 *
			 * @since 1.2.1
			 */
			do_action( 'fieldpress_field_status_changed', $this->id, $post_status );
		}

		function get_stops( $field_id = '', $status = 'any', $count = false ) {

			if ( $field_id == '' ) {
				$field_id = $this->id;
			}

			// Gets cached object array.
			$stops = Stop::get_stops_from_field( $field_id, $status, false );

			if ( $count ) {
				return count( $stops );
			} else {
				return $stops;
			}
		}

		function get_permalink( $field_id = '' ) {
			if ( $field_id == '' ) {
				$field_id = $this->id;
			}

			return get_permalink( $field_id );
		}

		function get_permalink_to_do( $field_id = '' ) {
			global $field_slug;
			global $stops_slug;

			if ( $field_id == '' ) {
				$field_id = get_post_meta( $post_id, 'field_id', true );
			}

			$field = new Field( $field_id );
			$field = $field->get_field();

			$stop_permalink = home_url() . '/' . $field_slug . '/' . $field->post_name . '/' . $stops_slug . '/' . $this->details->post_name . '/';

			return $stop_permalink;
		}

		function get_number_of_students( $field_id = '' ) {
			global $wpdb;

			if ( $field_id == '' ) {
				$field_id = $this->id;
			}

			$meta_key = '';
			if ( is_multisite() ) {
				$meta_key = $wpdb->prefix . 'enrolled_field_class_' . $field_id;
			} else {
				$meta_key = 'enrolled_field_class_' . $field_id;
			}

			$args = array(
				/* 'role' => 'student', */
				'meta_key' => $meta_key,
			);

			$wp_user_search = new WP_User_Query( $args );

			return count( $wp_user_search->get_results() );
		}

		function is_populated( $field_id = '' ) {
			if ( $field_id == '' ) {
				$field_id = $this->id;
			}

			$class_size = $this->get_field()->class_size;

			$number_of_enrolled_students = $this->get_number_of_students( $field_id );

			$is_limited = get_post_meta( $field_id, 'limit_class_size', true ) == 'on' ? true : false;

			if ( $is_limited ) {
				if ( $class_size == 0 ) {
					return false;
				} else {
					if ( $class_size > $number_of_enrolled_students ) {
						return false;
					} else {
						return true;
					}
				}
			} else {
				return false;
			}
		}

		function show_purchase_form( $product_id ) {
			echo do_shortcode( '[mp_product product_id="' . $product_id . '" title="true" content="full"]' );
			//echo do_shortcode( '[mp_product_meta product_id="' . $product_id . '"]' );
		}

		function is_user_purchased_field( $product_id, $user_id ) {
			global $mp;

			$args = array(
				'author'         => $user_id,
				'post_type'      => 'mp_order',
				'post_status'    => apply_filters( 'cp_is_user_purchased_mp_order_status', 'order_paid' ),
				'posts_per_page' => '-1'
			);

			$purchases = get_posts( $args );

			foreach ( $purchases as $purchase ) {

				$purchase_records = $mp->get_order( $purchase->ID );

				if ( array_key_exists( $product_id, $purchase_records->mp_cart_info ) ) {
					return true;
				}

				return false;
			}
		}

		function duplicate( $field_id = '' ) {
			global $wpdb;

			if ( $field_id == '' ) {
				$field_id = $this->id;
			}

			/**
			 * Allow field duplication to be cancelled when filter returns true.
			 *
			 * @since 1.2.1
			 */
			if ( apply_filters( 'fieldpress_field_cancel_duplicate', false, $field_id ) ) {

				/**
				 * Perform actions if the duplication was cancelled.
				 *
				 * @since 1.2.1
				 */
				do_action( 'fieldpress_field_duplicate_cancelled', $field_id );

				return false;
			}

			/* Duplicate field and change some data */

			$new_field    = $this->get_field();
			$old_field_id = $new_field->ID;
			unset( $new_field->ID );
			unset( $new_field->guid );

			$new_field->post_author = get_current_user_id();
			$new_field->post_status = 'private';
			$new_field->post_name   = $new_field->post_name . '-copy';
			$new_field->post_title  = $new_field->post_title . ' (copy)';

			$new_field_id = wp_insert_post( $new_field );

			/*
			 * Duplicate field post meta
			 */

			if ( ! empty( $new_field_id ) ) {
				$post_metas = get_post_meta( $old_field_id );
				foreach ( $post_metas as $key => $meta_value ) {
					$value = array_pop( $meta_value );
					$value = maybe_unserialize( $value );
					update_post_meta( $new_field_id, $key, $value );
				}
			}

			delete_post_meta( $new_field_id, 'meta_mp_product_id' );
			delete_post_meta( $new_field_id, 'mp_product_id' );
			delete_post_meta( $new_field_id, 'mp_sale_price' );
			delete_post_meta( $new_field_id, 'mp_price' );
			delete_post_meta( $new_field_id, 'mp_is_sale' );
			delete_post_meta( $new_field_id, 'mp_sku' );
			delete_post_meta( $new_field_id, 'auto_sku' );
			delete_post_meta( $new_field_id, 'paid_field' );
			delete_post_meta( $new_field_id, 'marketpress_product' );


			$stops = $this->get_stops( $old_field_id );

			foreach ( $stops as $stop ) {
				$stop_id = $stop['post']->ID;
				$unt = new Stop( $stop_id );
				$unt->duplicate( $stop_id, $new_field_id );
			}

			do_action( 'fieldpress_field_duplicated', $new_field_id );
		}

		public function enrollment_details() {

			$this->enroll_type           = get_post_meta( $this->id, 'enroll_type', true );
			$this->field_start_date     = get_post_meta( $this->id, 'field_start_date', true );
			$this->field_end_date       = get_post_meta( $this->id, 'field_end_date', true );
			$this->field_start_time     = get_post_meta( $this->id, 'field_start_time', true );
            $this->field_end_time       = get_post_meta( $this->id, 'field_end_time', true );
			$this->enrollment_start_date = get_post_meta( $this->id, 'enrollment_start_date', true );
			$this->enrollment_end_date   = get_post_meta( $this->id, 'enrollment_end_date', true );
			$this->open_ended_field     = 'off' == get_post_meta( $this->id, 'open_ended_field', true ) ? false : true;
			$this->open_ended_enrollment = 'off' == get_post_meta( $this->id, 'open_ended_enrollment', true ) ? false : true;
			$this->prerequisite          = get_post_meta( $this->id, 'prerequisite', true );

			$this->is_paid = get_post_meta( $this->id, 'paid_field', true );
			$this->is_paid = $this->is_paid && 'on' == $this->is_paid ? true : false;

			//$this->field_started     = strtotime( $this->field_start_date ) <= current_time( 'timestamp', 0 ) ? true : false;
			$this->field_started = true;
			$this->enrollment_started = strtotime( $this->enrollment_start_date ) <= current_time( 'timestamp', 0 ) ? true : false;
			$this->field_expired     = strtotime( $this->field_end_date ) + 86400 < current_time( 'timestamp', 1 ) ? true : false;
			$this->enrollment_expired = strtotime( $this->enrollment_end_date ) < current_time( 'timestamp', 0 ) ? true : false;
			$this->full               = $this->is_populated();
		}

		public static function get_allowed_pages( $field_id ) {

			$pages = array(
				'field_discussion' => get_post_meta( $field_id, 'allow_field_discussion', true ),
				'workbook'          => get_post_meta( $field_id, 'allow_workbook_page', true ),
			);

			return $pages;
		}

		public static function get_field_time_estimation( $field_id, $status = 'any' ) {

			$field_time    = '';
			$field_seconds = 0;
			$stops          = Stop::get_stops_from_field( $field_id, $status, false );

			foreach ( $stops as $stop ) {
				$stop_id = $stop['post']->ID;
				$stop_details = new Stop( $stop_id );
				$stop_time    = $stop_details->get_stop_time_estimation( $stop_id );

				$min_sec = explode( ':', $stop_time );
				if ( isset( $min_sec[0] ) ) {
					$field_seconds += intval( $min_sec[0] ) * 60;
				}
				if ( isset( $min_sec[1] ) ) {
					$field_seconds += intval( substr( $min_sec[1], 0, 2 ) );
				}
			}
			$total_seconds  = round( $field_seconds );
			$formatted_time = sprintf( '%02d:%02d:%02d', ( $total_seconds / 3600 ), ( $total_seconds / 60 % 60 ), $total_seconds % 60 );

			$field_time = apply_filters( 'fieldpress_field_get_time_estimation', $formatted_time, $total_seconds, $field_id );

			return $field_time;
		}

	}

}

































