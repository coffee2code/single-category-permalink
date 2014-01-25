<?php

class Single_Category_Permalink_Test extends WP_UnitTestCase {


	function setUp() {
		parent::setUp();
		$this->set_permalink();
	}
	/**
	 * HELPER FUNCTIONS
	 */



	private function set_permalink( $structure = '/%category%/%postname%/', $category_structure = '/category/%category%/' ) {
		update_option( 'permalink_structure', $structure );
		update_option( 'category_base',       $category_structure );
		$GLOBALS['wp_rewrite']->init();
		add_permastruct( 'category', ltrim( $category_structure, '/' ) );
		flush_rewrite_rules();
	}



	/**
	 * TESTS
	 */


	/* Test post permalink */

	function test_non_hierarchical_category_not_affected() {
		$cat_id  = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, $cat_id );

		$this->assertEquals( 'http://example.org/aaa/cat-post/', get_permalink( $post_id ) );
	}

	function test_post_assigned_hierarchical_leaf_category() {
		$cat1_id = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$cat2_id = $this->factory->category->create( array ( 'slug' => 'bbb', 'name' => 'BBB', 'parent' => $cat1_id ) );
		$cat3_id = $this->factory->category->create( array ( 'slug' => 'ccc', 'name' => 'CCC', 'parent' => $cat2_id ) );
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, $cat3_id );

		$this->assertEquals( 'http://example.org/ccc/cat-post/', get_permalink( $post_id ) );
	}

	function test_post_assigned_hierarchical_root_category() {
		$cat1_id = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$cat2_id = $this->factory->category->create( array ( 'slug' => 'bbb', 'name' => 'BBB', 'parent' => $cat1_id ) );
		$cat3_id = $this->factory->category->create( array ( 'slug' => 'ccc', 'name' => 'CCC', 'parent' => $cat2_id ) );
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, $cat1_id );

		$this->assertEquals( 'http://example.org/aaa/cat-post/', get_permalink( $post_id ) );
	}

	function test_post_assigned_hierarchical_midlevel_category() {
		$cat1_id = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$cat2_id = $this->factory->category->create( array ( 'slug' => 'bbb', 'name' => 'BBB', 'parent' => $cat1_id ) );
		$cat3_id = $this->factory->category->create( array ( 'slug' => 'ccc', 'name' => 'CCC', 'parent' => $cat2_id ) );
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, $cat2_id );

		$this->assertEquals( 'http://example.org/bbb/cat-post/', get_permalink( $post_id ) );
	}

	function test_post_assigned_multiple_hierarchical_leaf_categories() {
		$cat1_id = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$cat2_id = $this->factory->category->create( array ( 'slug' => 'bbb', 'name' => 'BBB', 'parent' => $cat1_id ) );
		$cat3_id = $this->factory->category->create( array ( 'slug' => 'ccc', 'name' => 'CCC', 'parent' => $cat2_id ) );
		$cat4_id = $this->factory->category->create( array ( 'slug' => 'ddd', 'name' => 'DDD' ) );
		$post_id = $this->factory->post->create( array( 'post_title' => 'Cat Post' ) );
		wp_set_post_categories( $post_id, array( $cat3_id, $cat4_id ) );

		$this->assertEquals( 'http://example.org/ccc/cat-post/', get_permalink( $post_id ) );
	}

	/* Test category permalink */

	function test_category_permalink_for_leaf_category() {
		$cat1_id = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$cat2_id = $this->factory->category->create( array ( 'slug' => 'bbb', 'name' => 'BBB', 'parent' => $cat1_id ) );
		$cat3_id = $this->factory->category->create( array ( 'slug' => 'ccc', 'name' => 'CCC', 'parent' => $cat2_id ) );

		$this->assertEquals( 'http://example.org/category/ccc/', get_category_link( $cat3_id ) );
	}

	function test_category_permalink_for_root_category() {
		$cat1_id = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$cat2_id = $this->factory->category->create( array ( 'slug' => 'bbb', 'name' => 'BBB', 'parent' => $cat1_id ) );
		$cat3_id = $this->factory->category->create( array ( 'slug' => 'ccc', 'name' => 'CCC', 'parent' => $cat2_id ) );

		$this->assertEquals( 'http://example.org/category/aaa/', get_category_link( $cat1_id ) );
	}

	function test_category_permalink_for_midlevel_category() {
		$cat1_id = $this->factory->category->create( array ( 'slug' => 'aaa', 'name' => 'AAA' ) );
		$cat2_id = $this->factory->category->create( array ( 'slug' => 'bbb', 'name' => 'BBB', 'parent' => $cat1_id ) );
		$cat3_id = $this->factory->category->create( array ( 'slug' => 'ccc', 'name' => 'CCC', 'parent' => $cat2_id ) );

		$this->assertEquals( 'http://example.org/category/bbb/', get_category_link( $cat2_id ) );
	}

	/* TODO: Test redirect of full hierarchical category permalink (post) to shorter version (e.g. /aaa/bbb/ccc/cat-post/ -> /ccc/cat-post/) */
	/* TODO: Test redirect of full hierarchical category permalink (category) to shorter version (e.g. /category/aaa/bbb/ccc/ -> /category/ccc/) */
	/* TODO: Test appropriate post(s) returned when querying shorter category permalink (e.g. /category/ccc/) */
	/* TODO: Test appropriate post(s) returned when querying leaf category off home (e.g. '/ccc/') */

}
