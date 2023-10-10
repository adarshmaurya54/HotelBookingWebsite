<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - FORGOT PASSWORD</title>
    <?php require("./inc/links.php") ?>
    <style>
        #alert {
            top: 20px !important;
            right: 20px !important;
        }
    </style>
</head>

<body class="bg-light" style="height: 100vh;">
    <?php
    if (!(isset($_GET['forgot_pass']) && isset($_GET['email']) && isset($_GET['token']))) {
        redirect("./index.php");
    } else {
        date_default_timezone_set('Asia/Kolkata');
        $frm = filteration($_GET);
        $user_q = 'SELECT * FROM `user_cred` WHERE `email`=? AND `token`=?';
        $value = [$frm['email'], $frm['token']];
        $res = select($user_q, $value, 'ss');
        if ($res->num_rows == 0) {
            echo "<script>alert('Invalid link')</script>";
            redirect("./index.php");
        } else {
            $res_assoc = mysqli_fetch_assoc($res);
            $date1 = strtotime($res_assoc['t_expire']); 
            $date2 = strtotime(date("Y-m-d")); 
            if($date1 <= $date2){
                echo "<script>alert('Link expired!')</script>";
                redirect("./index.php");
            }
        }
    }
    ?>

    <!-- reset form  -->
    <div class=" d-flex align-items-center justify-content-center container w-100 h-100">
        <div style="max-width: 450px;" class="px-5 py-4 shadow rounded bg-white">
            <h3 class="m-0 p-0 mb-3 pb-2 border-bottom"><i class="bi bi-shield-lock"></i> Setup New Password</h3>
            <form id="reset_pass_form">
                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="pass" class="form-control shadow-none">
                    <div class="form-text"><i class="bi bi-exclamation-circle-fill"></i> Your password should be between 6 to 20 characters and include at least one numeric digit, one uppercase letter, and one lowercase letter.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="c-pass" class="form-control shadow-none">
                </div>
                <input type="hidden" name="email" value="<?php echo $frm['email'] ?>">
                <input type="hidden" name="token" value="<?php echo $frm['token'] ?>">
                <button type="submit" class="shadow-none btn btn-dark">Submit</button>
            </form>
        </div>
    </div>
    <!-- reset form end -->


    <!-- For showing alerts and errors -->
    <div id="alert"></div>
    <div id="form-error"></div>
    <!-- Designed by name end -->
    <script src="./admin/scripts/essentials.js"></script>
    <script>
        const reset_pass_form = document.getElementById("reset_pass_form");
        reset_pass_form.addEventListener("submit", function(e) {
            e.preventDefault();
            var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
            let flag = true;
            if (reset_pass_form['pass'].value.trim() == '') {
                custom_alert("Password field can't be blank!", 'e');
                flag = false;
            } else if (!(reset_pass_form['pass'].value.match(passw))) {
                custom_alert("<i class='bi bi-exclamation-circle-fill'></i> Your password should be between 6 to 20 characters and include at least one numeric digit, one uppercase letter, and one lowercase letter.", 'e');
                flag = false;
            } else if (reset_pass_form['pass'].value.trim() != '' && reset_pass_form['c-pass'].value.trim() == '') {
                custom_alert("Confirm password field can't be blank!", 'e');
                flag = false;
            }

            if (reset_pass_form['pass'].value.trim() != '' && flag) {
                if (reset_pass_form['pass'].value.trim() != reset_pass_form['c-pass'].value.trim()) {
                    custom_alert("Confirm password not match with your password!", 'e');
                    flag = false;
                }
            }
            if (flag) {
                // ajax request
                custom_alert("Please wait", 'i', true, 'l');
                let formData = new FormData();
                formData.append("password", reset_pass_form['pass'].value.trim());
                formData.append("email", reset_pass_form['email'].value.trim());
                formData.append("token", reset_pass_form['token'].value.trim());
                formData.append("reset_pass", '');
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "./ajax/User_login_and_registration.php", true);
                xhr.onload = function() {
                    let res = xhr.responseText;
                    if(res == '1'){
                        custom_alert("Your password has been reset successfully. Please wait","s",true,'l');
                        setTimeout(function(){
                            window.location = "./index.php";
                        },3000);
                    }else if(res == "invemailtoken"){
                        custom_alert("Email or Token is invalid","e");
                    }else{
                        custom_alert("Password reset failed","e");
                    }
                }
                xhr.send(formData);
            }
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>