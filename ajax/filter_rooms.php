<?php
require("../inc/essentials.php");
require("../admin/inc/db_config.php");
session_start();
date_default_timezone_set('Asia/Kolkata');

if (isset($_GET['filter_room'])) {
    $count = 0;
    $rooms = "";
    // converting checking and checkout date from json string to json
    $dates = json_decode($_GET['chk_avail'], true);
    //validating dates
    if ($dates['chkin'] != '' && $dates['chkout'] != '') {
        $today = new DateTime(date("Y-m-d"));
        $chkin = new DateTime($dates['chkin']);
        $chkout  = new DateTime($dates['chkout']);

        $checkin = $chkin->format("Y-m-d");
        $checkout = $chkout->format("Y-m-d");
        $today_date = $today->format("Y-m-d");

        if ($checkout == $checkin) {
            $data = json_encode(["flag" => "same"]);
            echo $data;
            exit;
        } else if ($checkout < $checkin) {
            $data = json_encode(["flag" => "chkouterl"]);
            echo $data;
            exit;
        } else if ($checkin < $today_date) {
            $data = json_encode(["flag" => "chkinerl"]);
            echo $data;
            exit;
        }
    }

    //converting guests in json from json string
    $guests = json_decode($_GET['guests'], true);
    $adult = ($guests['adult'] == '')? 0: $guests['adult']; 
    $children = ($guests['children'] == '')? 0: $guests['children']; 
    
    // converting facilities from json string to json
    $facilities = json_decode($_GET['facilities'],true);

    $room_res = select("SELECT * FROM `rooms` WHERE `adult`>=? AND `children`>=? AND `status`=? AND `removed`=?", [$adult, $children, 1, 0], 'iiii');
    while ($room_data = mysqli_fetch_assoc($room_res)) {
        // checking if given chechin and checkout date is not empty
        if ($dates['chkin'] != '' && $dates['chkout'] != '') {
            $q = "SELECT count(*) AS `total_bookings` FROM `booking_order` WHERE `booking_status`=? 
        AND `room_id` = ? AND `check_out` > ? AND `check_in` < ?";
            $value = ["booked", $room_data['id'], $dates['chkin'], $dates['chkout']];
            $total_booked_room_res = select($q, $value, "siss");
            $total_booked_room = mysqli_fetch_assoc($total_booked_room_res);

            $room_quantity_res = select("SELECT `quantity` FROM `rooms` WHERE `id`=?", [$room_data['id']], 'i');
            $room_quantity = mysqli_fetch_assoc($room_quantity_res);

            if (($room_quantity['quantity'] - $total_booked_room['total_bookings']) <= 0) {
                continue;
            }
        }
            
        // Getting facilities of each room
        $facility_count = 0;
        $facility = "";
        $facility_q = "SELECT f.name,f.id FROM `room_facilities` rf INNER JOIN `facilities` f ON rf.facilities_id = f.id WHERE rf.room_id = ?";
        $f2_res = select($facility_q, [$room_data['id']], 'i');
        while ($f_row = mysqli_fetch_assoc($f2_res)) {
            if(in_array($f_row['id'],$facilities['facilities'])){
                $facility_count ++;
            }
            
            $facility .= "<span class='badge rounded-pill bg-light text-wrap lh-base text-dark mb-1 me-1'>
                                $f_row[name]
                            </span>";
        }

        if(count($facilities['facilities']) != $facility_count){
            continue;
        }

        // Getting feature of each room
        $feature_q = "SELECT f.feature_name FROM `room_features` rf INNER JOIN `features` f ON rf.feature_id = f.id WHERE rf.room_id = ?";
        $f_res = select($feature_q, [$room_data['id']], 'i');
        $feature = "";
        while ($f_row = mysqli_fetch_assoc($f_res)) {
            $feature .= "<span class='badge rounded-pill bg-light text-wrap lh-base text-dark mb-1 me-1'>
                                $f_row[feature_name]
                                </span>";
        }

        //getting thumbnail of room image
        $thum_q = "SELECT * FROM `room_images` WHERE `thumbnail`=? AND `room_id`=?";
        $values = [1, $room_data['id']];
        $thum_res = select($thum_q, $values, 'ii');
        $img = "";
        if ($thum_res->num_rows == 0) {
            $img = ROOMS_IMAGE_FOLDER_PATH . "thumbnail.jpg";
        } else {
            $thum_res_assoc = mysqli_fetch_assoc($thum_res);
            $img = ROOMS_IMAGE_FOLDER_PATH . $thum_res_assoc['room_img'];
        }
        $adult = ($room_data['adult'] > 1) ? "Adults" : "Adult";
        $children = ($room_data['children'] > 1) ? "Childrens" : "Children";
        // getting general data for checking if site is shutdown or not
        $general_q = "SELECT * FROM `general_settings` WHERE `id`=1";
        $general_assoc = mysqli_fetch_assoc($conn->query($general_q));
        if (!$general_assoc['shutdown']) {
            if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
                $booking = "<button onclick='checkLoginToBook(0,$room_data[id])' class='btn btn-sm w-100 text-white shadow-none custom-bg mb-3' data-bs-toggle='modal' data-bs-target='#loginmodal'>Book Now</button>";
            } else {
                $booking = "<button onclick='checkLoginToBook(1,$room_data[id])' class='btn btn-sm w-100 text-white shadow-none custom-bg mb-3'>Book Now</button>";
            }
        } else {
            $booking = "";
        }
        $rooms .= "
                    <div class='card border-0 shadow bg-white p-3 mb-4'>
                        <div class='row g-0 align-items-center'>
                            <div class='col-md-5 mb-lg-0 mb-md-0 mb-4'>
                                <img src='$img' loading='lazy' class='img-fluid rounded'>
                            </div>
                            <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                                <h5 class='mb-3'>$room_data[name]</h5>
                                <div class='features mb-2'>
                                    <h6 class='mb-1'>Features</h6>
                                    $feature
                                </div>
                                <div class='facilities mb-2'>
                                    <h6 class='mb-1'>Facilities</h6>
                                    $facility
                                </div>
                                <div class='guests'>
                                    <h6 class='mb-1'>Guests</h6>
                                    <span class='badge rounded-pill bg-light text-wrap lh-base text-dark mb-lg-0 mb-md-0 mb-1'>
                                        $room_data[adult] $adult
                                    </span>
                                    <span class='badge rounded-pill bg-light text-wrap lh-base text-dark mb-lg-0 mb-md-0 mb-1'>
                                        $room_data[children] $children
                                    </span>
                                </div>
                            </div>
                            <div class='col-md-2 mt-lg-0 mt-md-0 mt-4 mb-lg-0 mb-md-0 mb-3 text-center'>
                                <h6 class='mb-4'>₹$room_data[price] per night</h6>
                                $booking
                                <a href='./room_details.php?id=$room_data[id]' class='btn btn-sm shadow-none w-100 btn-outline-dark'>More Details</a>
                            </div>
                        </div>
                    </div>
            ";
        $count++;
    }
    if ($count == 0) {
        echo json_encode(["flag" => "noData", "data" => "<h2 class='w-100 text-center'>No record found!</h2>"]);
    } else {
        echo json_encode(["flag" => "rooms", "data" => $rooms]);
    }
}
