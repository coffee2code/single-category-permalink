=== Single Category Permalink ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: permalink, structure, link, category, coffee2code
Requires at least: 1.5
Tested up to: 3.2
Stable tag: 2.0
Version: 2.0

Reduce permalinks (category or post) that include entire hierarchy of categories to just having the lowest level category.


== Description ==

Reduce permalinks (category or post) that include entire hierarchy of categories to just having the lowest category in the hierarchy.

By default, WordPress replaces the %category% permalink tag in a custom permalink structure with the entire hierarchy of categories for the post's first matching category.  For example, assuming your site has a hierarchical category structure like so:

`
Applications
  |_ Desktop
  |_ Web
    |_ WordPress
`

By default, if you have a permalink structure defined as `%category%/%year%/%monthnum%/%day%/%postname%`, your post titled "Best Plugins" assigned to the "WordPress" category would have a permalink of:

`http://www.yourblog.com/applications/web/wordpress/2008/01/15/best-plugins`

If you activate the Single Category Permalink plugin, this would be the permalink generated for the post (and recognized by the blog):

`http://www.yourblog.com/wordpress/2008/01/15/best-plugins`

In order for a category to be used as part of a post's permalink structure, %category% must be explicitly defined in the Settings -> Permalinks admin page as part of a custom structure, i.e. `/%category%/%postname%`.

For category links, `%category%` is implied to follow the value set as the "Category base" (or the default category base if none is specified).  so if your category base is 'category', the above example would list posts in the 'WordPress' on this category listing page:

`http://www.yourblog.com/category/applications/web/wordpress/`

With this plugin activated, that link would become:

`http://www.yourblog.com/category/wordpress/`

NOTE: The fully hierarchical category and post permalinks will continue to work.  The plugin issues are 302 redirect to browsers and search engines pointing them to the shorter URL.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/single-category-permalink/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `single-category-permalink.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use `%category%` as a permalink tag in the `Settings` -> `Permalinks` admin options page when defining a custom permalink structure


== Frequently Asked Questions ==

= Will existing links to my site that used the full category hierarchy still work? =

Yes, WordPress will still serve the category listings and posts regardless of whether it is of the full category hierarchy format or just the single category format.  But do note that WordPress doesn't perform any sort of redirects; it responds directly to the category/post URL requested.

= Could this give the appearance that I have duplicate content on my site if pages are accessible via the full category hierarchy permalink format and the single category permalink format? =

Whether this plugin is active or not, WordPress treats both types of category links the same.  This plugin will however issue redirects for all of the non-canonical category and post permalink pages to point to the single category link version.

= What can this plugin do for me if I don't use `%category%` in my custom permalink structure? =

In addition to handling custom permalink structures (used to generate permalinks for posts) that contain `%category%`, the plugin also shortens category archive links.  WordPress by default generates those links in a fully hierarchical fashion which this plugin will reduce to a single category.  See the Description section for an example.


== Filters ==

The plugin exposes one filter for hooking.  Typically, customizations utilizing this hook would be put into your active theme's functions.php file, or used by another plugin.

= c2c_single_category_redirect_status (filter) =

The 'c2c_single_category_redirect_status' hook allows you to specify an HTTP status code used for the redirect.  By default this is 302.

Arguments:

* $status (integer) : The default HTTP status code

Example:

`
// Change single category redirect to 301
function scp_change_redirect_status( $code ) {
	return 301;
}
add_filter( 'c2c_single_category_redirect_status', 'scp_change_redirect_status' );
`


== Changelog ==

= 2.0 =
* Fix compatibility bug relating to generation of category permalink
* Rename single_category_postlink() to c2c_single_category_postlink()
* Rename single_category_catlink() to c2c_single_category_catlink()
* Add c2c_single_category_redirect() to redirect hierarchical category links to the single category alternative
* Add filter 'c2c_single_category_redirect_status' to allow override of default redirect status code
* Wrap all functions in if (!function_exists()) check
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Add plugin homepage and author links in description in readme.txt
* Note compatibility through WP3.2+
* Add PHPDoc documentation
* Expand documentation in readme.txt
* Minor tweaks to code formatting (spacing)
* Minor documentation reformatting in readme.txt
* Change description
* Add package info to top of plugin file
* Add Frequently Asked Questions, Filters, Changelog, and Upgrade Notice sections to readme.txt
* Update copyright date (2011)

= 1.0 =
* Initial release


== Upgrade Notice ==

= 2.0 =
Recommended update. No functional changes, but many changes to formatting and documentation; noted compatibility through WP 3.2.