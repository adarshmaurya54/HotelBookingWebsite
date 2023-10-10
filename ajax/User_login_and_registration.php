<?php

require("../admin/inc/db_config.php");
require("../inc/essentials.php");
date_default_timezone_set('Asia/Kolkata');

if (isset($_POST['registration'])) {
    $frm = filteration($_POST);
    if ($frm['password'] != $frm['c-password']) {
        echo "inv_pass";
        exit;
    }
    // checking if user is already exit...
    $u_q = "SELECT * FROM `user_cred` WHERE `email`=? OR `phone`=? LIMIT 1";
    $val = [$frm['email'], $frm['phone']];
    $res = select($u_q, $val, 'ss');
    if ($res->num_rows != 0) {
        $res_assoc = mysqli_fetch_assoc($res);
        echo ($res_assoc['email'] == $frm['email']) ? "email_already" : "phone_already";
        exit;
    }

    // Uploading user profile to server....
    $img_res = "default.png";
    if (!($_FILES['picture']['error'] === UPLOAD_ERR_NO_FILE)) {
        $img_res = uploadUserPic($_FILES['picture']);
        if ($img_res == "not_uploaded" || $img_res == "inv_mime") {
            echo $img_res;
            exit;
        }
    }
    // now sending verification link to user's email
    $token = bin2hex(random_bytes(16));
    if (!sendVarificationAndForgotMail($frm['email'], $frm['name'], $token, "verification")) {
        echo "email_send_error";
        exit;
    }

    $enc_pass = password_hash($frm['password'], PASSWORD_BCRYPT);

    $reg_q = "INSERT INTO `user_cred`(`name`, `password`, `email`, `profile`, `phone`, 
    `address`, `pincode`, `dob`, `token`) VALUES (?,?,?,?,?,?,?,?,?)";
    $reg_value = [
        $frm['name'], $enc_pass, $frm['email'], $img_res, $frm['phone'],
        $frm['address'], $frm['pincode'], $frm['date-of-birth'], $token
    ];

    if (insert($reg_q, $reg_value, "sssssssss")) {
        echo 1;
    } else {
        echo "ins_failed";
    }
}

if (isset($_POST['login'])) {
    $frm = filteration($_POST);
    // checking if user is already exit...
    $u_q = "SELECT * FROM `user_cred` WHERE `email`=? OR `phone`=? LIMIT 1";
    $val = [$frm['email_mob'], $frm['email_mob']];
    $res = select($u_q, $val, 'ss');
    if ($res->num_rows == 0) {
        echo "invalid_email_mob";
        exit;
    } else {
        $res_assoc = mysqli_fetch_assoc($res);
        if (!$res_assoc['is_verified']) {
            echo "not_verified";
            exit;
        } else if (!$res_assoc['status']) {
            echo "status";
            exit;
        } else {
            if (password_verify($frm['password'], $res_assoc['password'])) {
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['uId'] = $res_assoc['id'];
                $_SESSION['uName'] = $res_assoc['name'];
                $_SESSION['uPic'] = $res_assoc['profile'];
                $_SESSION['uMobile'] = $res_assoc['phone'];
                $_SESSION['uEmail'] = $res_assoc['email'];
                echo 1;
            } else {
                echo "not_matched";
                exit;
            }
        }
    }
}
if (isset($_POST['forgot'])) {
    $frm = filteration($_POST);
    //  checking if user is already exit...
    $u_q = "SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1";
    $val = [$frm['email']];
    $res = select($u_q, $val, 's');
    if ($res->num_rows == 0) {
        echo "invalid_email";
        exit;
    } else {
        $res_assoc = mysqli_fetch_assoc($res);
        if (!$res_assoc['is_verified']) {
            echo "not_verified";
            exit;
        } else if (!$res_assoc['status']) {
            echo "status";
            exit;
        } else {
            $token = bin2hex(random_bytes(16));
            if (!sendVarificationAndForgotMail($res_assoc['email'], $res_assoc['name'], $token, "account_recovery")) {
                echo "email_error";
                exit;
            } else {
                $currentTimestamp = time(); // Get the current timestamp
                $expiryTimestamp = strtotime('+2 days', $currentTimestamp); // Calculate the expiry timestamp

                // Format the expiry date as a string (optional)
                $expiryDate = date('Y-m-d', $expiryTimestamp);
                // forgot password link will expire within 2 days
                $upd_q = $conn->query("UPDATE `user_cred` SET `token`='$token',`t_expire`='$expiryDate' WHERE `id`=$res_assoc[id]");
                if (!$upd_q) {
                    echo "upd_failed";
                    exit;
                } else {
                    echo 1;
                }
            }
        }
    }
}
if (isset($_POST['reset_pass'])) {
    $frm = filteration($_POST);
    $user_q = 'SELECT * FROM `user_cred` WHERE `email`=? AND `token`=?';
    $value = [$frm['email'], $frm['token']];
    $res = select($user_q, $value, 'ss');
    if ($res->num_rows == 0) {
        echo "invemailtoken";
    } else {
        $res_assoc = mysqli_fetch_assoc($res);
        $ency_pass = password_hash($frm['password'],PASSWORD_BCRYPT);
        $upd_q = "UPDATE `user_cred` SET `password`=?,`token`=?,`t_expire`=? WHERE `id`=?";
        $upd_val = [$ency_pass,null,null,$res_assoc['id']];
        if(update($upd_q,$upd_val,'sssi')){
            echo 1;
        }else{
            echo 0;
        }
    }
}
