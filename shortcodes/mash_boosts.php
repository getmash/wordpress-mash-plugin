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
      'variant' => 'colorized'
    ), $atts, $tag
  );

  $output = '<script defer src="https://components.getmash.com/boost/boost.js"></script>';
  $output .= '<mash-boost-button ';
  $output .= 'icon="' . esc_attr( $boost_atts['icon'] ) . '" ';
  $output .= 'layout-mode="' . esc_attr( $boost_atts['layout-mode'] ) . '" ';
  $output .= 'variant="' . esc_attr( $boost_atts['variant'] ) . '" ';
  $output .= 'float-location="' . esc_attr( $boost_atts['float-location'] ) . '" ';
  $output .= '></mash-boost-button>';

  return $output;
}

add_shortcode('mash_boosts', 'mash_boost_button_shortcode');
?>
