<?php
session_start();

require('config.php');
require("../admin/inc/db_config.php");
require('razorpay-php/Razorpay.php');
header("Pragma: no-cache");
header("Cache-Controle: no-cache");
header("Expires: 0");

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect("../rooms.php");
}
// Create the Razorpay Order

use Razorpay\Api\Api;

if (isset($_POST['room_bookings']) && $_POST['room_bookings'] == true) {
    $frm = filteration($_POST);
    $api = new Api($keyId, $keySecret);

    //
    // We create an razorpay order using orders api
    // Docs: https://docs.razorpay.com/docs/orders
    //
    $orderData = [
        'receipt'         => 'RZR_' . time(),
        'amount'          => $_SESSION['room_booking']['payment'] * 100, // 2000 rupees in paise
        'currency'        => 'INR',
        'payment_capture' => 1
    ];

    $razorpayOrder = $api->order->create($orderData);

    $razorpayOrderId = $razorpayOrder['id'];

    $_SESSION['razorpay_order_id'] = $razorpayOrderId;

    $displayAmount = $amount = $orderData['amount'];

    if ($displayCurrency !== 'INR') {
        $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
        $exchange = json_decode(file_get_contents($url), true);

        $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
    }


    $img_path = LOGO;
    $data = [
        "key"               => $keyId,
        "amount"            => $amount,
        "name"              => "HB Website",
        "description"       => "Hotel booking",
        "image"             => "$img_path",
        "prefill"           => [
            "name"              => "$frm[name]",
            "email"             => "$_SESSION[uEmail]",
            "contact"           => "$frm[phone]",
        ],
        "notes"             => [
            "address"           => "Hello World",
            "merchant_order_id" => $razorpayOrderId,
        ],
        "theme"             => [
            "color"             => "#2ec1ac"
        ],
        "order_id"          => $razorpayOrderId,
    ];

    if ($displayCurrency !== 'INR') {
        $data['display_currency']  = $displayCurrency;
        $data['display_amount']    = $displayAmount;
    }

    $json = json_encode($data);

    // // inserting data to the database as pending
    $q1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`, 
    `order_id`) 
    VALUES (?,?,?,?,?)";

    $value = [
        $_SESSION['uId'], $_SESSION['room_booking']['id'],
        $frm['checkin'], $frm['checkout'], $_SESSION['razorpay_order_id']
    ];
    insert($q1, $value, "iisss");

    $booking_id = mysqli_insert_id($conn);
    $q2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`, `user_name`, `phonenum`, `address`)
     VALUES (?,?,?,?,?,?,?)";
    $value2 = [
        $booking_id, $_SESSION['room_booking']['name'], $_SESSION['room_booking']['price'],
        $_SESSION['room_booking']['payment'], $frm['name'], $frm['phone'], $frm['address']
    ];
    insert($q2, $value2, "isiisss");

    echo $json;
}
