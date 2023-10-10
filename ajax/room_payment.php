<?php

require("../admin/inc/db_config.php");
require("../inc/essentials.php");
session_start();
date_default_timezone_set('Asia/Kolkata');

if(isset($_POST['checkavailablility'])){
    $frm  =  filteration($_POST);
    $status = "";
    $data = "";

    $today_date = new DateTime(date("Y-m-d"));
    $checkin = new DateTime($frm['checkin']);
    $checkout = new DateTime($frm['checkout']);
    $data = json_encode(["status" => $status]);
    if($checkin == $checkout){
        $status = "samedate";
        $data = json_encode(["status" => $status]);
    }else if($checkout < $checkin){
        $status = "outearlier";
        $data = json_encode(["status" => $status]);
    }else if($checkin < $today_date){
        $status = "inearlier";
        $data = json_encode(["status" => $status]);
    }
    
    if($status !== ''){
        echo $data;
    } else{

        // Query for checking room availablity...
        $q = "SELECT count(*) AS `total_bookings` FROM `booking_order` WHERE `booking_status`=? 
        AND `room_id` = ? AND `check_out` > ? AND `check_in` < ?";
        $value = ["booked",$_SESSION['room_booking']['id'],$frm['checkin'],$frm['checkout']];
        $total_booked_room_res = select($q,$value,"siss");
        $total_booked_room = mysqli_fetch_assoc($total_booked_room_res);
        
        $room_quantity_res = select("SELECT `quantity` FROM `rooms` WHERE `id`=?",[$_SESSION['room_booking']['id']],'i');
        $room_quantity = mysqli_fetch_assoc($room_quantity_res);

        if(($room_quantity['quantity'] - $total_booked_room['total_bookings']) <= 0){
            $status = "unavailable";
            $data = json_encode(["status" => $status]);
            echo $data;
            exit;
        }


        $total_days = date_diff($checkin,$checkout)->days;
        $payment = $total_days * $_SESSION['room_booking']['price'];
        $_SESSION['room_booking']['payment'] = $payment;
        $_SESSION['room_booking']['available'] = true;
        $data = json_encode(['status'=>"available","days"=>$total_days,"amount"=>$payment]);
        echo $data;
    }
}