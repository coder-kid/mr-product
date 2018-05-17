/*--------------------------------------------*/
/* MR Affiliate shortcode button
/*--------------------------------------------*/
(function(){

    tinymce.PluginManager.add('mr_af_button', function(editor, url) {
        editor.addButton('mr_af_button', {
            text: 'Shortcode',
            icon: false,
            type: 'menubutton',
            menu: [
                {
                    text: 'Registration Shortcode',
                    onclick: function() {
                        editor.insertContent('[mr_affiliate_registration]');
                    }
                },
                {
                    text: 'Dashboard Shortcode',
                    onclick: function() {
                        editor.insertContent('[mr_affiliate_dashboard]');
                    }
                },
                {
                    text: 'Search Shortcode',
                    onclick: function() {
                        editor.insertContent('[mr_affiliate_search_shortcode]');
                    }
                },
                {
                    text: 'Form Shortcode',
                    onclick: function() {
                        editor.insertContent('[mr_affiliate_form]');
                    }
                },
                {
                    text: 'Listing Shortcode',
                    onclick: function() {
                        editor.windowManager.open({
                            title: 'Product Listing Shortcode',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'number',
                                    label: 'number',
                                    value: '-1'
                                },
                                {
                                    type: 'textbox',
                                    name: 'cat',
                                    label: 'Category Slug',
                                    value: ''
                                }
                            ],
                            onsubmit: function( e ) {
                                editor.insertContent('[mr_affiliate_listing number="' + e.data.number + '" cat="' + e.data.cat + '"]');
                            }
                        });
                    }
                },
                {
                    text: 'Single Product Page',
                    onclick: function() {
                        editor.windowManager.open({
                            title: 'Product Single Shortcode',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'product_id',
                                    label: 'Product ID',
                                    value: '0'
                                }
                            ],
                            onsubmit: function( e ) {
                                editor.insertContent('[mr_affiliate_single_product product_id="' + e.data.product_id + '"]');
                            }
                        });
                    }
                },
                {
                    text: 'Single Product Box',
                    onclick: function() {
                        editor.windowManager.open({
                            title: 'Single Product Box Shortcode',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'product_id',
                                    label: 'Product ID',
                                    value: '0'
                                }
                            ],
                            onsubmit: function( e ) {
                                editor.insertContent('[mr_af_product_box product_id="' + e.data.product_id + '"]');
                            }
                        });
                    }
                }
            ]
        });
    });

})();