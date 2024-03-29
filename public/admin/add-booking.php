<?php
session_start();
include("../../drop-files/lib/common.php");
include "../../drop-files/config/db.php";
$rides_array = array();
$route_data = [];
$new_customer_booking = [];
$id = 0;

if(isset($_SESSION['expired_session'])){
    header("location: ".SITE_URL."login.php?timeout=1");
    exit;
}

if(!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1)){ //if user is not logged in run this code
  header("location: ".SITE_URL."login.php"); //Yes? then redirect user to the login page
  exit;
}

if($_SESSION['account_type'] != 2 && $_SESSION['account_type'] != 3){ ////if user is an admin or dispatcher
    $_SESSION['action_error'][] = "Access Denied!";
    header("location: ".SITE_URL."admin/index.php"); //Yes? then redirect user to the login page
    exit;
}

$GLOBALS['admin_template']['page_title'] = "<i class='fa fa-bookmark'></i> New Booking"; //Set the title of the page on the admin interface
$GLOBALS['admin_template']['active_menu'] = "bookings"; //Set the appropriate menu item active
$GLOBALS['admin_template']['active_sub_menu'] = "booking-new"; //Set the appropriate menu item active


if(!empty($_GET['customer'])){

    $customerid = (int) $_GET['customer'];

    //get customer details

    $query = sprintf('SELECT firstname,lastname, country_dial_code,phone,route_id FROM %stbl_users WHERE user_id ="%s"', DB_TBL_PREFIX, $customerid);



    if($result = mysqli_query($GLOBALS['DB'], $query)){
        if(mysqli_num_rows($result)){
            $row = mysqli_fetch_assoc($result);
            $new_customer_booking['label'] = $row['firstname'] . " " . $row['lastname'] . " (" . $row['country_dial_code'] . $row['phone'] . ")";
            $new_customer_booking['value'] = $customerid;  
            $new_customer_booking['route_id'] = $row['route_id'];  
        }else{
            $_SESSION['action_error'][] = "Invalid user ID";
            header("location: ".SITE_URL."admin/index.php"); //Yes? then redirect user to the login page
            exit;
        }
    }else{
        $_SESSION['action_error'][] = "Invalid user ID";
        header("location: ".SITE_URL."admin/index.php"); //Yes? then redirect user to the login page
        exit;
    }

}




//Get all route tariff
$query = sprintf('SELECT *, %1$stbl_routes.id AS r_id FROM %1$stbl_routes
INNER JOIN %1$stbl_currencies ON %1$stbl_currencies.id = %1$stbl_routes.city_currency_id', DB_TBL_PREFIX); //Get required user information from DB


if($result = mysqli_query($GLOBALS['DB'], $query)){
    if(mysqli_num_rows($result)){
        while($row = mysqli_fetch_assoc($result)){
            $route_data[$row['r_id']] = $row;
        }
                         
    }    
}








if(empty($_POST)){ //let's render the add-new-ride page UI'
    
    ob_start();
    include('../../drop-files/templates/admin/addbookingtpl.php');
    $GLOBALS['admin_template']['page_content'] = ob_get_clean();
    include "../../drop-files/templates/admin/admin-interface.php";
    exit;


}

/* var_dump($_POST);
exit; */

if(DEMO){
    $_SESSION['action_error'][] = "You are running in Demo mode. Action cannot be completed";
    header("location: ".SITE_URL."admin/all-bookings.php"); //Yes? then redirect 
    exit;
}


if($_POST['zonetypeoption'] == "city" && (empty($_POST['pcity-zone-long']) || empty($_POST['pcity-zone-lat']) || empty($_POST['dcity-zone-lat']) || empty($_POST['dcity-zone-lat']))){

    $_SESSION['action_error'][]    = "Coordinates of the selected route was not found. Please use google map suggestions";
    
}


if($_POST['zonetypeoption'] == "state" && (empty($_POST['pcz-long']) || empty($_POST['pcz-lat']) || empty($_POST['dcz-long']) || empty($_POST['dcz-lat']))){

    $_SESSION['action_error'][]    = "Coordinates of the selected route was not found. Please use google map suggestions";
    
}

if($_POST['zonetypeoption'] == "city"){

    if(empty($_POST['cbooking-price'])){
        $_SESSION['action_error'][]    = "Please enter a price for this booking";
    
    }

    if(empty($_POST['pcity-zone']) || empty($_POST['dcity-zone'])){
        $_SESSION['action_error'][]    = "Pick-up and Drop-off locations must be set";
    
    }


}else{

    if(empty($_POST['booking-price'])){
        $_SESSION['action_error'][]    = "Please enter a price for this booking";
    
    }
    
    
    if(empty($_POST['booking-customerid']) || empty($_POST['booking-customer'])){
        $_SESSION['action_error'][]    = "Please select a customer from the autocomplete popup menu";
    
    }  
    
    



}

if(isset($_POST['schedule-bk'])){
    if(strtotime($_POST['date'] . " " . $_POST['time']) < (time() + 600)){
        $_SESSION['action_error'][]    = "Scheduled ride must be atleast 10 minutes ahead";
    }
}




if(!empty($_SESSION['action_error'])){
   
    ob_start();
            include('../../drop-files/templates/admin/addbookingtpl.php'); 
                $msgs = '';
                foreach($_SESSION['action_error'] as $action_error){
                    $msgs .= "<p style='text-align:left;'><i style='color:red;' class='fa fa-circle-o'></i> ".$action_error . "</p>";
                }

                $cache_prevent = RAND();
                echo"<script>
            setTimeout(function(){ 
                    jQuery( function(){
                    swal({
                        title: '<h1>Error</h1>'".',
            text:"'.$msgs .'",'.
            "imageUrl: '../img/info_.gif?a=" . $cache_prevent . "',
            html:true,
                    });
                    });
                    },500); 
                    
                    </script>";
            
                    unset($_SESSION['action_error']);
            
        


        $pageContent = ob_get_clean();
        $GLOBALS['admin_template']['page_content'] = $pageContent;
        include "../../drop-files/templates/admin/admin-interface.php";
        exit;




}







$paddr = '';
$daddr = '';
$plng = '';
$plat = '';
$dlng = '';
$dlat = '';
$bprice = '';
$route_id = 0;
$ride_id = 0;
$customer_id = 0;
$driver_id = 0;
$datetime = '';
$payment_type = 0;

$customer_data = [];
$driver_data = [];


$query = sprintf('SELECT * FROM %1$stbl_users WHERE %1$stbl_users.user_id = "%2$d"', DB_TBL_PREFIX, $_POST['booking-customerid']);

if($result = mysqli_query($GLOBALS['DB'], $query)){
    if(mysqli_num_rows($result)){

       $customer_data = mysqli_fetch_assoc($result);

    }
}


$completion_code = crypto_string("123456789",4);

$customer_firstname = !empty($customer_data['firstname']) ? $customer_data['firstname'] : '';
$customer_lastname = !empty($customer_data['lastname']) ? $customer_data['lastname'] : '';
$customer_phone = !empty($customer_data['phone']) ? $customer_data['country_dial_code']. $customer_data['phone'] : '';

$scheduled = isset($_POST['schedule-bk']) ? 1 : 0;

if($scheduled){
    $dispatch_mode = 0;
}else{
    $dispatch_mode = 1;
}

if($_POST['zonetypeoption'] == "city"){
    $paddr = $_POST['pcity-zone'];
    $daddr = $_POST['dcity-zone'];
    $plng = $_POST['pcity-zone-long'];
    $plat = $_POST['pcity-zone-lat'];
    $dlng = $_POST['dcity-zone-long'];
    $dlat = $_POST['dcity-zone-lat'];
    $bprice = $_POST['cbooking-price'];
    $route_id = (int) $_POST['route-city'];
    $ride_id = (int) $_POST['ride-type'];
    $customer_id = (int) $_POST['booking-customerid'];
    $datetime = !empty($scheduled) ? gmdate('Y-m-d H:i:s',strtotime($_POST['date'] . " " . $_POST['time'])) : gmdate('Y-m-d H:i:s');
    $payment_type = (int) $_POST['booking-paymethod'];

}else{

    $paddr = $_POST['pcz'];
    $daddr = $_POST['dcz'];
    $plng = $_POST['pcz-long'];
    $plat = $_POST['pcz-lat'];
    $dlng = $_POST['dcz-long'];
    $dlat = $_POST['dcz-lat'];
    $bprice = $_POST['booking-price'];
    $route_id = (int) $_POST['route-state'];
    $ride_id = (int) $_POST['sride-type'];
    $customer_id = (int) $_POST['booking-customerid'];
    $datetime = !empty($scheduled) ? gmdate('Y-m-d H:i:s',strtotime($_POST['date'] . " " . $_POST['time'])) : gmdate('Y-m-d H:i:s');
    $payment_type = $_POST['booking-paymethod'];
}

$route_data_arr = $route_data[$route_id];


$query = sprintf('INSERT INTO %stbl_bookings (dispatch_mode,cur_symbol,cur_exchng_rate,cur_code,completion_code,scheduled,user_firstname,user_lastname,user_phone,user_id, pickup_datetime, pickup_address, pickup_long, pickup_lat, dropoff_address, dropoff_long,dropoff_lat,estimated_cost,route_id,ride_id,payment_type,date_created) VALUES'.
'("%d","%s","%s","%s","%s","%d","%s","%s","%s","%d","%s","%s","%s","%s","%s","%s","%s","%s","%d","%d","%d","%s")', 
DB_TBL_PREFIX,
$dispatch_mode,
$route_data_arr['symbol'],
$route_data_arr['exchng_rate'],
$route_data_arr['iso_code'],
$completion_code,
$scheduled, 
$customer_firstname,
$customer_lastname,
$customer_phone,
$customer_id,
$datetime,
$paddr,
$plng,
$plat,
$daddr,
$dlng,
$dlat,
$bprice,
$route_id,
$ride_id,
$payment_type,
gmdate('Y-m-d H:i:s', time())
);


if(! $result = mysqli_query($GLOBALS['DB'], $query)){
    
    $_SESSION['action_error'][]    = "Error creating new booking in database";
    
}





/* echo mysqli_error($GLOBALS['DB']);
exit; */

if(!empty($_SESSION['action_error'])){
   
    ob_start();
            include('../../drop-files/templates/admin/addbookingtpl.php'); 
                $msgs = '';
                foreach($_SESSION['action_error'] as $action_error){
                    $msgs .= "<p style='text-align:left;'><i style='color:red;' class='fa fa-circle-o'></i> ".$action_error . "</p>";
                }

                $cache_prevent = RAND();
                echo"<script>
            setTimeout(function(){ 
                    jQuery( function(){
                    swal({
                        title: '<h1>Error</h1>'".',
            text:"'.$msgs .'",'.
            "imageUrl: '../img/info_.gif?a=" . $cache_prevent . "',
            html:true,
                    });
                    });
                    },500); 
                    
                    </script>";
            
                    unset($_SESSION['action_error']);
            
        


        $pageContent = ob_get_clean();
        $GLOBALS['admin_template']['page_content'] = $pageContent;
        include "../../drop-files/templates/admin/admin-interface.php";
        exit;




}


   
        $_SESSION['action_success'][] = "The booking was created successfully.";
        header("location: ".SITE_URL."admin/dispatch.php"); //Yes? then redirect
        /* if($scheduled){
            header("location: ".SITE_URL."admin/all-sbookings.php"); //Yes? then redirect
        }else{
            header("location: ".SITE_URL."admin/dispatch.php"); //Yes? then redirect
        } */
        exit;






?>