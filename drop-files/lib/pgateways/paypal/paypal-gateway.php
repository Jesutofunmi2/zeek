<?php

// Retrieve the request's body
$body = @file_get_contents("php://input");

//$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];



// parse event (which is json string) as object
// Give value to your customer but don't give any output

$gateway_data = json_decode($body,true);


/* file_put_contents('midtrans_dump',print_r($body, true));

exit; */


if(!(isset($gateway_data['event_type']) && $gateway_data['event_type'] == 'PAYMENTS.PAYMENT.CREATED')){
    exit;
}

$pay_id = $gateway_data['resource']['id'];
$payer_id = ["payer_id" => $gateway_data['resource']['payer']['payer_info']['payer_id']];


$gateway_transaction_id = $pay_id;

$transaction_ref = $gateway_data['resource']['transactions'][0]['custom'];
$transaction_data = [];



//verify transaction by checking DB
$query = sprintf('SELECT * FROM %stbl_pgateway WHERE transaction_ref = "%s"', DB_TBL_PREFIX, $transaction_ref);

if($result = mysqli_query($GLOBALS['DB'], $query)){
    if(mysqli_num_rows($result)){
        $transaction_data = mysqli_fetch_assoc($result);
    }else{
        exit;
    }
}else{
    exit;
}

$transaction_meta = json_decode($transaction_data['memo'], true);
$access_token = $transaction_meta['access_token'];



//cutomer has approved payment from his paypal account. Lets then deduct the amount from his paypal account


$curl = curl_init("https://api-m.sandbox.paypal.com/v1/payments/payment/{$pay_id}/execute");
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_USERPWD, P_G_PK . ":" . P_G_SK);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer {$access_token}"));
//curl_setopt($curl, CURLOPT_HTTPGET, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payer_id));
$json_response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$response = json_decode($json_response, true);



if(json_last_error()){
    exit;
}

if(!(isset($response['state']) && $response['state']=='approved')){
    exit;
}


http_response_code(200);






$response_code = $response['state'];
$response_message = $gateway_data['state'];
$response_amount = $gateway_data['resource']['transactions'][0]['amount']['total'];


    
$user_type = 0;
$user_id = 0;
$driver_data = [];
$customer_data = [];
$wallet_amount = 0.00;



$currency_symbol = $transaction_meta['cur_symbol'];
$currency_code = $transaction_meta['cur_code'];
$exchange_rate = (float) $transaction_meta['cur_exchng'];
$user_type = (int) $transaction_meta['user_type'];
$user_id = (int) $transaction_meta['user_id'];
$user_currency_amount = ((float) $transaction_meta['amount']) / 100;

    

    

if($response_code == 'approved'){

        
    //update transaction in gateways database table
    $query = sprintf('UPDATE %stbl_pgateway SET  p_transaction_ref = "%s", `status` = "%s", gateway_resp = "%s" WHERE transaction_ref = "%s"', 
    
        DB_TBL_PREFIX,
        $gateway_transaction_id,
        $response_code,
        $response_message,
        $transaction_ref
    );

    $result = mysqli_query($GLOBALS['DB'], $query);


    
    //update wallet

    if($user_type){ //driver

        $query = sprintf('SELECT * FROM %1$stbl_drivers
        WHERE %1$stbl_drivers.driver_id = "%2$d"', DB_TBL_PREFIX, $user_id);

        if($result = mysqli_query($GLOBALS['DB'], $query)){
            if(mysqli_num_rows($result)){

                $driver_data = mysqli_fetch_assoc($result);

            }else{
                exit;
            }
        }else{
            exit;
        }

        $wallet_amount =  !empty($driver_data['wallet_amount']) ? $driver_data['wallet_amount'] : 0.00;      
        
        //convert the amount paid by user to the default currency. wallet amount is based on the default currency set

        //$converted_amount_paid = (float) $response_amount / $exchange_rate; //commented out this code beacause amount has already been converted to default currency (Naira) on the app

        $wallet_amount += (float) $response_amount;

        $query = sprintf('UPDATE %stbl_drivers SET wallet_amount = %f WHERE driver_id = "%d"', DB_TBL_PREFIX, $wallet_amount,$user_id);
        $result = mysqli_query($GLOBALS['DB'], $query);

        
        
        //Add this transaction to wallet transactions database table
        
        $query = sprintf('INSERT INTO %stbl_wallet_transactions (cur_symbol,cur_exchng_rate,cur_code,transaction_id,amount,wallet_balance,user_id,user_type,`desc`,`type`,transaction_date) VALUES'.
        '("%s","%s","%s","%s","%s","%s","%d","%d","%s","%d","%s")', 
        DB_TBL_PREFIX,
        $currency_symbol,
        $exchange_rate,
        $currency_code,
        $transaction_ref,
        $user_currency_amount,
        $wallet_amount,
        $user_id,
        1,
        mysqli_real_escape_string($GLOBALS['DB'],$transaction_meta['memo']), 
        0,
        gmdate('Y-m-d H:i:s', time())

        );

        $result = mysqli_query($GLOBALS['DB'], $query);


    }else{ //customer

        $query = sprintf('SELECT * FROM %1$stbl_users WHERE %1$stbl_users.user_id = "%2$d"', DB_TBL_PREFIX, $user_id);

        if($result = mysqli_query($GLOBALS['DB'], $query)){
            if(mysqli_num_rows($result)){

                $customer_data = mysqli_fetch_assoc($result);

            }else{
                exit;
            }
        }else{
            exit;
        }


        $wallet_amount =  !empty($customer_data['wallet_amount']) ? $customer_data['wallet_amount'] : 0.00;
        
                
        //convert the amount paid by user to the default currency. wallet amount is based on the default currency set
        //$converted_amount_paid = (float) $response_amount / $exchange_rate;
        $wallet_amount += (float) $response_amount;

        $query = sprintf('UPDATE %stbl_users SET wallet_amount = %f WHERE user_id = "%d"', DB_TBL_PREFIX, $wallet_amount,$user_id);
        $result = mysqli_query($GLOBALS['DB'], $query);
        
        
                
        //Add this transaction to wallet transactions database table
        
        $query = sprintf('INSERT INTO %stbl_wallet_transactions (cur_symbol,cur_exchng_rate,cur_code,transaction_id,amount,wallet_balance,user_id,user_type,`desc`,`type`,transaction_date) VALUES'.
        '("%s","%s","%s","%s","%s","%s","%d","%d","%s","%d","%s")', 
        DB_TBL_PREFIX,
        $currency_symbol,
        $exchange_rate,
        $currency_code,
        $transaction_ref,
        $user_currency_amount,
        $wallet_amount,
        $user_id,
        0,
        mysqli_real_escape_string($GLOBALS['DB'],$transaction_meta['memo']), 
        0,
        gmdate('Y-m-d H:i:s', time())

        );

        $result = mysqli_query($GLOBALS['DB'], $query);


    }
    
    



}
    






?>