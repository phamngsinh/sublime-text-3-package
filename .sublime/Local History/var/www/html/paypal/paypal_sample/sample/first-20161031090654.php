<?php
ini_set('display_errors',1);
error_reporting(-1);
// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
// Used for composer based installation
require __DIR__  . '/vendor/autoload.php';
// Use below for direct download installation
// require __DIR__  . '/PayPal-PHP-SDK/autoload.php';
// After Step 1
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AbJJkGxwl8sRK2olmxPfEFzmLlAQG4tv4outZUlzmTz8vIpeRIVX6VVb3fSuYYhtHYvKBlAVCvpwjtdk',
        'EJaKgeTkh0y9A8n7DZWoaNoZQtNIu1uBaOms-dyxRRJuUqzuaFoNK8S2j7mOdBvaAIFceWNlqruiDEGI'      // ClientSecret
    )
);

// After Step 2
$creditCard = new \PayPal\Api\CreditCard();
$creditCard->setType("visa")
    ->setNumber("4417119669820331")
    ->setExpireMonth("11")
    ->setExpireYear("2019")
    ->setCvv2("012")
    ->setFirstName("Joe")
    ->setLastName("Shopper");


    // After Step 3
try {
    $creditCard->create($apiContext);
    echo $creditCard;
}
catch (\PayPal\Exception\PayPalConnectionException $ex) {
    // This will print the detailed information on the exception. 
    //REALLY HELPFUL FOR DEBUGGING
    echo $ex->getData();
}
