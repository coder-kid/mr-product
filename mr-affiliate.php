<?php
/*
 * Plugin Name:       MR Affiliate
 * Plugin URI:        https://www.themeum.com/product/wp-crowdfunding-plugin/
 * Description:       MR Affiliate for essential affiliate product promotion
 * Version:           1.0.0
 * Author:            Mahfuz
 * Author URI:        https://projuktilekha.com
 * Text Domain:       mr-affiliate
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! function_exists('is_plugin_active_for_network') ) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}

if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Define mr_affiliate version
define('MR_AFFILIATE_VERSION', '1.0.0');

/**
 * Type of MR Affiliate plugin type
 */
define('MR_AFFILIATE_PLUGIN_TYPE', 'free');

// Define mr_affiliate plugin's url path
define('MR_AFFILIATE_DIR_URL', plugin_dir_url(__FILE__) );

// Define mr_affiliate plugin's physical path
define('MR_AFFILIATE_DIR_PATH', plugin_dir_path(__FILE__));

// Define mr_affiliate plugin's file basename
define('MR_AFFILIATE_BASENAME', plugin_basename(__FILE__));


// Define mr_affliate slug
require_once MR_AFFILIATE_DIR_PATH . 'includes/class-mr-affiliate-initial-setup.php';

// Some task during plugin activation
register_activation_hook(__FILE__, ['MR_AFFLIATE_INITIAL_SETUP', 'plugin_initial_setup']);

/**
 * Include Require File
 */
$is_valid_plugin = apply_filters('is_wp_mr_affiliate_valid', true);

if($is_valid_plugin) {
    include_once MR_AFFILIATE_DIR_PATH . 'includes/mr-affiliate-general-functions.php';
    include_once MR_AFFILIATE_DIR_PATH . 'admin/menu-settings.php';

    /**
     * Checking vendor
     */
    if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || is_plugin_active_for_network('woocommerce/woocommerce.php'))
    {
        if( mr_affiliate_version_check() ) {
            require_once MR_AFFILIATE_DIR_PATH . 'includes/class-mr-affiliate-base.php';
            require_once MR_AFFILIATE_DIR_PATH . 'includes/woocommerce/class-mr-affiliate.php';
            require_once MR_AFFILIATE_DIR_PATH . 'includes/class-mr-affliate-frontend-dashboard.php';
        }else {
            add_action('admin_notices', array('MR_AFFLIATE_INITIAL_SETUP', 'wc_low_version'));
            deactivate_plugins(plugin_basename(__FILE__));
        }
    }else {
        add_action('admin_notices', array('MR_AFFLIATE_INITIAL_SETUP', 'no_vendor_notice'));
    }

}
