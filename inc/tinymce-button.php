<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('admin_head', 'rca_shop_add_mce_button');

function rca_shop_add_mce_button() {
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
        return;
    }
    add_filter( 'mce_external_plugins', 'rca_shop_add_tinymce_plugin' );
    add_filter( 'mce_buttons', 'rca_shop_register_mce_button' );
}

function rca_shop_add_tinymce_plugin( $plugin_array ) {
    $plugin_array['rca_shop_tinymce_button'] = plugins_url( '/../assets/js/tiny-mce-button.js' , __FILE__ );
    return $plugin_array;
}

function rca_shop_register_mce_button( $buttons ) {
    array_push( $buttons, 'rca_shop_tinymce_button' );
    return $buttons;
}




function rca_shop_button_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'text' => 'Download',
        'link' => '',
    ), $atts );

    $output = '';

    if ( !empty($a['link']) ) {
        $output .= '<div class="tutor-loop-cart-btn-wrap">
	                <a href="'.$a['link'].'" class="button product_type_simple" rel="nofollow">'.$a['text'].'</a>
	            </div>';
    }
    
    return $output;
}
add_shortcode('shopButton', 'rca_shop_button_shortcode');