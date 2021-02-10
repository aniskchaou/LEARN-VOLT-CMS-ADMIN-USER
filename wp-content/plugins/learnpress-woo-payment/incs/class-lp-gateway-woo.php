<?php
defined( 'ABSPATH' ) || exit();

class LP_Gateway_Woo extends LP_Gateway_Abstract {

	public $title = null;

	public $id = 'woo-payment';

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		parent::__construct();

		$this->icon               = apply_filters( 'learn_press_woo_icon', '' );
		$this->method_title       = $this->title = __( 'WooCommerce Payment', 'learnpress-woo-payment' );
		$this->method_description = __( 'Make a payment with WooCommerce payment methods.', 'learnpress-woo-payment' );

		add_action( 'learn_press_section_payments_' . $this->id, array( $this, 'payment_settings' ) );
		add_filter( 'learn-press/payment-method/display', array( $this, 'disable_default_payment' ), 10, 2 );
		add_filter( 'learn-press/end-payment-methods', array( $this, 'payment_form' ) );
		add_filter( 'learn-press/payment-gateway/' . $this->id . '/available', array( $this, 'is_available' ), 10, 2 );
		add_action( 'learn_press_order_received', array( $this, 'instructions' ), 99 );
	}

	private function _get_payment_method() {
		$method             = ! empty( $_REQUEST['payment_method'] ) ? $_REQUEST['payment_method'] : '';
		$woocommerce_method = ! empty( $_REQUEST['woocommerce_chosen_method'] ) ? $_REQUEST['woocommerce_chosen_method'] : '';
		if ( ( $method != 'woocommerce' ) || ! $woocommerce_method ) {
			return false;
		}

		return $woocommerce_method;
	}

	/**
	 * Process the payment and return the result
	 *
	 * @param int $order_id
	 *
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$method = $this->_get_payment_method();
		if ( ! $method ) {
			return false;
		}

		$gateways = WC()->payment_gateways()->get_available_payment_gateways();

		if ( array_key_exists( $method, $gateways ) && $gateways[ $method ]->is_available() ) {
			WC()->session->set( 'chosen_payment_method', $method );
			if ( $woo_order_id = get_post_meta( $order_id, '_woo_order_id', true ) ) {
				$results = $gateways[ $method ]->process_payment( $woo_order_id );

				return $results;
			}
		}

		return false;
	}

	/**
	 * Output for the order received page.
	 */
	public function instructions( $order ) {
		if ( $order && ( $this->id == $order->payment_method ) && $this->instructions ) {
			echo stripcslashes( wpautop( wptexturize( $this->instructions ) ) );
		}
	}

	public function get_title() {
		return $this->method_title;
	}

	public function payment_settings() {
		$settings = new LP_Settings_Base();
		foreach ( $this->get_settings() as $field ) {
			$settings->output_field( $field );
		}
	}

	public function get_settings() {
		$available_payment_html = '';
		$payment_gateways       = WC()->payment_gateways()->payment_gateways();
		ob_start();
		?>
        <style type="text/css">
            .learn-press-woo-payments {
                margin: 0;
            }

            .learn-press-woo-payments .dashicons {
                font-size: 16px;
                vertical-align: middle;
                width: 16px;
                height: 16px;
                cursor: not-allowed;
            }

            .learn-press-woo-payments label > * {
                vertical-align: middle;
            }

            .learn-press-woo-payments .dashicons-dismiss {
                color: #CCC;
            }

            .learn-press-woo-payments .dashicons-yes {
                color: #0085ba;
            }
        </style>
		<?php
		if ( $payment_gateways ) {
			foreach ( $payment_gateways as $payment_gateway ) {
				$title = sprintf(
					__( 'This payment is %s, please click the link beside to enable/disable.',
						'learnpress-woo-payment' ),
					$payment_gateway->enabled == 'yes' ? 'enabled' : 'disabled'
				);
				?>
                <li>
                    <label>
                        <span title="<?php echo $title; ?>"
                              class="dashicons <?php echo $payment_gateway->enabled == 'yes' ? 'dashicons-yes' : 'dashicons-dismiss'; ?>"></span>
                        <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . $payment_gateway->id ); ?>"
                           target="_blank"> <?php echo( $payment_gateway->method_title ); ?> </a>
                    </label>
                </li>
				<?php
			}
		}
		$available_payment_html .= ob_get_clean();

		$desc_guest_checkout = sprintf(
			'<br><br><strong><i style="color: red">To enable Guest checkout, please go to, please go to <a href="%s">WooCommerce Setting</a> then enable 2 options: "Allow customers to place orders without an account" and "Allow customers to create an account during checkout"</i></strong>',
			home_url( 'wp-admin/admin.php?page=wc-settings&tab=account' ) );

		return
			array(
				array(
					'title'   => __( 'Enable', 'learnpress-woo-payment' ),
					'id'      => '[enable]',//'woo_payment_enabled',
					'std'     => 'no',
					'default' => 'no',
					'type'    => 'yes-no',
					'class'   => 'woo_payment_enabled',
					'desc'    => __( 'If <strong>WooCommerce Payment</strong> is enabled you can not use other payments provided by LearnPress',
						'learnpress-woo-payment' )
				),
				array(
					'title'   => __( 'Redirect to Woo checkout', 'learnpress-woo-payment' ),
					'id'      => 'redirect_to_checkout',
					'default' => 'no',
					'type'    => 'yes-no',
					'class'   => '',
					'desc'    => __( 'Enable to redirect to page Checkout when add to cart' . $desc_guest_checkout, 'learnpress-woo-payment' ),
				),
				/*array(
					'title'   => __( 'Purchase button', 'learnpress-woo-payment' ),
					'id'      => '[purchase_button]',//'woo_purchase_button',
					'std'     => 'single',
					'default' => 'single',
					'type'    => 'radio',
					'class'   => 'woo_purchase_button',
					'options' => array(
						'single' => __( 'Single purchase button', 'learnpress-woo-payment' ),
						'cart'   => __( 'Add to cart button', 'learnpress-woo-payment' ),
					)
				),*/
				array(
					'title'   => __( 'WooCommerce Payments', 'learnpress-woo-payment' ),
					'id'      => '[available_payments]',
					'std'     => '',
					'default' => '',
					'type'    => 'html',
					'desc'    => __( 'List of all available payment gateways installed and activated for WooCommerce. Click on a payment method to go to <strong>WooCommerce Payment</strong> settings.',
						'learnpress-woo-payment' ),
					'html'    => $available_payment_html ? sprintf( '<ul class="learn-press-woo-payments">%s</ul>',
						$available_payment_html ) : '',
				)
			);
	}

	/**
	 * Enable Woo Payment
	 */
	public function is_available( $available, $gateway ) {
		return LP_Addon_Woo_Payment::instance()->is_enabled() && LP_Addon_Woo_Payment::instance()->woo_payment_enabled() && sizeof( WC()->payment_gateways()->get_available_payment_gateways() );
	}

	public function disable_default_payment( $return, $id ) {
		return false;
	}

	public function payment_form() {
		echo $this->get_payment_form();
	}

	/**
	 * Payment Gateways Form with WooCommerce
	 */
	public function get_payment_form() {
		if ( ! $payment_gateways = WC()->payment_gateways()->get_available_payment_gateways() ) {
			return '';
		}

		$is_selected = false;
		$checked     = false;
		foreach ( $payment_gateways as $gateway ) {
			if ( $is_selected = ( WC()->session->get( 'chosen_payment_method' ) == $gateway->id ) ) {
				break;
			}
		}

		ob_start();
		foreach ( $payment_gateways as $gateway ) :
			if ( ! $is_selected ) {
				$is_selected = true;
			}

			if ( $is_selected && $checked === false ) {
				$checked = checked( true, true, false );
			} else {
				$checked = '';
			}
			?>

            <li class="lp-payment-method lp-payment-method-<?php echo $gateway->id; ?><?php echo $gateway->is_selected ? ' selected' : ''; ?>"
                id="learn-press-payment-method-<?php echo $gateway->id; ?>">
                <label for="payment_method_<?php echo $gateway->id; ?>">
                    <input type="radio" class="input-radio" name="payment_method"
                           id="payment_method_<?php echo $gateway->id; ?>"
                           value="woocommerce"
                           data-method="<?php echo esc_attr( $gateway->id ); ?>"
						<?php echo $checked; ?>
                           data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"/>
					<?php echo $gateway->get_title(); ?>
					<?php echo $gateway->get_icon(); ?>
                </label>

				<?php if ( $payment_form = $gateway->get_description() ) { ?>
                    <div class="payment-method-form payment_method_<?php echo $gateway->id; ?>">
						<?php echo $payment_form; ?>
                    </div>
				<?php } ?>
            </li>

			<?php continue; ?>

            <li class="learn_press_woo_payment_methods">
                <label>
                    <input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio"
                           name="payment_method" value="woocommerce"
                           data-method="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( LP()->session->get( 'chosen_payment_method' ) == $gateway->id,
						true ); ?>
                           data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"/>
					<?php echo( $gateway->get_title() ); ?>
                </label>
				<?php if ( $payment_form = $gateway->get_description() ) { ?>
                    <div class="payment-method-form payment_method_<?php echo $gateway->id; ?>"><?php echo $payment_form; ?></div>
				<?php } ?>
            </li>
		<?php endforeach; ?>
		<?php
		return ob_get_clean();
	}

}
