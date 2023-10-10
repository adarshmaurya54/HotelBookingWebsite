<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - ROOM BOOKING</title>
    <?php require("./inc/links.php") ?>
    <style>
        #alert {
            top: 10px !important;
        }

        #rating_stars span {
            position: relative;
            z-index: 3;
        }

        #rating_stars span:first-child::before {
            content: 'Terrible';
            opacity: 0;
        }

        #rating_stars span:nth-child(2)::before {
            content: 'Poor';
            opacity: 0;
        }

        #rating_stars span:nth-child(3)::before {
            content: 'Okay';
            opacity: 0;
        }

        #rating_stars span:nth-child(4)::before {
            content: 'Good';
            opacity: 0;
        }

        #rating_stars span:last-child::before {
            content: 'Excellent';
            opacity: 0;
        }

        #rating_stars span.active::before {
            opacity: 1;
        }

        #rating_stars span::before {
            position: absolute;
            left: -4px;
            bottom: -27px;
            font-size: 0.7em;
            width: 100%;
            border-radius: 10px;
            line-height: 30px;
            color: black;
            z-index: 2;
        }
    </style>
</head>

<body class="bg-light">

    <?php
    $general_q = "SELECT * FROM `general_settings` WHERE `id`=?";
    $value = [1];
    $general_res = select($general_q, $value, "i");
    $general_assoc = mysqli_fetch_assoc($general_res);
    ?>

    <?php
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect("./index.php");
    }


    ?>

    <div class="container">
        <div class="my-4">
            <h4 class="fw-bold fs-2">BOOKINGS</h4>
            <div class="d-flex align-items-center gap-2 mt-3">
                <a href="./index.php" class="anchor-hover text-decoration-none text-muted">HOME</a>
                <span class="text-muted"> / </span>
                <span>BOOKINGS</span>
            </div>
        </div>
        <div class="row">
        
            <?php


            $q = "SELECT * FROM `booking_order` bo 
                INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
                 WHERE ((bo.booking_status='booked')
                    OR (bo.booking_status='cancelled')
                    OR (bo.booking_status='failed')) AND bo.user_id = ?
                    ORDER BY bo.booking_id DESC
                 ";
            $value = [$_SESSION['uId']];
            $res = select($q, $value, 'i');
            if ($res->num_rows == 0) {
                echo <<<data
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <h2>NO BOOKINGS</h2>
                </div>            
                data;
            }else{
                while ($data = mysqli_fetch_assoc($res)) {
                    $status = "";
                    $btn = "";
                    if ($data['booking_status'] == 'booked') {
                        $status = "bg-success";
                        if ($data['arrival'] == 1) {
                            $btn = "
                            <div class='d-flex align-items-center justify-content-between' id='downloadAndRatingBtns'>
                                <a href='./generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn me-1 shadow-none btn-outline-dark'><i class='bi bi-download'></i> Download PDF</a>
                                <button onclick='setIds($data[booking_id],$data[room_id])' class='btn btn-dark shadow-none' data-bs-toggle='modal' data-bs-target='#review_and_rating_modal'>Rate & Review</button>    
                            </div>
                             ";
                            if ($data['review_and_rating'] == 1) {
                                $btn = "
                                <a href='./generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn shadow-none btn-outline-dark'><i class='bi bi-download'></i> Download PDF</a>
                                <span class='badge bg-success mt-3' style='font-size: 0.7em; line-height: 15px; white-space: wrap;'>Thank you for sharing your hotel review with us! Your feedback is highly valuable to us as we strive to provide the best possible experience for our guests. We appreciate your time and hope to welcome you back in the future.</span>
                                ";
                            }
                        } else {
                            $btn = "<button onclick='cancel_booking($data[booking_id])' class='btn shadow-none btn-danger'>CANCEL BOOKING</button>";
                        }
                    } else if ($data['booking_status'] == 'cancelled') {
                        $status = "bg-warning text-dark";
                        if ($data['refund'] == 1) {
                            $btn = "
                            <div class='d-flex align-items-center justify-content-between'>
                                <a href='./generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn me-1 shadow-none btn-outline-dark'><i class='bi bi-download'></i> Download PDF</a>
                                <span class='badge bg-success h-100'>Money refunded</span>
                            </div>
                            ";
                        } else {
                            $btn = "<span class='badge bg-info'>Refund in process</span>";
                        }
                    } else {
                        $status = "bg-danger";
                        $btn = "
                            <a href='./generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn shadow-none btn-outline-dark'><i class='bi bi-download'></i> Download PDF</a>
                        ";
                    }
    
                    $checkin = date("d-m-Y", strtotime($data['check_in']));
                    $checkout = date("d-m-Y", strtotime($data['check_out']));
                    $date_time = date("d-m-Y h:i A", strtotime($data['date_time']));
    
                    if ($data['booking_status'] == "booked") {
                        $status_msg = "Booked";
                    } else if ($data['booking_status'] == "cancelled") {
                        $status_msg = "Cancelled";
                    } else if ($data['booking_status'] == "failed") {
                        $status_msg = "Payment Failed";
                    }
    
                    echo <<<booking_details
                        <div class="col-lg-4 mb-3">
                            <div class="d-flex flex-column align-items-center justify-content-between bg-white p-3 shadow rounded h-100">
                                <div class="w-100">
                                    <h4 class='h-font fw-bold'>$data[room_name]</h4>
                                    <p>₹$data[price].00 per night</p>
                                    <p>
                                        <strong>Check-in</strong> : $checkin <br>
                                        <strong>Check-out</strong> : $checkout
                                    </p>
                                    <p>
                                        <strong>Amount</strong> : ₹$data[total_pay].00 <br>
                                        <strong>Order ID</strong> : $data[order_id]<br>
                                        <strong>Date</strong> : $date_time
                                    </p>
                                </div>
                                <div class="w-100">
                                    <p>
                                        <span class="badge $status">$status_msg</span>
                                    </p>
                                    $btn
                                </div>
                            </div>
                        </div>
                    booking_details;
                }
            }

            ?>
        </div>
    </div>


    <!-- review_and_rating modal -->
    <div class="modal fade" id="review_and_rating_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form novalidate autocomplete="off" id="ratingreviewform">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-chat-heart-fill fs-3 me-3"></i> Review and Rating
                        </h5>
                        <button type="reset" class="btn-close shadow-none" onclick="reset_all_stars()" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Rating <span class="text-danger">*</span></label>
                            <div id="rating_stars" class="text-center pb-3 border rounded">
                                <span>
                                    <i onclick="cliked_star(this)" style="cursor: pointer;" class="bi bi-star fs-1 me-2"></i>
                                </span>
                                <span>
                                    <i onclick="cliked_star(this)" style="cursor: pointer;" class="bi bi-star fs-1 me-2"></i>
                                </span>
                                <span>
                                    <i onclick="cliked_star(this)" style="cursor: pointer;" class="bi bi-star fs-1 me-2"></i>
                                </span>
                                <span>
                                    <i onclick="cliked_star(this)" style="cursor: pointer;" class="bi bi-star fs-1 me-2"></i>
                                </span>
                                <span>
                                    <i onclick="cliked_star(this)" style="cursor: pointer;" class="bi bi-star fs-1 me-2"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review <span class="text-danger">*</span></label>
                            <textarea spellcheck="false" style="resize: none;" name="review" class="shadow-none form-control" rows="5"></textarea>
                        </div>
                        <div class="mb-2">
                            <button class="shadow-none btn btn-dark">SUBMIT</button>
                        </div>
                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="room_id">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- review_and_rating end -->
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
    <?php
    if ((isset($_GET['cancel_status']) && $_GET['cancel_status'] == true) && (isset($_GET['token']))) {
        echo <<<script
            <script>
                let stored_token = window.localStorage.getItem("token");
                let token = '$_GET[token]';
                if(token === stored_token){
                    custom_alert("Your booking cancelled!","s",true);
                    setTimeout(function() {
                        // Get the current URL
                        var currentURL = window.location.href;
            
                        // Check if the URL contains a query string (?)
                        if (currentURL.indexOf('?') !== -1) {
                            // Remove everything after the question mark (?)
                            var newURL = currentURL.split('?')[0];
            
                            // Modify the URL without reloading the page
                            window.history.replaceState({}, document.title, newURL);
                        }
                    }, 5000);
                }else{
                    window.location.href="./bookings.php";
                }
            </script>
        script;
    }
    ?>
    <script>
        function cancel_booking(booking_id) {
            if (confirm("Are you sure, you want to cancel this booking?")) {
                custom_alert("Please wait", 'i', true, 'l');
                let formData = new FormData();
                formData.append("booking_id", booking_id);
                formData.append("cancel_booking", '');
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "./ajax/cancel_booking.php", true);
                xhr.onload = function() {
                    let response = JSON.parse(xhr.responseText);
                    if (response.res == '1') {
                        window.localStorage.setItem("token", response.token)
                        window.location.href = "./bookings.php?cancel_status=true&token=" + response.token;
                    } else {
                        custom_alert("Booking cancellation failed", 'e');
                    }
                }
                xhr.send(formData);
            }
        }

        // review and rating js...
        const rating_stars = document.getElementById("rating_stars");
        const ratingreviewform = document.getElementById("ratingreviewform");

        function setIds(bid, rid) {
            ratingreviewform['booking_id'].value = bid;
            ratingreviewform['room_id'].value = rid;
        }

        function cliked_star(e) {
            let stars = rating_stars.getElementsByClassName("bi");
            let span = rating_stars.getElementsByTagName("span");
            for (let i = 0; i < stars.length; i++) {
                const element = stars[i];
                const element2 = span[i];
                element.classList.replace("bi-star", "bi-star-fill");
                element.classList.add("text-warning");
                if (element == e) {
                    element2.classList.add("active");
                    rating_stars.style.pointerEvents = "none";
                    break;
                }
            }
        }

        function reset_all_stars() {
            let stars = rating_stars.getElementsByClassName("bi");
            let span = rating_stars.getElementsByTagName("span");
            for (let i = 0; i < stars.length; i++) {
                const element = stars[i];
                const element2 = span[i];
                element.classList.replace("bi-star-fill", "bi-star");
                element.classList.remove("text-warning");
                element2.classList.remove("active");
                rating_stars.style.pointerEvents = "all";
            }
        }

        function countStars() {
            let stars = rating_stars.getElementsByClassName("bi");
            let count = 0;
            for (let i = 0; i < stars.length; i++) {
                const element = stars[i];
                if (element.classList.contains("bi-star-fill")) {
                    count++;
                }
            }
            return count;
        }
        ratingreviewform.addEventListener("submit", (e) => {
            e.preventDefault();
            if (countStars() == 0) {
                custom_error("Please give rating of our room!", 'e');
            } else if (ratingreviewform['review'].value.trim() == '') {
                custom_error("Please give review of our room!", 'e');
            } else {
                let formData = new FormData();
                custom_error("Please wait", 'i', true);
                formData.append("ratings", countStars());
                formData.append("review", ratingreviewform['review'].value);
                formData.append("booking_id", ratingreviewform['booking_id'].value);
                formData.append("room_id", ratingreviewform['room_id'].value);
                formData.append("review_and_ratings", '');
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "./ajax/reviews_and_ratings.php");
                xhr.onload = function() {
                    var myModalEl = document.getElementById('review_and_rating_modal')
                    var modal = bootstrap.Modal.getInstance(myModalEl)
                    modal.hide();
                    document.getElementById("form-error").style.display = "none";
                    if (xhr.responseText == "1") {
                        custom_alert("Your review and rating submitted!", 's');
                        document.getElementById("downloadAndRatingBtns").classList.remove("d-flex")
                        document.getElementById("downloadAndRatingBtns").classList.remove("align-items-center")
                        document.getElementById("downloadAndRatingBtns").classList.remove("justify-content-between")
                        document.getElementById("downloadAndRatingBtns").innerHTML = `
                            <a href='./generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn shadow-none btn-outline-dark'><i class='bi bi-download'></i> Download PDF</a>
                            <div class='badge bg-success mt-3' style='font-size: 0.7em; line-height: 15px; white-space: wrap;'>Thank you for sharing your hotel review with us! Your feedback is highly valuable to us as we strive to provide the best possible experience for our guests. We appreciate your time and hope to welcome you back in the future.</div>
                        `;
                    } else {
                        custom_alert("Something went wrong on server!", 'e');
                    }
                }
                xhr.send(formData);
            }
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>