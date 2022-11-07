<?php

add_action('wp_body_open', 'rca_ajax_add_to_cart_after_body_open_tag');

function rca_ajax_add_to_cart_after_body_open_tag()
{

    //WC()->cart->empty_cart();
    ?>

    <div id="ajax_cart_content_wrapper" class="ajax_cart_content_wrapper" style="display: none">
        <div class="ajax-cart-header">
            <span style="font-size: 15px; font-weight: bold;">MY CART</span>
            <span class="ajax-close-text" style="font-size: 14px; cursor: pointer; float:right">Close</span>
        </div>

        <ul id="rca-ajax-cart-items" class="woocommerce-mini-cart cart_list product_list_widget ajax-cart-ul">
            <h6 id="rca-cart-empty-text" style="display: none">Your Cart is Empty</h6>
            <?php
            do_action('woocommerce_before_mini_cart_contents');

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
                    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key);
                    $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    ?>
                    <li id="li-cart-item-<?php echo $cart_item_key; ?>"
                        class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">

                        <div id="rca-remove-cart-item" class="remove remove_from_cart_button"
                             aria-label="Remove this item" data-product_id="<?php echo $product_id; ?>"
                             data-cart_item_key="<?php echo $cart_item_key; ?>" data-product_sku="">×
                        </div>

                        <i id="rca-ajax-loading-icon-<?php echo $cart_item_key; ?>"
                           style="display: none; margin-right: 5px; font-weight: 800; position: absolute; right: 0; font-size: 20px"
                           class='fa fa-spinner fa-spin'>

                        </i>

                        <div class="product-thumbnail">
                            <?php
                            printf(
                                '%1$s %2$s %3$s',
                                !empty($product_permalink) ? '<a href="' . esc_url($product_permalink) . '">' : '',
                                $thumbnail,
                                !empty($product_permalink) ? '</a>' : ''
                            );
                            ?>
                        </div>

                        <div class="product-caption">
                            <h3 class="product-name">
                                <?php
                                printf(
                                    '%1$s %2$s %3$s',
                                    !empty($product_permalink) ? '<a href="' . esc_url($product_permalink) . '" class="link-in-title">' : '',
                                    esc_html($product_name),
                                    !empty($product_permalink) ? '</a>' : ''
                                );
                                ?>
                            </h3>
                            <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                            <?php echo apply_filters(
                                'woocommerce_widget_cart_item_quantity',
                                '<span class="quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], $product_price) . '</span>',
                                $cart_item,
                                $cart_item_key
                            ); ?>
                        </div>
                    </li>
                    <?php
                }
            }

            do_action('woocommerce_mini_cart_contents');
            ?>

        </ul>

        <div class="ajax-cart-footer">
            <div class="woocommerce-mini-cart__total total">
                <strong>Subtotal:</strong> <span
                        id="rca-cart-subtotal"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            </div>

            <div class="woocommerce-mini-cart__buttons buttons">
                <a href="<?php echo wc_get_checkout_url(); ?>" class="button checkout wc-forward">Checkout</a>
            </div>
        </div>
    </div>

    <script>
    
        function rcaAjaxBtnHandler(productID) {
            //console.log(productID);
            $('#rca-ajax-loading-icon-' + productID + '').show();

            $('#ajax_cart_content_wrapper').show();
            $('#rca-cart-empty-text').hide();
            var ajaxurl = "<?php echo esc_attr(admin_url('admin-ajax.php')); ?>";
            var data = {
                action: 'rca_ajax_add_to_cart',
                productID: productID
            };
            $.post(ajaxurl, data, function (cartItem) {
                //console.log(cartItem);
                if (cartItem.success) {
                    $(document.body).trigger('wc_fragment_refresh');

                    var html = '';

                    html += '<li id="li-cart-item-' + cartItem.key + '" class="woocommerce-mini-cart-item mini_cart_item">';

                    html += '<div id="rca-remove-cart-item" class="remove remove_from_cart_button" aria-label="Remove this item" data-product_id="' + cartItem.product_id + '" data-cart_item_key="' + cartItem.key + '" data-product_sku="' + cartItem.sku + '">×</div>';

                    html += '<i id="rca-ajax-loading-icon-' + cartItem.key + '" style="display: none; margin-right: 5px; font-weight: 800; position: absolute; right: 0; font-size: 20px" class=\'fa fa-spinner fa-spin\'></i>';

                    html += '<div class="product-thumbnail"><a href="' + cartItem.link + '"> ' + cartItem.image + ' </a></div>';

                    html += '<div class="product-caption"><h3 class="product-name"><a href="' + cartItem.link + '" class="link-in-title">' + cartItem.name + '</a></h3><span class="quantity">' + cartItem.quantity + ' × ' + cartItem.price + '</span></div>';

                    $('#rca-ajax-cart-items').prepend(html);

                    // Refresh the subtotal div
                    $("#rca-cart-subtotal").load(" #rca-cart-subtotal > *");

                    // Change add to cart button text after adding the item
                    $('#rca-ajax-add-to-cart-button-' + productID + '').html("VIEW CART");
                } else {
                    console.log(cartItem.message);
                }
                $('#rca-ajax-loading-icon-' + productID + '').hide();
            }, 'json');

        }


        // Remove cart item on click
        $(document).on('click', '#rca-remove-cart-item', function (e) {
            e.preventDefault();

            var cart_item_key = $(this).attr("data-cart_item_key");

            $('#rca-ajax-loading-icon-' + cart_item_key + '').show();

            $.ajax({
                url: "<?php echo esc_attr(admin_url('admin-ajax.php')); ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    action: "rca_ajax_cart_item_remove",
                    cart_item_key: cart_item_key
                },
                success: function (data) {
                    if (data.status === true) {
                        $(document.body).trigger('wc_fragment_refresh');
                        // Refresh the subtotal div
                        $("#rca-cart-subtotal").load(" #rca-cart-subtotal > *");
                        $('#li-cart-item-' + data.li_cart_item_key + '').fadeOut('slow', function () {
                            $(this).remove();
                        });
                        $('#rca-ajax-loading-icon-' + data.li_cart_item_key + '').hide();
                        //console.log('cart item removed');
                        if (data.isEmpty) {
                            setTimeout(() => {
                                $('#rca-cart-empty-text').show();
                            }, 500)
                        }
                    }
                    //console.log(data);
                }
            });
        });

        // hide ajax cart area
        $('.ajax-close-text').click(function () {
            $('#ajax_cart_content_wrapper').fadeOut('slow', function () {
                $(this).hide();
            });
        })

    </script>

    <?php
}


add_action('wp_ajax_rca_ajax_add_to_cart', "rca_ajax_add_to_cart");
add_action('wp_ajax_nopriv_rca_ajax_add_to_cart', "rca_ajax_add_to_cart");

function rca_ajax_add_to_cart()
{
    $cart_item = [];
    $cart_item['success'] = false;

    $product_id = $_POST['productID'];

    if ($product_id) {

        $cart_items = WC()->cart->get_cart();
        if ($cart_items) {
            foreach ($cart_items as $item) {
                $product = $item['data'];
                if ($product_id == $product->id) {
                    $cart_item['success'] = false;
                    wp_die();
                }
            }
        }

        if ($cart_item['success'] = !true) {
            return;
        } else {
            WC()->cart->add_to_cart($product_id);

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $cart_product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($cart_product_id == $product_id) {

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key);
                        $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);

                        $remover_cart_item_link = wc_get_cart_remove_url($cart_item_key);

                        $cart_item['name'] = $product_name;
                        $cart_item['link'] = $product_permalink;
                        $cart_item['price'] = $product_price;
                        $cart_item['image'] = $thumbnail;
                        $cart_item['removeUrl'] = $remover_cart_item_link;
                        $cart_item['sku'] = $_product->get_sku();
                        $cart_item['success'] = true;

                    }
                }
            }

            $cart_item['subtotal'] = WC()->cart->get_cart_subtotal();

            echo json_encode($cart_item);
            wp_die();
        }
    }

    // success = false no product id found
    echo json_encode($cart_item['message'] = 'No product id found');
    wp_die();
}


// Remove cart item ajax function
add_action('wp_ajax_rca_ajax_cart_item_remove', 'rca_ajax_cart_item_remove');
add_action('wp_ajax_nopriv_rca_ajax_cart_item_remove', 'rca_ajax_cart_item_remove');
function rca_ajax_cart_item_remove()
{

    $data = [];
    $data['isEmpty'] = false;

    $cart_item_key = $_POST['cart_item_key'];
    if ($cart_item_key) {
        WC()->cart->remove_cart_item($cart_item_key);

        if (WC()->cart->get_cart_contents_count() == 0) {
            $data['isEmpty'] = true;
        }
        $data['status'] = true;
        $data['li_cart_item_key'] = $cart_item_key;
        echo json_encode($data);
        wp_die();
    }

    echo json_encode(['status' => false]);
    wp_die();
}

?>