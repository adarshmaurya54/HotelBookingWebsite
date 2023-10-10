<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - CONTACT US</title>
    <?php require("./inc/links.php") ?>
</head>

<body class="bg-light">

    <?php require("./inc/header.php") ?>


    <!-- Contact us -->
    <div class="my-4 text-center">
        <h4 class="h-font fw-bold fs-2">CONTACT US</h4>
        <div class="h-line bg-dark"></div>
        <p class="mt-3">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Magnam numquam illum deserunt?<br> In error blanditiis voluptatum nesciunt, neque consectetur maiores.</p>
    </div>
    <?php
    $contact_q = "SELECT * FROM `contact_settings` WHERE `id`=?";
    $value = [1];
    $res = select($contact_q, $value, 'i');
    $res_assoc = mysqli_fetch_assoc(($res));
    ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-6 mb-lg-0 mb-3 px-3">
                <div class="p-4 bg-white rounded shadow">
                    <iframe class="w-100 rounded mb-4" height="380px" src="<?php echo $res_assoc['iframe'] ?>" loading="lazy"></iframe>
                    <h4>Address</h4>
                    <a class="badge rounded-pill fs-6 bg-light text-decoration-none text-dark d-inline-block mb-4 text-start text-wrap lh-base"  href="<?php echo $res_assoc['gmap'] ?>" target="_blank"><i class="bi bi-geo-alt-fill"></i> <?php echo $res_assoc['address'] ?></a>
                    <h4>Call us</h4>
                    <a href="#" class="badge rounded-pill fs-6 bg-light text-decoration-none text-dark d-inline-block mb-2">
                        <i class="bi bi-telephone-fill"></i> +91 <?php echo $res_assoc['ph1'] ?>
                    </a>
                    <br>
                    <?php
                    if ($res_assoc["ph2"] != '') {
                        echo <<<data
                                <a href="tel: $res_assoc[ph2]" class="badge rounded-pill fs-6 bg-light text-decoration-none text-dark d-inline-block mb-4">
                                    <i class="bi bi-telephone-fill"></i> +91 $res_assoc[ph2]
                                </a>
                            data;
                    }
                    ?>
                    <h4>Email</h4>
                    <a href="mailto: <?php echo $res_assoc['email'] ?>" class="text-wrap lh-base badge rounded-pill fs-6 bg-light text-decoration-none text-dark d-inline-block mb-4">
                        <i class="bi bi-envelope-fill"></i> <?php echo $res_assoc['email'] ?>
                    </a>
                    <h4>Follow us</h4>
                    <?php
                    $social_q2 = "SELECT * FROM `social_links`";
                    $social_res2 = $conn->query($social_q2);
                    while ($row = mysqli_fetch_assoc($social_res2)) {
                        if ($row['social_link'] != '') {
                            echo <<<social
                                <a href="$row[social_link]" class="text-decoration-none d-inline-block fs-4 text-dark me-2">
                                    <i class="bi $row[icon_class_name]"></i>
                                </a>
                            social;
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-6 px-3">
                <div class="p-4 bg-white rounded shadow ">
                    <h4 class="mb-4 border-bottom border-3 border-dark pb-2">Contact Us Form</h4>
                    <form novalidate id="contact_us_form">
                        <div class="mb-3 ps-0">
                            <label class="form-label">Name</label>
                            <input spellcheck="false" id="cont_name" name="cont_name" type="text" class="shadow-none form-control">
                            <span id="name_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;">
                                 
                            </span>
                        </div>
                        <div class="mb-3 p-0">
                            <label class="form-label">Email</label>
                            <input spellcheck="false" type="email" id="cont_email" name="cont_email" class="shadow-none form-control">
                            <div class="form-text"><i class="bi bi-exclamation-circle-fill"></i> Please make sure to enter a valid email address, as the response will be sent to the provided email.</div>
                            <span id="email_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;">
                                   
                            </span>
                        </div>
                        <div class="mb-3 p-0">
                            <label class="form-label">Subject</label>
                            <input spellcheck="false" type="text" id="cont_subject" name="cont_subject" class="shadow-none form-control">
                            <span id="subject_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;">
                                
                            </span>
                        </div>
                        <div class="mb-3 p-0">
                            <label class="form-label">Message</label>
                            <textarea spellcheck="false" style="resize: none;" id="cont_msg" name="cont_msg" class="shadow-none form-control" rows="5"></textarea>
                            <span id="message_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;">
                                 
                            </span>
                        </div>
                        <div class="mt-4">
                            <button class="shadow-none btn custom-bg text-white my-1">SEND</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact us end -->
    
    <!-- For showing alerts and errors -->
    <div id="alert"></div>
    <div id="form-error"></div>

    <?php require("./inc/footer.php") ?>
    
    <script src="./scripts/contact_us.js"></script>
</body>

</html>