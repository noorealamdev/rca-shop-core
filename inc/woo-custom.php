<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



// Remove woo checkout fields 
add_filter( 'woocommerce_checkout_fields' , 'rca_remove_checkout_fields' );

function rca_remove_checkout_fields( $fields ) {
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_phone']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_company']);
    unset($fields['order']['order_comments']);
    return $fields;
}


add_filter('woocommerce_product_add_to_cart_text', 'woo_archive_custom_cart_button_text');   // 2.1 +

function woo_archive_custom_cart_button_text()
{
    return __('Buy Now', 'woocommerce');
}

// change add to cart text on single course page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );
function woo_custom_single_add_to_cart_text() {
    return __( 'ADD TO CART', 'woocommerce' );
}

// empty cart button url
function wc_empty_cart_redirect_url() {
    return home_url();
}
add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );

// remove product category from search result
function remove_product_categories_from_search_results( $query ) {
    if (
        ! is_admin()
        && $query->is_main_query()
        && $query->is_search()
    ) {
        $query->set( 'post_type', array( 'courses' ) );
        $tax_query = array(
            array(
                // likely what you are after
                'taxonomy' => 'course-category',
                'field'   => 'slug',
                'terms'   => array('hidden'),
                'operator' => 'NOT IN',
            ),
        );
        $query->set( 'tax_query', $tax_query );
    }

}
add_action( 'pre_get_posts', 'remove_product_categories_from_search_results', 99 );