<?php
include dirname(__DIR__)."/drop-files/lib/common.php";
include dirname(__DIR__)."/drop-files/config/db.php";
require dirname(__DIR__)."/drop-files/lang/autodispatch/dict.php";

if(!isset($argv) )die("not for browser!"); //run only through command line or cron


define('BOOKING_PROCESSING_LIMIT',50); //maximum number of bookings processed at each execution loop.
define('BOOKING_PICKUP_TIME_AUTO_CANCEL', DRIVER_ALLOCATE_ACCEPT_DURATION); //Bookings with pickup times older that this will be automatically cancelled if n driver is found or non accepts the booking.
define('BOOKING_PICKUP_TIME_AGE_LIMIT', BOOKING_PICKUP_TIME_AUTO_CANCEL + (DRIVER_ALLOCATE_ACCEPT_DURATION * 5)); //Bookings with pickup times older that this will be ignored or stopped being search for a driver to allocate.

define('MAX_CURRENT_DRIVER_REQUESTS', 1); //maximum allowable concurrent serviceable requests a driver can have at a time
define('MAX_DRIVERS_ALLOCATE',1); //maximum number of drivers to allocate booking to at one time.
define('UNCOMPLETED_BOOKING_AUTO_CANCEL_AGE',86400); //automatically cancel any booking that is uncompleted (onride, pending) after pickup time has passed.(86400) 24h default
define('SLEEP_TIME', 1); // number of seconds to sleep to reduce cpu load

set_time_limit(0); //script never times out
date_default_timezone_set('UTC');


/* Cron tasks
    *Cancel any booking with pickup time in the past.
    * Check for recent booking with pickup time closer to the current time and allocate the closest driver. Send push notification to driver.
        Monitor driver response. If driver doesnt accept the allocated booking after a period of time, assign to another closer driver.
        Repeat untill allocation has been accepted by a driver. If none accepts, Cancel the booking, notify customer to try again later.
    *Notify customers of their rides set in the future when time gets close
*/


//Ensure only one instance of script can be run for at any given time.

$file = fopen ( __DIR__ . '/' .'crondata.dat', 'w' );


if ($file === false ) {
  echo "Unable to open file, check that you have permissions to open/write";
  exit;
}

$instances = exec("ps aux|grep ". basename(__FILE__) ."|grep -v grep|wc -l"); //ensure only one instance of cron is runnning at a time
if($instances > 2) {
   exit;
}

$count = 0;
$cron_exec_time = 0.00;
$data_count = 0;
$elapsed_exec_time_array = [];
$average_exec_time = 0.00;
$soft_tmr = 0;

While(1){
		$cron_exec_time = microtime(true);
        if($cron_file_run = fopen( dirname(__FILE__). '/crondata.txt', 'r+' )){
        	//echo "file open success";
            $data = fgets($cron_file_run);
            $data = trim($data);
            if($data == '0' || $data == 'off'){
                fclose($cron_file_run);
                exit; 
            }elseif($data == '2' || $data == "restart"){
            	echo 'restarted -' . time();
                fseek($cron_file_run,0);
                fwrite($cron_file_run,"1");
                fclose($cron_file_run);
                exit;
            }            
            fclose($cron_file_run);
        }else{
        	//echo "file open error";
            exit;
        }
		
        //always check the database connection is live else quit script. Cron will try to restart script on next set cron interval
        if (!mysqli_ping($GLOBALS['DB'])){
            exit;
        }

        
        //OK. run code


        //cancel any driver allocation which the driver hasn't responded to after DRIVER_ALLOCATE_ACCEPT_DURATION set time.
        $elapsed_allocate_time = gmdate('Y-m-d H:i:s', time() - DRIVER_ALLOCATE_ACCEPT_DURATION);

        $query = sprintf('UPDATE %1$stbl_driver_allocate
        JOIN %1$stbl_drivers ON %1$stbl_drivers.driver_id = %1$stbl_driver_allocate.driver_id
        SET %1$stbl_driver_allocate.status = 3, %1$stbl_drivers.rejected_rides = %1$stbl_drivers.rejected_rides + 1
        WHERE %1$stbl_driver_allocate.status = 0 AND %1$stbl_driver_allocate.date_allocated <  "%2$s"', DB_TBL_PREFIX, $elapsed_allocate_time);
        $result = mysqli_query($GLOBALS['DB'], $query);
        
        
        $soft_tmr++;

        if($soft_tmr > 60){
            $soft_tmr = 0;
            //run every minute
        
            //cancel all pending or onride bookings whose pickup time has elapsed by a specific time
            $elapsed_pickup_time = gmdate('Y-m-d H:i:s', time() - (BOOKING_PICKUP_TIME_AGE_LIMIT * 2));       

            $query = sprintf('UPDATE %1$stbl_bookings SET %1$stbl_bookings.status = 5 
            WHERE %1$stbl_bookings.status = 0 AND %1$stbl_bookings.pickup_datetime <  "%2$s" AND %1$stbl_bookings.driver_id = 0 AND %1$stbl_bookings.dispatch_mode = 0', DB_TBL_PREFIX,$elapsed_pickup_time);
            $result = mysqli_query($GLOBALS['DB'], $query);


            //finalize all driver allocations which the booking has already been cancelled or completed
            $query = sprintf('UPDATE %1$stbl_driver_allocate
            JOIN %1$stbl_bookings ON %1$stbl_bookings.id = %1$stbl_driver_allocate.booking_id
            SET %1$stbl_driver_allocate.status = 4
            WHERE %1$stbl_driver_allocate.status = 1 AND (%1$stbl_bookings.status = 5 OR %1$stbl_bookings.status = 3 OR %1$stbl_bookings.status = 2 OR %1$stbl_bookings.status = 4)', DB_TBL_PREFIX);
            $result = mysqli_query($GLOBALS['DB'], $query);

        }


                
       

        //Get location data for pending bookings 
        $bookings_data = [];
        $bookings_data_routes = [];
        $bookings_data_driver_alloc = [];

        $bookings_pickuptime_lower_limit = gmdate('Y-m-d H:i:s', time() - BOOKING_PICKUP_TIME_AGE_LIMIT);
        $bookings_pickuptime_upper_limit = gmdate('Y-m-d H:i:s', time()); 

        
        $query = sprintf('SELECT %1$stbl_bookings.waypoint1_address,%1$stbl_bookings.waypoint1_long,%1$stbl_bookings.waypoint1_lat,%1$stbl_bookings.waypoint2_address,%1$stbl_bookings.waypoint2_long,%1$stbl_bookings.waypoint2_lat,%1$stbl_users.user_rating, %1$stbl_users.photo_file, %1$stbl_users.firstname, %1$stbl_users.user_id, %1$stbl_users.push_notification_token, %1$stbl_bookings.id AS booking_id,%1$stbl_bookings.pickup_address,%1$stbl_bookings.dropoff_address,%1$stbl_bookings.completion_code,%1$stbl_bookings.pickup_long,%1$stbl_bookings.pickup_lat,%1$stbl_bookings.dropoff_long,%1$stbl_bookings.dropoff_lat,%1$stbl_bookings.route_id,%1$stbl_bookings.user_phone,%1$stbl_bookings.estimated_cost,%1$stbl_bookings.payment_type,%1$stbl_bookings.coupon_code,%1$stbl_bookings.coupon_discount_type,%1$stbl_bookings.coupon_discount_value,%1$stbl_bookings.coupon_min_fare,%1$stbl_bookings.coupon_max_discount,%1$stbl_bookings.referral_discount_value,%1$stbl_bookings.referral_used,%1$stbl_bookings.ride_id,%1$stbl_bookings.pickup_datetime,%1$stbl_driver_allocate.driver_id AS driver_id, %1$stbl_driver_allocate.status, %1$stbl_users.disp_lang, %1$stbl_routes.pickup_city_id FROM %1$stbl_bookings
        INNER JOIN %1$stbl_routes ON %1$stbl_routes.id = %1$stbl_bookings.route_id
        INNER JOIN %1$stbl_users ON %1$stbl_users.user_id = %1$stbl_bookings.user_id
        LEFT JOIN %1$stbl_driver_allocate ON %1$stbl_driver_allocate.booking_id = %1$stbl_bookings.id
        WHERE %1$stbl_bookings.status = 0 AND %1$stbl_bookings.driver_id = 0 AND %1$stbl_bookings.pickup_datetime > "%2$s" AND %1$stbl_bookings.pickup_datetime < "%3$s" AND %1$stbl_bookings.dispatch_mode = 0 LIMIT %4$d', DB_TBL_PREFIX, $bookings_pickuptime_lower_limit, $bookings_pickuptime_upper_limit, BOOKING_PROCESSING_LIMIT);

        if($result = mysqli_query($GLOBALS['DB'], $query)){
            if(mysqli_num_rows($result)){

                while($row = mysqli_fetch_assoc($result)){
                    if(!empty($row['driver_id'])){
                        $bookings_data_driver_alloc[$row['booking_id']][$row['driver_id']] = $row;
                    }
                    
                    $bookings_data[$row['booking_id']]['b_id'] = $row['booking_id'];
                    $bookings_data[$row['booking_id']]['p_long'] = $row['pickup_long'];
                    $bookings_data[$row['booking_id']]['p_address'] = $row['pickup_address'];
                    $bookings_data[$row['booking_id']]['d_address'] = $row['dropoff_address'];
                    $bookings_data[$row['booking_id']]['p_lat'] = $row['pickup_lat'];
                    $bookings_data[$row['booking_id']]['p_lng'] = $row['pickup_long'];
                    $bookings_data[$row['booking_id']]['d_lat'] = $row['dropoff_lat'];
                    $bookings_data[$row['booking_id']]['d_lng'] = $row['dropoff_long'];
                    $bookings_data[$row['booking_id']]['token'] = $row['push_notification_token'];
                    $bookings_data[$row['booking_id']]['uid'] = $row['user_id'];
                    $bookings_data[$row['booking_id']]['pickup_datetime'] = $row['pickup_datetime'];
                    $bookings_data[$row['booking_id']]['user_rating'] = $row['user_rating'];
                    $bookings_data[$row['booking_id']]['user_firstname'] = $row['firstname'];
                    $bookings_data[$row['booking_id']]['user_photo'] = $row['photo_file'];
                    $bookings_data[$row['booking_id']]['user_phone'] = $row['user_phone'];
                    $bookings_data[$row['booking_id']]['coupon_code'] = $row['coupon_code'];
                    $bookings_data[$row['booking_id']]['coupon_discount_type'] = $row['coupon_discount_type'];
                    $bookings_data[$row['booking_id']]['referral_discount_value'] = $row['referral_discount_value'];
                    $bookings_data[$row['booking_id']]['coupon_discount_value'] = $row['coupon_discount_value'];
                    $bookings_data[$row['booking_id']]['coupon_min_fare'] = $row['coupon_min_fare'];
                    $bookings_data[$row['booking_id']]['coupon_max_discount'] = $row['coupon_max_discount'];
                    $bookings_data[$row['booking_id']]['referral_used'] = $row['referral_used'];
                    $bookings_data[$row['booking_id']]['estimated_cost'] = $row['estimated_cost'];
                    $bookings_data[$row['booking_id']]['payment_type'] = $row['payment_type'];
                    $bookings_data[$row['booking_id']]['ride_id'] = $row['ride_id'];
                    $bookings_data[$row['booking_id']]['route_id'] = $row['route_id'];
                    $bookings_data[$row['booking_id']]['u_lang'] = $row['disp_lang'];
                    $bookings_data[$row['booking_id']]['completion_code'] = $row['completion_code'];                    
                    $bookings_data[$row['booking_id']]['waypoint1_address'] = $row['waypoint1_address'];
                    $bookings_data[$row['booking_id']]['waypoint1_long'] = $row['waypoint1_long'];
                    $bookings_data[$row['booking_id']]['waypoint1_lat'] = $row['waypoint1_lat'];
                    $bookings_data[$row['booking_id']]['waypoint2_address'] = $row['waypoint2_address'];
                    $bookings_data[$row['booking_id']]['waypoint2_long'] = $row['waypoint2_long'];
                    $bookings_data[$row['booking_id']]['waypoint2_lat'] = $row['waypoint2_lat'];
                    $bookings_data[$row['booking_id']]['pickup_city_id'] = $row['pickup_city_id'];
                    $bookings_data_routes[] = $row['route_id'];
                    if(!empty($row['pickup_city_id']))$bookings_data_routes[] = $row['pickup_city_id'];

                }
                
                mysqli_free_result($result);

            }else{
            	sleep(SLEEP_TIME);
            	continue;
            }
        
        }else{
        	sleep(SLEEP_TIME);
        	continue;
        }

		

        $bookings_data_routes = array_unique($bookings_data_routes);
        $route_ids_string = implode(',',$bookings_data_routes);

        //error_log("booking ids: ".$route_ids_string);


         
         $drivers_location_data = [];
         $drivers_alloc_pending = [];
         $drivers_alloc_servicing = [];
         $location_info_age = gmdate('Y-m-d H:i:s', time() - LOCATION_INFO_VALID_AGE);
         
         

        
         //get booking allocation data of drivers that are pending booking accept or are servicing a booking
         $query = sprintf('SELECT driver_id, `status` FROM %stbl_driver_allocate
         WHERE `status` = 0 OR `status` = 1', DB_TBL_PREFIX);

         if($result = mysqli_query($GLOBALS['DB'], $query)){
            if(mysqli_num_rows($result)){
                while($row = mysqli_fetch_assoc($result)){
                    if($row['status'] == '0'){
                        $drivers_alloc_pending[$row['driver_id']] = 1;
                    }elseif($row['status'] == '1'){
                        if(empty($drivers_alloc_servicing[$row['driver_id']])){
                            $drivers_alloc_servicing[$row['driver_id']] = 1;
                        }else{
                            $drivers_alloc_servicing[$row['driver_id']] += 1;
                        }
                        
                    }
                    
                }
                
            }
         }

         
        
         //Get location information for all drivers that are available in a city, accounts activated, and location coordinates were updated within the inactivity timeout period
         $query = sprintf('SELECT %1$stbl_drivers.driver_id,%1$stbl_drivers.disp_lang,%1$stbl_drivers.push_notification_token,%1$stbl_driver_location.long,%1$stbl_driver_location.lat, %1$stbl_drivers.route_id, %1$stbl_drivers.ride_id FROM %1$stbl_driver_location 
         INNER JOIN %1$stbl_drivers ON %1$stbl_driver_location.driver_id = %1$stbl_drivers.driver_id
         WHERE %1$stbl_drivers.route_id IN' .'(' . '%3$s'. ')'. ' AND %1$stbl_drivers.is_activated = 1 AND %1$stbl_drivers.available = 1 AND %1$stbl_driver_location.location_date > "%2$s" AND %1$stbl_drivers.wallet_amount > %4$f', DB_TBL_PREFIX,$location_info_age, $route_ids_string, DRIVER_MIN_WALLET_BALANCE);
 
         if($result = mysqli_query($GLOBALS['DB'], $query)){

            if(mysqli_num_rows($result)){
                
                while($row = mysqli_fetch_assoc($result)){
                    
                    if(isset($drivers_alloc_pending[$row['driver_id']])){
                        continue;
                    }else{
                        if(empty($drivers_alloc_servicing[$row['driver_id']])){
                            $drivers_location_data[$row['driver_id']] = $row;
                        }else{
                            if($drivers_alloc_servicing[$row['driver_id']] < MAX_CURRENT_DRIVER_REQUESTS){
                                $drivers_location_data[$row['driver_id']] = $row;
                            }
                        }
                    }                    
                        
                    
                }
                
                mysqli_free_result($result);
    
            }
         
         }
 
         //error_log("Drivers data: " .print_r($drivers_location_data,1));







        /* echo mysqli_error($GLOBALS['DB']);

        echo $query;
        var_dump($bookings_data); */


        


        foreach ($bookings_data as $bookingsdata){
        
            $driver_pending_accept = 0;
            $drivers_cancelled_allocate = [];
            

            
            if(!empty($bookings_data_driver_alloc[$bookingsdata['b_id']])){
                foreach($bookings_data_driver_alloc[$bookingsdata['b_id']] as $bookingsdata_allocated){
                    if($bookingsdata_allocated['status'] == '0' || $bookingsdata_allocated['status'] == '1'){ //driver allocated booking pending acceptance or has accepted
                        $driver_pending_accept = 1;            
                    }

                    if($bookingsdata_allocated['status'] == '2' || $bookingsdata_allocated['status'] == '3'){ //driver previously didn't accept allocated booking
                        $drivers_cancelled_allocate[] = $bookingsdata_allocated['driver_id']; //save the driver id 
                    }
                }
            }

            //error_log("Drivers alloc: " .print_r($bookings_data_driver_alloc,1));

            //error_log("Drivers accept: " .print_r($driver_pending_accept,1));

            //error_log("Drivers cancelled: " .print_r($drivers_cancelled_allocate,1));

            if($driver_pending_accept)continue; //process next booking

            $drivers_distance_array = [];

            //calculate distance of all available driver from this booking pickup location while ignoring drivers that cancelled allocation earlier
            foreach($drivers_location_data as $driverslocationdata){
                
                if($bookingsdata['route_id'] != $driverslocationdata['route_id'] && $bookingsdata['pickup_city_id'] != $driverslocationdata['route_id'])continue; //skip drivers are not in the same city of this booking
                if($bookingsdata['ride_id'] != $driverslocationdata['ride_id'])continue; //skip drivers whose car does not match booking car
                if(in_array($driverslocationdata['driver_id'],$drivers_cancelled_allocate))continue; //skip drivers who ignored or cancelled the allocation
                
                $booking_pickup_long = !empty($bookingsdata['p_long']) ? $bookingsdata['p_long'] : 0;
                $booking_pickup_lat = !empty($bookingsdata['p_long']) ? $bookingsdata['p_lat'] : 0;

                $driver_location_long = !empty($driverslocationdata['long']) ? $driverslocationdata['long'] : 0;
                $driver_location_lat = !empty($driverslocationdata['lat']) ? $driverslocationdata['lat'] : 0;

                $distance = distance($booking_pickup_lat,$booking_pickup_long,$driver_location_lat,$driver_location_long);

                $drivers_distance_array[$driverslocationdata['driver_id']] = $distance;

                //echo "Distance = ".$distance . " Driver ID = " . $driverslocationdata['driver_id'] . " Booking ID = ".$bookingsdata['b_id'];                   
                

            }

            
            asort($drivers_distance_array); //sort distance of drivers from this pick-up coordinate in ascending order

                     
            $driver_found = 0;
            $number_of_drivers_to_allocate = 0;
            foreach($drivers_distance_array as $driver_id=>$driver_distance){                                      

                    $number_of_drivers_to_allocate ++;

                    if($number_of_drivers_to_allocate > MAX_DRIVERS_ALLOCATE)break; //Allocate booking to this maximum number of drivers at one time

                    
                    if(!empty($driver_id) && $driver_distance <= MAX_DRIVER_DISTANCE){

                        if(empty($bookingsdata['pickup_city_id'])){ //for long distance or inter state trips allocate trip request to any diver in the pickup city irrespective of proximity to rider
                            if($driver_distance > MAX_DRIVER_DISTANCE)continue;
                        }

                        $driver_found = 1;

                        //allocate driver to booking
                        $query = sprintf('INSERT INTO %stbl_driver_allocate(booking_id,driver_id,`status`,date_allocated) VALUES 
                        ("%d","%d","%d","%s")', 
                        DB_TBL_PREFIX,
                        $bookingsdata['b_id'],
                        $driver_id,
                        0,
                        gmdate('Y-m-d H:i:s', time())
                        ); 
                        $result = mysqli_query($GLOBALS['DB'], $query);

                        $closest_driver_data = [];
                        
                        //$photo = explode('/',$bookingsdata['user_photo']);
                        $photo_file = isset($bookingsdata['user_photo']) ? $bookingsdata['user_photo'] : "0";
                        $data = array(
                                        "action"=>"driver-allocate",
                                        "booking_id" => $bookingsdata['b_id'],
                                        "p_address" => $bookingsdata['p_address'],
                                        "p_lat" => $bookingsdata['p_lat'],
                                        "p_lng" => $bookingsdata['p_lng'],
                                        "d_address" => $bookingsdata['d_address'],
                                        "d_lat" => $bookingsdata['d_lat'],
                                        "d_lng" => $bookingsdata['d_lng'],
                                        "rider_image"=> SITE_URL . "ajaxuserphotofile.php?file=". $photo_file,
                                        "rider_name"=>$bookingsdata['user_firstname'],
                                        "rider_phone"=>$bookingsdata['user_phone'],
                                        'rider_rating'=>$bookingsdata['user_rating'],
                                        "completion_code"=>$bookingsdata['completion_code'],
                                        "driver_accept_duration" => DRIVER_ALLOCATE_ACCEPT_DURATION,
                                        "sent_time"=>time(),
                                        "fare"=>$bookingsdata['estimated_cost'],
                                        "payment_type" => $bookingsdata['payment_type'],
                                        "coupon_code"=> $bookingsdata['coupon_code'],
                                        "coupon_discount_type"=> $bookingsdata['coupon_discount_type'],
                                        "coupon_discount_value"=> $bookingsdata['coupon_discount_value'],
                                        "coupon_min_fare" => $bookingsdata['coupon_min_fare'],
                                        "coupon_max_discount" => $bookingsdata['coupon_max_discount'],
                                        "referral_discount_value"=>$bookingsdata['referral_discount_value'],
                                        "referral_used"=>$bookingsdata['referral_used'],
                                        "waypoint1_address" => $bookingsdata['waypoint1_address'],
                                        "waypoint1_long" => $bookingsdata['waypoint1_long'],
                                        "waypoint1_lat" => $bookingsdata['waypoint1_lat'],
                                        "waypoint2_address" => $bookingsdata['waypoint2_address'],
                                        "waypoint2_long" => $bookingsdata['waypoint2_long'],
                                        "waypoint2_lat" => $bookingsdata['waypoint2_lat']
                                    );
                        
                        //notify the driver via push notification
                        $booking_title = str_pad($bookingsdata['b_id'] , 5, '0', STR_PAD_LEFT);
                        $title = WEBSITE_NAME . " - " . ___("New Booking",[],$drivers_location_data[$driver_id]['disp_lang']);
                        $body = ___("You have a new booking with ID({---1}). Please respond to this booking immediately as your customer is waiting.",["{$booking_title}"],$drivers_location_data[$driver_id]['disp_lang']);
                        $device_tokens = !empty($drivers_location_data[$driver_id]['push_notification_token']) ? $drivers_location_data[$driver_id]['push_notification_token'] : 0;
                        
                        $ext_msg_data = array(
                            'mode' => 2,
                            'payload' => $data
                        );            
                        
                        if(!empty($device_tokens)){
                            //sendPushNotification($title,$body,$device_tokens,NULL,0,1);
                            sendPushNotification($title,$body,$device_tokens,null,json_encode($ext_msg_data),1,null);
                        }

                        //silent notification
                        $title = "";
                        $body = "";
                        
                        $device_tokens = !empty($drivers_location_data[$driver_id]['push_notification_token']) ? $drivers_location_data[$driver_id]['push_notification_token'] : 0;
                        if(!empty($device_tokens)){
                            sendPushNotification($title,$body,$device_tokens,$data,0);                            
                        }

                        //send through realtime notification
                        sendRealTimeNotification('drvr-' . $driver_id, $data);

                        /* //add to driver DB notification
                        $content = "You have a new booking with ID({$booking_title}). Please respond to this booking immediately as your customer is waiting.";
                        $query = sprintf('INSERT INTO %stbl_notifications(person_id,user_type,content,n_type,date_created) VALUES 
                        ("%d",1,"%s",0,"%s")', 
                        DB_TBL_PREFIX,
                        $driver_id,
                        mysqli_real_escape_string($GLOBALS['DB'],$content),
                        gmdate('Y-m-d H:i:s', time()) 
                        ); 
                        $result = mysqli_query($GLOBALS['DB'], $query); */

                        

                        //remove driver location record from array
                        unset($drivers_location_data[$driver_id]);

                    }
                    
            }
                    
            if(!$driver_found && strtotime($bookingsdata['pickup_datetime'] . ' UTC') < time() - BOOKING_PICKUP_TIME_AUTO_CANCEL){ //no driver within range of booking pickup location

                        //update booking. Cancel the booking. customer should try again later
                        $query = sprintf('UPDATE %stbl_bookings SET `status` = 5 WHERE id = "%d"', DB_TBL_PREFIX,$bookingsdata['b_id']);
                        $result = mysqli_query($GLOBALS['DB'], $query);

                        //delete all allocated drivers for this booking
                        $query = sprintf('DELETE FROM %stbl_driver_allocate WHERE booking_id = "%d"', DB_TBL_PREFIX,$bookingsdata['b_id']); 
                        $result = mysqli_query($GLOBALS['DB'], $query);
                                               

                        //notify the user via push notification
                        $booking_title = str_pad($bookingsdata['b_id'] , 5, '0', STR_PAD_LEFT);
                        $title = WEBSITE_NAME . " - " . ___("Booking Cancelled",[],$bookingsdata['u_lang']);
                        $body = ___("We couldn't locate a driver close to your set pickup location for booking ID({---1}). Please try again later.",["{$booking_title}"],$bookingsdata['u_lang']);
                        $device_tokens = $bookingsdata['token'];
                        if(!empty($device_tokens)){
                            sendPushNotification($title,$body,$device_tokens,NULL,0);
                        } 


                        //notify the user via silent push notification
                        $booking_title = str_pad($bookingsdata['b_id'] , 5, '0', STR_PAD_LEFT);
                        $title = "";
                        $body = "";
                        $device_tokens = $bookingsdata['token'];
                        $data = array(
                            "action"=>"app-message",
                            "no_driver" => 1,
                            "booking_id"=>$bookingsdata['b_id'],
                            "title"=>"",
                            "message"=> ___("Your Booking with booking ID({---1}) has been cancelled. No driver is available within your set pickup location. Please try again later.",["{$booking_title}"],$bookingsdata['u_lang'])
                        );
                        if(!empty($device_tokens)){
                            sendPushNotification($title,$body,$device_tokens,$data,0);
                        }

                        
                        //notify user through realtime notification
                        sendRealTimeNotification('ridr-' . $bookingsdata['uid'], $data);
                        

                        //add to user DB notification
                        /* $content = ___("Your Booking with booking ID({---1}) has been cancelled. No driver is available within your set pickup location. Please try again later.",["{$booking_title}"],$bookingsdata['u_lang']);
                        $query = sprintf('INSERT INTO %stbl_notifications(person_id,user_type,content,n_type,date_created) VALUES 
                        ("%d",0,"%s",0,"%s")', 
                        DB_TBL_PREFIX,
                        $bookingsdata['uid'],
                        mysqli_real_escape_string($GLOBALS['DB'],$content),
                        gmdate('Y-m-d H:i:s', time()) 
                        ); 
                        $result = mysqli_query($GLOBALS['DB'], $query); */
                    
                        

            }




        }


        //clean up
        unset(
            $body,
            $booking_pickup_lat,
            $booking_pickup_long,
            $booking_title,
            $bookings_data,
            $bookings_data_driver_alloc,
            $bookings_data_routes,
            $bookings_pickuptime_lower_limit,
            $bookings_pickuptime_upper_limit,
            $bookingsdata,
            $bookingsdata_allocated,
            $closest_driver_data,
            $content,
            $cron_file_run,
            $data,
            $device_tokens,
            $distance,
            $driver_distance,
            $driver_found,
            $driver_id,
            $driver_location_lat,
            $driver_location_long,
            $driver_pending_accept,
            $drivers_cancelled_allocate,
            $drivers_distance_array,
            $drivers_location_data,
            $driverslocationdata,
            $elapsed_allocate_time,
            $file,
            $location_info_age,
            $number_of_drivers_to_allocate,
            $query,
            $result,
            $route_ids_string,
            $row,
            $title
        );
		
		//mysqli_close($GLOBALS['DB']);
		//$thread_id = mysqli_thread_id($GLOBALS['DB']);
        //mysqli_kill($GLOBALS['DB'], $thread_id);
        
		$elapsed_exec_time = microtime(true) - $cron_exec_time;
		$elapsed_exec_time_array[] = $elapsed_exec_time;
		$data_count++;
		if($data_count > 2){
        	$data_count = 0;
            $average_exec_time = array_sum($elapsed_exec_time_array) / 3;
            $elapsed_exec_time_array = [];
        }
			
		$data = array('elapsed_exec'=>$elapsed_exec_time,'avg_time'=>$average_exec_time,'last_updated'=>time());
		/*file_put_contents(realpath('/home/droptaxi/public_html/') . '/cron_exec_stats.php','<?php $cron_exec_stats = ' . var_export($data, true) . " ?>");*/
		
        sleep(SLEEP_TIME); //run at atleast every 10 seconds interval
        
       

}





fclose ( $file );
exit;


?>