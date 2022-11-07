(function() {

    console.log('hello Tiny cart')

    tinymce.PluginManager.add('rca_shop_add_to_cart_tinymce_button', function( editor, url ) {

        editor.addButton('rca_shop_add_to_cart_tinymce_button', {

            title: 'Add To Cart Button',

            text: 'Add To Cart Button',

            icon: false,

            onclick: function () {

                // Open a TinyMCE modal

                editor.windowManager.open({

                    title: 'Add To Cart Button',

                    body: [{

                        type: 'textbox',

                        name: 'text',

                        label: 'Button Text'

                    }, {

                        type: 'textbox',

                        name: 'url',

                        label: 'Button URL'

                    }],

                    onsubmit: function (e) {

                        editor.insertContent('[rca-buy-button btn_text="' + e.data.text + '" btn_url="' + e.data.url + '"]');

                    }

                });

            }

        });

    });

})();