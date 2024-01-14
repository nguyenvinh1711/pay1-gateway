<?php
/*
 * Plugin Name: PAY1
 * Description: Integrate PAY1 paygate into Woocommerce
 * Version: 1.0.3
 * Author: PAY1
 * Author URI: http://pay1.vn/
 * License: NHT
 */

use pay1\Gateways\{pay1Gateway, pay1InternationalResponse};
use pay1\Traits\Pages;
// require "pay1Signature.php";

require 'vendor/autoload.php';
//require 'src/return.php';


/**
 */
class pay1
{
	use pay1\Traits\Pages;

	
	protected $shortcodes = array();

	
	protected $responses;

	public function __construct()
	{
		$this->constants();
		add_action('init', array($this, 'renderPages'));
		add_action('plugins_loaded', array($this, 'pay1GatewayInit'));
		add_filter('woocommerce_locate_template', array($this, 'pay1WoocommerceTemplates'), 10, 3);
		$this->loadModule();
		$this->responseListener();
	}

	
	public function constants()
	{
		$consts = array(
			'URL' => plugins_url('', __FILE__),
			'IMAGE' => plugins_url('/assets/images', __FILE__)
		);

		foreach ($consts as $key => $value) {
			define($key, $value);
		}
	}

	
	public function pay1GatewayInit()
	{
      // if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

		add_filter('woocommerce_payment_gateways', array($this, 'addPay1Gateway'));
	}

	
	public function addPay1Gateway($methods)
	{
		$methods[] = 'pay1\Gateways\pay1Gateway';
		return $methods;
	}


	public function loadModule()
	{
		$this->shortcodes[] = new pay1\Shortcodes\Thankyou;
	}

	public function responseListener()
	{
		if (isset($_GET['type'])) {
			switch ($_GET['type']) {
				case 'international':
					$this->responses[] = new pay1InternationalResponse;
					break;
				
			}
		}
	}

	
	public function renderPages()
	{
		$checkRenderPage = (!get_option('pay1_settings')) ? false : get_option('pay1_settings');
		if ($checkRenderPage != false) return;
		if (!empty($this->pages)) {
			foreach ($this->pages as $slug => $args) {
				$page = new pay1\Page($args);
			}
			update_option('pay1_settings', true);
		}
	}

	public function pay1WoocommerceTemplates($template, $template_name, $template_path)
	{
		global $woocommerce;

		$_template = $template;

		if (!$template_path) $template_path = $woocommerce->template_url;

		$plugin_path  = __DIR__ . '/woocommerce/';

		$template = locate_template(
			array(
			  $template_path . $template_name,
			  $template_name
			)
		);

		if (!$template && file_exists( $plugin_path . $template_name))

		$template = $plugin_path . $template_name;

		if (!$template) $template = $_template;

		return $template;
	}
}

new pay1;