<?php 

function mash_paywall_shortcode( $atts = array(), $content = null, $tag = '' ) {
  // Based on the regex here: https://ihateregex.io/expr/uuid/
  $resource_regex = '/^[0-9a-f]{8}\b-[0-9a-f]{4}\b-[0-9a-f]{4}\b-[0-9a-f]{4}\b-[0-9a-f]{12}$/i';

  // Normalize keys
  $atts = array_change_key_case( (array)$atts, CASE_LOWER );

  // Override default attributes
  $paywall_atts = shortcode_atts(
    array(
      'resource' => '',
      'title' => 'Enjoy some free content, then pay-to-enjoy',
      'subtitle' => 'Setting a budget will auto unlock content.',
    ), $atts, $tag
  );

  if (!preg_match($resource_regex, $paywall_atts['resource'])) {
    // Render an error if the resource attribute is invalid
    return '<p style="color: red">[mash_paywall error: Invalid configuration (resource ID must be specified in UUID format). Please contact website owner.]</p>';
  }
  
  $output = '<div> ';
  $output .= '<script defer src="https://components.getmash.com/content/paywall.js"></script>';
  $output .= '<mash-paywall ';
  // Use the post's name as the web component's key
  $output .= 'key="' . esc_attr( get_post_field( 'post_name', get_post() ) ) . '" ';
  $output .= 'resource="' . esc_attr( $paywall_atts['resource'] ) . '" ';
  $output .= 'title="' . esc_attr( $paywall_atts['title'] ) . '" ';
  $output .= 'subtitle="' . esc_attr( $paywall_atts['subtitle'] ) . '" ';
  $output .= '>';
  $output .= $content;
  $output .= '</mash-paywall> ';
  $output .= '</div>' ;

  return $output;
}

add_shortcode('mash_paywall', 'mash_paywall_shortcode');
