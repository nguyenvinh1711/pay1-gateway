<?php

/**
 * 
 * @author vinhsmile
 * @since  1.0.2
 */

namespace pay1\Facades;

interface FacadeResponse {

    public function getResponseDescription($responseCode);

    public function checkResponse($txnResponseCode);

    public function ipn_url_pay1($txnResponfseCode);

    public function getOrder($orderId);
}
