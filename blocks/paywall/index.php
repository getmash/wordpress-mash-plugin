<?php

defined( 'ABSPATH' ) || exit;

// Registers the block in the plugin
function register_mash_paywall() {
	register_block_type( __DIR__, array('render_callback' => 'mash_paywall_render_callback'));
}

// Callback to load the shortcode for the block passing through the attributes
function mash_paywall_render_callback($attributes, $content) {
	return mash_paywall_shortcode($attributes, $content);
}

add_action( 'init', 'register_mash_paywall' );
