=== Single Category Permalink ===
Contributors: coffee2code
Donate link: http://coffee2code.com
Tags: permalink, structure, link, category
Requires at least: 1.5
Tested up to: 2.5
Stable tag: trunk
Version: 1.0

Modify the `%category%` permalink structure tag to generate a category-based permalink structure that only displays the lowest category in a hierarchy, as opposed to the entire hierarchy of categories.

== Description ==

Modify the `%category%` permalink structure tag to generate a category-based permalink structure that only displays the lowest category in a hierarchy, as opposed to the entire hierarchy of categories.

By default, WordPress replaces the %category% permalink tag in a custom permalink structure with the entire hierarchy of categories for the post's first matching category.  For example, assuming your site has a hierarchical category structure like so:

Applications
  |_ Desktop
  |_ Web
    |_ Wordpress

By default, if you have a permalink structure defined as `%category%/%year%/%monthnum%/%day%/%postname%`, your post titled "Best Plugins" assigned to the 
"WordPress" category would have a permalink of:
http://www.yourblog.com/applications/web/wordpress/2008/01/15/best-plugins

If you activate the Single Category Permalink plugin, this would be the permalink generated for the post (and recognized by the blog):
http://www.yourblog.com/wordpress/2008/01/15/best-plugins

== Installation ==

1. Unzip `single-category-permalink.zip` inside the `/wp-content/plugins/` directory, or upload `single-category-permalink.php` into `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use `%category%` as a permalink tag in the `Options` -> `Permalinks` admin options page when defining a custom permalink structure
