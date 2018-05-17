<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_shortcode('mr_affiliate_registration', 'mr_affiliate_registration_shortcode');

function mr_affiliate_registration_shortcode() {

	if( is_user_logged_in() ) {
		?>
		<h3 class="mraf-center"><?php _e('You are already logged in.', 'mr-affiliate'); ?></h3>
		<?php
	}else {
		global $reg_errors, $reg_success;
		$nonce = wp_create_nonce('mraf-nonce-registration');
		?>

		<div class="mr-affiliate-user-registration-wrap">
			<form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" id="mr-affiliate-registration" method="post">
				<input type="hidden" name="_nonce" value="<?php echo $nonce; ?>">
				<?php
					$mraf_user_registration_meta_array = [
						[
							'id'            => 'fname',
							'label'         => __( 'First Name', 'mr-affiliate' ),
							'type'          => 'text',
							'placeholder'   => __( 'Enter First Name', 'mr-affiliate' ),
							'value'         => '',
							'class'         => '',
							'wrapclss'      => 'mraf-first-half',
							'autocomplete'  => 'off'
						],
						[
							'id'            => 'lname',
							'label'         => __( 'Last Name', 'mr-affiliate' ),
							'type'          => 'text',
							'placeholder'   => __( 'Enter Last Name', 'mr-affiliate' ),
							'value'         => '',
							'class'         => '',
							'wrapclass'     => 'mraf-second-half',
							'autocomplete'  => 'off'
						],
						[
							'id'            => 'username',
							'label'         => __( 'Username *', 'mr-affiliate' ),
							'type'          => 'text',
							'placeholder'   => __( 'Enter Username', 'mr-affiliate' ),
							'value'         => '',
							'class'         => 'required',
							'wrapclass'     => '',
							'autocomplete'  => 'off'
						],
						[
							'id'            => 'password',
							'label'         => __( 'Password *', 'mr-affiliate' ),
							'type'          => 'password',
							'placeholder'   => __( 'Enter Password', 'mr-affiliate' ),
							'value'         => '',
							'class'         => 'required',
							'wrapclass'     => '',
							'autocomplete'  => 'off'
						],
						[
							'id'            => 'email',
							'label'         => __( 'Email *', 'mr-affiliate' ),
							'type'          => 'text',
							'placeholder'   => __( 'Enter Email', 'mr-affiliate' ),
							'value'         => '',
							'class'         => 'required',
							'wrapclass'     => 'mraf-first-half',
							'autocomplete'  => 'off'
						],
						[
							'id'            => 'website',
							'label'         => __( 'Website', 'mr-affiliate' ),
							'type'          => 'text',
							'placeholder'   => __( 'Enter Website', 'mr-affiliate' ),
							'value'         => '',
							'class'         => '',
							'wrapclass'     => 'mraf-second-half',
							'autocomplete'  => 'off'
						],
						[
							'id'            => 'nickname',
							'label'         => __( 'Nickname', 'mr-affiliate' ),
							'type'          => 'text',
							'placeholder'   => __( 'Enter Nickname', 'mr-affiliate' ),
							'value'         => '',
							'class'         => '',
							'wrapclass'     => '',
							'autocomplete'  => 'off'
						],
						[
							'id'            => 'bio',
							'label'         => __( 'About / Bio', 'mr-affiliate' ),
							'type'          => 'textarea',
							'placeholder'   => __( 'Enter About / Bio', 'mr-affiliate' ),
							'value'         => '',
							'class'         => '',
							'wrapclass'     => '',
							'autocomplete'  => 'off'
						]

					];

					$mraf_user_registration_meta = apply_filters('mr_affiliate_user_registration_fields', $mraf_user_registration_meta_array);

					foreach ($mraf_user_registration_meta as $item) { ?>
						<div class="mraf-single <?php echo (isset($item['wrapclass']) ? $item['wrapclass'] : ""); ?>">
							<div class="mraf-name"><?php echo (isset($item['label']) ? $item['label'] : ""); ?></div>
							<div class="mraf-fields">
								<?php
									switch($item['type']) {
										case 'text':
											echo '<input
												type="text" id="'.$item['id'].'"
												autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'"
												name="'.$item['id'].'" placeholder="'.$item['placeholder'].'" >';
											break;

										case 'password':
											echo '<input
												type="password" id="'.$item['id'].'"
												autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'"
												name="'.$item['id'].'" placeholder="'.$item['placeholder'].'" >';
											break;

										case 'textarea':
											echo '<textarea id="'.$item['id'].'" autocomplete="'.$item['autocomplete'].'" class="'.$item['class'].'" name="'.$item['id'].'"></textarea>';
											break;

										case 'submit':
											echo '<input type="submit" id="'.$item['id'].'" class="'.$item['class'].'" name="'.$item['id'].'">';
											break;

										case 'shortcode':
											echo do_shortcode($item['shortcode']);
											break;
									}
								?>
							</div>
						</div>
					<?php } ?>
				<div class="mraf-single mraf-register">
					<a href="<?php echo get_home_url(); ?>" class="mraf-cance-registration"><?php _e('Cancel', 'mr-affiliate'); ?></a>
					<input type="hidden" name="action" value="mr_affiliate_registration_action" />
					<input type="hidden" name="current_page" value="<?php echo get_the_permalink(); ?>" />
					<input type="submit" class="mraf-submit-user-registration" id="user-registration-btn" value="<?php echo _e('Sign Up', 'mr-affiliate'); ?>">
				</div>
			</form>
		</div>
		<?php
	}

	return ob_get_clean();

}