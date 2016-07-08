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
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'FieldPress_Object' ) ) {

	/**
	 * FieldPress object class.
	 *
	 * Add's features to FieldPress objects (for example caching).
	 *
	 * @since 1.2.1
	 *
	 * @return object
	 */
	class FieldPress_Object {

		// Primary FieldPress types
		const TYPE_FIELD = 'fieldpress_field';
		const TYPE_STOP = 'fieldpress_stop';
		const TYPE_MODULE = 'fieldpress_module';
		const TYPE_MODULE_RESPONSE = 'fieldpress_module_response';
		const TYPE_STOP_MODULES = 'fieldpress_stop_modules';
		const TYPE_STOP_MODULES_PERF = 'fieldpress_stop_modules_perf';
		const TYPE_STOP_STATIC = 'fieldpress_stop_static';

		protected static function load( $type, $key, &$object = null ) {
			$found = false;
			// USE OBJECT CACHE
			$object = wp_cache_get( $key, $type, false, $found );
			$found = empty( $object ) ? false : $found;
			return $found;
		}

		protected static function cache( $type, $key, $object ) {
			if ( ! empty( $key ) ) {
				// USE OBJECT CACHE
				wp_cache_set( $key, $object, $type );
			}
		}

		protected static function kill( $type, $key ) {
			// REMOVE OBJECT CACHE OBJECT
			wp_cache_delete( $key, $type );
		}

		protected static function kill_related( $type, $key ) {
			switch ( $type ) {

				case self::TYPE_FIELD:
					// Field related caches to kill
					self::kill( self::TYPE_STOP_STATIC, 'list-publish-' . $key );
					self::kill( self::TYPE_STOP_STATIC, 'list-any-' . $key );
					self::kill( self::TYPE_STOP_STATIC, 'object-publish-' . $key );
					self::kill( self::TYPE_STOP_STATIC, 'object-any-' . $key );
					break;
			}
		}

	}

}