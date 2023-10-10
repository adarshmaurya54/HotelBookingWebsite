<?php
require("../admin/inc/db_config.php");
require("../admin/inc/essentials.php");

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect("./index.php");
}else if(isset($_POST["cancel_booking"])){
    $frm = filteration($_POST);
    $q = "UPDATE `booking_order` SET `booking_status`=?, `refund`=? WHERE `booking_id`=? AND `user_id`=?";
    $value = ["cancelled",0,$frm['booking_id'],$_SESSION['uId']];
    $res = update($q,$value,"siii");
    $token = bin2hex(random_bytes(16));
    echo json_encode(["res"=>$res,"token"=>$token]);
}