<?php

namespace Rtwpvs\Controllers;

class BlackFridayOffer {
	public function __construct() {
		add_action(
			'admin_init',
			function () {
				$current = time();
				if ( mktime( 0, 0, 0, 11, 17, 2022 ) <= $current && $current <= mktime( 0, 0, 0, 1, 15, 2023 ) ) {
					if ( get_option( 'rtwpvs_ny_2023' ) != '1' ) {
						if ( ! isset( $GLOBALS['rtwpvs_ny_2023_notice'] ) ) {
							$GLOBALS['rtwpvs_ny_2023_notice'] = 'rtwpvs_ny_2023';
							self::notice();
						}
					}
				}
			}
		);
	}

	/**
	 * Undocumented function.
	 *
	 * @return void
	 */
	public static function notice() {
		add_action(
			'admin_enqueue_scripts',
			function () {
				wp_enqueue_script( 'jquery' );
			}
		);

		add_action(
			'admin_notices',
			function () {
				$plugin_name   = 'Variation Swatches for WooCommerce Pro';
				$download_link = 'https://www.radiustheme.com/downloads/woocommerce-variation-swatches/'; ?>
				<div class="notice notice-info is-dismissible" data-rtwpvsdismissable="rtwpvs_ny_2023"
					style="display:grid;grid-template-columns: 100px auto;padding-top: 25px; padding-bottom: 22px;">
					<img alt="<?php echo esc_attr( $plugin_name ); ?>"
						src="<?php echo rtwpvs()->get_assets_uri( 'images/icon-128x128.png' ); ?>" width="74px"
						height="74px" style="grid-row: 1 / 4; align-self: center;justify-self: center"/>
					<h3 style="margin:0;"><?php echo sprintf( '%s New Year Deal!!', $plugin_name ); ?></h3>

					<p style="margin:0 0 2px;">
						<?php echo esc_html__( "Don't miss out on our biggest sale of the year! Get your.", 'review-schema' ); ?>
						<b><?php echo esc_attr( $plugin_name ); ?> plan</b> with <b>UP TO 50% OFF</b>! Limited time offer!!
					</p>

					<p style="margin:0;">
						<a class="button button-primary" href="<?php echo esc_url( $download_link ); ?>" target="_blank">Buy Now</a>
						<a class="button button-dismiss" href="#">Dismiss</a>
					</p>
				</div>
					<?php
			}
		);

		add_action(
			'admin_footer',
			function () {
				?>
				<script type="text/javascript">
					(function ($) {
						$(function () {
							setTimeout(function () {
								$('div[data-rtwpvsdismissable] .notice-dismiss, div[data-rtwpvsdismissable] .button-dismiss')
									.on('click', function (e) {
										e.preventDefault();
										$.post(ajaxurl, {
											'action': 'rtwpvs_dismiss_admin_notice',
											'nonce': <?php echo json_encode( wp_create_nonce( 'rtwpvs-dismissible-notice' ) ); ?>
										});
										$(e.target).closest('.is-dismissible').remove();
									});
							}, 1000);
						});
					})(jQuery);
				</script>
					<?php
			}
		);

		add_action(
			'wp_ajax_rtwpvs_dismiss_admin_notice',
			function () {
				check_ajax_referer( 'rtwpvs-dismissible-notice', 'nonce' );

				update_option( 'rtwpvs_ny_2023', '1' );
				wp_die();
			}
		);
	}
}
