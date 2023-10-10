<?php
require("../inc/db_config.php");
require("../inc/essentials.php");
session_start();
if(isset($_POST['admin_login'])){
    $frm = filteration($_POST);
    if($frm['adminname'] == '' || $frm['adminpass'] == ''){
        echo "blank";
    }else{
        $query = "SELECT * FROM `admin_login` WHERE `admin_name`=?";
        $values = [$frm['adminname']];
        $res = select($query,$values,"s");
        if($res->num_rows > 0){
            $res_assoc = mysqli_fetch_assoc($res);
            if(password_verify($frm['adminpass'],$res_assoc['password'])){
                $_SESSION['admin_login'] = true;
                $_SESSION['admin_id'] = $res_assoc['id'];
                echo "success";
            }else{
                echo "inv_pass";
            }
        }else{
            echo "inv_user";
        }
    }
}
