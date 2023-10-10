<?php

require("../inc/db_config.php");
require("../inc/essentials.php");
isAdminLogin();

if (isset($_GET['NewBookings_Refund_queries_and_review_count'])) {
    $res = $conn->query("SELECT COUNT(CASE WHEN `booking_status`='booked' AND `arrival`=0 THEN 1 END) AS `New_Bookings`,
    COUNT(CASE WHEN `booking_status`='cancelled' AND `refund`=0 THEN 1 END) AS `refund` FROM `booking_order`");
    $res_assoc = mysqli_fetch_assoc($res);
    $res2 = $conn->query("SELECT COUNT(id) AS `queries` FROM `user_queries` WHERE `seen`=0");
    $res2_assoc = mysqli_fetch_assoc($res2);
    $res3 = $conn->query("SELECT COUNT(id) AS `reviews` FROM `reviews_and_ratings` WHERE `seen`=0");
    $res3_assoc = mysqli_fetch_assoc($res3);
    echo json_encode(["data" => [$res_assoc['New_Bookings'], $res_assoc['refund'], $res2_assoc['queries'], $res3_assoc['reviews']]]);
} else if (isset($_GET['getUsers'])) {
    $q = $conn->query("SELECT COUNT(id) AS `total`,
    COUNT(CASE WHEN `status`=1 THEN 1 END) AS `active`,
    COUNT(CASE WHEN `status`=0 THEN 1 END) AS `inactive`,
    COUNT(CASE WHEN `is_verified`=0 THEN 1 END) AS `unverified`
     FROM `user_cred`");
    $res = mysqli_fetch_assoc($q);
    echo json_encode(["data" => [$res['total'], $res['active'], $res['inactive'], $res['unverified']]]);
} else if (isset($_POST['bookingsAnalytics'])) {
    $frm = filteration($_POST);

    $condition = "";
    if ($frm['period'] == 1) {
        $condition = "WHERE `date_time` BETWEEN NOW() - INTERVAL 7 DAY AND NOW()";
    } else if ($frm['period'] == 2) {
        $condition = "WHERE `date_time` BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    } else if ($frm['period'] == 3) {
        $condition = "WHERE `date_time` BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    }


    $q = $conn->query("SELECT 
    COUNT(CASE WHEN `booking_status` != 'pending' AND `booking_status`!='failed' THEN 1 END) AS `total_bookings`,
    SUM(CASE WHEN `booking_status` != 'pending' AND `booking_status`!='failed' THEN `trans_amount` END) AS `total_amt`,

    COUNT(CASE WHEN `booking_status`='booked' AND `arrival`=1 THEN 1 END) AS `active_bookings`,
    SUM(CASE WHEN `booking_status`='booked' AND `arrival`=1 THEN `trans_amount` END) AS `active_amt`,
    
    COUNT(CASE WHEN `booking_status`='cancelled' AND `refund`=1 THEN 1 END) AS `cancel_bookings`,
    SUM(CASE WHEN `booking_status`='cancelled' AND `refund`=1 THEN `trans_amount` END) AS `cancel_amt`
     FROM `booking_order` $condition");

    $res = mysqli_fetch_assoc($q);

    echo json_encode($res);
} else if (isset($_POST['otherAnalytics'])) {
    $frm = filteration($_POST);

    $condition = "";
    if ($frm['period'] == 1) {
        $condition = "WHERE `date` BETWEEN NOW() - INTERVAL 7 DAY AND NOW()";
    } else if ($frm['period'] == 2) {
        $condition = "WHERE `date` BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    } else if ($frm['period'] == 3) {
        $condition = "WHERE `date` BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    }

    $new_regist = $conn->query("SELECT COUNT(id) AS `new_reg` FROM `user_cred` $condition");
    $new_regist_assoc = mysqli_fetch_assoc($new_regist);

    $user_queries = $conn->query("SELECT COUNT(id) AS `user_queries` FROM `user_queries` $condition");
    $user_queries_assoc = mysqli_fetch_assoc($user_queries);

    $reviews = $conn->query("SELECT COUNT(id) AS `reviews` FROM `reviews_and_ratings` $condition");
    $reviews_assoc = mysqli_fetch_assoc($reviews);

    echo json_encode(["new_reg"=>$new_regist_assoc['new_reg'], "user_queries"=>$user_queries_assoc['user_queries'], "reviews"=>$reviews_assoc['reviews']]);
}
