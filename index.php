<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - HOME</title>
    <?php require("./inc/links.php") ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        .availablity-form {
            margin-top: -60px;
            z-index: 2;
            position: relative;
            transition: margin 0.5s ease;
        }

        .testimonial-container-swiper {
            width: 380px;
            height: 470px;
        }

        @media screen and (max-width: 575px) {
            .availablity-form {
                margin-top: 20px;
                padding: 0 20px;
            }

            .testimonial-container-swiper {
                width: 280px;
                height: 370px;
            }

            .testimonials {
                overflow: hidden;
            }
        }

        .design-by a:hover {
            border-bottom: 0.7px solid orange;
        }

        .swiper-slide {
            border-radius: 18px;
        }
        .swiper-slide p::-webkit-scrollbar {
            /* display: none; */
            border-radius: 10px;
        }

        .swiper-slide p::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            /* Track color */
            border-radius: 10px;
        }

        .swiper-slide p::-webkit-scrollbar-thumb {
            background-color: #888;
            /* Thumb color */
            border-radius: 10px;
        }

        *::-webkit-scrollbar {
            width: 6px;
            /* Adjust the width as needed */
        }

        footer.container-fluid a:hover {
            text-decoration: underline !important;
        }
    </style>
</head>

<body class="bg-light">

    <?php require("./inc/header.php") ?>

    <!-- carousels -->
    <div class="container-fluid  mt-3 px-lg-4">
        <!-- Swiper -->
        <div class="swiper swiper-container shadow-sm">
            <div class="swiper-wrapper">
                <?php
                $carousel_query = "SELECT * FROM `carousels`";
                $carousel_res = $conn->query($carousel_query);
                while ($row = mysqli_fetch_assoc($carousel_res)) {
                    $path = CAROUSEL_IMAGE_FOLDER_PATH . $row['picture'];
                    echo <<<carousel
                            <div class="swiper-slide">
                                <img src="$path" class="h-100 w-100 d-block rounded" />
                            </div>
                        carousel;
                }
                ?>
            </div>
        </div>
    </div>
    <!-- carousels end-->

    <!-- Check availability form -->
    <div class="container availablity-form">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 shadow rounded bg-white p-4">
                    <form action="rooms.php">
                        <h5 class="mb-4 d-flex align-items-center justify-content-between">
                            <span>Check Availability</span>
                            <button type="reset" class=" mt-lg-0 mt-2 btn btn-sm border btn-white shadow-none text-dark">Reset All</button>
                        </h5>
                        <div class="row align-items-end">
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" style="font-weight: 500;">Check-in</label>
                                <input spellcheck="false" type="date" name="checkin" class="shadow-none form-control">
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label class="form-label" style="font-weight: 500;">Check-out</label>
                                <input spellcheck="false" type="date" name="checkout" class="shadow-none form-control">
                            </div>
                            <div class="col-lg mb-3">
                                <label class="form-label" style="font-weight: 500;">Adult</label>
                                <select class="form-select shadow-none" name="adults">
                                    <option value='0'>--SELECT--</option>
                                    <?php
                                    $res = mysqli_fetch_assoc($conn->query("SELECT MAX(adult) AS `adults`,MAX(children) AS `children` FROM `rooms`"));
                                    for ($i = 1; $i <= $res['adults']; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg mb-3">
                                <label class="form-label" style="font-weight: 500;">Children</label>
                                <select class="form-select shadow-none" name="childrens">
                                    <option value='0'>--SELECT--</option>
                                    <?php
                                    for ($i = 1; $i <= $res['children']; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="checkavail">
                            <div class="col-lg mb-lg-3 mt-3">
                                <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Check availability form end -->

    <!-- our rooms -->
    <h4 class="text-center mt-5 pt-4 mb-4 h-font fw-bold fs-2">OUR ROOMS</h4>
    <div class="container">
        <div class="row">
            <?php
            $room_q = "SELECT * FROM `rooms` ORDER BY `id` DESC LIMIT 3";
            $room_res = $conn->query($room_q);
            while ($room_data = mysqli_fetch_assoc($room_res)) {
                // Getting feature of each room
                $feature_q = "SELECT f.feature_name FROM `room_features` rf INNER JOIN `features` f ON rf.feature_id = f.id WHERE rf.room_id = ?";
                $f_res = select($feature_q, [$room_data['id']], 'i');
                $feature = "";
                while ($f_row = mysqli_fetch_assoc($f_res)) {
                    $feature .= "<span class='badge rounded-pill bg-light text-wrap lh-base text-dark me-1 mb-1'>
                                                $f_row[feature_name]
                                            </span>";
                }

                // Getting facilities of each room
                $facility = "";
                $facility_q = "SELECT f.name FROM `room_facilities` rf INNER JOIN `facilities` f ON rf.facilities_id = f.id WHERE rf.room_id = ?";
                $f2_res = select($facility_q, [$room_data['id']], 'i');
                while ($f_row = mysqli_fetch_assoc($f2_res)) {
                    $facility .= "<span class='badge me-1 rounded-pill bg-light text-wrap lh-base text-dark mb-1'>
                                                $f_row[name]
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
                // calculating rating of current room
                $ratings_q = select("SELECT AVG(rating) AS `rating` FROM `reviews_and_ratings` WHERE `room_id` = ? ORDER BY `id` DESC LIMIT 30", [$room_data['id']], 'i');
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

                // if website is shutdown then booking button is removed 
                if (!$general_assoc['shutdown']) {
                    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
                        $booking = "<button onclick='checkLoginToBook(0,$room_data[id])' class='btn shadow-none btn-sm text-white custom-bg' data-bs-toggle='modal' data-bs-target='#loginmodal'>Book Now</button>";
                    } else {
                        $booking = "<button onclick='checkLoginToBook(1,$room_data[id])' class='btn shadow-none btn-sm text-white custom-bg'>Book Now</button>";
                    }
                } else {
                    $booking = "";
                }
                echo <<<data
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-0 shadow h-100" style="max-width: 350px; margin: auto;">
                                <img src="$img" class="card-img-top img-fluid">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h5>$room_data[name]</h5>
                                        <h6 class="mb-4">₹$room_data[price] per night</h6>
                                        <div class="features mb-2">
                                            <h6 class="mb-1">Features</h6>
                                            $feature    
                                        </div>
                                        <div class="facilities mb-2">
                                            <h6 class="mb-1">Facilities</h6>
                                            $facility
                                        </div>
                                        <div class="guests mb-2">
                                            <h6 class="mb-1">Guests</h6>
                                            <span class="badge rounded-pill bg-light text-wrap lh-base text-dark mb-3">
                                                $room_data[adult] $adult
                                            </span>
                                            <span class="badge rounded-pill bg-light text-wrap lh-base text-dark mb-3">
                                                $room_data[children] $children
                                            </span>
                                        </div>
                                        <div class="rating mb-4">
                                            <h6 class="mb-1">Rating</h6>
                                            $rating_out
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-evenly mb-2">
                                        $booking
                                        <a href="./room_details.php?id=$room_data[id]" class="shadow-none btn btn-sm btn-outline-dark">More details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    data;
            }
            ?>
        </div>
        <div class="text-center">
            <a href="./rooms.php" class="btn btn-sm btn-outline-dark shadow-none fw-bold rounded mt-4">More Rooms >>></a>
        </div>
    </div>
    <!-- our rooms end -->

    <!-- our facilities -->
    <h4 class="text-center mt-5 pt-4 mb-4 h-font fw-bold fs-2">OUR FACILITIES</h4>
    <div class="container">
        <div class="row d-flex justify-content-between px-lg-0 px-md-0 px-5">
            <?php
            $faci_q = "SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 5;";
            $res = $conn->query($faci_q);
            while ($faci_row = mysqli_fetch_assoc($res)) {
                $path = FACILITIES_IMAGE_FOLDER_PATH . $faci_row['picture'];
                echo <<<facilities
                        <div class="col-lg-2 col-md-2 py-3 my-3 bg-white shadow text-center rounded">
                            <img src="$path" width="80px">
                            <h6 class="mt-3">$faci_row[name]</h6>
                        </div>
                    facilities;
            }
            ?>
        </div>
        <div class="text-center">
            <a href="./facilities.php" class="btn btn-sm btn-outline-dark shadow-none fw-bold rounded mt-4">More Facilities >>></a>
        </div>
    </div>
    <!-- our facilities end -->

    <!-- our testimonials -->
    <!-- Swiper -->
    <h4 class="text-center mt-5 pt-4 mb-4 h-font fw-bold fs-2">TESTIMONIALS</h4>
    <div class="container testimonials my-5 d-flex align-items-center justify-content-center py-md-0 py-lg-0 py-5">
        <div class="testimonial-container-swiper mx-md-4 testimonial-swiper">
            <div class="swiper-wrapper">

                <?php
                $q = "SELECT rr.*, uc.name AS uname, uc.profile, r.name AS rname  FROM `reviews_and_ratings` rr INNER JOIN `user_cred` uc
                    ON rr.user_id = uc.id INNER JOIN `rooms` r ON rr.room_id = r.id
                 ORDER BY `id` DESC LIMIT 6";
                $res = $conn->query($q);
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
                        <div class="swiper-slide p-3 bg-white shadow d-flex flex-column justify-content-evenly align-items-start">
                            <div class="profile text-center w-100">
                                <img src="$img" loading="lazy" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                                <h6 class="mt-3">$row[uname]</h6>
                            </div>
                            <p class="my-4 p-3 overflow-auto text-center w-100">
                                $row[review]    
                            </p>
                            <span class="badge rounded-pill bg-light p-3 fs-4">
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
    <div class="text-center ">
        <a href="#" class="btn btn-sm btn-outline-dark shadow-none fw-bold rounded mt-4">Know More >>></a>
    </div>
    <!-- our testimonials end -->

    <!-- Reach us-->
    <?php
    $contact_q = "SELECT * FROM `contact_settings` WHERE `id`=?";
    $value = [1];
    $res = select($contact_q, $value, 'i');
    $res_assoc = mysqli_fetch_assoc(($res));
    ?>
    <h4 class="text-center mt-5 pt-4 mb-4 h-font fw-bold fs-2">REACH US</h4>
    <div class="container">
        <div class="row px-md-0 px-lg-0 px-3">
            <div class="col-md-8 bg-white rounded shadow p-3 mb-lg-0 mb-5">
                <iframe class="w-100 rounded" height="380px" src="<?php echo $res_assoc['iframe'] ?>" loading="lazy"></iframe>
            </div>
            <div class="col-md-4 px-lg-3 px-md-3 px-0">
                <div class="p-4 mb-3 bg-white shadow rounded">
                    <h4 class="mb-4">Call Us</h4>
                    <a href="#" class="badge rounded-pill fs-6 bg-light text-decoration-none text-dark d-inline-block mb-2">
                        <i class="bi bi-telephone-fill"></i> +91 <?php echo $res_assoc['ph1'] ?>
                    </a>
                    <br>
                    <a href="#" class="badge rounded-pill fs-6 bg-light text-decoration-none text-dark d-inline-block mb-2">
                        <i class="bi bi-telephone-fill"></i> +91 <?php echo $res_assoc['ph2'] ?>
                    </a>
                </div>
                <div class="p-4 bg-white shadow rounded">
                    <h4 class="mb-4">Follow Us</h4>
                    <?php
                    while ($row = mysqli_fetch_assoc($social_res)) {
                        echo <<<social
                            <a href="$row[social_link]" class="badge text-decoration-none d-inline-block rounded-pill fs-6 bg-light text-dark mb-3">
                                <i class="bi $row[icon_class_name]"></i> $row[name]
                            </a></br>
                        social;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Reach us end-->

    <?php require("./inc/footer.php") ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <!-- carousels Initialize Swiper -->
    <script>
        var swiper = new Swiper(".swiper-container", {
            spaceBetween: 30,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            }
        });
    </script>
    <!-- Testimonials Initialize Swiper -->
    <script>
        var swiper = new Swiper(".testimonial-swiper", {
            effect: "cards",
            grabCursor: true
        });
    </script>
</body>

</html>