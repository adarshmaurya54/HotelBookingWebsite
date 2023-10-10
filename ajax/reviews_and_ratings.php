<?php
require("../inc/essentials.php");
require("../admin/inc/db_config.php");
session_start();
if(isset($_POST['review_and_ratings'])){
    $frm = filteration($_POST);
    // updating review and rating value in booking order table by 1
    $upd = "UPDATE `booking_order` SET `review_and_rating`=? WHERE `booking_id`=? AND `user_id`=?";
    $val = [1,$frm['booking_id'],$_SESSION['uId']];
    if(update($upd,$val,"iii")){
        $ins_q = "INSERT INTO `reviews_and_ratings`(`booking_id`, `user_id`, `room_id`, `rating`, `review`) VALUES (?,?,?,?,?)";
        $values = [$frm['booking_id'],$_SESSION['uId'],$frm['room_id'],$frm['ratings'],$frm['review']];
        if(insert($ins_q,$values,"iiiis")){
            echo 1;
        }else{
            echo 0;
        }
    }else{
        echo 0;
    }
}