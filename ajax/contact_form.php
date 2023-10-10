<?php 
require("../inc/essentials.php");
require("../admin/inc/db_config.php");
if(isset($_POST['submitContactUsForm'])){
    $frm = filteration($_POST);
    $query = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
    $values = [$frm['cont_name'],$frm['cont_email'],$frm['cont_subject'],$frm['cont_msg']];
    $res = insert($query,$values,"ssss");
    if($res >= 1 && sendMail($frm['cont_email'],$frm['cont_name'],$frm['cont_subject'],$frm['cont_msg'])){
        echo "send";
    }else{
        echo "error";
    }
}