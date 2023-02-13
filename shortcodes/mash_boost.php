<?php 

function mash_boost_button_shortcode( $atts = array(), $content = null, $tag = '' ) {

  // normalize keys
  $atts = array_change_key_case( (array)$atts, CASE_LOWER );

  // override default attributes
  $boost_atts = shortcode_atts(
    array( 
      'icon' => 'lightning',
      'layout-mode' => 'float',
      'float-location' => 'top-center',
      'variant' => 'colorized',
      'display-mode' => 'with-text',
      'size' => 'md',
      'mobile-display-mode' => 'inherit',
      'mobile-float-location' => 'inherit',
      'mobile-size' => 'inherit'
    ), $atts, $tag
  );

  $output = '<script defer src="https://widgets.getmash.com/boost/boost.js"></script>';
  $output .= '<mash-boost-button ';
  $output .= 'icon="' . esc_attr( $boost_atts['icon'] ) . '" ';
  $output .= 'layout-mode="' . esc_attr( $boost_atts['layout-mode'] ) . '" ';
  $output .= 'variant="' . esc_attr( $boost_atts['variant'] ) . '" ';
  $output .= 'display-mode="' . esc_attr( $boost_atts['display-mode'] ) . '" ';
  $output .= 'float-location="' . esc_attr( $boost_atts['float-location'] ) . '" ';
  $output .= 'size="' . esc_attr( $boost_atts['size'] ) . '" ';

  if ($boost_atts['mobile-display-mode'] != 'inherit') {
    $output .= 'mobile-display-mode="' . esc_attr( $boost_atts['mobile-display-mode'] ) . '" ';
  }

  if ($boost_atts['mobile-float-location'] != 'inherit') {
    $output .= 'mobile-float-location="' . esc_attr( $boost_atts['mobile-float-location'] ) . '" ';
  }

  if ($boost_atts['mobile-size'] != 'inherit') {
    $output .= 'mobile-size="' . esc_attr( $boost_atts['mobile-size'] ) . '" ';
  }

  $output .= '></mash-boost-button>';

  return $output;
}

add_shortcode('mash_boost', 'mash_boost_button_shortcode');
// Include deprecated plural version
add_shortcode('mash_boosts', 'mash_boost_button_shortcode');
