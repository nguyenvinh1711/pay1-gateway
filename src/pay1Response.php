<?php

/**
 * 
 * 
 * @author vinhsmile
 * @since  1.0.2
 */

namespace pay1\Responses;

use pay1\Gateways\pay1Gateway;
use pay1\Facades\FacadeResponse;

abstract class pay1Response implements FacadeResponse {
    protected $hashCode;
    public function __construct() {
        $this->action();
    }

    public function action() {
        add_action('wp_ajax_payment_response', array($this, 'checkResponse'));
        add_action('wp_ajax_nopriv_payment_response', array($this, 'checkResponse'));
        add_action('wp_ajax_payment_response_pay1', array($this, 'ipn_url_pay1'));
        add_action('wp_ajax_nopriv_payment_response_pay1', array($this, 'ipn_url_pay1'));
    }

    public function checkResponse($txnResponseCode) {
      //   global $woocommerce;
        $woocommerce->cart->get_checkout_url();
        $order = $this->getOrder($_GET["pay1_TxnRef"]);
            $url = wc_get_checkout_url() . 'order-received/' . $order->id . '/?key=' . $order->order_key;
            wp_redirect($url);
        WC()->cart->empty_cart();
        exit();
    }

    public function ipn_url_pay1($txnResponseCode) {
      //   global $woocommerce;
      //   $checkouturl = $woocommerce->cart->get_checkout_url();
        $transStatus = '';

        $order = $this->getOrder($_GET["pay1_TxnRef"]);
        $gateway = new pay1Gateway;
        $hashSecret = $gateway->get_option('privateKey');

        //  ($hashSecret);
        $params = array();
        $returnData = array();
        $data = $_GET;

        foreach ($data as $key => $value) {
            if (substr($key, 0, 4) == "pay1_") {
                $params[$key] = $value;
            }
        }
        $pay1_SecureHash = $params['pay1_SecureHash'];
        unset($params['pay1_SecureHashType']);
        unset($params['action']);
        unset($params['type']);
        unset($params['pay1_SecureHash']);
        ksort($params);
        $i = 0;
        $hashData = "";
        foreach ($params as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key). "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $hashSecret);
//Check Orderid 
        if ($order->post_status != \NULL && $order->post_status != '') {
            //Check chữ ký
            if ($secureHash == $pay1_SecureHash) {
                //Check Status của đơn hàng
                if ($order->post_status != \NULL && $order->post_status == 'wc-on-hold'){
                    if ($params['pay1_ResponseCode'] == '00') {
                        $returnData['RspCode'] = '00';
                        $returnData['Message'] = 'Confirm Success';
                        $returnData['Signature'] = $secureHash;
                        $transStatus = $this->getResponseDescription($txnResponseCode);
                        $order->update_status('processing');
                        $order->add_order_note(__($transStatus, 'woocommerce'));
                        WC()->cart->empty_cart();
                    }
                    elseif ($params['pay1_ResponseCode'] == '24') {
                     $returnData['RspCode'] = '00';
                     $returnData['Message'] = 'Confirm Success';
                     $returnData['Signature'] = $secureHash;
                     $order->update_status('cancelled');
                     $order->add_order_note(__('Khách hàng hủy giao dịch', 'woocommerce'));
                     WC()->cart->empty_cart();
                    }
                    else
                    {
                    $returnData['RspCode'] = '00';
                    $returnData['Message'] = 'Confirm Success';
                    $returnData['Signature'] = $secureHash;
                    $transStatus = $this->getResponseDescription($txnResponseCode);
                    $order->add_order_note(__($transStatus, 'woocommerce'));
                    $order->update_status('failed');
                    WC()->cart->empty_cart();
                    }
                } else {
                    $returnData['RspCode'] = '02';
                    $returnData['Message'] = 'Order already confirmed';
                   WC()->cart->empty_cart();
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Chu ky khong hop le';
                $returnData['Signature'] = $secureHash;
                WC()->cart->empty_cart();
            }
        } else {
            $returnData['RspCode'] = '01';
            $returnData['Message'] = 'Order not found';
            WC()->cart->empty_cart();
        }
        echo json_encode($returnData);
        exit();
    }

    abstract public function thankyou();

    abstract public function getResponseDescription($responseCode);

    public function getOrder($orderId) {
        preg_match_all('!\d+!', $orderId, $matches);
        $order = new \WC_Order($matches[0][0]);
        return $order;
    }

}
