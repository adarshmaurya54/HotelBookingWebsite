<?php
header("Pragma: no-cache");
header("Cache-Controle: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - ROOM BOOKING</title>
    <?php require("./inc/links.php") ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        #alert {
            top: 10px !important;
        }
        .razorpay-backdrop span{
            display: none!important;
        }
        .razorpay-backdrop{
            backdrop-filter: blur(10px)!important;
            transition: backdrop-filter 0.3s ease;
        }
    </style>
</head>

<body class="bg-light">

    <?php
    $social_q = "SELECT * FROM `social_links`";
    $social_res = $conn->query($social_q);
    $general_q = "SELECT * FROM `general_settings` WHERE `id`=?";
    $value = [1];
    $general_res = select($general_q, $value, "i");
    $general_assoc = mysqli_fetch_assoc($general_res);
    ?>

    <?php
    if (!(isset($_GET['id']) && $_GET['id'] != '') || !(is_numeric($_GET['id']) && intval($_GET['id']) == $_GET['id'])) { #checking if id index is present in $_get variable and value is an integer value...
        redirect("./rooms.php");
    } else if ($general_assoc['shutdown']) {
        redirect("./rooms.php");
    } else  if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect("./rooms.php");
    }


    $frm = filteration($_GET);

    $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?", [$frm['id'], 1, 0], 'iii');
    if ($room_res->num_rows == 0) {
        redirect("./rooms.php");
    }
    $room_data = mysqli_fetch_assoc($room_res);

    $_SESSION['room_booking'] = [
        "id" => $room_data['id'],
        "name" => $room_data['name'],
        "price" => $room_data['price'],
        "payment" => null,
        "available" => false
    ];



    //now fetching the logined user
    $u_q = "SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1";
    $val = [$_SESSION['uId']];
    $user_res = select($u_q, $val, 'i');
    if ($user_res->num_rows == 0) {
        redirect("./rooms.php");
    }
    $user_data = mysqli_fetch_assoc($user_res);
    ?>

    <div class="container">
        <div class="my-4">
            <h4 class="fw-bold fs-2">CONFIRM BOOKING</h4>
            <div class="d-flex align-items-center gap-2 mt-3">
                <a href="./index.php" class="anchor-hover text-decoration-none text-muted">HOME</a>
                <span class="text-muted"> / </span>
                <a href="./rooms.php" class="anchor-hover text-decoration-none text-muted">ROOMS</a>
                <span class="text-muted"> / </span>
                <span>CONFIRM BOOKING</span>
            </div>
        </div>
        <div class="row mt-5 d-flex align-items-center">
            <div class="col-md-12 col-lg-7 mb-lg-0 mb-3">
                <div class="card">
                    <div class="card-body">
                        <?php
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
                        echo <<<data
                            <img src='$img' class='img-fluid rounded'>
                            <h4 class="mt-3">$room_data[name]</h4>
                            <h6 class="mt-1">₹$room_data[price] per night</h6>
                        data;
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5 class="mb-3 text-center pb-2 border-bottom">Booking Details</h5>
                        <form id="booking_form" action>
                            <input type="hidden" name="room_bookings" />
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Name<span class="text-danger">*</span></label>
                                    <input spellcheck="false" name="name" value="<?php echo $user_data['name'] ?>" type="text" class="shadow-none form-control">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Phone<span class="text-danger">*</span></label>
                                    <input spellcheck="false" name="phone" type="number" value="<?php echo $user_data['phone'] ?>" class="shadow-none form-control">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Address<span class="text-danger">*</span></label>
                                    <textarea spellcheck="false" style="resize: none;" name="address" class="shadow-none form-control" rows="2"><?php echo $user_data['address'] . " " . $user_data['pincode'] ?></textarea>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <label class="form-label" style="font-weight: 500;">Check-in</label>
                                    <input spellcheck="false" onchange="check_availablility()" name="checkin" type="date" class="shadow-none form-control">
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <label class="form-label" style="font-weight: 500;">Check-out</label>
                                    <input spellcheck="false" onchange="check_availablility()" name="checkout" type="date" class="shadow-none form-control">
                                </div>
                                <div class="col-12 mt-3 text-center">
                                    <div class="alert w-100 alert-info text-center" id="pay_info" role="alert">
                                        Please provide checking & checkout date
                                    </div>
                                    <div class="spinner-border my-2 d-none" id="pay_loader" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <button disabled id="pay_now_btn" class="shadow-none btn custom-bg text-white w-100">Pay Now</button>
                                </div>
                                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                                <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer-->
    <footer class="container-fluid py-5 px-3 mt-5 bg-white m-0">
        <div class="row">
            <div class="col-md-4 mb-lg-0 mb-md-0 mb-4">
                <h3 class="h-font fw-bold fs-2 mb-3"><?php echo $general_assoc['site_title'] ?></h3>
                <p class="fs-5"><?php echo $general_assoc['site_desc'] ?></p>
            </div>
            <div class="col-md-4  mb-lg-0 mb-md-0 mb-4">
                <h6 class="fs-3 mb-4 fw-normal">Links</h6>
                <a href="./index.php" class="text-decoration-none d-inline-block mb-1 text-dark">Home</a><br>
                <a href="./rooms.php" class="text-decoration-none d-inline-block mb-1 text-dark">Rooms</a><br>
                <a href="./facilities.php" class="text-decoration-none d-inline-block mb-1 text-dark">Facilities</a><br>
                <a href="./contact.php" class="text-decoration-none d-inline-block mb-1 text-dark">Contact Us</a><br>
                <a href="./about.php" class="text-decoration-none d-inline-block mb-1 text-dark">About</a><br>
            </div>
            <div class="col-md-4">
                <h6 class="fs-3 mb-3 fw-normal">Follow Us</h6>
                <?php
                $social_q = "SELECT * FROM `social_links`";
                $social_res = $conn->query($social_q);
                while ($row = mysqli_fetch_assoc($social_res)) {
                    if ($row['social_link'] != '') {
                        echo <<<social
                        <a href="$row[social_link]" class="badge text-decoration-none d-inline-block rounded-pill fs-6 bg-light text-dark mb-3">
                            <i class="bi $row[icon_class_name]"></i> $row[name]
                        </a></br>
                    social;
                    }
                }
                ?>
            </div>
        </div>
    </footer>
    <!-- Footer end-->

    <!-- Designed by name -->
    <div class="bg-dark text-white p-4 text-center fs-4 design-by">
        Designed and Developed by <a href="https://adarshmaurya54.github.io/Portfolio/" class="text-decoration-none" style="color: orange;">Adarsh Maurya</a>
    </div>
    <!-- For showing alerts and errors -->
    <div id="alert"></div>
    <div id="form-error"></div>
    <!-- Designed by name end -->


    <script src="./admin/scripts/essentials.js"></script>
    <script src="./scripts/pay_now.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>