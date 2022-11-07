<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function rca_shop_allow_post_tags( $allowedposttags ){

    $allowedposttags['iframe'] = array(
 
        'src' => true,
 
        'width' => true,
 
        'height' => true,
 
        'class' => true,
 
        'frameborder' => true,
 
        'webkitAllowFullScreen' => true,
 
        'mozallowfullscreen' => true,
 
        'allowFullScreen' => true
 
    );
 
    return $allowedposttags;
 
 }
 
 add_filter('wp_kses_allowed_html','rca_shop_allow_post_tags', 1);