<?php

/**
 * 
 * 
 * @author vinhsmile
 * @since  1.0.0
 */

namespace pay1\Gateways;

use pay1;
use pay1\API as pay1API;

// global constants
define("WOO_DOMAIN", "woocommerce");

class pay1Gateway extends \WC_Payment_Gateway
{

   public function __construct()
   {
      $this->id = 'pay1';
      $this->icon = $this->get_option('logo'); // show an image next to the gateway’s name on the frontend, enter a URL to an image.
      $this->has_fields = false; // set true to show on the checkout (if doing a direct integration).
      $this->method_title = __('Pay1', WOO_DOMAIN); // title of the payment method for the admin page.
      $this->method_description = 'Description of Pay1 payment gateway'; // will be displayed on the options page

      $this->supports = array(
         'products',
         'refunds'
      );
      
      // Method with all the options fields
      $this->init_form_fields();
      // Load the settings.
      $this->init_settings();

      // Define user set variables
      $this->title = $this->get_option('title');
      $this->description = $this->get_option('description');
      $this->instruction = $this->get_option('instruction');
      // $this->url = $this->get_option('url');

      $this->privateKey = $this->get_option('privateKey');
      $this->merchantCode = $this->get_option('merchantCode');
      $this->terminalId = $this->get_option('terminalId');
      $this->locale = $this->get_option('locale');

      if (!$this->isValidCurrency()) {
         $this->enabled = 'no';
      }

      add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
   }

   public function init_form_fields()
   {
      $this->form_fields = array(
         'enabled' => array(
            'title' => __('Enable/Disable', WOO_DOMAIN),
            'type' => 'checkbox',
            'label' => __('Enable pay1 Paygate', WOO_DOMAIN),
            'default' => 'yes',
         ),
         'title' => array(
            'title' => __('Title', WOO_DOMAIN),
            'type' => 'text',
            'description' => 'Payment title',
            'default' => 'PAY1 gateway payment',
            'desc_tip' => true
         ),
         'description' => array(
            'title' => __('Description', WOO_DOMAIN),
            'type' => 'textarea',
            'description' => __('Mô tả phương thức thanh toán', WOO_DOMAIN),
            'default' => __('Thanh toán trực tuyến qua PAY1', WOO_DOMAIN),
            'desc_tip' => true
         ),
         'url' => array(
            'title' => __('PAY1 URL', WOO_DOMAIN),
            'type' => 'text',
            'description' => 'url khởi tạo giao dịch sang PAY1(PAY1 Cung cấp)',
            'default' => '',
            'desc_tip' => true
         ),
         'merchantCode' => array(
            'title' => __('Mã nhà bán', WOO_DOMAIN),
            'type' => 'text',
            'description' => 'Mã nhà bán PAY1 cung cấp',
            'default' => '',
            'desc_tip' => true
         ),
         'terminal' => array(
            'title' => __('Terminal ID', WOO_DOMAIN),
            'type' => 'text',
            'description' => 'Mã terminal PAY1 cung cấp',
            'default' => '',
            'desc_tip' => true
         ),
         'privateKey' => array(
            'title' => __('Private key', WOO_DOMAIN),
            'type' => 'password',
            'description' => _e('Private key of merchant', WOO_DOMAIN),
            'default' => '',
            'desc_tip' => true
         ),
         'publicKey' => array(
            'title' => __('Public key', WOO_DOMAIN),
            'type' => 'text',
            'description' => _e('Public key of merchant', WOO_DOMAIN),
            'default' => '',
            'desc_tip' => true
         ),
         'locale' => array(
            'title' => __('Locale', WOO_DOMAIN),
            'type' => 'select',
            'class' => 'wc-enhanced-select',
            'description' => __('Choose your locale', WOO_DOMAIN),
            'desc_tip' => true,
            'default' => 'vn',
            'options' => array(
               'vn' => 'vn',
               'en' => 'en'
            )
         ),
      );
   }

   // get called after Place Order button is clicked
   public function process_payment($order_id)
   {
      // $order = new \WC_Order($order_id);

      return array(
         'result' => 'success',
         'redirect' => $this->redirect($order_id)
      );
   }

   public function redirect($order_id)
   {
      date_default_timezone_set('Asia/Ho_Chi_Minh');
      $order = new \WC_Order($order_id, __( 'Awaiting payment', WOO_DOMAIN ));
      
      // Mark as on-hold (we're awaiting the cheque)
      $order->update_status('on-hold');
      $order->add_order_note(__('Giao dịch chờ thanh toán hoặc chưa hoàn tất', WOO_DOMAIN));

      //
      $forenamefw = $order->get_billing_first_name();
      $forename = convert_vi_to_en($forenamefw);
      $surnamefw = $order->get_billing_last_name();
      $surname = convert_vi_to_en($surnamefw);
      $mobile = $order->get_billing_phone();
      $emailfw = $order->get_billing_email();
      $email = convert_vi_to_en($emailfw);

      global $WOOCS;
      $currency = $WOOCS->current_currency;
      $merchantOrderId = uniqid();
      
      // $amount = number_format($order->order_total, 2, '.', '') * 100;
      $amount = number_format((float)$order->order_total, 2, '.', '');
      $pay1_TxnRef = $order_id;
      $date = date('Y-m-d H:i:s');
      // $pay1_url = $this->url;
      // $pay1_Returnurl = admin_url('admin-ajax.php') . '?action=payment_response&type=international';
      $merchantCode = $this->merchantCode;
      $terminalId = $this->terminalId;
      $privateKey = $this->privateKey;
      // $pay1_OrderInfo = 'Ma giao dich thanh toan:' . $order_id . '-' . 'Ho va ten KH:' . $surname . ' ' . $forename . '-' . 'SDT:' . $mobile . '-' . 'Email:' . $email;
      // $pay1_OrderType = 'orther';
      $pay1_Locale = $this->locale;

      $userIp = array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR'];

      $data = array(
         "merchantCode" => $merchantCode,
         "terminalId" => $terminalId,
         "merchantOrderId" => $merchantOrderId,
         "totalAmount" => $amount,
         "currency" => $currency || "VND",
         "exp" => 1703236837,
         "terminalId" => $terminalId,
         // "createdDate" => date("Y-m-d"),
         "pay1_IpAddr" => $userIp,
         "pay1_Locale" => $pay1_Locale,
         // "pay1_OrderInfo" => $pay1_OrderInfo,
         // "pay1_OrderType" => $pay1_OrderType,
         "pay1_Returnurl" => $pay1_Returnurl,
      );
      // ksort($data);
      // $query = "";
      // $i = 0;
      // $hashdata = "";
      // foreach ($data as $key => $value) {
      //    if ($i == 1) {
      //       $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
      //    } else {
      //       $hashdata .= urlencode($key) . "=" . urlencode($value);
      //       $i = 1;
      //    }
      //    $query .= urlencode($key) . "=" . urlencode($value) . '&';
      // }
      // $pay1_url = $pay1_url . "?" . $query;
      // if (isset($hashSecret)) {
      //    $pay1SecureHash = hash_hmac('sha512', $hashdata, $hashSecret);
      //    $pay1_url .= 'pay1_SecureHash=' . $pay1SecureHash;
      // }

      // call api create Pay1 signature

      $result = pay1API::CreatePaymentURL($data);

      echo "result: " . $result . "\n";
      $rs = (array)json_decode($result);
      echo "result json: " . strcmp($rs['resultCode'],"SUCCESS") . "\n";

      $pay1_url = "";
      // if result is failed
      if (strcmp($rs['resultCode'],"SUCCESS") != 0) {
         wc_add_notice( __('Payment error:', WOO_DOMAIN) . "Thanh toán Pay1 thất bại", 'error' );
         return;
      }
      
      return $rs['redirectUrl'];
   }

   public function isValidCurrency()
   {
      return in_array(get_woocommerce_currency(), array('VND'));
   }

   public function admin_options()
   {
      if ($this->isValidCurrency()) {
         parent::admin_options();
      } else {
?>
         <div class="inline error">
            <p>
               <strong>
                  <?php _e('Gateway Disabled', WOO_DOMAIN); ?>
               </strong> :
               <?php
               _e('pay1 does not support your store currency. Currently, pay1 only supports VND currency.', WOO_DOMAIN);
               ?>
            </p>
         </div>
<?php
      }
   }


   // deprecated
   public function getPagesList()
   {
      $pagesList = array();
      $pages = get_pages();
      if (!empty($pages)) {
         foreach ($pages as $page) {
            $pagesList[$page->ID] = $page->post_title;
         }
      }
      return $pagesList;
   }

}


// helper functions
function convert_vi_to_en($str)
{
   $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
   $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
   $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
   $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
   $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
   $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
   $str = preg_replace("/(đ)/", 'd', $str);
   $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
   $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
   $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
   $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
   $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
   $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
   $str = preg_replace("/(Đ)/", 'D', $str);
   return $str;
}
