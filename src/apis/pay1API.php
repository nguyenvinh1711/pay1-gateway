<?php

/**
 * 
 * 
 * @author vinhsmile
 * @since  1.0.0
 */

namespace pay1\API;

// use function PHPSTORM_META\type;

// class pay1Gateway extends \WC_Payment_Gateway
// {

// private key for Production
$prikey_content = '-----BEGIN RSA PRIVATE KEY-----
MIIBVAIBADANBgkqhkiG9w0BAQEFAASCAT4wggE6AgEAAkEAlcVP89QrOk1RdDUp
ufGJSORAbh2Q8EkN4hTbDZW5qPR5X0bZNIX7WrO2x5HvLrOGzIgPtrBl/4xXT0fb
3KMshwIDAQABAkBFk1TyE5VFT1fQoUXSEKfTCd34iZmkVnuxlDiy1eRa3QlZ7lha
nQczHNGpPJYcYmlHYHQ/ZbMoKbATH0WQUkL5AiEAy2gdmGN13vgJtRgcqlICpPKZ
U4w4TAqQK0uIMq9jQm0CIQC8fu3V6PT8fYmvkQRQMnZ2SkpkWQC4Qi+OD4JoI72y
QwIgF+xa7r1j86GAosf/NxkGLphJ6EWpqWfkpPLxReTS69ECIHGUgjd9EfH6qzZx
SYoRfjQ0+7KPttFQgFs3mTqrHqkFAiEAjlEE7sVSn7JhLnrLtc9L316Hlbbwdk1N
CefkON938Ys=
-----END RSA PRIVATE KEY-----
';
$private_key = <<<EOD
$prikey_content
EOD;
// echo $private_key;


// publickey for Production
$pubkey_content = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoL4tR0JZKhLwTx6BZxd96jMnDroDD2BRBiiYvIOkydcYTHTJkYQo6QicryaCvWASS4p9Kh1zVq1B1//cc6LmeJe65TVO178V7xpgdwtk8O4SSx/TWMmyXhdHTL1dGYjjcxooCR+0eLULKVPheIjJBf4fEKpN7YLgSgYf+sDYBgL+c5rV+v3RmpLyMe5rkwoMbOkQhW7YJAYD48IB9ITveJ3sTIp/St3tijBFlwoGrZ4HZY4O1vKg3oVTXbos+IiRIlprzL48Epw/LaOl/4sO9ZGXvMxJEDJCL8HC6foD3yI7o4G4AUtwLOcsrkCOdugAcyxaRIbilIubJCQ3omvY7wIDAQAB
-----END PUBLIC KEY-----
';
// pay1 publickey
$pubkey_content = '-----BEGIN PUBLIC KEY-----
QkuBbIt3kaAcgcwffPXY4fu7acTNJUiW
-----END PUBLIC KEY-----
';
// merchant publickey
// $pub_key = '-----BEGIN PUBLIC KEY-----
// MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAJXFT/PUKzpNUXQ1KbnxiUjkQG4dkPBJDeIU2w2Vuaj0eV9G2TSF+1qztseR7y6zhsyID7awZf+MV09H29yjLIcCAwEAAQ==
// -----END PUBLIC KEY-----
// ';
// $pub_key = <<<EOD
// $pubkey_content
// EOD;
$pub_key = $pubkey_content;
// echo "public key: " . $pub_key . "\n";

// create crypttext after this function
// openssl_public_encrypt($pay1_result_return_str, $crypttext, $pub_key);
// $crypttext = "YkEU+zlOQoEpYYJ5TJSOkTpuTFTiA8FHDQJCxCr0X15liWc27GoJ8FE5vha5oUBXp0VdHO8GrQRHS06YBMLP3MiWZKbbzZOQCvO2QX7du0/7qaHza8BWTm9Fb71aOK52FWve+DmS41DBO1pbXotH2s=";

// $pay1_result_return_str = json_encode($pay1_result_return_arr);
// echo "pay1_result_return_str: " . $pay1_result_return_str . "\n";
// echo base64_encode($pay1_result_return_str);

// die;
// openssl_private_decrypt($crypttext, $newsource, $pub_key);

// $fp = fopen("/Users/mac/pkcs8.key", "r");
// $priv_key = fread($fp, 8192);
// fclose($fp);
// $passphrase = "";
// $res = openssl_get_privatekey($priv_key, $passphrase); // $passphrase is required if your key is encoded (suggested)
// var_dump($res) . "\n";

// merchant's private key
$private_key = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIIBUwIBADANBgkqhkiG9w0BAQEFAASCAT0wggE5AgEAAkEApOcLzDdhN/UlIkLn
mOLeYki0U3kaUUOn8h8EHxoRxE5yHNYt3QBjYHLSE69K1DfGqya731c3kVeJrxIK
pUBLpQIDAQABAkBrGTPsWjtl4G1RpypLfWz5YEbdv9V72fkFo2dF2+SWHWGoS+kk
g+tilv7zkPXB6zXndBEhBb4Jdv6vPM11QV6xAiEA1QKnX+BOh0mroDBnRcrIQHom
e/8aX6hHHU+nFoMexeMCIQDGLtz5oCM+wmSMWqg4HlYMRij53GUrilePxcNcuYKe
1wIgGDvVDvNV+85F2FOpbCdF4RAEkNbVCUz7yjnlqIoCVkECIAHKSpS2rZrThrT9
3KclHwHdYGFhbEiIZ2IVFz6vLk7hAiAL35yStPiLEG8PccbUitYbLNF/QCfp0Plr
UG4S7A4bpA==
-----END RSA PRIVATE KEY-----
EOD;

define('PAY1_SERCRET_KEY', $private_key);

function CreateSignature(array $data, string $privateKey = PAY1_SERCRET_KEY): string
{
   echo $privateKey;

   // use below or use time() + 24*60*60
   $plusADayExp = strtotime('+1 day') * 1000; //convert to miliseconds
   echo "Exp time : " . $plusADayExp . "\n";
   $amount = number_format((float)$data['totalAmount'], 2, '.', '');
   echo "Amount formatted: " . $amount . "\n";

   // order must be unchanged
   $pay1CreatSignatureData =
      sprintf("%s%s%s%s%s%s", $data['merchantCode'], $data['terminalId'], $data['merchantOrderId'], $data['totalAmount'], $data['currency'], $data['exp']);

   echo "pay1CreatSignatureData: " . $pay1CreatSignatureData . "\n";

   // create signature rsa-sha256
   openssl_sign($pay1CreatSignatureData, $signature, $privateKey, OPENSSL_ALGO_SHA256);
   echo "signature: " . $signature . "\n";

   $encodedSig = base64_encode($signature);
   echo "After encode base64 : " . $encodedSig . "\n";

   return $encodedSig;
}

// encrypt with priv and decrypt with pub keys
// openssl_private_encrypt($pay1CreatSignatureData, $crypttext, $private_key);
// echo "String encrypted : " . $crypttext . "\n";
// encode $crypttext with base64

// $encodeStr = urlencode($encodeStr);
// echo "After url encode : " . $encodeStr . "\n";
// echo "Source : " . $source == null . "\n";

// echo "dataCreate json: " . json_encode($dataCreate) . "\n";
// $dataCreate = json_encode($dataCreate);


// sample data
$plusADayExp = strtotime('+1 day') * 1000; //convert to miliseconds
echo "Exp time : " . $plusADayExp . "\n";
$amount = number_format((float)20000, 2, '.', '');
echo "Amount formatted: " . $amount . "\n";
$merchantOrderId = uniqid();
echo "merchantOrderId: " . $merchantOrderId . "\n";

$data  = [
   "merchantCode" => "YMALL1212", // fixed value from Pay1
   "terminalId" => "P13J15U82TJNOJAM4PF95JZOYK0RVFR8", // fixed value from Pay1
   "merchantOrderId" => $merchantOrderId, // 13 chars random, id for every order
   "totalAmount" => $amount,
   "currency" => "VND",
   "exp" => $plusADayExp,
];
// echo "data: " . var_dump($data) . "\n";
// echo "dataCreate json: " . json_encode($dataCreate) . "\n";
// echo "dataCreate json: " . gettype(json_encode($dataCreate)) . "\n";

// $arr1 = ["ipnUrl" => "https://ymall.vn/wp-json/wp/v2/pay1/payment-success"];
// $arr2 = ["ipnUrl" => "https://ymall.vn/vi/pay1-payment-result/"] + $arr1;
// echo "arr1: " . json_encode($arr1) . "\n";
// echo "arr2: " . json_encode($arr2) . "\n";

CreatePaymentURL($data);

function CreatePaymentURL(array $data = null): string
{
   $url = "https://gate-api.stg.pay1.vn/external/create-order";
   $method = "POST";
   
   // $mTransactionID = substr(str_shuffle("0123456789"), 0, 13),
   // $mTransactionID = uniqid();
   // echo "Transaction Id: " . $mTransactionID . "\n";

   $signature = CreateSignature($data);
   echo "signature: " . $signature . "\n";
   
   $data = $data +
      [
         "paymentMethods" => "DC",
         "description" => "Thanh toan don hang HD001",
         "ipnUrl" => "https://ymall.vn/wp-json/wp/v2/pay1/payment-success",
         "resultUrl" => "https://ymall.vn/vi/pay1-payment-result/",
         "cancelUrl" => "https://ymall.vn/vi/pay1-payment-cancelled",
         "errorUrl" => "https://ymall.vn/vi/pay1-payment-error",
        
      ];
   echo "dataCreate: " . json_encode($data) . "\n";

   $data = json_encode($data);

   $curl = curl_init();
   curl_setopt(
      $curl,
      CURLOPT_HTTPHEADER,
      [
         "Signature: $signature",
         "User-Agent: NoUserAgent_Header",
         "Content-Type: application/json",
         'Terminalid: P13J15U82TJNOJAM4PF95JZOYK0RVFR8',
         'merchantCode: YMALL1212',
      ]
   );

   switch ($method) {
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);

         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_PUT, 1);
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }

   // Optional Authentication:
   // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   //  echo "curl info: " . $curl . "\n";
   // $result = curl_exec($curl);

   $info = curl_getinfo($curl);
   echo "curl info: " . gettype($info) . "\n";
   // print_r($info) ;

   logRequest($info);
   // var_dump(curl_getinfo($curl));
   

   curl_close($curl);


   // echo "result: " . $result . "\n";
   // $rs = json_decode($result ,true); // convert json to array
   // echo "result json: " . strcmp($rs['resultCode'],"SUCCESS") . "\n";
   // return $result;
   return "";
}


// decode $crypttext with base64
// echo "String : " . $crypttext . "\n";
// $crypttext = base64_decode($crypttext);
// echo "String decoded : " . $crypttext . "\n";

// openssl_public_decrypt($crypttext, $newsource, $pub_key);

// echo "String decrypted : " . $newsource . "\n";
// echo "Json decrypted : " . json_decode($newsource);
// $newJson = json_encode($newsource);
// $test = json_decode($newJson, true);
// echo "decode json : " . $newsource['orderId'] . "\n";

// convert $newsource to array
// print_r (json_decode($newsource, true));
// print_r (json_decode($newsource, true)["result"]["message"]);
// convert $newsource to object
// print_r (json_decode($newsource)->orderId);


// encrypt with pub and decrypt with private keys
// $source = "test Vinh";
// openssl_public_encrypt($source,$crypttext,$pub_key);
// echo "String encrypted : " . $crypttext . "\n";

// openssl_private_decrypt($crypttext, $newsource, $priv_key);
// echo "String decrypted : " . $newsource . "\n";

// $encoded_url = "http://test.merchant.com/result?data=YkEU%2BzlOQoEpYYJ5TJSOkTpuTFTiA8FHDQJCxCr0X15liWc27GoJ8FE5vha5oUBXp0VdHO8GrQRHS06YBMLP3MiWZKbbzZOQCvO2QX7du0%2F7qaHza8BWTm9Fb71aOK52FWve%2BDmS41DBO1pbXotH2s%3D";
// echo urldecode($encoded_url);


// define( 'PAY1_SERCRET_KEY', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAq6SmtAPsljWD8m++p+cDXGzRKJ4V78h8CtjMoZ7i86mie+F1oemEiKjrgQGnTeSH857CszuY4vLO4CGo1RU7C8mke7RvmmZPSpjnbsYRL2enXdoVHcwV2Ey75FdTjYZr9NoCIRjU7Gq1NexjOiZjjYoWxOhG63reN8T8bVZdRRjt0e6bUVNjHC4jdgk0e48h8J1qe/DxstBsPTRvMlN9HloAhcYXhKFZU44UJXtsHg4JePq5vdghO7YHPSwj8VbL8KnX6EmerWMq2bNZ0+NBvQ/MRQsQWW/wW9GKG/g/MWBbmCX1IMM8NrNgpKj3kDvpsHp9bb08M1RKYqli1TkYVQIDAQAB' );


// $res = '-----BEGIN PRIVATE KEY-----
// MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCgvi1HQlkqEvBP
// HoFnF33qMycOugMPYFEGKJi8g6TJ1xhMdMmRhCjpCJyvJoK9YBJLin0qHXNWrUHX
// /9xzouZ4l7rlNU7XvxXvGmB3C2Tw7hJLH9NYybJeF0dMvV0ZiONzGigJH7R4tQsp
// U+F4iMkF/h8Qqk3tguBKBh/6wNgGAv5zmtX6/dGakvIx7muTCgxs6RCFbtgkBgPj
// wgH0hO94nexMin9K3e2KMEWXCgatngdljg7W8qDehVNduiz4iJEiWmvMvjwSnD8t
// o6X/iw71kZe8zEkQMkIvwcLp+gPfIjujgbgBS3As5yyuQI526ABzLFpEhuKUi5sk
// JDeia9jvAgMBAAECggEAD7jKIPAiXHUhv/tR2M15aVC7fzs+VWCsTBfoaHfPxFYf
// cErz9CGjBrfq606u9urQn0baWyYqQ84KZKAH5d+G/0CH7Sc7oOp1sL85tCsPImmG
// rIZeW47OvVk1hhH3aynyZS2nwMyzECNVBiPIJ8vpLYbfbvfZS2PDoDndIt99wYid
// v9M5anVUb5zBTq7WDVDMDjuutplFkPlwLA9jsX3WCsYER9KQ8YqJcJ/IuU4wrhem
// Yf0WZreL5yln+wnY38+vg4TKv36UVPR4aS1UOC5FxYQsr8fFR/44jQeowfgCk3bT
// MoYvslwv9fNdpo1Ziw0h7eEFE1lsEebcWkJZbeONcQKBgQDMFojRGWrwsYbOOfdF
// GKe//hbtuFe57KJcmJKAgG8qpxojEHQBssHjSwZJwOYusRcYPZ+CI4zjGpu1JTsR
// SM6Af5PhGm/XEF2jkEa3t7trtagw/UFM8cs345JLgrbiAao4T+NZeQ4kZNhP/Brw
// 8l0S9QCdP34vSN4yVY9/wHX+HQKBgQDJoSpAZp20s6SqV1SnoVREAT4W43S5pMc2
// SlMPW0M8hui0ywfXxNxCrBCcBcn8FCgu6SvMXn3/olANY6LQ22Vsmp23BAugNwKR
// KkXzaX+vPUcqrxIC6ucjaIhgQSGQHSc7lbM1NwLQh05g+PTOyOOMzbBLBeSKREqs
// aqEIEV31ewKBgD/+mOwoMjZDFGg6GOUbtA4p2Hq2IRv1rXung9izbShMuY9hK18U
// 9GMXNWOPDx54/Srmt8uQ5i6YO1bz06+5yrbUSkR9i7A+O358URBulmBR1+l0oDNG
// ZliiDU+ML1hy+TMrKbj2ko6q6IE8Jh6DgNfe51BnhmPBT5ss5RBEAzupAoGAD/32
// afE5pPFyqAgRMNJ86n/mus4Dng3Rtx6iA9/LL2vL3TsVPinhBnC7SIS1BodawI3B
// 6Y29FJ99FfLHISWln72LsM5jVavHSlg+ABZoCDAmCt6j4RNcjdRmrKu9y75kNDel
// ZR0d3gg9UTetwxDv62UNDIZFICpWeeJjL06hUy0CgYBon4d5eOXvDowxGA76uxO6
// RTTJjY7YNGO1Q1NMTzGd08MKzxn2xVrfHTU0l6xVBuZc1eICPqvKu3sK6y/OOCgk
// Td3vWnbZvNXnUb8d0iED/fGMCCDe5uDsaFmqIhK9zSYsfaR6sW3GpleQwWuKPrWj
// wVKClArA1UthTAz+c969lQ==
// -----END PRIVATE KEY-----';


function logRequest($request)
{
   if (!file_exists('log.txt')) {
      file_put_contents('log.txt', '');
   }
   // print_r($_SERVER);
   // print_r(array_key_exists('REMOTE_ADDR', $_SERVER));

   $ip = array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR'];

   $time = date('Y-m-d H:i:s');

   $request['ip'] = $ip;
   $request['time'] = $time;

   // $log = "\n" . $ip . " - " . $time . "\t" . $message;
   // echo $log;
   file_put_contents('log.txt', var_dump($request), FILE_APPEND);
}