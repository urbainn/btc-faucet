<?php

// Replace the API key with your own, you need to create a faucet in the "faucet owner dashboard" on faucetpay.io

function getBalance($currency){
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"https://faucetpay.io/api/v1/balance");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=YOUR_API_KEY&currency=$currency");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    return json_decode(curl_exec($ch));

    curl_close ($ch);
    

}

function checkAddress($currency, $address){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"https://faucetpay.io/api/v1/checkaddress");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=YOUR_API_KEY&address=$address&currency=$currency");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    return json_decode(curl_exec($ch));

    curl_close ($ch);
    

}

function sendCoins($address, $amount, $currency){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"https://faucetpay.io/api/v1/send");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=YOUR_API_KEY&to=$address&amount=$amount&currency=$currency");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    return json_decode(curl_exec($ch));

    curl_close ($ch);
    
}

?>