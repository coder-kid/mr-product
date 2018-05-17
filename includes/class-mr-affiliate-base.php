<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! class_exists('mr_affiliate_base') ) {

    class mr_affiliate_base {

        /**
         * @var null
         *
         * Instance of this class
         */
        protected static $_instance = null;

        /**
         * @return null|mr_affiliate
         */
        public static function instance() {
            if( is_null(self::$_instance) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * mr_affiliate constructor
         *
         * @hook
         */
        public function __construct() {

            add_action('admin_enqueue_scripts', array($this, 'mr_af_enqueue_admin_script')); // Add aditional backend js and css
            add_action('wp_enqueue_scripts', array($this, 'mr_af_frontend_script')); // Add js and css
	        add_action('init', array($this, 'load_mr_affiliate_functions'));
	        add_action('init', array($this, 'mr_affiliate_template_hook'));
	        add_action('init', array($this, 'mr_af_get_user_own_media_after_load_pluggable'));
	        add_action('admin_init', array($this, 'mr_af_network_disabled_notice'));
	        add_action('admin_head', array($this, 'mr_affiliate_mce_button'));
	        add_filter('plugin_action_links_' . MR_AFFILIATE_BASENAME, array($this, 'mr_affiliate_settings_link'), 10, 5);

	        // Ajax action
	        add_action('wp_ajax_mr_affiliate_reset', array($this, 'mr_affiliate_reset'));

	        // Disable plugin update notification
	        if(MR_AFFILIATE_PLUGIN_TYPE == 'free') {
	        	//
	        }

	        if(MR_AFFILIATE_PLUGIN_TYPE == 'free') {
	        	// Footer text, asking rating
		        add_filter('admin_footer_text', array($this, 'mr_affiliate_admin_footer_text'), 2);
		        add_action('wp_ajax_mraf_rated', array($this, 'mr_af_footer_text_rated'));
	        }

        }

        /**
         * Registering necessary jquery script, js and css
         *
         * @backend
         */
        public function mr_af_enqueue_admin_script() {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script(
                'mr-af-jquery-scripts',
                MR_AFFILIATE_DIR_URL . 'assets/js/mr-affiliate.js',
                array('jquery', 'wp-color-picker'), MR_AFFILIATE_VERSION, true
            );
	        wp_register_style(
                'mr-af-css',
                MR_AFFILIATE_DIR_URL . 'assets/css/mr-affiliate.css',
                false, MR_AFFILIATE_VERSION
            );
            wp_enqueue_style('mr-af-css');
        }

        public function mr_af_frontend_script() {
        	wp_enqueue_script('jquery');
        	wp_enqueue_script('jquery-ui-date-picker');
        	wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
        	wp_enqueue_style('jquery-ui');
        	wp_enqueue_script(
        		'jquery.easypiechart',
		        MR_AFFILIATE_DIR_URL . 'assets/js/jquery.easypiechart.min.js',
		        array('jquery'), MR_AFFILIATE_VERSION, true
	        );
        	wp_enqueue_script(
        		'mr-af-jquery-scripts-front',
		        MR_AFFILIATE_DIR_URL . 'assets/js/mr-affiliate-front.js',
		        array('jquery'), MR_AFFILIATE_VERSION, true
	        );
        	wp_localize_script('mr-af-jquery-scripts-front', 'mr_af_ajax_object', ['ajax_url' => admin_url('amin-ajax.php')]);
        	wp_register_style('mr-affiliate-css-front', MR_AFFILIATE_DIR_URL . 'assets/css/mr-affiliate-front.css', false, MR_AFFILIATE_VERSION);
        	wp_enqueue_style('mr-affiliate-css-front');
        	wp_enqueue_media();
        }

        public function load_mr_affiliate_functions() {
        	require_once MR_AFFILIATE_DIR_PATH . 'includes/mr-affiliate-template-functions.php';
        }

        public function mr_affiliate_template_hook() {
        	require_once MR_AFFILIATE_DIR_PATH . 'includes/mr-affiliate-template-hook.php';
        }

        public function mr_af_get_user_own_media_after_load_pluggable() {
        	if( is_user_logged_in() ) {
        		if( is_admin() ) {
        			if(current_user_can('mr_affiliate_product_form_submit')) {
        				add_action('pre_get_posts', array($this, 'mr_af_get_user_own_media'));
			        }
		        }
	        }
        }

        // Attachment filter
        public function mr_af_get_user_own_media($query) {
        	if($query) {
        		if(! empty($query->query['post_type'])) {
        			if( ! current_user_can('administrator')) {
        				if($query->query['post_type'] == 'attachment') {
        					$user = wp_get_current_user();
        					$query->set('author', $user->ID);
				        }
			        }
		        }
	        }
        }

	    /**
	     * Set notice for disable network
	     */
        public function mr_af_network_disabled_notice() {
        	if( is_plugin_active_for_network(MR_AFFILIATE_BASENAME) ) {
        		add_action('admin_notices', array($this, 'disable_from_network_notice'));
	        }
        }

        // Disable from notice
	    public static function disable_from_network_notice() {
        	$html = '';
        	$html .= '<div class="notice notice-error is-dismissible">';
        	$html .= '<p>'.__('MR Affiliate plugin will not work properly if you activate it from network, please deactivate it
        	from network and activate it from individual site admin.', 'mr-affiliate').'</p>';
        	$html .= '</div>';
        	echo $html;
	    }

	    // Hooks your functions in a correct filters
	    public function mr_affiliate_mce_button() {
        	// check permission
		    if( ! current_user_can('edit_posts') && ! current_user_can('edit_posts') ) {
		    	return;
		    }

		    // Check if WYSIWYG is enabled
		    if( 'true' == get_user_option('rich_editing') ) {
		    	add_filter('mce_external_plugins', array($this, 'mr_affiliate_add_tinymce_js'));
		    	add_filter('mce_buttons', array($this, 'mr_affiliate_register_mce_button'));
		    }
	    }


	    // Declare script for new button
	    public function mr_affiliate_add_tinymce_js($plugins) {
        	$plugins['mr_af_button'] = MR_AFFILIATE_DIR_URL . 'assets/js/mce-button.js';
        	return $plugins;
	    }

	    // Register a new button for the editor
	    public function mr_affiliate_register_mce_button( $buttons ) {
        	array_push($buttons, 'mr_af_button');
        	return $buttons;
	    }

	    public function mr_affiliate_settings_link( $links ) {
        	$new_link = ['settings' => '<a href="'.admin_url('admin.php?page=mr-affiliate').'">Settings</a>'];
        	return array_merge($new_link, $links);
	    }

	    public function mr_affiliate_reset() {
        	$initial_setup = new MR_AFFLIATE_INITIAL_SETUP();
        	$initial_setup->mr_affiliate_reset();
	    }

	    public function mr_affiliate_admin_footer_text($footer_text) {
        	if( ! function_exists('wc_get_screen_ids')) {
        		return $footer_text;
	        }

	        $current_screen = get_current_screen();
        	$mr_affliate_screen_ids = mraf_screen_id();

        	if( ! in_array($current_screen->id, $mr_affliate_screen_ids)) {
        		return $footer_text;
	        }

	        if( ! get_option('mr_af_footer_text_rated')) {
        		$footer_text = sprintf(__('If you like <strong>MR Affiliate</strong> please leave us a 5-stars %s rating.
				A huge thanks in advance!', 'mr-affiliate'), '<a href="" target="_blank" class="mr-af-rating-link"
				data-rated="'.__('Thanks :)', 'woocommerce').'" >&#9733;&#9733;&#9733;&#9733;&#9733;</a>');

        		wp_enqueue_js("
        		    jQuery('a.mr-af-rating-link').on('click', function() {
        		        jQuery.post('".admin_url('admin-ajax.php')."', {action: 'mraf_rated'});
        		        jQuery( this ).parent().text(jQuery(this).data('rated));
        		    });
        		");
	        }else {
        		$footer_text = sprintf(__('Thanks for using <strong>MR Affiliate</strong> by %s', 'mr-affiliate'), '<a href="" target="_blank">Mahfuz</a>');
	        }

	        return $footer_text;
	    }

	    public function mr_af_footer_text_rated() {
        	update_option('mr_af_footer_text_rated', 'true');
	    }


    }

}

mr_affiliate_base::instance(); //Call base class


require_once MR_AFFILIATE_DIR_PATH . 'includes/class-mr-affiliate-templating.php';
require_once MR_AFFILIATE_DIR_PATH . 'includes/class-mr-affiliate-user-registration.php';


// Shortcode Add to the plugin
include MR_AFFILIATE_DIR_PATH . 'shortcode/registration.php';








