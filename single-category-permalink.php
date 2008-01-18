<?php
/*
Plugin Name: Single Category Permalink
Version: 0.9.5
Plugin URI: http://www.coffee2code.com/wp-plugins/
Author: Scott Reilly
Author URI: http://www.coffee2code.com
Description: Modify the %category% permalink structure tag to generate a category-based permalink structure that only displays the lowest category in a hierarchy, as opposed to the entire hierarchy of categories.

Compatible with WordPress 1.5+, 2.0+, 2.1+, 2.2+, and 2.3+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://www.coffee2code.com/wp-plugins/single-category-permalink.zip and unzip it into your 
/wp-content/plugins/ directory.
-OR-
Copy and paste the the code ( http://www.coffee2code.com/wp-plugins/single-category-permalink.phps ) into a file called 
single-category-permalink.php, and put that file into your /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use %category% as a permalink tag in the 'Options' -> 'Permalinks' admin options page when defining a custom permalink structure

*/

/*
Copyright (c) 2007-2008 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

function single_category_postlink($permalink, $post) {
	$permalink_structure = get_option('permalink_structure');
    if (strpos($permalink_structure, '%category%') !== false) {
		$cats = get_the_category($post->ID);
		if ( $cats )
			usort($cats, '_usort_terms_by_ID'); // order by ID
		$category = $cats[0]->slug;
		if ( $parent=$cats[0]->parent )
			$category = get_category_parents($parent, FALSE, '/', TRUE) . $category;
		// Now we know what the permalink component involving category hierarchy consists of.  Get rid of it.
		$permalink = str_replace($category, $cats[0]->slug, $permalink);
    }
	return $permalink;
}

function single_category_catlink($catlink, $category_id) {
	global $wp_rewrite;
	$catlink = $wp_rewrite->get_category_permastruct();

	if ( empty($catlink) ) {
		$file = get_option('home') . '/';
		$catlink = $file . '?cat=' . $category_id;
	} else {
		$category = &get_category($category_id);
		if ( is_wp_error( $category ) )
			return $category;
		$category_nicename = $category->slug;

		$catlink = str_replace('/category/', '/', $catlink);
		$catlink = str_replace('%category%', $category_nicename, $catlink);
		$catlink = get_option('home') . user_trailingslashit($catlink, 'category');
	}
	return $catlink;
}

add_filter('post_link', 'single_category_postlink', 10, 2);
add_filter('category_link', 'single_category_catlink', 10, 2);

?>