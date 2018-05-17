<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! class_exists('MR_AFFLIATE_INITIAL_SETUP') ) {

    class MR_AFFLIATE_INITIAL_SETUP {
        /**
         * @var null
         *
         * Instance of this class
         */
        public static $_instance = null;


        /**
         * @return null | MR_AFFILIATE
         */
        public static function instance() {
            if( is_null(self::$_instance) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Some task during plugin activation
         */
        public function plugin_initial_setup() {
            if( get_option('mr_affiliate_plugin_is_used') )
                return false;

            update_option('mr_affiliate_plugin_is_used', MR_AFFILIATE_VERSION); // Insert plugin version name into Option
            update_option('mr_affiliate_selected_theme', 'basic'); //Select a basic theme
            update_option('vendor_type', 'woocommerce'); // Select default payment type to WooCommerce
            update_option('mr_affiliate_default_product_status', 'draft'); // Select default product status
            update_option('mr_affiliate_enable_color_styling', 'true'); // Set check true at Enable color styling option for custom color layout
            update_option('mr_affiliate_show_min_price', 'true'); // Set check true at min price show during product add
            update_option('mr_affiliate_show_max_price', 'true'); // Set check true at max price show during product add
            update_option('mr_affiliate_recommended_price', 'true'); // Set check true at recommended price show during campaign add.
            update_option('mr_affiliate_show_target_goal', 'true'); // Set check at product end method
            update_option('mr_affiliate_show_target_date', 'true');
            update_option('mr_affiliate_show_target_goal_and_date', 'true');
            update_option('mr_affiliate_show_product_never_end', 'true');
            update_option('mr_affiliate_enable_paypal_per_product_email', 'true');
            update_option('mr_affiliate_single_page_template', 'in_mr_affiliate'); // Single page rewards
            update_option('mr_affiliate_single_page_reward_design', '1'); // Single page rewards
            update_option('hide_mr_affiliate_product_from_shop_page', 'false'); // Hide products from shop page initial value
            update_option('mr_affiliate_add_to_cart_redirect', 'checkout_page'); // Redirect Add to cart

            // WooCommerce Settings
            update_option('mr_affiliate_single_page_id', 'true'); // Redirect Add to cart

            /**
             * Recaptcha page settings
             */
            update_option('mr_affiliate_enable_recaptcha', 'false');
            update_option('mr_affiliate_enable_recaptcha_in_user_registration', 'false');
            update_option('mr_affiliate_enable_recaptcha_in_product_submit_page', 'false');
            update_option('mr_affiliate_requirement_agree_title', 'I agree with the terms and conditions'); // accept agreement during add campaign


            // Create page object
            $mr_affiliate_dashboard_page = [
                'post_title'    => 'MR Dashboard',
                'post_content'  => '[mr_affiliate_dashboard]',
                'post_type'     => 'page',
                'post_status'   => 'publish'
            ];

            $mr_affiliate_form_page = [
                'post_title'    => 'MR product form',
                'post_content'  => '[mr_affiliate_form]',
                'post_type'     => 'page',
                'post_status'   => 'publish'
            ];

            $mr_affiliate_listing_page = [
                'post_title'    => 'MR Listing Page',
                'post_conent'   => '[mr_affiliate_listing]',
                'post_type'     => 'page',
                'post_status'   => 'publish'
            ];

            $mr_affiliate_registration_page_arg = [
                'post_title'    => 'MR User Registration',
                'post_content'  => '[mr_affiliate_registration]',
                'post_type'     => 'page',
                'post_status'   => 'publish'
            ];

            // Insert the page into the database
            $insert_dashboard_page = wp_insert_post($mr_affiliate_dashboard_page);
            wp_insert_post($mr_affiliate_registration_page_arg);
            $mr_affiliate_frm_page = wp_insert_post($mr_affiliate_form_page);
            wp_insert_post($mr_affiliate_listing_page);

            /**
             * Update option mr affiliate dashboard page
             */
            if($insert_dashboard_page) {
                update_option('mr_affiliate_dashboard_page_id', $insert_dashboard_page);
            }

            /**
             * add or update option
             */
            if($mr_affiliate_frm_page) {
                update_option('mr_affiliate_form_page_id', $mr_affiliate_frm_page);
            }

            // Upload permission
            update_option('mr_affiliate_role_selector', ['administrator', 'editor', 'author', 'shop_manager']);
            $role_list = get_option('mr_affiliate_role_selector');
            if( is_array($role_list) ) {
                if( ! empty($role_list) ) {
                    foreach($role_list as $val ) {
                        $role = get_role($val);
                        $role->add_cap('mr_affiliate_product_form_submit');
                        $role->add_cap('upload_files');
                    }
                }
            }

        }

        /**
         * Reset method, the ajax will call that method
         */
        public function mr_affiliate_reset() {


            update_option('mr_affiliate_plugin_is_used', MR_AFFILIATE_VERSION); // Insert plugin version name into Option
            update_option('mr_affiliate_selected_theme', 'basic'); //Select a basic theme
            update_option('vendor_type', 'woocommerce'); // Select default payment type to WooCommerce
            update_option('mr_affiliate_default_product_status', 'draft'); // Select default product status
            update_option('mr_affiliate_show_min_price', 'true'); // Set check true at min price show during product add
            update_option('mr_affiliate_show_max_price', 'true'); // Set check true at max price show during product add
            update_option('mr_affiliate_recommended_price', 'true'); // Set check true at recommended price show during campaign add.
            update_option('mr_affiliate_show_campaign_end_method', 'true'); // Set check true at recommended price show during campaign add.
            update_option('mr_affiliate_show_target_goal', 'true'); // Set check at product end method
            update_option('mr_affiliate_show_target_date', 'true');
            update_option('mr_affiliate_show_target_goal_and_date', 'true');
            update_option('mr_affiliate_show_product_never_end', 'true');
            update_option('mr_affiliate_enable_paypal_per_product_email', 'true');
            update_option('mr_affiliate_single_page_reward_design', '1'); // Single page rewards
            update_option('hide_mr_affiliate_product_from_shop_page', 'false'); // Hide products from shop page initial value
            update_option('mr_affiliate_add_to_cart_redirect', 'checkout_page'); // Redirect Add to cart



            /**
             * Recaptcha page settings
             */
            update_option('mr_affiliate_enable_recaptcha', 'false');
            update_option('mr_affiliate_enable_recaptcha_in_user_registration', 'false');
            update_option('mr_affiliate_enable_recaptcha_in_product_submit_page', 'false');
            update_option('mr_affiliate_requirement_agree_title', 'I agree with the terms and conditions'); // accept agreement during add campaign


            // Init Setup Action
            update_option('mr_affiliate_role_selector', ['administrator', 'editor', 'author', 'shop_manager']);
            $role_list = get_option('mr_affiliate_role_selector');
            if( is_array($role_list) ) {
                if( ! empty($role_list) ) {
                    foreach($role_list as $val ) {
                        $role = get_role($val);
                        $role->add_cap('mr_affiliate_product_form_submit');
                        $role->add_cap('upload_files');
                    }
                }
            }
        }


        /**
         * Show notice if there is no vendor
         */
        public static function no_vendor_notice() {
            $html = '';

            $html .= '<div class="notice notice-error is-dismissible">
                        <p>'.__('Please install & activivate WooCommerce in order to use MR Affiliate Product Plugin', 'mr-affiliate').'</p>
                      </div>';

            echo $html;
        }

        public static function wc_low_version() {
            $html = '';
            $html .= '
                <div class="notice notice-error is-dismissible">
                    <p>'.__('Your WooCommerce version is below 3.0, please update', 'mr-affilite').'</p>
                </div>
            ';
            echo $html;
        }


    }


}