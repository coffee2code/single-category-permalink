# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Test if sub-category pagination is broken.
* Add filters to allow separate control of whether single-category is enforced for category URLs and post URLs (w/ '%category%' in permalink).
  See: https://wordpress.org/support/topic/good-plugin-needs-to-be-configurable/
* Support custom depths (i.e. instead of showing the single category, can include a 2nd, or a 3rd level up)
  See: https://wordpress.org/support/topic/woocommerce-463/
* Support WooCommerce's `%product_cat%` permalink placeholder
  See: https://wordpress.org/support/topic/woocommerce-463/
* Support hierarchical taxonomies, in general, by default? Or at least facilitate per-hierarchical taxonomy support.
* Remove deprecated functions `c2c_single_category_postlink()`, `c2c_single_category_catlink()`, and `c2c_single_category_redirect()`
* Add unit tests for `template_redirect()`

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/single-category-permalinks/) or on [GitHub](https://github.com/coffee2code/single-category-permalinks/) as an issue or PR).