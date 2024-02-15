<div class="box box-success">
        <!-- <div class="box-header with-border">
        <h3 class="box-title">Options</h3>
        
        </div> -->
        <!-- /.box-header -->
        <div class="box-body">
        
        
                <form  enctype="multipart/form-data" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>" method="post" >

                              
                
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label for="default-payment-gateway">Payment Gateway</label>
                                <p>Select the default payment gateway for wallet top-up on apps </p>
                                <select class="form-control" id="default-payment-gateway" name="default-payment-gateway">
                                <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'none') ? 'selected' : ''; ?> value="none">None</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'paystack') ? 'selected' : ''; ?> value="paystack">Paystack</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'paypal') ? 'selected' : ''; ?> value="paypal">PayPal</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'pesapal') ? 'selected' : ''; ?> value="pesapal">Pesapal</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'stripe') ? 'selected' : ''; ?> value="stripe">Stripe</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'paytr') ? 'selected' : ''; ?> value="paytr">PayTR</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'paytm') ? 'selected' : ''; ?> value="paytm">PayTM</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'phonepe') ? 'selected' : ''; ?> value="phonepe">PhonePe</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'flutterwave') ? 'selected' : ''; ?> value="flutterwave">FlutterWave</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'payku') ? 'selected' : ''; ?> value="payku">Payku</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'paymob') ? 'selected' : ''; ?> value="paymob">Paymob</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'midtrans') ? 'selected' : ''; ?> value="midtrans">Midtrans</option>
                                    <option <?php echo isset($settings_data2['default-payment-gateway']) &&  ($settings_data2['default-payment-gateway'] == 'custom') ? 'selected' : ''; ?> value="custom">Custom</option>
                                </select>
                                <br>
                            </div>
                        </div> 
                        
                        <div class="form-group">
                            
                            <div class="col-sm-3" id="merchid-box">
                                <label id="merchid-box-title" for="payment-gateway-merchant-id"><span style="color:red">*</span>Merchant ID.</label>
                                <p id="merchid-box-stitle">Merchant ID provided by the payment gateway</p>
                                <input  type="text" class="form-control" id="payment-gateway-merchant-id" placeholder="" name="payment-gateway-merchant-id" value="<?php echo isset($settings_data2['payment-gateway-merchant-id']) ? (!empty(DEMO) ? mask_string($settings_data2['payment-gateway-merchant-id'] ) : $settings_data2['payment-gateway-merchant-id'] ): ''; ?>" >
                            </div>
                            
                            <div class="col-sm-3" id="pubk-box">
                                <label for="payment-gateway-public-key" id="pubk-box-title"><span style="color:red">*</span>Public Key</label>
                                <p id="pubk-box-stitle">Payment Gateway Public Key</p>
                                <input  type="text" class="form-control" id="payment-gateway-public-key" placeholder="" name="payment-gateway-public-key" value="<?php echo isset($settings_data2['payment-gateway-public-key']) ? (!empty(DEMO) ? mask_string($settings_data2['payment-gateway-public-key'] ) : $settings_data2['payment-gateway-public-key'] ) : ''; ?>" >
                            </div>
                            
                            <div class="col-sm-3" id="privk-box">
                                <label id="privk-box-title" for="payment-gateway-private-key"><span style="color:red">*</span>Private Key</label>
                                <p id="privk-box-stitle">Payment Gateway Private Key</p>
                                <input  type="text" class="form-control" id="payment-gateway-private-key" placeholder="" name="payment-gateway-private-key" value="<?php echo isset($settings_data2['payment-gateway-private-key']) ? (!empty(DEMO) ? mask_string($settings_data2['payment-gateway-private-key'] ) : $settings_data2['payment-gateway-private-key'] ) : ''; ?>" >
                            </div>

                            <div class="col-sm-3" id="saltk-box">
                                <label id="saltk-box-title" for="payment-gateway-salt-key"><span style="color:red">*</span>Salt Key</label>
                                <p id="saltk-box-stitle">Payment Gateway Salt Key</p>
                                <input  type="text" class="form-control" id="payment-gateway-salt-key" placeholder="" name="payment-gateway-salt-key" value="<?php echo isset($settings_data2['payment-gateway-salt-key']) ? (!empty(DEMO) ? mask_string($settings_data2['payment-gateway-salt-key'] ) : $settings_data2['payment-gateway-salt-key'] ) : ''; ?>" >
                            </div>

                            <div class="col-sm-12">
                                <p id="pg-note" style="font-size:12px;margin-top: 15px;display: inline-block;padding: 10px;border: thin solid red;"></p>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                        
                            <div class="col-sm-6">
                                <label for="google-maps-api-key"><span style="color:red">*</span>Google Maps API Key</label>
                                <p>Google Maps API Key used in Web Panel</p>
                                <input  type="text" required class="form-control" id="google-maps-api-key" placeholder="" name="google-maps-api-key" value="<?php echo isset($settings_data2['google-maps-api-key']) ? (!empty(DEMO) ? mask_string($settings_data2['google-maps-api-key'] ) : $settings_data2['google-maps-api-key'] ) : ''; ?>" >
                            </div>

                        </div>

                        <hr>

                        <div class="form-group">
                        
                            <div class="col-sm-6">
                                <label for="sms-otp-service"><span style="color:red">*</span>Default OTP SMS Service</label>
                                <p>Set the default service that will be used for sending registration and login OTP SMS.</p>
                                <select id="sms-otp-service" name="sms-otp-service">
                                    <option <?php echo isset($settings_data2['sms-otp-service']) &&  ($settings_data2['sms-otp-service'] == 'firebase') ? 'selected' : ''; ?> value="firebase">Firebase</option>
                                    <option <?php echo isset($settings_data2['sms-otp-service']) &&  ($settings_data2['sms-otp-service'] == 'custom') ? 'selected' : ''; ?> value="custom">Custom</option>
                                </select>
                            </div>  
                            
                        </div>

                        <div class="form-group sms-otp-service-conf" id="custom-service-conf" style="display:none;">                            
                            
                            <div class="col-sm-12">
                                <p id="sms-otp-service-integration-note" style="font-size:12px;margin-top: 15px;display: inline-block;padding: 10px;border: thin solid red;">Please contact us for integration of your SMS service provider.</p>
                            </div>

                        </div>

                        <hr>


                        <div class="form-group" style="display:none;">
                        
                            <div class="col-sm-6">
                                <label for="google-push-server-key"><span style="color:red">*</span>FCM Server Push Key</label>
                                <p>Google Firebase Cloud Messaging Push Notification Server Key. Allows the apps to be notified by the server</p>
                                <input  type="text" required class="form-control" id="google-push-server-key" placeholder="" name="google-push-server-key" value="<?php echo isset($settings_data2['google-push-server-key']) ? (!empty(DEMO) ? mask_string($settings_data2['google-push-server-key'] ) : $settings_data2['google-push-server-key'] ) : ''; ?>" >
                            </div>

                        </div>

                        <!-- <hr> -->


                        <div class="form-group">
                        
                            <div class="col-sm-4">
                                <label for="firebase-web-api-key"><span style="color:red">*</span>Firebase Web API Key</label>
                                <p>Enter your firebase Web API key</p>
                                <input  type="text" required class="form-control" id="firebase-web-api-key" placeholder="" name="firebase-web-api-key" value="<?php echo isset($settings_data2['firebase-web-api-key']) ? (!empty(DEMO) ? mask_string($settings_data2['firebase-web-api-key'] ) : $settings_data2['firebase-web-api-key'] ) : ''; ?>" >
                            </div>

                            <div class="col-sm-4">
                                <label for="firebase-rtdb-url"><span style="color:red">*</span>Firebase RTDB URL</label>
                                <p>Enter the URL of the Firebase Realtime Database</p>
                                <input  type="text" required class="form-control" id="firebase-rtdb-url" placeholder="" name="firebase-rtdb-url" value="<?php echo isset($settings_data2['firebase-rtdb-url']) ? (!empty(DEMO) ? mask_string($settings_data2['firebase-rtdb-url'] ) : $settings_data2['firebase-rtdb-url'] ) : ''; ?>" >
                            </div>

                            <div class="col-sm-4">
                                <label for="firebase-storage-bucket"><span style="color:red">*</span>Firebase Storage Bucket</label>
                                <p>Enter the Firebase Storage Bucket</p>
                                <input  type="text" required class="form-control" id="firebase-storage-bucket" placeholder="" name="firebase-storage-bucket" value="<?php echo isset($settings_data2['firebase-storage-bucket']) ? (!empty(DEMO) ? mask_string($settings_data2['firebase-storage-bucket'] ) : $settings_data2['firebase-storage-bucket'] ) : ''; ?>" >
                            </div>

                        </div>

                        <hr>


                        
                    <button type="submit" class="btn btn-primary btn-block" value="1" name="savesettings2" >Save</button> 
                </form>



                            
        </div>
        <!-- /.box-body -->
    </div>

    
    <script>
        var sms_otp_service_selected = $('#sms-otp-service').val();

        if(sms_otp_service_selected == 'firebase'){
            $('#custom-service-conf').fadeOut();
        }else if(sms_otp_service_selected == 'custom'){
            $('#custom-service-conf').fadeIn();
        }


        $('#sms-otp-service').on('change', function(){

            sms_otp_service_selected = $(this).val();

            if(sms_otp_service_selected == 'firebase'){
                $('#custom-service-conf').fadeOut();
            }else if(sms_otp_service_selected == 'custom'){
                $('#custom-service-conf').fadeIn();
            }

        })



        var payment_gateway_selected = $('#default-payment-gateway').val();


        switch(payment_gateway_selected){

            case 'none':
            $('#merchid-box').hide();
            $('#merchid-box-title').text('<span style="color:red">*</span>');
            $('#merchid-box-stitle').text('');

            $('#pubk-box').hide();
            $('#pubk-box-title').text('<span style="color:red">*</span>');
            $('#pubk-box-stitle').text('');

            $('#privk-box').hide();
            $('#privk-box-title').html('<span style="color:red">*</span>');
            $('#privk-box-stitle').text('');

            $('#saltk-box').hide();
            $('#saltk-box-title').html('<span style="color:red">*</span>');
            $('#saltk-box-stitle').text('');  

            $('#pg-note').hide();
            $('#pg-note').html('');
            break;

            case 'paystack':
            $('#merchid-box').hide();
            $('#merchid-box-title').html('<span style="color:red">*</span>');
            $('#merchid-box-stitle').text('');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Public Key');
            $('#pubk-box-stitle').text('Enter Paystack Public key');

            $('#privk-box').show();
            $('#privk-box-title').html('<span style="color:red">*</span> Secret Key');
            $('#privk-box-stitle').text('Enter Paystack Secret key');

            $('#saltk-box').hide();
            $('#saltk-box-title').html('<span style="color:red">*</span>');
            $('#saltk-box-stitle').text('');

            $('#pg-note').show();
            $('#pg-note').html('Under your paystack account settings page | API Keys & Webhooks tab, Enter <b><?php echo SITE_URL . "paynotify.php?callback=true"  ?></b> as your callback URL. Enter <b><?php echo SITE_URL . "paynotify.php"  ?></b> as your Webhook URL');
            break;


            case 'paypal':
            $('#merchid-box').hide();
            $('#merchid-box-title').html('<span style="color:red">*</span>');
            $('#merchid-box-stitle').text('');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> PayPal Client ID');
            $('#pubk-box-stitle').text('Enter your PayPal account Client ID');

            $('#privk-box').show();
            $('#privk-box-title').html('<span style="color:red">*</span> Secret Key');
            $('#privk-box-stitle').text('Enter your PayPal account Secret key');

            $('#saltk-box').hide();
            $('#saltk-box-title').html('<span style="color:red">*</span>');
            $('#saltk-box-stitle').text('');

            $('#pg-note').show();
            $('#pg-note').html('Login to your paypal developer account. Ensure you are in Live mode. In the Apps & Credentials tab, click the Create app button. Enter an App Name then click the Create App button. Copy and paste your Client ID and Secret into the resective fields above. Click the Add Webhook button. Enter <b><?php echo SITE_URL . "paynotify.php"  ?></b> as your Webhook URL. Under event types, check the following checkboxes: <b>Payment authorization created, Payment authorization voided, Payment capture completed, Payment capture declined, Payment capture denied, Payment capture pending, Payment capture refunded, Payment capture reversed</b>. Click Save.');
            break;

            case 'pesapal':
            $('#merchid-box').hide();
            $('#merchid-box-title').html('<span style="color:red">*</span>');
            $('#merchid-box-stitle').text('');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Consumer Key');
            $('#pubk-box-stitle').text('Enter Pesapal Consumer Key');

            $('#privk-box').show();
            $('#privk-box-title').html('<span style="color:red">*</span> Consumer Secret');
            $('#privk-box-stitle').text('Enter Pesapal Consumer Secret');

            $('#saltk-box').hide();
            $('#saltk-box-title').html('<span style="color:red">*</span>');
            $('#saltk-box-stitle').text(''); 

            $('#pg-note').show();
            $('#pg-note').html('In your PesaPal account, under IPN Settings menu option, enter this <b><?php echo SITE_URL ?></b> as your Website Domain <br> and this <b><?php echo SITE_URL . "paynotify.php"  ?></b> as your Website IPN Listener URL');
            break;


            case 'stripe':
            $('#merchid-box').hide();
            $('#merchid-box-title').html('<span style="color:red">*</span>');
            $('#merchid-box-stitle').text('');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Publishable Key');
            $('#pubk-box-stitle').text('Enter Stripe Publishable Key');

            $('#privk-box').show();
            $('#privk-box-title').html('<span style="color:red">*</span> Secret Key');
            $('#privk-box-stitle').text('Enter Stripe Secret Key');

            $('#saltk-box').hide();
            $('#saltk-box-title').html('<span style="color:red">*</span>');
            $('#saltk-box-stitle').text('');

            $('#pg-note').show();
            $('#pg-note').html('In your Stripe account under Developers page, select Webhook and click the Add Endpoint button. <br>Under Endpoint URL, Enter (<b><?php echo SITE_URL . "paynotify.php"  ?></b>)<br>Under events, Expand Payment Intent and select these events: <b>Payment_intent.canceled</b>, <b>Payment_intent.payment_failed</b>, <b>Payment_intent.succeeded</b>');
            break;


            case 'paytr':
            $('#merchid-box').show();
            $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
            $('#merchid-box-stitle').text('Enter your PayTR Merchant ID');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Merchant Key');
            $('#pubk-box-stitle').text('Enter your PayTR Merchant Key');

            $('#privk-box').hide();
            $('#privk-box-title').html('<span style="color:red">*</span> ');
            $('#privk-box-stitle').text('');

            $('#saltk-box').show();
            $('#saltk-box-title').html('<span style="color:red">*</span> Merchant Salt');
            $('#saltk-box-stitle').text('Enter your PayTR Merchant Salt');  

            $('#pg-note').show();
            $('#pg-note').html('In your PayTR account, under settings, set Callback URL to <b><?php echo SITE_URL . "paynotify.php"  ?></b>');
            break;



            case 'paytm':
            $('#merchid-box').show();
            $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
            $('#merchid-box-stitle').text('Enter your PayTM Merchant ID');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Merchant Key');
            $('#pubk-box-stitle').text('Enter your PayTM Merchant Key');

            $('#privk-box').hide();
            $('#privk-box-title').html('<span style="color:red">*</span> ');
            $('#privk-box-stitle').text('');

            $('#saltk-box').hide();
            $('#saltk-box-title').html('<span style="color:red">*</span> Merchant Salt');
            $('#saltk-box-stitle').text('Enter your PayTR Merchant Salt');  

            $('#pg-note').show();
            $('#pg-note').html('In your PayTM account dashboard, on the left sidebar, Click the developer menu, then API keys. under Production API details, click generate keys, copy and paste your Merchant ID (MID) and Merchant Key into the appropriate fields above. Under Website, set Callback URL to <b><?php echo SITE_URL . "paynotify.php"  ?></b>');
            break;




            case 'phonepe':
            $('#merchid-box').show();
            $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
            $('#merchid-box-stitle').text('Enter your PhonePe Merchant ID');

            $('#pubk-box').hide();
            $('#pubk-box-title').html('<span style="color:red">*</span> Merchant Key');
            $('#pubk-box-stitle').text('Enter your PayTM Merchant Key');

            $('#privk-box').hide();
            $('#privk-box-title').html('<span style="color:red">*</span> ');
            $('#privk-box-stitle').text('');

            $('#saltk-box').show();
            $('#saltk-box-title').html('<span style="color:red">*</span> Merchant Salt');
            $('#saltk-box-stitle').text('Enter your PhonePe Merchant Salt');  

            $('#pg-note').show();
            $('#pg-note').html('Contact PhonePe support after registering and request for your Merhant ID and Salt Key');
            break;




            case 'flutterwave':
            $('#merchid-box').hide();
            $('#merchid-box-title').html('<span style="color:red">*</span>');
            $('#merchid-box-stitle').text('');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Public key');
            $('#pubk-box-stitle').text('Enter your Flutterwave public key');

            $('#privk-box').show();
            $('#privk-box-title').html('<span style="color:red">*</span> Secret key');
            $('#privk-box-stitle').text('Enter your Flutterwave Secret key');

            $('#saltk-box').hide();
            $('#saltk-box-title').html('<span style="color:red">*</span>');
            $('#saltk-box-stitle').text(''); 

            $('#pg-note').show();
            $('#pg-note').html('In your Flutterwave account, under settings, Webhooks tab, Enter <b><?php echo SITE_URL . "paynotify.php"  ?></b> as your  Webhook URL and <b><?php echo sha1(SITE_URL . 'Droptaxi')?></b> as your Secret hash');
            break;


            case 'payku':
            $('#merchid-box').hide();
            $('#merchid-box-title').html('<span style="color:red">*</span>');
            $('#merchid-box-stitle').text('');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Public token');
            $('#pubk-box-stitle').text('Enter your Payku public token');

            $('#privk-box').show();
            $('#privk-box-title').html('<span style="color:red">*</span> Private token');
            $('#privk-box-stitle').text('Enter your Payku private token');

            $('#saltk-box').hide();
            $('#saltk-box-title').html('<span style="color:red">*</span>');
            $('#saltk-box-stitle').text(''); 

            $('#pg-note').show();
            $('#pg-note').html('On the sidebar menu in payku dashboard, highlight integrations menu item then select tokens integration and API. Create your tokens by clicking the Create button and entering a name for the tokens. A public and private token will be created. Copy and paste them into the appropriate fields above.');
            break;


            case 'paymob':
            $('#merchid-box').hide();
            $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
            $('#merchid-box-stitle').text('Enter your Paymob Merchant ID');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Iframe ID');
            $('#pubk-box-stitle').text('Enter the iframe ID to use');

            $('#privk-box').show();
            $('#privk-box-title').html('<span style="color:red">*</span> API Key');
            $('#privk-box-stitle').text('Enter your Paymob API key');

            $('#saltk-box').show();
            $('#saltk-box-title').html('<span style="color:red">*</span>Payment Integration IDs');
            $('#saltk-box-stitle').text('Enter your Card and Kiosk payment integration IDs. Separate with a pipe "|" character'); 

            $('#pg-note').show();
            $('#pg-note').html('In your Paymob dashboard, on the left sidebar, expand the Developer menu and select payment integrations. Add your payment integrations such as Online card, Accept Kiosk etc. For each payment integration ensure you enter <b><?php echo SITE_URL . 'paynotify.php'?></b> as the Transaction Processed Callback and for the Transaction Response Callback, enter <b><?php echo SITE_URL . 'paynotify.php?callback=true'?></b>');
            break;


            case 'midtrans':
            $('#merchid-box').show();
            $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
            $('#merchid-box-stitle').text('Enter your Midtrans merchant ID');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Client Key');
            $('#pubk-box-stitle').text('Enter your Midtrans client key');

            $('#privk-box').show();
            $('#privk-box-title').html('<span style="color:red">*</span> Server Key');
            $('#privk-box-stitle').text('Enter your Midtrans server key');

            $('#saltk-box').hide();
            $('#saltk-box-title').html('<span style="color:red">*</span>');
            $('#saltk-box-stitle').text(''); 

            $('#pg-note').show();
            $('#pg-note').html('Expand the settings menu item on the sidebar menu of the Midtrans dashboard and select Access Keys. Copy your Merchant ID, Client Key and Server keys and paste them into the appropriate fields above.');
            break;


            case 'custom':
            $('#merchid-box').show();
            $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
            $('#merchid-box-stitle').text('Enter your custom gateway merchant ID');

            $('#pubk-box').show();
            $('#pubk-box-title').html('<span style="color:red">*</span> Public Key');
            $('#pubk-box-stitle').text('Enter your custom gateway Public Key');

            $('#privk-box').show();
            $('#privk-box-title').html('<span style="color:red">*</span> Private Key');
            $('#privk-box-stitle').text('Enter your custom gateway Private Key');

            $('#saltk-box').show();
            $('#saltk-box-title').html('<span style="color:red">*</span> Salt Key');
            $('#saltk-box-stitle').text('Enter your custom gateway Salt Key');    

            $('#pg-note').show();
            $('#pg-note').html('Use these contants to access the values entered in these fields in your custom gateway implementation:<br> <b>P_G_MERCHANT_ID</b>, <b>P_G_PK</b>, <b>P_G_SK</b> and <b>P_G_SALT_K</b>. Learn more in the Droptaxi documentation.');
            break;

        }


        $('#default-payment-gateway').on('change', function(){
            let payment_gateway = $(this).val();
            switch(payment_gateway){

                case 'none':
                $('#merchid-box').hide();
                $('#merchid-box-title').text('<span style="color:red">*</span>');
                $('#merchid-box-stitle').text('');

                $('#pubk-box').hide();
                $('#pubk-box-title').text('<span style="color:red">*</span>');
                $('#pubk-box-stitle').text('');
                
                $('#privk-box').hide();
                $('#privk-box-title').html('<span style="color:red">*</span>');
                $('#privk-box-stitle').text('');

                $('#saltk-box').hide();
                $('#saltk-box-title').html('<span style="color:red">*</span>');
                $('#saltk-box-stitle').text('');  
                
                $('#pg-note').hide();
                $('#pg-note').html('');
                break;

                case 'paystack':
                $('#merchid-box').hide();
                $('#merchid-box-title').html('<span style="color:red">*</span>');
                $('#merchid-box-stitle').text('');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Public Key');
                $('#pubk-box-stitle').text('Enter Paystack Public key');
                
                $('#privk-box').show();
                $('#privk-box-title').html('<span style="color:red">*</span> Secret Key');
                $('#privk-box-stitle').text('Enter Paystack Secret key');

                $('#saltk-box').hide();
                $('#saltk-box-title').html('<span style="color:red">*</span>');
                $('#saltk-box-stitle').text('');
                
                $('#pg-note').show();
                $('#pg-note').html('Under your paystack account settings page | API Keys & Webhooks tab, Enter <b><?php echo SITE_URL . "paynotify.php?callback=true"  ?></b> as your callback URL. Enter <b><?php echo SITE_URL . "paynotify.php"  ?></b> as your Webhook URL');
                break;

                case 'paypal':
                $('#merchid-box').hide();
                $('#merchid-box-title').html('<span style="color:red">*</span>');
                $('#merchid-box-stitle').text('');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> PayPal Client ID');
                $('#pubk-box-stitle').text('Enter your PayPal account Client ID');

                $('#privk-box').show();
                $('#privk-box-title').html('<span style="color:red">*</span> Secret Key');
                $('#privk-box-stitle').text('Enter your PayPal account Secret key');

                $('#saltk-box').hide();
                $('#saltk-box-title').html('<span style="color:red">*</span>');
                $('#saltk-box-stitle').text('');

                $('#pg-note').show();
                $('#pg-note').html('Login to your paypal developer account. Ensure you are in Live mode. In the Apps & Credentials tab, click the Create app button. Enter an App Name then click the Create App button. Copy and paste your Client ID and Secret into the resective fields above. Click the Add Webhook button. Enter <b><?php echo SITE_URL . "paynotify.php"  ?></b> as your Webhook URL. Under event types, check the following checkboxes: <b>Payment authorization created, Payment authorization voided, Payment capture completed, Payment capture declined, Payment capture denied, Payment capture pending, Payment capture refunded, Payment capture reversed</b>. Click Save.');
                break;

                case 'pesapal':
                $('#merchid-box').hide();
                $('#merchid-box-title').html('<span style="color:red">*</span>');
                $('#merchid-box-stitle').text('');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Consumer Key');
                $('#pubk-box-stitle').text('Enter Pesapal Consumer Key');
                
                $('#privk-box').show();
                $('#privk-box-title').html('<span style="color:red">*</span> Consumer Secret');
                $('#privk-box-stitle').text('Enter Pesapal Consumer Secret');

                $('#saltk-box').hide();
                $('#saltk-box-title').html('<span style="color:red">*</span>');
                $('#saltk-box-stitle').text(''); 
                
                $('#pg-note').show();
                $('#pg-note').html('In your PesaPal account, under IPN Settings menu option, enter this <b><?php echo SITE_URL ?></b> as your Website Domain <br> and <b><?php echo SITE_URL . "paynotify.php"  ?></b> as your Website IPN Listener URL');
                break;


                case 'stripe':
                $('#merchid-box').hide();
                $('#merchid-box-title').html('<span style="color:red">*</span>');
                $('#merchid-box-stitle').text('');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Publishable Key');
                $('#pubk-box-stitle').text('Enter Stripe Publishable Key');
                
                $('#privk-box').show();
                $('#privk-box-title').html('<span style="color:red">*</span> Secret Key');
                $('#privk-box-stitle').text('Enter Stripe Secret Key');

                $('#saltk-box').hide();
                $('#saltk-box-title').html('<span style="color:red">*</span>');
                $('#saltk-box-stitle').text('');
                
                $('#pg-note').show();
                $('#pg-note').html('In your Stripe account under Developers page, select Webhook and click the Add Endpoint button. <br>Under Endpoint URL, Enter <b><?php echo SITE_URL . "paynotify.php"  ?></b><br>Under events, Expand Payment Intent and select these events: <b>Payment_intent.canceled</b>, <b>Payment_intent.payment_failed</b>, <b>Payment_intent.succeeded</b>');
                break;


                case 'paytr':
                $('#merchid-box').show();
                $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
                $('#merchid-box-stitle').text('Enter your PayTR Merchant ID');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Merchant Key');
                $('#pubk-box-stitle').text('Enter your PayTR Merchant Key');
                
                $('#privk-box').hide();
                $('#privk-box-title').html('<span style="color:red">*</span> ');
                $('#privk-box-stitle').text('');

                $('#saltk-box').show();
                $('#saltk-box-title').html('<span style="color:red">*</span> Merchant Salt');
                $('#saltk-box-stitle').text('Enter your PayTR Merchant Salt');  
                
                $('#pg-note').show();
                $('#pg-note').html('In your PayTR account, under settings, set Callback URL to <b><?php echo SITE_URL . "paynotify.php"  ?></b>');
                break;


                case 'phonepe':
                $('#merchid-box').show();
                $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
                $('#merchid-box-stitle').text('Enter your PhonePe Merchant ID');

                $('#pubk-box').hide();
                $('#pubk-box-title').html('<span style="color:red">*</span>');
                $('#pubk-box-stitle').text('');

                $('#privk-box').hide();
                $('#privk-box-title').html('<span style="color:red">*</span> ');
                $('#privk-box-stitle').text('');

                $('#saltk-box').show();
                $('#saltk-box-title').html('<span style="color:red">*</span> Merchant Salt');
                $('#saltk-box-stitle').text('Enter your PhonePe Merchant Salt');  

                $('#pg-note').show();
                $('#pg-note').html('Contact PhonePe support and request for your Merhant ID and Salt Key');
                break;

                case 'paytm':
                $('#merchid-box').show();
                $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
                $('#merchid-box-stitle').text('Enter your PayTM Merchant ID');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Merchant Key');
                $('#pubk-box-stitle').text('Enter your PayTM Merchant Key');

                $('#privk-box').hide();
                $('#privk-box-title').html('<span style="color:red">*</span> ');
                $('#privk-box-stitle').text('');

                $('#saltk-box').hide();
                $('#saltk-box-title').html('<span style="color:red">*</span> Merchant Salt');
                $('#saltk-box-stitle').text('Enter your PayTR Merchant Salt');  

                $('#pg-note').show();
                $('#pg-note').html('In your PayTM account dashboard, on the left sidebar, Click the developer menu, then API keys. under Production API details, click generate keys, copy and paste your Merchant ID (MID) and Merchant Key into the appropriate fields above. Under Website, set Callback URL to <b><?php echo SITE_URL . "paynotify.php"  ?></b>');
                break;


                case 'flutterwave':
                $('#merchid-box').hide();
                $('#merchid-box-title').html('<span style="color:red">*</span>');
                $('#merchid-box-stitle').text('');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Public key');
                $('#pubk-box-stitle').text('Enter your Flutterwave public key');

                $('#privk-box').show();
                $('#privk-box-title').html('<span style="color:red">*</span> Secret key');
                $('#privk-box-stitle').text('Enter your Flutterwave Secret key');

                $('#saltk-box').hide();
                $('#saltk-box-title').html('<span style="color:red">*</span>');
                $('#saltk-box-stitle').text(''); 

                $('#pg-note').show();
                $('#pg-note').html('In your Flutterwave account, under settings, Webhooks tab, Enter <b><?php echo SITE_URL . "paynotify.php"  ?></b> as your  Webhook URL and <b><?php echo sha1(SITE_URL . 'Droptaxi')?></b> as your Secret hash');
                break;

                case 'payku':
                $('#merchid-box').hide();
                $('#merchid-box-title').html('<span style="color:red">*</span>');
                $('#merchid-box-stitle').text('');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Public token');
                $('#pubk-box-stitle').text('Enter your Payku public token');

                $('#privk-box').show();
                $('#privk-box-title').html('<span style="color:red">*</span> Private token');
                $('#privk-box-stitle').text('Enter your Payku private token');

                $('#saltk-box').hide();
                $('#saltk-box-title').html('<span style="color:red">*</span>');
                $('#saltk-box-stitle').text(''); 

                $('#pg-note').show();
                $('#pg-note').html('On the sidebar menu in payku dashboard, highlight integrations menu item then select tokens integration and API. Create your tokens by clicking the Create button and entering a name for the tokens. A public and private token will be created. Copy and paste them into the appropriate fields above.');
                break;

                case 'paymob':
                $('#merchid-box').hide();
                $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
                $('#merchid-box-stitle').text('Enter your Paymob Merchant ID');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Iframe ID');
                $('#pubk-box-stitle').text('Enter the iframe ID to use');

                $('#privk-box').show();
                $('#privk-box-title').html('<span style="color:red">*</span> API Key');
                $('#privk-box-stitle').text('Enter your Paymob API key');

                $('#saltk-box').show();
                $('#saltk-box-title').html('<span style="color:red">*</span>Payment Integration IDs');
                $('#saltk-box-stitle').text('Enter your Card and Kiosk payment integration IDs. Separate with a pipe "|" character'); 

                $('#pg-note').show();
                $('#pg-note').html('In your Paymob dashboard, on the left sidebar, expand the Developer menu and select payment integrations. Add your payment integrations such as Online card, Accept Kiosk etc. For each payment integration ensure you enter <b><?php echo SITE_URL . 'paynotify.php'?></b> as the Transaction Processed Callback and for the Transaction Response Callback, enter <b><?php echo SITE_URL . 'paynotify.php?callback=true'?></b>');
                break;


                case 'midtrans':
                $('#merchid-box').show();
                $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
                $('#merchid-box-stitle').text('Enter your Midtrans merchant ID');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Client Key');
                $('#pubk-box-stitle').text('Enter your Midtrans client key');

                $('#privk-box').show();
                $('#privk-box-title').html('<span style="color:red">*</span> Server Key');
                $('#privk-box-stitle').text('Enter your Midtrans server key');

                $('#saltk-box').hide();
                $('#saltk-box-title').html('<span style="color:red">*</span>');
                $('#saltk-box-stitle').text(''); 

                $('#pg-note').show();
                $('#pg-note').html('Expand the settings menu item on the sidebar menu of the Midtrans dashboard and select Access Keys. Copy your Merchant ID, Client Key and Server keys and paste them into the appropriate fields above.');
                break;


                case 'custom':
                $('#merchid-box').show();
                $('#merchid-box-title').html('<span style="color:red">*</span> Merchant ID');
                $('#merchid-box-stitle').text('Enter your custom gateway merchant ID');

                $('#pubk-box').show();
                $('#pubk-box-title').html('<span style="color:red">*</span> Public Key');
                $('#pubk-box-stitle').text('Enter your custom gateway Public Key');
                
                $('#privk-box').show();
                $('#privk-box-title').html('<span style="color:red">*</span> Private Key');
                $('#privk-box-stitle').text('Enter your custom gateway Private Key');

                $('#saltk-box').show();
                $('#saltk-box-title').html('<span style="color:red">*</span> Salt Key');
                $('#saltk-box-stitle').text('Enter your custom gateway Salt Key');    
                
                $('#pg-note').show();
                $('#pg-note').html('Use these contants to access the values entered in these fields in your custom gateway implementation:<br> <b>P_G_MERCHANT_ID</b>, <b>P_G_PK</b>, <b>P_G_SK</b> and <b>P_G_SALT_K</b>. Learn more in the Droptaxi documentation.');
                break;

            }
        });
        
    </script>


