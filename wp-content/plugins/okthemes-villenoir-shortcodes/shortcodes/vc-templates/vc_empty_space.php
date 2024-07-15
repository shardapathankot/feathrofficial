<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $height
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Empty_space $this
 */
$height = $height_tablet = $height_mobile = $el_class = $el_id = $css = $output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_rand_class = 'spacer-'.rand();

$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
// allowed metrics: https://www.w3schools.com/cssref/css_units.asp

$regexr = preg_match( $pattern, $height, $matches );
$value = isset( $matches[1] ) ? (float) $matches[1] : (float) WPBMap::getParam( 'vc_empty_space', 'height' );
$unit = isset( $matches[2] ) ? $matches[2] : 'px';
$height = $value . $unit;

$regexr_tablet = preg_match( $pattern, $height_tablet, $matches_tablet );
$value_tablet = isset( $matches_tablet[1] ) ? (float) $matches_tablet[1] : (float) WPBMap::getParam( 'vc_empty_space', 'height_tablet' );
$unit_tablet = isset( $matches_tablet[2] ) ? $matches_tablet[2] : 'px';
$height_tablet = $value_tablet . $unit_tablet;

if ( $height_tablet ) {
	$output .= '<style>@media (max-width: 991px) {.'.$css_rand_class.' {height: '.$height_tablet.' !important;}}</style>';
}

$regexr_mobile = preg_match( $pattern, $height_mobile, $matches_mobile );
$value_mobile = isset( $matches_mobile[1] ) ? (float) $matches_mobile[1] : (float) WPBMap::getParam( 'vc_empty_space', 'height_mobile' );
$unit_mobile = isset( $matches_mobile[2] ) ? $matches_mobile[2] : 'px';
$height_mobile = $value_mobile . $unit_mobile;

if ( $height_mobile ) {
	$output .= '<style>@media (max-width: 480px) {.'.$css_rand_class.' {height: '.$height_mobile.' !important;}}</style>';
}

$inline_css = ( (float) $height >= 0.0 ) ? ' style="height: ' . esc_attr( $height ) . '"' : '';

$class = 'vc_empty_space ' . $this->getExtraClass( $el_class ) . vc_shortcode_custom_css_class( $css, ' ' ) . $css_rand_class;
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class, $this->settings['base'], $atts );
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$output .= '<div class="' . esc_attr( trim( $css_class ) ) . '" ';
$output .= implode( ' ', $wrapper_attributes ) . ' ' . $inline_css;
$output .= '><span class="vc_empty_space_inner"></span></div>';

return $output;
