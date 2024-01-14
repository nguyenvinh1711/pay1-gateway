<?php

/**
 * 
 * 
 * @author vinhsmile
 * @since  1.0.2
 */

namespace pay1\Gateways;

use pay1\Responses\pay1Response;
use pay1\Gateways\pay1Gateway;

class pay1InternationalResponse extends pay1Response {

    public function __construct() {
        parent::__construct();
    }

    public function getResponseDescription($responseCode) {
        if ($_GET['pay1_ResponseCode'] == '00') {
            $result= "Giao dịch thanh toán thành công qua PAY1";
        } else {
            $result= "Giao dịch không thành công";
        }
        
        return $result;
    }

  
    public function thankyou() {
        $gateway = new pay1Gateway;
        return $gateway->get_option('receipt_return_url');
    }

   

}
