<?php
    require("./admin/inc/db_config.php");
    require("./admin/inc/essentials.php");
    session_start();
    $social_q = "SELECT * FROM `social_links`";
    $social_res = $conn->query($social_q);
    $general_q = "SELECT * FROM `general_settings` WHERE `id`=?";
    $value = [1];
    $general_res = select($general_q, $value, "i");
    $general_assoc = mysqli_fetch_assoc($general_res);

