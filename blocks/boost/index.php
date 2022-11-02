<?php

defined( 'ABSPATH' ) || exit;

// Registers the block in the plugin
function register_mash_boost_button() {
	register_block_type( __DIR__, array('render_callback' => 'mash_boost_button_render_callback'));
}

// Callback to load the shortcode for the block passing through the attributes
function mash_boost_button_render_callback($attributes) {
	return mash_boost_button_shortcode($attributes);
}

add_action( 'init', 'register_mash_boost_button' );
