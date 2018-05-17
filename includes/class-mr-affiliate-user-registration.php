<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if( ! class_exists('Mr_Affiliate_User_Registration')) {

	class Mr_Affiliate_User_Registration {

		/**
		 * @var null
		 *
		 * Instance of this class
		 */
		protected static $_instance = null;

		/**
		 * @return null | MR_Afffiliate
		 */
		public static function instance() {
			if(is_null(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * MR_Affiliate constructor
		 *
		 * @hook
		 */
		public function __construct() {
			add_action('init', array($this, 'mr_affiliate_registration_function'));
			add_action('wp_ajax_mr_affiliate_registration_action', array($this, 'mr_affiliate_registration_function'));
		}

		// Register a new user
		public function mr_affiliate_registration_function() {
			if( wp_verify_nonce(mr_affiliate_post('_wpnonce'), 'mraf-nonce-registration' )) {

				// Add some option
				do_action('mraf_before_user_registration_action'); // Check recaptcha system

				$username = $password = $email = $website = $first_name = $last_name = $nickname = $bio = '';

				// Sanitize user form input
				$username = sanitize_user($_POST['username']);
				$password = sanitize_text_field($_POST['password']);
				$email = sanitize_email($_POST['email']);
				$website = sanitize_text_field($_POST['website']);
				$first_name = sanitize_text_field($_POST['fname']);
				$last_name = sanitize_text_field($_POST['lname']);
				$nickname = sanitize_text_field($_POST['nickname']);
				$bio = implode("\n", array_map('sanitize_text_field', explode("\n", $_POST['bio'])));

				$this->mraf_registration_validation(
					$username,
					$password,
					$email,
					$website,
					$first_name,
					$last_name,
					$nickname,
					$bio
				);
				$this->mraf_complete_registration( $username, $password, $email, $first_name, $last_name, $nickname, $website, $bio );

			}else {
				global $reg_errors;
				$reg_errors = new WP_Error;
				$reg_errors->add('secureity', __('Security Error', 'mr-affiliate'));
			}
		}

		public function mraf_registration_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {
			global $reg_errors;
			$reg_errors = new WP_Error;

			if( empty($username) || empty($password) || empty($email) ) {
				$reg_errors->add('field', __( 'Required form field is missing', 'mr-affiliate'));
			}

			if(strlen($username) < 4) {
				$reg_errors->add('username_length', __( 'Username too short. At least 4 characters is required', 'mr-affiliate' ));
			}

			if(username_exists($username)) {
				$reg_errors->add('user_name', __( 'Sorry, that username is already exists!', 'mr-affiliate' ));
			}

			if( ! validate_username($username) ) {
				$reg_errors->add('username_invalid', __( 'Sorry, the username you have entered is invalid', 'mr-affiliate'));
			}

			if(strlen($password) < 6) {
				$reg_errors->add('password', __( 'Password length must be greater than 6', 'mr-affiliate' ));
			}

			if(! is_email($email)) {
				$reg_errors->add('email_invalid', __( 'Email is not valid', 'mr-affiliate' ));
			}

			if( email_exists($email) ) {
				$reg_errors->add('email', __('Email Already in use', 'mr-affiliate'));
			}

			if( ! empty($website) ) {
				if( ! filter_var($website, FILTER_VALIDATE_URL) ) {
					$reg_errors->add('website', __('Website is not a valid URL', 'mr-affiliate'));
				}
			}
		}

		public function mraf_complete_registration($username, $password, $email, $firs_name, $last_name, $nickname, $website, $bio) {
			global $reg_errors;

			if(count($reg_errors->get_error_messages()) < 1) {
				$userdata = [
					'username'      => $username,
					'password'      => $password,
					'email'         => $email,
					'first_name'    => $firs_name,
					'last_name'     => $last_name,
					'nickname'      => $nickname,
					'website'       => $website,
					'bio'           => $bio
				];

				$user_id = wp_insert_user($userdata);

			}
		}

	}

}













