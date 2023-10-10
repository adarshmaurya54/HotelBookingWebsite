<?php

require("../admin/inc/db_config.php");
require("../inc/essentials.php");
session_start();
if (isset($_POST['change_details'])) {
    $frm = filteration($_POST);

    $q = "SELECT * FROM `user_cred` WHERE `phone`=? AND `id`!=?";
    $value = [$frm['phone'], $_SESSION['uId']];
    $res = select($q, $value, 'si');
    if ($res->num_rows > 0) {
        echo json_encode(["flag" => "phone_already"]);
        exit;
    } else {
        $upd_q = "UPDATE `user_cred` SET `name`=?, `phone`=?, `dob`=?, `address`=?, `pincode`=? WHERE `id`=?";
        $upd_value = [$frm['name'], $frm['phone'], $frm['dob'], $frm['address'], $frm['pincode'], $_SESSION['uId']];
        if (update($upd_q, $upd_value, 'sssssi')) {
            $_SESSION['uMobile'] = $frm['phone'];
            $_SESSION['uName'] = $frm['name'];
            $selectdata = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], 'i');
            $sel_assoc = mysqli_fetch_assoc($selectdata);
            echo json_encode(["flag" => 1, "username" => $_SESSION['uName'], "user_data" => $sel_assoc]);
        } else {
            echo json_encode(["flag" => 0]);
        }
    }
} else if (isset($_POST['user_profile'])) {
    // Uploading user profile to server....
    if (!($_FILES['picture']['error'] === UPLOAD_ERR_NO_FILE)) {
        // if user's image is not default.png then we have to delete the user's previous picture otherwise not 
        if ($_SESSION['uPic'] != "default.png") {
            $path = UPLOAD_IMAGES_PATH . USER_FOLDER . $_SESSION['uPic'];
            $delRes = deleteFile($path);
            if ($delRes == "Deleted") {
                $img_res = uploadUserPic($_FILES['picture']);
                if ($img_res == "not_uploaded" || $img_res == "inv_mime") {
                    echo json_encode(["flag" => $img_res]);
                    exit;
                }
                $upd = update("UPDATE `user_cred` SET `profile`=? WHERE `id`=?", [$img_res, $_SESSION['uId']], "si");
                if ($upd == 1) {
                    $_SESSION['uPic'] = $img_res;
                    echo json_encode(["flag" => 1, "profile" => USERS_FOLDER_PATH . $img_res]);
                }
            } else {
                echo json_encode(["flag" => $delRes]);
            }
        } else {
            $img_res = uploadUserPic($_FILES['picture']);
            if ($img_res == "not_uploaded" || $img_res == "inv_mime") {
                echo json_encode(["flag" => $img_res]);
                exit;
            }
            $upd = update("UPDATE `user_cred` SET `profile`=? WHERE `id`=?", [$img_res, $_SESSION['uId']], "si");
            if ($upd == 1) {
                $_SESSION['uPic'] = $img_res;
                echo json_encode(["flag" => 1, "profile" => USERS_FOLDER_PATH . $img_res]);
            }
        }
    } else {
        echo json_encode(["flag" => "nofile"]);
    }
}
