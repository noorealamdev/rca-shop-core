(function() {
    tinymce.PluginManager.add('rca_shop_tinymce_button', function( editor, url ) {
        editor.addButton('rca_shop_tinymce_button', {
            title: 'Add Button',
            text: 'Add Button',
            icon: false,
            onclick: function () {
                // Open a TinyMCE modal
                editor.windowManager.open({
                    title: 'Button',
                    body: [{
                        type: 'textbox',
                        name: 'text',
                        label: 'Button Text'
                    }, {
                        type: 'textbox',
                        name: 'link',
                        label: 'Link URL'
                    }],
                    onsubmit: function (e) {
                        editor.insertContent('[shopButton text="' + e.data.text + '" link="' + e.data.link + '"]');
                    }
                });
            }
        });
    });
})();