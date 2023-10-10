<?php
require('config.php');
require("../admin/inc/db_config.php");
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
session_start();

function recreate_session($uId) // function to recreate session for user
{
    $u_q = "SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1";
    $val = [$uId];
    $res = select($u_q, $val, 'i');
    $res_assoc = mysqli_fetch_assoc($res);

    $_SESSION['login'] = true;
    $_SESSION['uId'] = $res_assoc['id'];
    $_SESSION['uName'] = $res_assoc['name'];
    $_SESSION['uPic'] = $res_assoc['profile'];
    $_SESSION['uMobile'] = $res_assoc['phone'];
    $_SESSION['uEmail'] = $res_assoc['email'];

}


$amt = $_SESSION['room_booking']['payment'];
unset($_SESSION['room_booking']);

require('razorpay-php/Razorpay.php');

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;
$error = "Payment Failed";

// if payment is failed then this if block will executed other wise else block will executed where payment signature will match if signature matched then payment was successful else razorpay error will be store in the databse.
if (isset($_POST['epayment_failed_3423jklsi'])) {
    // getting pending payment details and updating as payment failed
    $selc_q = "SELECT `booking_id`, `user_id` FROM `booking_order` WHERE `order_id`=?";
    $val = [$_SESSION['razorpay_order_id']];
    $res = select($selc_q, $val, "s");

    $res_assoc = mysqli_fetch_assoc($res);

    // if user session is expire then we have to recreate the session for user
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        recreate_session($res_assoc['user_id']);
    }


    $transaction_id = $_POST['erazorpay_payment_id'];
    $payment_status = "TNX_failed";
    $trns_desc = $_POST['error_desc'];
    $amount = $amt;

    $upd_q = "UPDATE `booking_order` SET `booking_status`=?,`trans_id`=?,`trans_amount`=?,`trans_status`=?,`trans_resp_msg`=? WHERE `booking_id`=?";
    $upd_val = ['failed', $transaction_id, $amount, $payment_status, $trns_desc, $res_assoc['booking_id']];
    update($upd_q, $upd_val, "ssissi");

    echo json_encode([
        "msg" => "e1",
        "order_id" => $_POST['erazorpay_order_id']
    ]);
} else if (empty($_POST['razorpay_payment_id']) === false) {
    $api = new Api($keyId, $keySecret);

    try {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    } catch (SignatureVerificationError $e) {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }


    $selc_q = "SELECT `booking_id`, `user_id` FROM `booking_order` WHERE `order_id`=?";
    $val = [$_SESSION['razorpay_order_id']];
    $res = select($selc_q, $val, "s");
    $res_assoc = mysqli_fetch_assoc($res);
    // if user session is expire then we have to recreate the session for user
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        recreate_session($res_assoc['user_id']);
    }


    if ($success === true) {
        $transaction_id = $_POST['razorpay_payment_id'];
        $payment_status = "TNX_successful";
        $trns_desc = "Transaction successful";
        $amount = $_POST['amount'];

        $upd_q = "UPDATE `booking_order` SET `booking_status`=?,`trans_id`=?,`trans_amount`=?,`trans_status`=?,`trans_resp_msg`=? WHERE `booking_id`=?";
        $upd_val = ['booked', $transaction_id, $amount, $payment_status, $trns_desc, $res_assoc['booking_id']];
        update($upd_q, $upd_val, "ssissi");

        echo json_encode([
            "msg" => "s1",
            "order_id" => $_SESSION['razorpay_order_id']
        ]);
    } else {
        $transaction_id = $_POST['razorpay_payment_id'];
        $payment_status = "error";
        $trns_desc = $error;
        $amount = $_POST['amount'];

        $upd_q = "UPDATE `booking_order` SET `booking_status`=?,`trans_id`=?,`trans_amount`=?,`trans_status`=?,`trans_resp_msg`=? WHERE `booking_id`=?";
        $upd_val = ['error', $transaction_id, $amount, $payment_status, $trns_desc, $res_assoc['booking_id']];
        update($upd_q, $upd_val, "ssissi");
        echo json_encode([
            "msg" => "e1",
            "order_id" => $_SESSION['razorpay_order_id']
        ]);
    }
}
