
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - BOOKING STATUS</title>
    <?php require("./inc/links.php") ?>
</head>

<body class="bg-light">

    <?php
    $general_q = "SELECT * FROM `general_settings` WHERE `id`=?";
    $value = [1];
    $general_res = select($general_q, $value, "i");
    $general_assoc = mysqli_fetch_assoc($general_res);

    if (!(isset($_GET['order']) && $_GET['order'] != '')) {
        redirect("./index.php");
    } else  if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect("./index.php");
    }

    ?>

    <div class="container">
        <div class="my-4">
            <h4 class="fw-bold fs-2">BOOKING STATUS</h4>
            <div class="d-flex align-items-center gap-2 mt-3">
                <a href="./index.php" class="anchor-hover text-decoration-none text-muted">HOME</a>
                <span class="text-muted"> / </span>
                <a href="./rooms.php" class="anchor-hover text-decoration-none text-muted">ROOMS</a>
                <span class="text-muted"> / </span>
                <span>BOOKING STATUS</span>
            </div>
        </div>
        <div class="row mt-5 d-flex align-items-center">

            <?php
            $frm = filteration($_GET);
            $select_q = "SELECT * FROM `booking_order` bo INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id WHERE bo.order_id = ? AND user_id=? AND bo.booking_status != ?; ";
            $o_value = [$frm['order'], $_SESSION['uId'], 'pending'];
            $res = select($select_q, $o_value, "sis");
            if ($res->num_rows == 0) {
                echo <<<data
                        <div class="col-md-12">
                            <div class="alert alert-danger ">
                                <h4 class="m-0 fw-bold p-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Invalid order id</h4>
                            </div>
                        </div>
                    data;
                redirect("./index.php");
            }
            $p_res_assoc = mysqli_fetch_assoc($res);

            if ($p_res_assoc['trans_status'] == "TNX_successful") {
                echo <<<data
                        <div class="col-md-12">
                            <div class="alert alert-success ">
                                <h4 class="m-0 fw-bold p-0"><i class="bi bi-check-circle-fill me-2"></i>Your transaction has been successful.🎉</h4>
                                <div>$p_res_assoc[trans_resp_msg]</div>
                                <a href="./bookings.php" class="shadow-none btn btn-sm mt-3 btn-success">Go to bookings</a>
                            </div>
                        </div>
                    data;
            } else {
                echo <<<data
                        <div class="col-md-12">
                            <div class="alert alert-danger ">
                                <h4 class="m-0 fw-bold p-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Your transaction has been failed.</h4>
                                <div>$p_res_assoc[trans_resp_msg]</div>
                                <a href="./bookings.php" class="shadow-none btn btn-sm mt-3 btn-danger">Go to bookings</a>
                            </div>
                        </div>
                    data;
            }

            ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>