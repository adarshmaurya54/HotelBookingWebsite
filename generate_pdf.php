<?php

require("./admin/inc/db_config.php");
require("./admin/inc/essentials.php");
require("./admin/inc/mpdf/vendor/autoload.php");
session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect("./index.php");
}else if (!(isset($_GET['gen_pdf']) && $_GET['id'] != '') || !(is_numeric($_GET['id']) && intval($_GET['id']) == $_GET['id'])) { #checking if id index is present in $_get variable and value is an integer value...
    redirect("./index.php");
} else {
    $general_q = "SELECT * FROM `general_settings` WHERE `id`=?";
    $value = [1];
    $general_res = select($general_q, $value, "i");
    $general_assoc = mysqli_fetch_assoc($general_res);
    $frm = filteration($_GET);
    $q = "SELECT bo.*,bd.*,uc.email FROM `booking_order` bo 
    INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
    INNER JOIN `user_cred` uc ON uc.id = bo.user_id
     WHERE ((bo.booking_status='booked' AND bo.arrival=1)
        OR (bo.booking_status='cancelled' AND bo.refund=1)
        OR (bo.booking_status='failed')) AND bo.booking_id=$frm[id]";

    $q_res = $conn->query($q);
    if ($q_res->num_rows == 0) {
        redirect("./index.php");
    } else {
        $q_assoc = mysqli_fetch_assoc($q_res);
        $checkin = date("d-m-Y", strtotime($q_assoc['check_in']));
        $checkout = date("d-m-Y", strtotime($q_assoc['check_out']));
        $date_time = date("d-m-Y h:m: A", strtotime($q_assoc['date_time']));
        if ($q_assoc['booking_status'] == "booked") {
            $status_msg = "Booked";
        } else if ($q_assoc['booking_status'] == "cancelled") {
            $status_msg = "Cancelled";
        } else if ($q_assoc['booking_status'] == "failed") {
            $status_msg = "Payment Failed";
        }
        $table_data = "
    <!DOCTYPE html>
    <html lang='en'>
        <head>
            <link rel='preconnect' href='https://fonts.googleapis.com'>
            <link rel='stylesheet' href='./css/style.css'>
            <link rel='icon' href='../images/logo.png'>
            <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
            <link href='https://fonts.googleapis.com/css2?family=Merienda:wght@300;400;500;700;900&family=Poppins:wght@100;200;300;400;500;600;700&display=swap' rel='stylesheet'>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>
            <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>
            <style>
                body {
                    font-family: 'Poppins', sans-serif;
                }

                .container {
                    border: 1px solid black;
                    padding: 20px;
                }

                .h-font {
                    font-family: 'Merienda', cursive;
                    font-weight: 400;
                    font-size: 24px;
                    margin: 0;
                }

                h1 {
                    font-size: 32px;
                    margin-bottom: 20px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }

                table, th, td {
                    border: 1px solid #000;
                }
                
                th, td {
                    padding: 10px;
                    text-align: left;
                }
                
                strong {
                    font-weight: bold;
                }
            </style>
        </head>
                
        <body>    
            <div class='container'>
                <div style='position: absolute; top: 10px; left: 10px;'>
                    <img src='" . LOGO . "' width='50px'/>
                    <img src='" . LOGO1 . "' width='100px' style='position: fixed; right: 10px!important;'/>
                </div>
                <p class='text-center h-font'>$general_assoc[site_title]</p>
                <h1 class='text-center mb-5'>BOOKING RECEIPT</h1>
                <table>
                    <tr>
                        <td><strong>Order Id</strong>: $q_assoc[order_id]</td>
                        <td><strong>Booking Date</strong>: $date_time</td>
                    </tr>
                    <tr>
                        <td colspan='2'><strong>Status</strong>: $status_msg</td>
                    </tr>
                    <tr>
                        <td><strong>Name</strong>: $q_assoc[user_name]</td>
                        <td><strong>Email</strong>: $q_assoc[email]</td>
                    </tr>
                    <tr>
                        <td><strong>Phone Number</strong>: $q_assoc[phonenum]</td>
                        <td><strong>Address</strong>: $q_assoc[address]</td>
                    </tr>
                    <tr>
                        <td><strong>Room Name</strong>: $q_assoc[room_name]</td>
                        <td><strong>Price</strong>: ₹$q_assoc[price].00 per night</td>
                    </tr>
                    <tr>
                        <td><strong>Check-in</strong>: $checkin</td>
                        <td><strong>Check-out</strong>: $checkout</td>
                    </tr>
                ";
        if ($q_assoc['booking_status'] == "booked") {
            $table_data .= "
                    <tr>
                        <td><strong>Room Number</strong> : $q_assoc[room_no]</td>
                        <td><strong>Amount Paid</strong> : ₹$q_assoc[trans_amount].00</td>
                    </tr>
                    ";
        } else if ($q_assoc['booking_status'] == "cancelled") {
            $refund = ($q_assoc['refund'] == 1) ? "Money Refunded" : "Not yet refunded";
            $table_data .= "
                    <tr>
                        <td><strong>Amount</strong> : ₹$q_assoc[trans_amount].00</td>
                        <td><strong>Refund</strong> : $refund</td>
                    </tr>
                    ";
        } else if ($q_assoc['booking_status'] == "failed") {
            $table_data .= "
                    <tr>
                        <td><strong>Transaction Amount</strong> : ₹$q_assoc[trans_amount].00</td>
                        <td><strong>Failure Message</strong> : $q_assoc[trans_resp_msg]</td>
                    </tr>
                    ";
        }
        $table_data .= "
                </table>
            </div>
        </body>
    </html>
        ";
        // Create an instance of the class:
        $mpdf = new \Mpdf\Mpdf();

        // Write some HTML code:
        $mpdf->WriteHTML($table_data);

        // Output a PDF file directly to the browser
        $mpdf->Output('PDF_' . $q_assoc['order_id'] . '.pdf', "D");
    }
}
