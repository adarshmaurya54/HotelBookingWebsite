<?php

require("admin/inc/db_config.php");
require("admin/inc/essentials.php");
$general_q = "SELECT * FROM `general_settings` WHERE `id`=?";
$value = [1];
$general_res = select($general_q, $value, "i");
$general_assoc = mysqli_fetch_assoc($general_res);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='icon' href='./images/logo.png'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $general_assoc['site_title'] ?> - Email varification</title>
</head>

<body>
    <?php
    if (isset($_GET['email_confirmation'])) {
        $frm = filteration($_GET);
        $user_q = 'SELECT * FROM `user_cred` WHERE `email`=? AND `token`=?';
        $value = [$frm['email'], $frm['token']];
        $res = select($user_q, $value, 'ss');
        if ($res->num_rows == 0) {
            echo "<script>alert('Invalid email or token')</script>";
            redirect("./index.php");
        } else {
            $res_assoc = mysqli_fetch_assoc($res);
            if ($res_assoc['is_verified']) {
                echo "<script>alert('Email is already verified!')</script>";
                redirect("./index.php");
            } else {
                $upd_q = "UPDATE `user_cred` SET `is_verified`=? WHERE `id`=?";
                $upd_v = [1, $res_assoc['id']];
                if (update($upd_q, $upd_v, 'ii')) {
                    echo "<script>alert('Email varification successfull')</script>";
                    redirect("./index.php");
                } else {
                    echo "<script>alert('Server down! please try again latter')</script>";
                    redirect("./index.php");
                }
            }
        }
    } else {
        echo "<script>alert('Invalid link')</script>";
        redirect("./index.php");
    }
    ?>
</body>

</html>