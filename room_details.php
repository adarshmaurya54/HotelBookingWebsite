
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - ROOM DETAILS</title>
    <?php require("./inc/links.php") ?>
    <style>
        #alert {
            top: 10px !important;
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
    }
    $frm = filteration($_GET);
    $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?", [$frm['id'], 1, 0], 'iii');
    if ($room_res->num_rows == 0) {
        redirect("./rooms.php");
    }
    $room_data = mysqli_fetch_assoc($room_res);
    ?>

    <div class="container">
        <div class="my-4">
            <h4 class="h-font fw-bold fs-2"><?php echo $room_data['name'] ?></h4>
            <div class="d-flex align-items-center gap-2 mt-3">
                <a href="./index.php" class="anchor-hover text-decoration-none text-muted">HOME</a>
                <span class="text-muted"> / </span>
                <a href="./rooms.php" class="anchor-hover text-decoration-none text-muted">ROOMS</a>
                <span class="text-muted"> / </span>
                <span>ROOM DETAILS</span>
            </div>
        </div>
        <div class="row mt-3 d-flex align-items-center">
            <div class="col-md-12 col-lg-7 mb-lg-0 mb-3">
                <div id="roomCarousels" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $room_img_q = "SELECT * FROM `room_images` WHERE `room_id`=?";
                        $room_img_res = select($room_img_q, [$room_data['id']], 'i');

                        $room_img = ROOMS_IMAGE_FOLDER_PATH . "thumbnail.jpg";
                        if ($room_img_res->num_rows == 0) {
                            echo <<<data
                                <div class="carousel-item active">
                                <img src="$room_img" class="rounded d-block w-100" alt="thumbnail.jpg">
                                </div>
                                data;
                        } else {
                            $activeClass = "active";
                            while ($img_row = mysqli_fetch_assoc($room_img_res)) {
                                $room_img = ROOMS_IMAGE_FOLDER_PATH . $img_row['room_img'];
                                echo <<<data
                                        <div class="carousel-item $activeClass shadow">
                                            <img src="$room_img" class="rounded d-block w-100" alt="$img_row[room_img]">
                                        </div>
                                    data;
                                $activeClass = '';
                            }
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousels" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousels" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <?php
                        echo <<<price
                                    <h4 class="mb-2">₹$room_data[price] per night</h4>
                                price;
                        $ratings_q = select("SELECT AVG(rating) AS `rating` FROM `reviews_and_ratings` WHERE `room_id` = ?  ORDER BY `id` DESC LIMIT 30", [$room_data['id']], 'i');
                        $rating = mysqli_fetch_assoc($ratings_q);
                        $rating_out = "";
                        $avg = round($rating['rating']);
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $avg) {
                                $rating_out .= "
                                            <i class='bi bi-star-fill text-warning'></i>
                                            ";
                            } else {
                                $rating_out .= "
                                                <i class='bi bi-star text-dark'></i>
                                                ";
                            }
                        }
                        echo <<<rating
                                    <span class="badge rounded-pill bg-light mb-3">
                                        $rating_out
                                    </span>
                                rating;

                        // Getting feature of each room
                        $feature_q = "SELECT f.feature_name FROM `room_features` rf INNER JOIN `features` f ON rf.feature_id = f.id WHERE rf.room_id = ?";
                        $f_res = select($feature_q, [$room_data['id']], 'i');
                        $feature = "";
                        while ($f_row = mysqli_fetch_assoc($f_res)) {
                            $feature .= "<span class='badge rounded-pill bg-light text-wrap lh-base text-dark me-1 mb-1'>
                                                    $f_row[feature_name]
                                                </span>";
                        }
                        echo <<<features
                                    <div class="mb-2">
                                        <h6 class="mb-2">Features</h6>
                                        $feature
                                    </div>
                                features;
                        // Getting facilities of each room
                        $facility = "";
                        $facility_q = "SELECT f.name FROM `room_facilities` rf INNER JOIN `facilities` f ON rf.facilities_id = f.id WHERE rf.room_id = ?";
                        $f2_res = select($facility_q, [$room_data['id']], 'i');
                        while ($f_row = mysqli_fetch_assoc($f2_res)) {
                            $facility .= "<span class='badge me-1 rounded-pill bg-light text-wrap lh-base text-dark mb-1'>
                                                    $f_row[name]
                                                </span>";
                        }

                        echo <<<facilities
                                    <div class="mb-2">
                                        <h6 class="mb-2">Facilities</h6>
                                        $facility
                                    </div>
                                facilities;

                        $adult = ($room_data['adult'] > 1) ? "Adults" : "Adult";
                        $children = ($room_data['children'] > 1) ? "Childrens" : "Children";

                        echo <<<guests
                                    <div class="mb-2">
                                        <h6 class="mb-1">Guests</h6>
                                        <span class="badge rounded-pill bg-light text-wrap lh-base text-dark mb-lg-0 mb-md-0 mb-1">
                                            $room_data[adult] $adult
                                        </span>
                                        <span class="badge rounded-pill bg-light text-wrap lh-base text-dark mb-lg-0 mb-md-0 mb-1">
                                            $room_data[children] $children
                                        </span>
                                    </div>
                                guests;

                        echo <<<area
                            <h6 class="mb-2">Area</h6>
                            <span class="badge rounded-pill bg-light text-wrap lh-base text-dark mb-lg-0 mb-md-0 mb-1">
                                $room_data[area] sq. ft.
                            </span>
                        area;
                        if (!$general_assoc['shutdown']) {

                            if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
                                echo <<<booknow
                                <button onclick='checkLoginToBook(0,$room_data[id])' class="btn w-100 shadow-none text-white custom-bg mt-3" data-bs-toggle='modal' data-bs-target='#loginmodal'>Please Login To Book</button>
                                booknow;
                            } else {
                                echo <<<booknow
                                            <button onclick='checkLoginToBook(1,$room_data[id])' class="btn w-100  shadow-none text-white custom-bg mt-3">Book Now</button>
                                    booknow;
                            }
                        } else {
                            echo <<<booknow
                                    <a href="javascript: void(0);" class="btn w-100 shadow-none text-white btn-danger mt-3"><i class="bi bi-exclamation-triangle-fill me-2"></i> Booking Closed</a>
                            booknow;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-5">
                <div class="mb-5">
                    <h4>Description</h4>
                    <p style="text-align: justify;">
                        <?php
                        echo $room_data['description']
                        ?>
                    </p>
                </div>
                <div>
                    <h4 class="border-bottom border-4 border-secondary pb-2 mb-4">Ratings & Reviews</h4>
                    <?php
                    $query = "SELECT rr.*, uc.name AS uname, uc.profile  FROM `reviews_and_ratings` rr INNER JOIN `user_cred` uc
                    ON rr.user_id = uc.id INNER JOIN `rooms` r ON rr.room_id = r.id
                    WHERE rr.room_id = $room_data[id]
                 ORDER BY `id` DESC LIMIT 15";
                    $res = $conn->query($query);
                    if ($res->num_rows == 0) {
                        echo <<<data
                        <h2 class="text-center w-100">No records yet!</h2>
                        data;
                    } else {
                        while ($row = mysqli_fetch_assoc($res)) {
                            $img = USERS_FOLDER_PATH . $row['profile'];
                            $stars = "<i class='bi bi-star-fill text-warning'></i>";
                            for ($i = 1; $i < 5; $i++) {
                                if ($i < $row['rating']) {
                                    $stars .= "<i class='bi bi-star-fill text-warning'></i> ";
                                } else {
                                    $stars .= "<i class='bi bi-star text-dark'></i> ";
                                }
                            }
                            echo <<<data
                            <div class="border-bottom pb-2 mb-2">
                                <div class="profile w-100">
                                    <img src="$img" loading="lazy" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;" width="30px">
                                    <h6 class="mt-3">$row[uname]</h6>
                                </div>
                                <p class="my-4">
                                    $row[review]     
                                </p>
                                <span class="badge rounded-pill bg-light">
                                    $stars
                                </span>
                            </div>
                        data;
                        }
                    }
                    ?>
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
    <!-- Login modal -->
    <div class="modal fade" id="loginmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form novalidate autocomplete="off" id="login_form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-person-fill fs-3 me-3"></i> Login
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Email / Mobile</label>
                            <input spellcheck="false" type="text" name="email_mob" class="shadow-none form-control">
                            <span id="email_mob_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input spellcheck="false" type="password" name="password" class="shadow-none form-control">
                            <span id="password_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                        </div>
                        <div class="mb-2 d-flex align-items-center justify-content-between">
                            <button class="shadow-none btn btn-dark">Login</button>
                            <button type="button" class="btn text-secondary shadow-none" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal">
                                Forgot Password?
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Login modal end -->
    <!-- forgot password modal -->
    <div class="modal fade" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form novalidate autocomplete="off" id="forgot_form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-question-circle-fill me-3"></i> Forgot Password
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <span class="badge bg-light text-wrap lh-base text-dark mb-3">
                                Note : A link will be sent to your email to reset your password.
                            </span>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <input spellcheck="false" type="text" name="email" class="shadow-none form-control">
                            <span id="forg_email_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                        </div>
                        <div class="mb-2 text-end">
                            <button type="button" class="me-2 btn text-secondary shadow-none" data-bs-toggle="modal" data-bs-target="#loginmodal" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="submit" class="shadow-none btn btn-dark">SEND MAIL</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- forgot password modal end -->
    <!-- Designed by name -->
    <div class="bg-dark text-white p-4 text-center fs-4 design-by">
        Designed and Developed by <a href="https://adarshmaurya54.github.io/Portfolio/" class="text-decoration-none" style="color: orange;">Adarsh Maurya</a>
    </div>
    <!-- For showing alerts and errors -->
    <div id="alert"></div>
    <div id="form-error"></div>
    <!-- Designed by name end -->
    <script src="./admin/scripts/essentials.js"></script>
    <script src="./scripts/login.js"></script>
    <script src="./scripts/forgot.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>