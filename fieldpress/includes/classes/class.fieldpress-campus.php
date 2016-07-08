<?php
/**
 * @copyright Incsub ( http://incsub.com/ )
 *
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 ( GPL-2.0 )
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


if ( ! class_exists( 'FieldPress_Campus' ) ) {

	/**
	 * CampusPress/Edublogs Specifics
	 *
	 * @since 1.2.1
	 *
	 */
	class FieldPress_Campus {


		function __construct() {

			// Administration area
			if ( is_admin() ) {
			}

			// Public area

			/**
			 * Remove FieldPress payable features.
			 *
			 * @since 1.2.1
			 */
			add_filter( 'fieldpress_offer_paid_fields', array( &$this, 'remove_paid_feature' ) );

			/**
			 * Remove timeout from wp_remote_ request.
			 *
			 * @since 1.2.1
			 */
			add_filter( 'fieldpress_force_download_parameters', array( &$this, 'remove_timeout_from_request' ) );

			// Shortcode Filters
			$this->add_shortcode_filters();


		}

		/**
		 * Remove FieldPress payable features.
		 *
		 * Users will no longer be able to offer fields for sale.
		 *
		 * @since 1.2.1
		 *
		 * @param bool $offer_paid Offer payable fields.
		 */
		function remove_paid_feature( $offer_paid ) {
			$offer_paid = false;

			return $offer_paid;
		}

		/**
		 * Remove timeout from wp_remote_ request.
		 *
		 * @since 1.2.1
		 */
		function remove_timeout_from_request( $header_params ) {
			unset( $header_params['timeout'] );

			return $header_params;
		}

		/*
		 * ======== SHORTCODE FILTERS ========
		 */

		/**
		 * Add filters for shortcodes.
		 *
		 * @since 1.2.1
		 */
		function add_shortcode_filters() {

			add_filter( 'shortcode_atts_field_cost', array( &$this, 'remove_shortcode_cost_labels' ), 10, 3 );
			add_filter( 'shortcode_atts_field_stop', array( &$this, 'remove_shortcode_cost_labels' ), 10, 3 );

		}

		/**
		 * Remove FREE price label from shortcodes.
		 *
		 * @since 1.2.1
		 */
		function remove_shortcode_cost_labels( $out, $pairs, $atts ) {

			if ( isset( $out['show_icon'] ) ) {
				$out['show_icon'] = false;
			}
			if ( isset( $out['no_cost_text'] ) ) {
				$out['no_cost_text'] = '';
			}

			return $out;
		}


	}
}

if ( FieldPress_Capabilities::is_campus() ) {
	$fieldpress_campus = new FieldPress_Campus();
}