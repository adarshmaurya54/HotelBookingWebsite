<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - ROOM BOOKING</title>
    <?php require("./inc/links.php") ?>
    <style>
        :root {
            --size: 50vmin;
            --white: #fff;
            --blue: #3051f1;
            --purple: #c92bb7;
            --red: #f73344;
            --orange: #fa8e37;
            --yellow: #fcdf8f;
            --yellow_to: #fbd377;
        }

        #user_profile_container {
            position: relative;
            padding-left: 60px !important;
        }

        #user_profile_container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        #user_profile_container .profile-img {
            position: absolute;
            top: 50%;
            left: -100px;
            transform: translate(50%, -50%);
            width: 100px;
            height: 100px;
            padding: 6px;
            background: radial-gradient(circle farthest-corner at 28% 100%,
                    var(--yellow) 0%,
                    var(--yellow_to) 10%,
                    var(--orange) 22%,
                    var(--red) 35%,
                    transparent 65%), linear-gradient(145deg, var(--blue) 10%, var(--purple) 70%);
            border-radius: 50%;
        }

        #user_profile_container .profile-img::after {
            content: '';
            position: absolute;
            top: 50.1%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 93%;
            width: 93%;
            border-radius: 50%;
            border: 3px solid white;
        }

        @media screen and (max-width : 768px) {
            #user_profile_container {
                padding-top: 60px !important;
                padding-left: 1.5em !important;
            }

            #user_profile_container h5 {
                text-align: center;
            }

            #user_profile_container .profile-img {
                left: 50%;
                top: -115px;
                width: 115px;
                height: 115px;
                transform: translate(-50%, 50%);
            }

        }
    </style>
</head>

<body class="bg-light">
    <?php require("./inc/header.php") ?>
    <?php
    $general_q = "SELECT * FROM `general_settings` WHERE `id`=?";
    $value = [1];
    $general_res = select($general_q, $value, "i");
    $general_assoc = mysqli_fetch_assoc($general_res);
    ?>

    <?php
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect("./index.php");
    } else {
        $q = "SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1";
        $val = [$_SESSION['uId']];
        $q_res = select($q, $val, 'i');

        if ($q_res->num_rows == 0) {
            redirect("./index.php");
        } else {
            $u_assoc = mysqli_fetch_assoc($q_res);
        }
    }

    ?>

    <div class="container p-3">
        <div class="my-4">
            <h4 class="fw-bold fs-2">PROFILE</h4>
            <div class="d-flex align-items-center gap-2 mt-3">
                <a href="./index.php" class="anchor-hover text-decoration-none text-muted">HOME</a>
                <span class="text-muted"> / </span>
                <span>PROFILE</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="bg-white rounded shadow p-3 p-md-4" id="user_profile_container">
                    <div class="profile-img">
                        <img src="<?php echo USERS_FOLDER_PATH . $u_assoc['profile'] ?>">
                    </div>
                    <form id="user_profile">
                        <h5 class="fw-bold mb-4" id="user-name"></h5>
                        <div class="col-12 mb-3 p-0">
                            <label class="form-label">Update Profile</label>
                            <input spellcheck="false" type="file" name="picture" accept=".gif, .png , .jpeg , .jpg , .webp" class="shadow-none form-control">
                        </div>
                        <button class="btn-dark mt-3 btn btn-sm text-white shadow-none">CHANGE PROFILE</button>
                    </form>
                </div>
            </div>
            <div class="col-md-7 mt-lg-0 mt-md-0 mt-4">
                <div class="bg-white rounded shadow p-3 h-100 p-md-4">
                    <h5 class="fw-bold mb-4">BASIC DETAILS</h5>
                    <div class="row">
                        <div class="col-md-7" id="basic-details1">
                            <p style="font-size: 0.8em;" class="mb-2">
                                <strong class="mb-2 d-inline-block">Name</strong> : <?php echo $u_assoc['name'] ?><br>
                                <strong class="mb-2 d-inline-block">Email</strong> : <?php echo $u_assoc['email'] ?><br>
                                <strong class="mb-2 d-inline-block">Address</strong> : <?php echo $u_assoc['address'] ?><br>
                                <strong>Date of Birth</strong> : <?php echo date("d F Y", strtotime($u_assoc['dob'])) ?><br>
                            </p>
                        </div>
                        <div class="col-md-5" id="basic-details2">
                            <p style="font-size: 0.8em;">
                                <strong class="mb-2 d-inline-block">Pincode</strong> : <?php echo $u_assoc['pincode'] ?><br>
                                <strong>Phone Number</strong> : <?php echo $u_assoc['phone'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="bg-white rounded shadow p-3 p-md-4">
                    <form id="change_basic_detalis">
                        <h5 class="fw-bold mb-2">EDIT BASIC INFORMATION</h5>
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Name<span class="text-danger">*</span></label>
                                    <input spellcheck="false" value="<?php echo $u_assoc['name'] ?>" name="name" type="text" class="shadow-none form-control">
                                    <span id="name_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Phone Number<span class="text-danger">*</span></label>
                                    <input spellcheck="false" name="phone" value="<?php echo $u_assoc['phone'] ?>" type="number" class="shadow-none form-control">
                                    <span id="phone_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date of Birth<span class="text-danger">*</span></label>
                                    <input spellcheck="false" name="dob" type="date" value="<?php echo $u_assoc['dob'] ?>" class="shadow-none form-control">
                                    <span id="dob_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Address<span class="text-danger">*</span></label>
                                    <textarea spellcheck="false" style="resize: none;" name="address" class="shadow-none form-control" rows="3"><?php echo $u_assoc['address'] ?></textarea>
                                    <span id="address_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                                </div>
                                <div class="col-md-4 mb-3 d-flex flex-column align-items-end justify-content-between">
                                    <div class="w-100 mb-md-0 mb-lg-0 mb-4">
                                        <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                        <input spellcheck="false" name="pincode" type="number" value="<?php echo $u_assoc['pincode'] ?>" class="shadow-none form-control">
                                        <span id="pincode_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                                    </div>
                                    <div class="text-end w-100  mb-sm-0">
                                        <button class="btn-dark w-100 btn text-white shadow-none">SAVE CHANGES</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <?php require("./inc/footer.php") ?>
    <!-- For showing alerts and errors -->
    <div id="alert"></div>
    <div id="form-error"></div>
    <!-- Designed by name end -->



    <script>
        const change_basic_detalis = document.getElementById("change_basic_detalis");
        const user_profile = document.getElementById("user_profile");
        change_basic_detalis.addEventListener("submit", function(e) {
            e.preventDefault();
            // validation
            if (change_basic_detalis['name'].value.trim() == '' || change_basic_detalis['phone'].value.trim() == '' || change_basic_detalis['dob'].value.trim() == '' || change_basic_detalis['address'].value.trim() == '' || change_basic_detalis['pincode'].value.trim() == '') {
                custom_alert("All * fileds are required!", "e");
            } else {
                let formData = new FormData(change_basic_detalis);
                formData.append("change_details", "");
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "./ajax/change_basic_detalis.php", true);
                xhr.onload = function() {
                    let res = JSON.parse(this.responseText)
                    if (res.flag == "phone_already") {
                        custom_alert("Phone number already exit", 'e');
                    } else if (res.flag == "1") {
                        document.getElementById("user_name").innerText = res.username;
                        // formatting user dob
                        const date = new Date(res.user_data.dob);
                        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                        const day = date.getDate();
                        const month = monthNames[date.getMonth()];
                        const year = date.getFullYear();

                        const formattedDate = `${day} ${month} ${year}`;
                        // formatting user dob end 

                        document.getElementById("basic-details1").innerHTML = `
                                <p style="font-size: 0.8em;" class="mb-0">
                                    <strong class="mb-2 d-inline-block">Name</strong> : ${res.user_data.name}<br>
                                    <strong class="mb-2 d-inline-block">Email</strong> : ${res.user_data.email}<br>
                                    <strong class="mb-2 d-inline-block">Address</strong> : ${res.user_data.address}<br>
                                    <strong>Date of Birth</strong> : ${formattedDate}<br>
                                </p>
                        `;
                        document.getElementById("user-name").innerHTML = res.user_data.name;
                        document.getElementById("basic-details2").innerHTML = `
                                <p style="font-size: 0.8em;">
                                    <strong class="mb-2 d-inline-block">Pincode</strong> : ${res.user_data.pincode}<br>
                                    <strong>Phone Number</strong> : ${res.user_data.phone}
                                </p>
                        `;
                        document.getElementById("user_name").innerText = res.username;
                        custom_alert("Changes saved", 's');
                    } else if (res.flag == "0") {
                        custom_alert("No changes appiled", 'i');
                    } else {
                        custom_alert("Something went wrong!", 'e');
                    }
                }
                xhr.send(formData);
            }
        });
        user_profile.addEventListener("submit", function(e) {
            e.preventDefault();
            if (!user_profile['picture'].files[0]) {
                custom_alert("Please choose a file!", 'w');
            } else {
                let formData = new FormData(user_profile);
                formData.append("user_profile", "");
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "./ajax/change_basic_detalis.php", true);
                xhr.onload = function() {
                    let res = JSON.parse(this.responseText);
                    console.log(res.flag);
                    if (res.flag == "nofile") {
                        custom_alert("Please choose a file!", 'w');
                    } else if (res.flag == "1") {
                        user_profile.reset();
                        custom_alert("Profile changed", 's');
                        document.querySelector("#user_profile_container img").src = res.profile;
                        document.getElementById("login_user_profile").src = res.profile;
                    } else if (res.flag == "FileNotFound") {
                        custom_alert("Oops! File not found", 'e');
                    } else if (res.flag == "UnableToDelete") {
                        custom_alert("Unable to delete file!", 'e');
                    } else if (res.flag == "inv_mime") {
                        custom_alert("Invalid type of file (.jpg, .png, .jpeg, .webp or .gif only allowed)!", 'e');
                    } else if (res.flag == "not_uploaded") {
                        custom_alert("File uploadation failed!", 'e');
                    } else {
                        custom_alert("Something went wrong!", 'e');
                    }
                }
                xhr.send(formData);
            }
        });
        document.getElementById("user-name").innerHTML = '<?php echo $_SESSION['uName'] ?>'
    </script>

</body>

</html>