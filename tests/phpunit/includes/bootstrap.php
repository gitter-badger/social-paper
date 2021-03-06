<?php

$wp_tests_dir = false;

$wp_develop_dir = getenv( 'WP_DEVELOP_DIR' );
if ( ! $wp_develop_dir ) {
	die( 'To run these tests, you must have a checkout of the develop.svn.wordpress.org repository, and an environment variable WP_DEVELOP_DIR that points to the checkout path.' . "\n" );
}

require_once $wp_develop_dir . '/tests/phpunit/includes/functions.php';

function _bootstrap_cacsp() {
	require dirname( __FILE__ ) . '/../../../social-paper.php';
}
tests_add_filter( 'muplugins_loaded', '_bootstrap_cacsp', 20 );

if ( ! defined( 'BP_TESTS_DIR' ) ) {
	define( 'BP_TESTS_DIR', dirname( __FILE__ ) . '/../../../../buddypress/tests/phpunit' );
}

$do_buddypress = file_exists( BP_TESTS_DIR . '/bootstrap.php' );
define( 'CACSP_TESTS_DO_BUDDYPRESS', $do_buddypress );

function _bootstrap_test_requirements() {
	// Make sure BP is installed and loaded first.
	if ( CACSP_TESTS_DO_BUDDYPRESS ) {
		require BP_TESTS_DIR . '/includes/loader.php';
	}

	// We need a compatible commenting plugin. Prefer inline-comments.
	require dirname( __FILE__ ) . '/../../../../inline-comments/inline-comments.php';
}
tests_add_filter( 'muplugins_loaded', '_bootstrap_test_requirements' );

// Bootstrap WordPress.
require_once $wp_develop_dir . '/tests/phpunit/includes/bootstrap.php';

// We use a different testcase for BuddyPress vs non-BuddyPress.
if ( $do_buddypress ) {
	require BP_TESTS_DIR . '/includes/testcase.php';
	require dirname( __FILE__ ) . '/testcase-base-bp.php';
	require dirname( __FILE__ ) . '/factory-base-bp.php';
} else {
	require dirname( __FILE__ ) . '/factory-base.php';
	require dirname( __FILE__ ) . '/testcase-base.php';
}

require dirname( __FILE__ ) . '/factory.php';
require dirname( __FILE__ ) . '/testcase.php';
