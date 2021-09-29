<?php

defined( 'ABSPATH' ) or die();

class Single_Category_Permalink_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->set_permalink();
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	private function set_permalink( $structure = '/%category%/%postname%/', $category_structure = '/category/%category%/' ) {
		update_option( 'permalink_structure', $structure );
		update_option( 'category_base',       $category_structure );
		$GLOBALS['wp_rewrite']->init();
		add_permastruct( 'category', ltrim( $category_structure, '/' ) );
		flush_rewrite_rules();
	}

	private function unset_permalink_structures() {
		global $wp_rewrite;

		$wp_rewrite->remove_permastruct( 'category' );
		delete_option( 'permalink_structure' );
		delete_option( 'category_base' );
		$wp_rewrite->init();
	}

	public function change_redirect_status( $status ) {
		return 302;
	}

	private function create_hierarchical_categories() {
		$cat1_id = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$cat2_id = $this->factory->category->create( array ( 'slug' => 'bbb', 'name' => 'BBB', 'parent' => $cat1_id ) );
		$cat3_id = $this->factory->category->create( array ( 'slug' => 'ccc', 'name' => 'CCC', 'parent' => $cat2_id ) );

		return array( $cat1_id, $cat2_id, $cat3_id );
	}

	//
	//
	// TESTS
	//
	//


	public function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_SingleCategoryPermalink' ) );
	}

	public function test_get_version() {
		$this->assertEquals( '2.5.2', c2c_SingleCategoryPermalink::version() );
	}

	public function test_hooks_plugins_loaded() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', array( 'c2c_SingleCategoryPermalink', 'init' ) ) );
	}

	public function test_default_hooks() {
		$this->assertEquals( 10, has_filter( 'term_link',         array( 'c2c_SingleCategoryPermalink', 'category_link' ) ) );
		$this->assertEquals( 10, has_filter( 'post_link',         array( 'c2c_SingleCategoryPermalink', 'post_link' ) ) );
		$this->assertEquals( 10, has_filter( 'template_redirect', array( 'c2c_SingleCategoryPermalink', 'template_redirect' ) ) );
	}

	/* Test post permalink */

	public function test_non_hierarchical_category_not_affected() {
		$cat_id  = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, $cat_id );

		$this->assertEquals( 'http://example.org/aaa/cat-post/', get_permalink( $post_id ) );
	}

	public function test_post_assigned_hierarchical_leaf_category() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, $cat3_id );

		$this->assertEquals( 'http://example.org/ccc/cat-post/', get_permalink( $post_id ) );
	}

	public function test_post_assigned_hierarchical_root_category() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, $cat1_id );

		$this->assertEquals( 'http://example.org/aaa/cat-post/', get_permalink( $post_id ) );
	}

	public function test_post_assigned_hierarchical_midlevel_category() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, $cat2_id );

		$this->assertEquals( 'http://example.org/bbb/cat-post/', get_permalink( $post_id ) );
	}

	public function test_post_assigned_multiple_hierarchical_leaf_categories() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$cat4_id = $this->factory->category->create( array ( 'slug' => 'ddd', 'name' => 'DDD' ) );
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, array( $cat3_id, $cat4_id ) );

		$this->assertEquals( 'http://example.org/ccc/cat-post/', get_permalink( $post_id ) );
	}

	/* Test category permalink */

	public function test_non_category_hierarchical_taxonomy_not_affected() {
		register_taxonomy( 'color', 'post', array(
			'hierarchical' => true,
			'has_archive'  => true,
			'rewrite'      => array( 'hierarchical' => true ),
		) );

		$term1_id = $this->factory->term->create( array ( 'taxonomy' => 'color', 'slug' => 'aaa', 'name' => 'AAA' ) );
		$term2_id = $this->factory->term->create( array ( 'taxonomy' => 'color', 'slug' => 'bbb', 'name' => 'BBB', 'parent' => $term1_id ) );
		$term3_id = $this->factory->term->create( array ( 'taxonomy' => 'color', 'slug' => 'ccc', 'name' => 'CCC', 'parent' => $term2_id ) );

		$this->assertEquals( 'http://example.org/color/aaa/bbb/ccc/', get_term_link( $term3_id ) );
	}

	public function test_category_permalink_for_leaf_category() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();

		$this->assertEquals( 'http://example.org/category/ccc/', get_category_link( $cat3_id ) );
	}

	public function test_category_permalink_for_root_category() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();

		$this->assertEquals( 'http://example.org/category/aaa/', get_category_link( $cat1_id ) );
	}

	public function test_category_permalink_for_midlevel_category() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();

		$this->assertEquals( 'http://example.org/category/bbb/', get_category_link( $cat2_id ) );
	}

	/*
	 * get_http_redirect_status()
	 */

	public function test_get_http_redirect_status() {
		$this->assertEquals( 301, c2c_SingleCategoryPermalink::get_http_redirect_status() );
	}

	/*
	 * Filter: c2c_single_category_redirect_status
	 */

	public function test_default_redirect_status() {
		add_filter( 'c2c_single_category_redirect_status', array( $this, 'change_redirect_status' ) );

		$this->assertEquals( 302, c2c_SingleCategoryPermalink::get_http_redirect_status() );
	}

	/*
	 * category_link()
	 */

	public function test_category_link_does_not_affect_link_for_non_category() {
		$cat_id  = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$expected = 'http://example.com/some/aaa/';

		$this->assertEquals( $expected, c2c_SingleCategoryPermalink::category_link( $expected, $cat_id, 'something' ) );
	}

	public function test_category_link_returns_wp_error_for_invalid_category_id() {
		$expected = 'http://example.com/some/aaa/';

		$this->assertTrue( is_null( c2c_SingleCategoryPermalink::category_link( $expected, 999999, 'category' ) ) );
	}

	public function test_category_link_returns_wp_error_for_empty_category_id() {
		$expected = 'http://example.com/some/aaa/';

		$this->assertTrue( is_wp_error( c2c_SingleCategoryPermalink::category_link( $expected, '', 'category' ) ) );
		$this->assertTrue( is_wp_error( c2c_SingleCategoryPermalink::category_link( $expected, null, 'category' ) ) );
		$this->assertTrue( is_wp_error( c2c_SingleCategoryPermalink::category_link( $expected, false, 'category' ) ) );
	}

	public function test_category_link_when_no_category_permastruct_defined_and_invalid_category_id() {
		$this->unset_permalink_structures();

		$this->assertEquals( 'http://example.org/?cat=999999', c2c_SingleCategoryPermalink::category_link( 'url', 999999, 'category' ) );
	}

	public function test_category_link_when_no_category_permastruct_defined_and_valid_category_id() {
		$cat_id  = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$this->unset_permalink_structures();

		$this->assertEquals( 'http://example.org/?cat=' . $cat_id, c2c_SingleCategoryPermalink::category_link( 'url', $cat_id, 'category' ) );
	}

	public function test_category_link_with_valid_category_id() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();

		$this->assertEquals( 'http://example.org/category/ccc/', c2c_SingleCategoryPermalink::category_link( 'url', $cat3_id, 'category' ) );
	}

	/*
	 * post_link()
	 */

	public function test_post_link_non_hierarchical_category_not_affected() {
		$cat_id  = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$post = $this->factory->post->create_and_get( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post->ID, $cat_id );

		$expected = 'http://example.org/aaa/cat-post/';

		$this->assertEquals( $expected, c2c_SingleCategoryPermalink::post_link( $expected, $post ) );
	}

	public function test_post_link_post_assigned_hierarchical_leaf_category() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$post = $this->factory->post->create_and_get( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post->ID, $cat3_id );

		$this->assertEquals( 'http://example.org/ccc/cat-post/', c2c_SingleCategoryPermalink::post_link( 'http://example.org/aaa/bbb/ccc/cat-post/', $post ) );
	}

	public function test_post_link_post_assigned_hierarchical_root_category() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$post = $this->factory->post->create_and_get( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post->ID, $cat1_id );

		$this->assertEquals( 'http://example.org/aaa/cat-post/', c2c_SingleCategoryPermalink::post_link( 'http://example.org/aaa/cat-post/', $post ) );
	}

	public function test_post_link_post_assigned_hierarchical_midlevel_category() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$post = $this->factory->post->create_and_get( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post->ID, $cat2_id );

		$this->assertEquals( 'http://example.org/bbb/cat-post/', c2c_SingleCategoryPermalink::post_link( 'http://example.org/aaa/bbb/cat-post/', $post ) );
	}

	public function test_post_link_post_assigned_multiple_hierarchical_leaf_categories() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$cat4_id = $this->factory->category->create( array ( 'slug' => 'ddd', 'name' => 'DDD' ) );
		$post = $this->factory->post->create_and_get( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post->ID, array( $cat3_id, $cat4_id ) );

		$this->assertEquals( 'http://example.org/ccc/cat-post/', c2c_SingleCategoryPermalink::post_link( 'http://example.org/aaa/bbb/ccc/cat-post/', $post ) );
	}

	public function test_post_link_post_assigned_hierarchical_root_category_with_same_name_as_post() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$post = $this->factory->post->create_and_get( array( 'post_title' => 'aaahhh' ) );
		wp_set_post_categories( $post->ID, $cat1_id );

		$this->assertEquals( 'http://example.org/aaa/aaa/', c2c_SingleCategoryPermalink::post_link( 'http://example.org/aaa/aaa/', $post ) );
	}

	public function test_post_link_post_assigned_hierarchical_leaf_category_with_same_name_as_post() {
		list( $cat1_id, $cat2_id, $cat3_id ) = $this->create_hierarchical_categories();
		$post = $this->factory->post->create_and_get( array( 'post_title' => 'ccc' ) );
		wp_set_post_categories( $post->ID, $cat3_id );

		$this->assertEquals( 'http://example.org/ccc/ccc/', c2c_SingleCategoryPermalink::post_link( 'http://example.org/aaa/bbb/ccc/ccc/', $post ) );
	}


	/* TODO: Test redirect of full hierarchical category permalink (post) to shorter version (e.g. /aaa/bbb/ccc/cat-post/ -> /ccc/cat-post/) */
	/* TODO: Test redirect of full hierarchical category permalink (category) to shorter version (e.g. /category/aaa/bbb/ccc/ -> /category/ccc/) */
	/* TODO: Test appropriate post(s) returned when querying shorter category permalink (e.g. /category/ccc/) */
	/* TODO: Test appropriate post(s) returned when querying leaf category off home (e.g. '/ccc/') */

}
