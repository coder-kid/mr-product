<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! function_exists('mr_affiliate_post')) {
	function mr_affiliate_post($post_item) {
		if( ! empty($_POST[$post_item]) ) {
			return $_POST[$post_item];
		}
		return null;
	}
}

/**
 * @param string $version
 *
 * @return bool
 *
 */
function mr_affiliate_version_check( $version = '3.0' ) {
    $all_plugins = get_plugins();
    if(isset($all_plugins['woocommerce/woocommerce.php'])) {
        $current_wc_version = $all_plugins['woocommerce/woocommerce.php']['Version'];
        if(version_compare($current_wc_version, $version, '>=') ) {
            return true;
        }
    }
    return false;
}


/**
 * @return mixed|void
 *
 * @return MR_AFFILIATE admin page ID
 */
if( ! function_exists('mraf_screen_id') ) {
	function mraf_screen_id() {
		$screen_ids = [
			'toplevel_page_mr-affiliate',
			'mr_affiliate_page_mr-affiliate-reports',
			'mr_affiliate_page_mr-affiliate-withdraw'
		];

		return apply_filters('mraf_screen_id', $screen_ids);
	}
}