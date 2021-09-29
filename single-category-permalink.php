<?php
/**
 * Plugin Name: Single Category Permalink
 * Version:     2.5.2
 * Plugin URI:  https://coffee2code.com/wp-plugins/single-category-permalink/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: single-category-permalink
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Reduce permalinks (category or post) that include entire hierarchy of categories to just having the lowest level category.
 *
 * Compatible with WordPress 4.6 through 5.8+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/single-category-permalinks/
 *
 * @package Single_Category_Permalink
 * @author  Scott Reilly
 * @version 2.5.2
 */

/*
	Copyright (c) 2007-2021 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_SingleCategoryPermalink' ) ) :

class c2c_SingleCategoryPermalink {

	/**
	 * Returns version of the plugin.
	 *
	 * @since 2.2
	 */
	public static function version() {
		return '2.5.2';
	}

	/**
	 * Initialization.
	 *
	 * @since 2.2
	 */
	public static function init() {
		// Load textdomain.
		load_plugin_textdomain( 'single-category-permalink' );

		add_filter( 'term_link',         array( __CLASS__, 'category_link' ),    10, 3 );
		add_filter( 'post_link',         array( __CLASS__, 'post_link' ),        10, 2 );
		add_filter( 'template_redirect', array( __CLASS__, 'template_redirect' )       );
	}

	/**
	 * Returns the HTTP status to use for redirects.
	 *
	 * @since 2.2
	 * @uses filter c2c_single_category_redirect_code
	 *
	 * @return string
	 */
	public static function get_http_redirect_status() {
		/**
		 * Filters the HTTP status code used for redirects.
		 *
		 * @since 2.0
		 *
		 * @param int The HTTP status code to be used for redirects. Default 301.
		 */
		return (int) apply_filters( 'c2c_single_category_redirect_status', 301 );
	}

	/**
	 * Returns category URI for a given category.
	 *
	 * If the given category is hierarchical, then this function kicks into gear to
	 * reduce a hierarchical category structure to its lowest category in the link.
	 *
	 * @param  string $catlink     The default URI for the category
	 * @param  int    $category_id The category ID
	 * @param  string $taxonomy    Taxonomy slug. Default 'category'.
	 * @return string|WP_Error|null The category URI. WP_Error if category is
	 *                              empty, null if it does not exist.
	 */
	public static function category_link( $catlink, $category_id, $taxonomy = 'category' ) {
		global $wp_rewrite;

		// Bail early if taxonomy is not 'category'.
		if ( 'category' !== $taxonomy ) {
			return $catlink;
		}

		$catlink = $wp_rewrite->get_category_permastruct();

		if ( ! $catlink ) {
			$file    = trailingslashit( get_option( 'siteurl' ) );
			$catlink = $file . '?cat=' . $category_id;
		} else {
			$category = get_category( $category_id );
			if ( ! $category || is_wp_error( $category ) ) {
				return $category;
			}
			$category_nicename = $category->slug;

			//$catlink = str_replace('/category/', '/', $catlink);
			$catlink = str_replace( '%category%', $category_nicename, $catlink );
			$catlink = home_url( user_trailingslashit( $catlink, 'category' ) );
		}

		return $catlink;
	}

	/**
	 * Returns post URI for a given post.
	 *
	 * If the post permalink structure includes %category%, then this function
	 * kicks into gear to reduce a hierarchical category structure to its lowest
	 * category.
	 *
	 * @param  string  $permalink The default URI for the post
	 * @param  WP_Post $post      The post
	 * @return string             The post URI.
	 */
	public static function post_link( $permalink, $post ) {
		$permalink_structure = get_option( 'permalink_structure' );

		// Only do anything if '%category%' is part of the post permalink
		if ( strpos( $permalink_structure, '%category%' ) !== false ) {

			// Find the canonical category for the post (assigned category with
			// lowest id)
			$cats = get_the_category( $post->ID );
			if ( $cats ) {
				// Order categories by term_id.
				if ( function_exists( 'wp_list_sort' ) ) { // Introduced in WP 4.7
					$cats = wp_list_sort( $cats, 'term_id' );
				} else {
					usort( $cats, '_usort_terms_by_ID' );
				}
				$category = $cats[0];
			} else {
				$category = get_category( absint( get_option( 'default_category' ) ) );
			}

			// Find category hierachy for the category. By default, these would be
			// part of the full category permalink.
			$category_hierarchy = $category->slug;
			if ( $parent = $category->parent ) {
				$category_hierarchy = get_category_parents( $parent, false, '/', true ) . $category->slug;
			}

			// Now that the permalink component involving category hierarchy consists of is known, get rid of it.
			$permalink = str_replace( $category_hierarchy, $category->slug, $permalink );
		}

		return $permalink;
	}

	/**
	 * Redirects fully hierarchical category links to the single category link.
	 *
	 * @since 2.0
	 */
	public static function template_redirect() {
		global $wp_query, $post;

		$redirect      = null;
		$category_name = isset( $wp_query->query['category_name'] ) ? $wp_query->query['category_name'] : '';

		if ( is_category() ) {
			if ( $category_name && $category_name != $wp_query->query_vars['category_name'] ) {
				$redirect = c2c_SingleCategoryPermalink::category_link( '', $wp_query->query_vars['cat'] );
			}
		}
		elseif ( is_single() ) {
			if ( $category_name && substr_count( $category_name, '/' ) > 1 ) {
				$redirect = get_permalink( $post );
			}
		}

		if ( $redirect ) {
			wp_redirect( $redirect, self::get_http_redirect_status() );
		}
	}

} // end c2c_SingleCategoryPermalink

add_action( 'plugins_loaded', array( 'c2c_SingleCategoryPermalink', 'init' ) );

endif; // end if !class_exists()


if ( ! function_exists( 'c2c_single_category_postlink' ) ) :
/**
 * Returns post URI for a given post.
 *
 * If the post permalink structure includes %category%, then this function
 * kicks into gear to reduce a hierarchical category structure to its lowest
 * category.
 *
 * @deprecated 2.2 Use c2c_SingleCategoryPermalink::post_link() instead.
 *
 * @param  string  $permalink The default URI for the post
 * @param  WP_Post $post      The post
 * @return string  The post URI
 */
function c2c_single_category_postlink( $permalink, $post ) {
	_deprecated_function( __FUNCTION__, '2.2', 'c2c_SingleCategoryPermalink::post_link()' );

	return c2c_SingleCategoryPermalink::post_link( $permalink, $post );
}
endif;

if ( ! function_exists( 'c2c_single_category_catlink' ) ) :
/**
 * Returns category URI for a given category.
 *
 * If the given category is hierarchical, then this function kicks into gear to
 * reduce a hierarchical category structure to its lowest category in the link.
 *
 * @deprecated 2.2 Use c2c_SingleCategoryPermalink::category_link() instead.
 *
 * @param  string $catlink     The default URI for the category
 * @param  int    $category_id The category ID
 * @return string The category URI
 */
function c2c_single_category_catlink( $catlink, $category_id ) {
	_deprecated_function( __FUNCTION__, '2.2', 'c2c_SingleCategoryPermalink::category_link()' );

	return c2c_SingleCategoryPermalink::category_link( $catlink, $category_id );
}
endif;

if ( ! function_exists( 'c2c_single_category_redirect' ) ) :
/**
 * Redirects fully hierarchical category links to the single category link.
 *
 * @since 2.0
 * @deprecated 2.2 Use c2c_SingleCategoryPermalink::template_redirect() instead.
 */
function c2c_single_category_redirect() {
	_deprecated_function( __FUNCTION__, '2.2', 'c2c_SingleCategoryPermalink::template_redirect()' );

	return c2c_SingleCategoryPermalink::template_redirect();
}
endif;
