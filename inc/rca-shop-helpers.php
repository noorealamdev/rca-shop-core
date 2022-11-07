<?php
defined('ABSPATH') || exit;

if (!class_exists('RCA_Shop_Helper')) {
    class RCA_Shop_Helper
    {
        public static function rca_shop_top_image($template_position = 'below', $class)
        {
            $rca_top_image = carbon_get_theme_option('rca_top_image');
            $rca_show_top_image_home = carbon_get_theme_option('rca_show_top_image_home');

            if ($rca_show_top_image_home == 'yes') {
                if (is_front_page()) { ?>
                    <div id="page-slider" class="page-slider <?php echo $class; ?>">
                        <img src="<?php echo $rca_top_image; ?>" alt="">
                    </div>
                <?php } ?>

                <?php
            } else { ?>
                <div id="page-slider" class="page-slider <?php echo $class; ?>">
                    <img src="<?php echo $rca_top_image; ?>" alt="">
                </div>
            <?php }
        }

        public static function rca_shop_slider( $template_position = 'below' ) {
            //$rca_show_top_image_home = carbon_get_theme_option( 'rca_show_top_image_home' );
            $rca_slider = carbon_get_theme_option( 'rca_slider' );
            ?>

            <?php if ( is_front_page() ) { ?>

                <style>
                    .carousel-cell {
                        width: 100%;
                        margin-right: 10px;
                    }
                </style>

                <!-- Flickity HTML init -->
                <div class="rca-slider carousel">
                    <?php foreach ( $rca_slider as $slider ) { ?>
                        <div class="carousel-cell slider-image">
                            <?php if ( !empty( $slider['url'] ) ) { ?>
                                <a href="<?php echo $slider['url']; ?>">
                                    <?php echo wp_get_attachment_image( $slider['image'], 'full' ); ?>
                                </a>
                            <?php } else {
                                echo wp_get_attachment_image( $slider['image'], 'full' );
                            }
                            ?>

                        </div>
                    <?php } ?>
                </div>

                <script>
                    jQuery(document).ready(function ($) {
                        $('.rca-slider').flickity({
                            // options
                            freeScroll: true,
                            autoPlay: 4000,
                            wrapAround: true
                        });
                    })
                </script>
            <?php } ?>

        <?php }

        private function rca_upsell_popup()
        {
            $course_id = get_the_ID();
            $product_id = tutor_utils()->get_course_product_id();
            $product    = wc_get_product( $product_id );
            $upsell_product_id = get_field('upsell_product_id', $course_id);
            $upsell_title = get_field('upsell_title', $course_id);
            $upsell_description = get_field('upsell_description', $course_id);
            $upsell_price = get_field('upsell_price', $course_id);
            $upsell_product = wc_get_product( $upsell_product_id );


            $upsell_sale_price = get_post_meta($upsell_product_id, '_sale_price', true);
            $product_sale_price = get_post_meta($product_id, '_sale_price', true);
            ?>

            <button type="submit" class="rca-upsell-btn single_add_to_cart_button tutor-button alt">
                <i class="far fa-shopping-cart"></i>
                <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
            </button>

            <div class="edumall-popup popup-user-register rca-upsell-popup" id="rca-upsell-popup">
                <div class="popup-overlay"></div>
                <div class="popup-content">
                    <div class="button-close-popup"></div>
                    <div class="popup-content-wrap">
                        <div class="popup-content-inner" style="">
                            <div class="popup-content-header">
                                <?php if ( !empty($upsell_title) ) { ?>
                                    <h3 class="popup-title"><?php echo $upsell_title ?></h3>
                                <?php } else { ?>
                                    <h3 class="popup-title">FOR ONLY <span style="color: #57ec57"><?php echo $upsell_sale_price - $product_sale_price; ?></span> MORE, YOU WILL GET ANOTHER COURSE</h3>
                                <?php } ?>

                                <?php if ( !empty($upsell_description) ) { ?>
                                    <p class="popup-description" style="margin:10px">
                                        <?php echo esc_html($upsell_description); ?>
                                    </p>
                                <?php } ?>

                                <?php if ( !empty($upsell_price) ) { ?>
                                    <p class="popup-price">
                                        <?php echo esc_html($upsell_price); ?>
                                    </p>
                                <?php } ?>
                            </div>

                            <div class="popup-content-body rca-upsell-body-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="popup-left">
                                            <img src="<?php echo get_the_post_thumbnail_url( $upsell_product_id ); ?>" alt="<?php echo $upsell_product_id; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="popup-right">
                                            <a href="<?php echo home_url() . '/?add-to-cart='.$upsell_product_id.'' ?>" class="single_add_to_cart_button button tutor-button" style="padding: 0; background: #1FA306; border: none; margin-top: 10px; width: 100%">
                                                YES, I want the combo!
                                            </a>
                                            <a href="<?php echo home_url() . '/?add-to-cart='.$product_id.'' ?>" class="popup-red-button single_add_to_cart_button button tutor-button" style="padding: 0; background: #D52011; border: none; margin-top: 20px; width: 100%">
                                                ONLY "<?php the_title(); ?>"
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $('.rca-upsell-btn').on('click', function () {
                    $('#rca-upsell-popup').addClass('open')
                })
            </script>
        <?php }

        public static function rca_single_course_enroll_box()
        {
            global $edumall_course;

            $is_administrator = current_user_can('administrator');
            $is_instructor = tutor_utils()->is_instructor_of_this_course();
            $course_content_access = (bool)get_tutor_option('course_content_access_for_ia');

            if ($edumall_course->is_enrolled()) {
                tutor_load_template('single.course.custom.enrolled-action-buttons');
            } elseif ($course_content_access && ($is_administrator || $is_instructor)) {
                tutor_load_template('single.course.continue-lesson');
            } else {
                $enable_upsell = get_field('enable_upsell', $edumall_course->get_id());
                $upsell_product_id = get_field('upsell_product_id', $edumall_course->get_id());

                if ( $enable_upsell && $upsell_product_id ) {
                    self::rca_upsell_popup();
                } else {
                    tutor_load_template('single.course.add-to-cart');
                }

            }

            return true;
        }


        public static function rca_course_price_html()
        {
            $is_purchasable = tutor_utils()->is_course_purchasable();
            $price = apply_filters('get_tutor_course_price', null, get_the_ID());
            ?>
            <?php if ($is_purchasable && $price) : ?>
            <div class="rca-single-course-price">
                <?php
                $product_id = tutor_utils()->get_course_product_id(get_the_ID());
                $regular_price = get_post_meta($product_id, '_regular_price', true);
                $sale_price = get_post_meta($product_id, '_sale_price', true);
                $you_save_price = (int)$regular_price - (int)$sale_price;
                //$you_save_price = number_format((float)$you_save_price, 2, '.', '');

                echo '', $price;

                $badge_format = esc_html__('You Save %s', 'edumall');
                $badge_text = Edumall_Tutor::instance()->get_course_price_badge_text(get_the_ID(), $badge_format);
                if (!empty($badge_text)) {
                    echo '<span class="course-price-badge onsale">' . $badge_text . ' ($' . $you_save_price . '<span class="rca-decimals-separator">.00</span>)</span>';
                }
                ?>
            </div>
        <?php else : ?>
            <div class="tutor-price course-free">
                <?php esc_html_e('Free', 'edumall'); ?>
            </div>
        <?php
        endif;
        }


        public static function rca_single_course_add_to_cart_section_html()
        { ?>

            <div class="rca-single-course-section"
                 style="background-image: url(https://online.chess-teacher.com/wp-content/uploads/2022/04/add-to-cart-box-single-course-page-scaled.jpg)">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="banner-content">
                                <h3 class="course-title">Click the <span style="color: #c99542">"Add To Cart"</span>
                                    button to order <span style="color: #c99542"><?php the_title(); ?></span> now.</h3>
                                <div class="course-add-to-cart" style="display: inline-block">
                                    <?php RCA_Shop_Helper::rca_single_course_enroll_box(); ?>
                                </div>

                                <div class="course-price">
                                    <?php RCA_Shop_Helper::rca_course_price_html(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php }


        public static function rca_learn_anytime_anywhere_any_device_html()
        {
            echo do_shortcode('[elementor-template id="57169"]');
        }


        public static function rca_add_to_cart_button_html($product_id)
        {
            global $edumall_course;
            $enable_upsell = get_field('enable_upsell', $edumall_course->get_id());
            $upsell_product_id = get_field('upsell_product_id', $edumall_course->get_id());
            ?>
            <div class="rca-custom-add-to-cart-button">
                <?php if ( $enable_upsell && $upsell_product_id ) { ?>
                    <button class="rca-upsell-btn action_button add_to_cart">
                        <span class="text">Add to Cart <i class="fa fa-chevron-right" style="margin-left: 15px"></i></span>
                    </button>
                <?php } else { ?>
                    <button id="rca-ajax-add-to-cart-button-<?php echo $product_id; ?>"
                            onclick="rcaAjaxBtnHandler(<?php echo $product_id; ?>)" type="submit"
                            class="action_button add_to_cart">
                        <span class="text">Add to Cart <i class="fa fa-chevron-right" style="margin-left: 15px"></i></span>
                    </button>
                <?php } ?>
            </div>
        <?php }


        public static function rca_badge_html($course_id)
        {
            // course image badge acf fields
            $rca_enable_badge = get_field('rca_enable_badge', $course_id);
            $rca_badge = get_field('rca_badge', $course_id);
            ?>
            <?php if ($rca_enable_badge) { ?>
            <div class="banner_holder">

                <?php if ($rca_badge == 'SALE Badge') { ?>
                    <div class="sale_banner thumbnail_banner">Sale</div>
                <?php } ?>

                <?php if ($rca_badge == 'NEW Badge') { ?>
                    <div class="new_banner thumbnail_banner">New</div>
                <?php } ?>

                <?php if ($rca_badge == 'SALE Badge + NEW Badge') { ?>
                    <div class="sale_banner thumbnail_banner">Sale</div>
                    <div class="new_banner thumbnail_banner">New</div>
                <?php } ?>

            </div>
            <?php } ?>

        <?php }


    } // Class end

    new RCA_Shop_Helper();

}
