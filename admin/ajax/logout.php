<?php 
    require("../inc/essentials.php");
    if(isset($_POST['logout'])){
        session_start();
        unset($_SESSION['admin_login']);
        unset($_SESSION['admin_id']);
        echo "logout";
    }
