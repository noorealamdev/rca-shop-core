<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;


add_action( 'carbon_fields_register_fields', 'rca_shop_core_options_fields' );
function rca_shop_core_options_fields() {
    
    $slider_labels = array(
		'plural_name' => 'More Images',
		'singular_name' => 'More Image',
	);
	
    Container::make( 'theme_options', __( 'RCA Shop Core' ) )
        ->add_tab( __('General Settings'), array(
            Field::make( 'image', 'rca_top_image', __( 'Top Image  (below the menu)' ) )
                ->set_help_text( 'Perfect size is 1920x400 pixels' )
                ->set_value_type( 'url' ),

            //Field::make( 'footer_scripts', 'crb_footer_scripts', __( 'Footer Scripts' ) ),

            Field::make( 'checkbox', 'rca_show_top_image_home', __( 'Show top image only on home page' ) )
                ->set_option_value( 'yes' ),
                
                
            Field::make( 'complex', 'rca_slider', __( 'Top Image (Slider)' ) )
                ->setup_labels( $slider_labels )
                ->add_fields( array(
	                Field::make( 'image', 'image', __( 'Slide Image' ) )
		                ->set_help_text( 'Perfect size is 1920x400 pixels' ),
	                Field::make( 'text', 'url', __( 'Image Link' ) )
            ) ),

        ) );
}