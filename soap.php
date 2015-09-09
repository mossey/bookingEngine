
<?php
   

try{
      $time = time();
      $password = "848087c7a7ab842715acf1f42a5c82fce3335310dfb7506bb9cfe99f31bdb494";
      $paybill = "972988";
      $string = $paybill . ',' . $password . "," . $time;
      $hashed = base64_decode(hash('sha256', $string));
      $location = "https://safaricom.co.ke/mpesa_online/lnmo_checkout_server.php?wsdl";
      $client = new SoapClient($location);
      $client->__setLocation('https://safaricom.co.ke/mpesa_online/lnmo_checkout_server.php');
      $response = $client->confirmTransaction(array('MERCHANT_TRANSACTION_ID' => '972988',
          ' PASSWORD' => $hashed,
          'TIMESTAMP' => $time
      ));
      $action = "https://safaricom.co.ke/mpesa_online/lnmo_checkout_server.php";
      $result = $client->__doRequest($request, $location, $action, 1);
  }
  catch (Exception $e){
      echo ($e->getMessage());
    }