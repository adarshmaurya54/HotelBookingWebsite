<?php
require("../admin/inc/essentials.php");
$keyId = RAZORPAY_KEY_ID;
$keySecret = RAZORPAY_KEY_SECRET;
$displayCurrency = 'INR';

//These should be commented out in production
// This is for error reporting
// Add it to config.php to report any errors
// error_reporting(E_ALL);
ini_set('display_errors', 1);
