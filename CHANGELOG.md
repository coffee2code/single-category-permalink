# Changelog

## 2.5.2 _(2021-09-28)_
* Change: Note compatibility through WP 5.8+
* Unit tests:
    * Change: Restructure unit test directories
        * Change: Move `phpunit/bin/` into `tests/`
        * Change: Move `phpunit/` into `tests/`
    * Change: Remove 'test-' prefix from unit test file
    * Change: In bootstrap, store path to plugin file constant
    * Change: In bootstrap, add backcompat for PHPUnit pre-v6.0

## 2.5.1 _(2021-04-30)_
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

## 2.5 _(2020-09-23)_
* Fix: Default the `$taxonomy` argument of `category_link()` to 'category' to avoid a PHP warning/error
* Fix: Handle the possibility that `get_category()` could return `null` for an invalid category ID
* New: Add a TODO item about removing deprecated functions (which is not something I want to do just yet, hence the TODO)
* Change: Update docs for return value of `category_link()` to reflect that `WP_Error` or `null` are also possible values
* Change: Note compatibility through WP 5.5+
* Change: Restructure unit test file structure
    * New: Create new subdirectory `phpunit/` to house all files related to unit testing
    * Change: Move `bin/` to `phpunit/bin/`
    * Change: Move `tests/bootstrap.php` to `phpunit/`
    * Change: Move `tests/` to `phpunit/tests/`
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
* Unit tests:
    * New: Add tests for `category_link()`, `post_link()`
    * New: Add `unset_permalink_structures()` to unset configured permalink structures

## 2.4.2 _(2020-06-01)_
* Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests (and remove commented-out code)
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS
* Change: Update URLs used in examples to be HTTPS
* Change: Unit tests: Remove unnecessary unregistering of hooks

## 2.4.1 _(2020-01-18)_
* Fix: Prevent interfering with permalinks for non-category taxonomies. Props mynameisrabby.

## 2.4 _(2020-01-17)_
* Change: Use `term_link` filter instead of the deprecated `category_link` filter. Props webgmclassics.
* New: Add TODO.md and move existing TODO list from top of main plugin file into it (and add more items to the list)
* Unit tests:
    * Change: Update unit test install script and bootstrap to use latest WP unit test repo
    * New: Add test for default hooks
    * Fix: Rename and fix miscoded test for plugin initialization
* Change: Note compatibility through WP 5.3+
* Change: Update copyright date (2020)
* Fix: Correct typo in GitHub URL

## 2.3 _(2019-03-18)_
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* New: Add inline documentation for hook
* Change: Initialize plugin on 'plugins_loaded' action instead of on load
* Change: Merge `do_init()` into `init()`
* CHange: Cast return value of `c2c_single_category_redirect_status` filter as integer
* Change: Split paragraph in README.md's "Support" section into two
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

## 2.2.1 _(2018-07-21)_
* Change: Switch away from using deprecated `c2c_single_category_catlink()` internally
* Change: Move `@uses` DockBlock entries to `get_http_redirect_status()`
* New: Unit tests: Add `create_hierarchical_categories()` for creating hierarchical categories so the behavior isn't duplicated in a number of tests
* New: Add README.md
* New: Add GitHub link to readme
* Change: Minor whitespace tweaks to unit test bootstrap
* Change: Note compatibility through WP 4.9+
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Modify formatting of hook name in readme to prevent being uppercased when shown in the Plugin Directory
* Change: Improve installation instructions
* Change: Minor readme.txt tweaks
* Change: Update copyright date (2018)

## 2.2 _(2017-02-10)_
* Fix: Replace use of deprecated (in WP 4.7) `_usort_terms_by_ID()` with `wp_list_sort()` for WP 4.7+.
* Change: Change default redirect HTTP status code from 302 (temporary redirect) to 301 (permanent redirect).
* Change: Wrap functionality in class.
    * Create class `c2c_SingleCategoryPermalink`
    * Deprecate existing functions: `c2c_single_category_catlink()`, `c2c_single_category_postlink()`, `c2c_single_category_redirect()`
    * Move deprecated function functionality to class methods: `category_link()`, `post_link()`, `template_redirect()`
    * Add method `version()` to return plugin version
* Add `get_http_redirect_status()` for getting the HTTP status code for redirects.
* Change: Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable.
* Change: Enable more error output for unit tests.
* Change: Note compatibility through WP 4.7+.
* Change: Remove support for WordPress older than 4.6 (should still work for earlier versions back to WP 1.5)
* Change: Update copyright date (2017).

## 2.1.2 _(2016-03-29)_
* New: Add LICENSE file.
* New: Add empty index.php to prevent files from being listed if web server has enabled directory listings.
* New: Add 'Text Domain' to plugin header.
* Change: Minor code reformatting.
* Change: Explicitly declare methods in unit tests as public; minor unit test doc reformatting.
* Change: Note compatibility through WP 4.5+.
* Change: Update copyright date (2016).

## 2.1.1 _(2015-02-17)_
* Reformat plugin header
* Note compatibility through WP 4.1+
* Change documentation links to wp.org to be https
* Update copyright date (2015)
* Add plugin icon

## 2.1 _(2014-01-24)_
* Add unit tests
* Minor documentation improvements
* Minor code reformatting (spacing, bracing)
* Note compatibility through WP 3.8+
* Update copyright date (2014)
* Change donate link
* Add banner

## 2.0.4
* Add check to prevent execution of code if file is directly accessed
* Note compatibility through WP 3.5+
* Update copyright date (2013)
* Minor code reformatting (spacing)

## 2.0.3
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Remove ending PHP close tag
* Note compatibility through WP 3.4+

## 2.0.2
* Note compatibility through WP 3.3+
* Add link to plugin directory page to readme.txt
* Update copyright date (2012)

## 2.0.1
* Fix bug triggered when creating new post

## 2.0
* Fix compatibility bug relating to generation of category permalink
* Rename `single_category_postlink()` to `c2c_single_category_postlink()`
* Rename `single_category_catlink()` to `c2c_single_category_catlink()`
* Add `c2c_single_category_redirect()` to redirect hierarchical category links to the single category alternative
* Add filter `c2c_single_category_redirect_status` to allow override of default redirect status code
* Wrap all functions in `if (!function_exists())` check
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

## 1.0
* Initial release
