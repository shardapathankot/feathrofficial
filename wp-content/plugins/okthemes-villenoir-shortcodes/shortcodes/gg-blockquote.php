<?php
if ( ! class_exists( 'WPBakeryShortCode_gg_blockquote' ) ) {
	
	class WPBakeryShortCode_gg_blockquote extends WPBakeryShortCode {

		public function __construct() {  
			add_shortcode('blockquote', array($this, 'gg_blockquote'));  
		}

		public function gg_blockquote( $atts, $content = null ) { 

			$output = $quote = $quote_color_style = $author_color_style = $quote_color = $quote_style = $author_sep_color = $author_sep_color_style = '';
			extract(shortcode_atts(array(
                    'quote_style' => 'style_1',
					'quote'       => '',
					'quote_color' => '',
                    'author'       => '',
                    'author_color' => '',
                    'author_sep_color' => '',
					'css'         => ''
			), $atts));

			if ($quote_color != '') {
				$quote_color_style = 'style="color: '.$quote_color.';"';
			}
            if ($author_color != '') {
                $author_color_style = 'style="color: '.$author_color.';"';
            }
            if ($author_sep_color != '') {
                $author_sep_color_style = 'style="background: '.$author_sep_color.';"';
            }

			$output .= "\n\t".'<blockquote '.$quote_color_style.' class="gg-vc-quote '.$quote_style.' ' . esc_attr( trim( vc_shortcode_custom_css_class( $css ) ) ) . '">';
			$output .= "\n\t".$quote;
            
            if ($author) {

                if ( $quote_style == 'style_2' ) {
                    $output .= "\n\t".'<hr '.$author_sep_color_style.'/>';
                }
                
                $output .= "\n\t".'<cite '.$author_color_style.'>'.$author.'</cite>';    
            }

			$output .= "\n\t".'</blockquote>';

			return $output;
		}
		
	}// END class WPBakeryShortCode_gg_blockquote

	$WPBakeryShortCode_gg_blockquote = new WPBakeryShortCode_gg_blockquote();

}// END if ( ! class_exists( 'WPBakeryShortCode_gg_blockquote' ) ) {


if ( function_exists( 'vc_map' ) ) {

	vc_map( array(
        "name"              => esc_html__("Blockquote", "okthemes-villenoir-shortcodes"),
        "description"       => esc_html__('Display a blockquote.', 'okthemes-villenoir-shortcodes'),
        "base"              => "blockquote",
        "icon"              => "gg_vc_icon",
        'admin_enqueue_css' => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/css/styles.css'),
        'admin_enqueue_js'  => array(VILLENOIR_SHORTCODES_DIR . '/shortcodes/js/custom-vc.js'),
        "category"          => esc_html__('Villenoir', 'okthemes-villenoir-shortcodes'),
		"params" => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Style', 'okthemes-villenoir-shortcodes' ),
                'value' => array(
                  esc_html__( 'Style 1', 'okthemes-villenoir-shortcodes' ) => 'style_1',
                  esc_html__( 'Style 2', 'okthemes-villenoir-shortcodes' ) => 'style_2',
                ),
                'param_name' => 'quote_style',
            ),
			array(
                "type"        => "textarea",
                "heading"     => esc_html__("Quote", "okthemes-villenoir-shortcodes"),
                "param_name"  => "quote",
                "admin_label" => true,
			),
			array(
                "type"       => "colorpicker",
                "heading"    => esc_html__("Quote color", "okthemes-villenoir-shortcodes"),
                "param_name" => "quote_color"
			),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__("Author", "okthemes-villenoir-shortcodes"),
                "param_name"  => "author",
                "admin_label" => true,
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => esc_html__("Author color", "okthemes-villenoir-shortcodes"),
                "param_name"  => "author_color",
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => esc_html__("Separator color", "okthemes-villenoir-shortcodes"),
                "param_name"  => "author_sep_color",
                "dependency" => Array('element' => "quote_style", 'value' => array('style_2'))
            ),
			array(
                'type'       => 'css_editor',
                'heading'    => __( 'CSS box', 'okthemes-villenoir-shortcodes' ),
                'param_name' => 'css',
                'group'      => __( 'Design Options', 'okthemes-villenoir-shortcodes' ),
			)
		 ),
	) );
}

?>