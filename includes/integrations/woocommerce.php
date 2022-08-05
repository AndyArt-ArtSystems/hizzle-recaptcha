<?php
/**
 * Contains the main woocommerce class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce integration Class.
 *
 */
class Hizzle_reCAPTCHA_WooCommerce_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 101.0.0
	 */
	public function __construct() {
        add_action( 'woocommerce_review_order_before_submit', array( $this, 'display' ), 11 );
		add_action( 'woocommerce_checkout_process', array( $this, 'verify_token' ) );
	}

	/**
	 * Displays the checkbox.
	 *
	 * @since 1.0.0
	 */
	public function display() {
		Hizzle_reCAPTCHA::$load_scripts = false;

		$attributes = array(
			'class'         => 'g-recaptcha hizzle-recaptcha',
			'style'         => 'max-width: 100%; overflow: hidden; margin-top: 10px; margin-bottom: 10px;',
			'data-sitekey'  => hizzle_recaptcha_get_option( 'site_key' ),
			'data-theme'    => 'light',
			'data-size'     => 'normal',
			'data-tabindex' => '0',
		);

		echo '<div';

		foreach ( apply_filters( 'hizzle_recaptcha_attributes', $attributes ) as $key => $value ) {
			printf(
				' %s="%s"',
				esc_attr( $key ),
				esc_attr( $value )
			);
		}

		echo '></div>';

		$url = apply_filters( 'hizzle_recaptcha_api_url', 'https://www.google.com/recaptcha/api.js' );
		echo '<script type="text/javascript" src="' . $url . '"></script>';
	}

	/**
	 * Handles the submission of comments.
	 *
	 * @since 1.0.0
	 */
	public function verify_token() {

		$error = $this->is_valid();

	    if ( is_wp_error( $error ) ) {
			throw new Exception( $error->get_error_message() );
	    }

	}

}
