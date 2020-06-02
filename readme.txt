=== Single Category Permalink ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: permalink, structure, link, category, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.6
Tested up to: 5.4
Stable tag: 2.4.1

Reduce permalinks (category or post) that include entire hierarchy of categories to just having the lowest level category.


== Description ==

Reduce permalinks (category or post) that include entire hierarchy of categories to just having the lowest category in the hierarchy.

By default, WordPress replaces the %category% permalink tag in a custom permalink structure with the entire hierarchy of categories for the post's first matching category. For example, assuming your site has a hierarchical category structure like so:

`
Applications
  |_ Desktop
  |_ Web
    |_ WordPress
`

By default, if you have a permalink structure defined as `%category%/%year%/%monthnum%/%day%/%postname%`, your post titled "Best Plugins" assigned to the "WordPress" category would have a permalink of:

`http://www.example.com/applications/web/wordpress/2008/01/15/best-plugins`

If you activate the Single Category Permalink plugin, this would be the permalink generated for the post (and recognized by the blog):

`http://www.example.com/wordpress/2008/01/15/best-plugins`

In order for a category to be used as part of a post's permalink structure, `%category%` must be explicitly defined in the Settings -> Permalinks admin page as part of a custom structure, i.e. `/%category%/%postname%`.

For category links, `%category%` is implied to follow the value set as the "Category base" (or the default category base if none is specified). So if your category base is 'category', the above example would list posts in the 'WordPress' category on this category listing page:

`http://www.example.com/category/applications/web/wordpress/`

With this plugin activated, that link would become:

`http://www.example.com/category/wordpress/`

NOTE: The fully hierarchical category and post permalinks will continue to work. The plugin issues a 302 redirect to browsers and search engines pointing them to the shorter URL.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/single-category-permalink/) | [Plugin Directory Page](https://wordpress.org/plugins/single-category-permalink/) | [GitHub](https://github.com/coffee2code/single-category-permalink/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `single-category-permalinks.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use `%category%` as a permalink tag in the `Settings` -> `Permalinks` admin options page when defining a custom permalink structure


== Frequently Asked Questions ==

= Will existing links to my site that used the full category hierarchy still work? =

Yes, WordPress will still serve the category listings and posts regardless of whether it is of the full category hierarchy format or just the single category format. But do note that WordPress doesn't perform any sort of redirects; it responds directly to the category/post URL requested.

= Could this give the appearance that I have duplicate content on my site if pages are accessible via the full category hierarchy permalink format and the single category permalink format? =

Whether this plugin is active or not, WordPress treats both types of category links the same. This plugin will however issue redirects for all of the non-canonical category and post permalink pages to point to the single category link version.

= What can this plugin do for me if I don't use `%category%` in my custom permalink structure? =

In addition to handling custom permalink structures (used to generate permalinks for posts) that contain `%category%`, the plugin also shortens category archive links. WordPress by default generates those links in a fully hierarchical fashion which this plugin will reduce to a single category. See the Description section for an example.

= Does this plugin include unit tests? =

Yes.


== Hooks ==

The plugin exposes one filter for hooking. Code using this filter should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain). Less ideally, you could put them in your active theme's functions.php file.

**c2c_single_category_redirect_status (filter)**

The 'c2c_single_category_redirect_status' hook allows you to specify an HTTP status code used for the redirect. By default this is 302.

Arguments:

* $status (integer) : The default HTTP status code

Example:

`
/**
 * Change the redirection HTTP status to a 302.
 *
 * @param  int $code The HTTP status code. By default 301.
 * @return int
 */
function scp_change_redirect_status( $code ) {
	return 302;
}
add_filter( 'c2c_single_category_redirect_status', 'scp_change_redirect_status' );
`


== Changelog ==

= 2.4.1 (2020-01-18) =
* Fix: Prevent interfering with permalinks for non-category taxonomies. Props mynameisrabby.

= 2.4 (2020-01-17) =
* Change: Use `term_link` filter instead of the deprecated `category_link` filter. Props webgmclassics.
* New: Add TODO.md and move existing TODO list from top of main plugin file into it (and add more items to the list)
* Unit tests:
    * Change: Update unit test install script and bootstrap to use latest WP unit test repo
    * New: Add test for default hooks
    * Fix: Rename and fix miscoded test for plugin initialization
* Change: Note compatibility through WP 5.3+
* Change: Update copyright date (2020)
* Fix: Correct typo in GitHub URL

= 2.3 (2019-03-18) =
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* New: Add inline documentation for hook
* Change: Initialize plugin on 'plugins_loaded' action instead of on load
* Change: Merge `do_init()` into `init()`
* CHange: Cast return value of `c2c_single_category_redirect_status` filter as integer
* Change: Split paragraph in README.md's "Support" section into two
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/single-category-permalink/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.4.1 (2020-01-18) =
Recommended bugfix release: prevented interfering with permalinks for non-category taxonomies

= 2.4 =
Minor update: modernized unit tests, noted compatibility through WP 5.3+, added TODO.md, and updated copyright date (2020)

= 2.3 =
Minor update: tweaked plugin initialization process, created CHANGELOG.md to store historical changelog outside of readme.txt, noted compatibility through WP 5.1+, updated copyright date (2019)

= 2.2.1 =
Trivial update: minor documentation and code formatting tweaks, noted compatibility through WP 4.9+, and updated copyright date (2018)

= 2.2 =
Recommended update: changed default HTTP redirect status code to 301 (permanent), fixed PHP warning in WP 4.7 due to function deprecation, restructured code, compatibility is now WP 4.6-4.7+, added more unit tests, updated copyright date, more

= 2.1.2 =
Trivial update: verified compatibility through WP 4.5; updated copyright date (2016).

= 2.1.1 =
Trivial update: noted compatibility through WP 4.1+; updated copyright date (2015); added plugin icon

= 2.1 =
Minor update: added unit tests; noted compatibility through WP 3.8+

= 2.0.4 =
Trivial update: noted compatibility through WP 3.5+

= 2.0.3 =
Trivial update: noted compatibility through WP 3.4+; explicitly stated license

= 2.0.2 =
Trivial update: noted compatibility through WP 3.3+

= 2.0.1 =
Bugfix release: fixed bug triggered when creating new post (especially recommended if using %category% in custom permalink structure)

= 2.0 =
Recommended update. No functional changes, but many changes to formatting and documentation; noted compatibility through WP 3.2.
