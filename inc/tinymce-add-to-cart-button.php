<?php



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('admin_head', 'rca_shop_add_to_cart_mce_button');

function rca_shop_add_to_cart_mce_button() {

    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {

        return;

    }

    add_filter( 'mce_external_plugins', 'rca_shop_add_to_cart_tinymce_plugin' );

    add_filter( 'mce_buttons', 'rca_shop_register_add_to_cart_mce_button' );

}



function rca_shop_add_to_cart_tinymce_plugin( $plugin_array ) {

    $plugin_array['rca_shop_add_to_cart_tinymce_button'] = plugins_url( '/../assets/js/tiny-mce-add-to-cart-button.js' , __FILE__ );

    return $plugin_array;

}



function rca_shop_register_add_to_cart_mce_button( $buttons ) {

    array_push( $buttons, 'rca_shop_add_to_cart_tinymce_button' );

    return $buttons;

}





function rca_shop_add_to_cart_button_shortcode( $atts ) {

    $a = shortcode_atts( array(

        'btn_text' => 'ADD TO CART',

        'btn_url' => '',

    ), $atts );



    $output = '';



    if ( !empty($a['btn_url']) ) {

        $output .= '<div class="rca-custom-add-to-cart-button">
                <a href="'.$a['btn_url'].'" class="action_button add_to_cart">
                    <span class="text">'.$a['btn_text'].' <i class="fa fa-chevron-right" style="margin-left: 15px"></i></span>
                </a>
            </div>';
    }

    

    return $output;

}

add_shortcode('rca-buy-button', 'rca_shop_add_to_cart_button_shortcode');