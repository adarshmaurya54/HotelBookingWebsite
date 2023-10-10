<?php
require("../inc/db_config.php");
require("../inc/essentials.php");
isAdminLogin();
if (isset($_POST['get_general_s'])) {
    $query = "select * from `general_settings`";
    $res = $conn->query($query);
    if ($res->num_rows > 0) {
        $res_assoc = mysqli_fetch_assoc($res);
        $result = json_encode($res_assoc);
        echo $result;
    } else {
        $result = json_encode(["site_title" => "no_data"]);
        echo $result;
    }
} else if (isset($_POST['change_general_s'])) {
    $frm = filteration($_POST);
    $query = "UPDATE `general_settings` SET `site_title`=?,`site_desc`=? WHERE id=?";
    $values = [$frm['site_title'], $frm['site_desc'], 1];
    $res = update($query, $values, 'ssi');
    echo $res;
} else if (isset($_POST['changeShutdown'])) {
    $frm = filteration($_POST);
    $query = "UPDATE `general_settings` SET `shutdown`=? WHERE `id`=?";
    $values = [$frm['val'], 1];
    $res = update($query, $values, "si");
    if ($res == 1) {
        echo $frm['val'];
    }
} else if (isset($_POST['editContact'])) {
    $frm = filteration($_POST);
    $query = "UPDATE `contact_settings` SET `address`=?,`gmap`=?,`ph1`=?,`ph2`=?,`email`=?,`iframe`=? WHERE `id`=?";
    $values = [$frm['address'], $frm['gmap'], $frm['ph1'], $frm['ph2'], $frm['email'], $frm['iframe'], 1];
    $res = update($query, $values, 'ssssssi');
    echo $res;
} else if (isset($_POST['getContact'])) {
    $query = "SELECT * FROM `contact_settings`";
    $res = $conn->query($query);
    if ($res->num_rows >= 1) {
        $res_assoc = mysqli_fetch_assoc($res);
        $result = json_encode($res_assoc);
        echo $result;
    }
} else if (isset($_POST['getSocial'])) {
    $query = "SELECT * FROM `social_links`";
    $res = $conn->query($query);

    if ($res->num_rows >= 1) {
        $results1 = "";
        $results2 = "";
        $i = 1;
        while ($row = mysqli_fetch_assoc($res)) {
            $results1 .= "
                <p class='mb-1'>
                    <i class='bi $row[icon_class_name]'></i>
                    <span id='fb'>$row[social_link]</span>
                </p>
            "; 
            $results2 .= "
                <div class='input-group mb-3'>
                    <div class='input-group-prepend h-100'>
                        <span class='input-group-text'>
                            <i class='bi $row[icon_class_name]'></i>
                        </span>
                    </div>
                    <input spellcheck='false' name='$i' value='$row[social_link]' type='text' class='shadow-none form-control'>
                    <input type='hidden' name='id-$i' value='$row[id]'>                
                </div>
            "; 
            $i++;
        }
        $json_result = json_encode(["result1"=>$results1, "result2"=>$results2]);
        echo $json_result;
    }
} else if (isset($_POST['updateSocialLinks'])) {
    $frm = filteration($_POST);
    for ($i=1; $i <= $frm['length']; $i++) { 
        $id = "id-".$i;
        $query = "UPDATE `social_links` SET `social_link`=? WHERE `id`=?";
        $values = [$frm[$i],$frm[$id]];
        $res = update($query,$values,"si");
        echo $res;
    }
} else if (isset($_POST['addSocialLink'])) {
    $frm = filteration($_POST);
    if($frm['bootstrapclass'] == ''){
        $query = "INSERT INTO `social_links`(`name`) VALUES (?)";
        $value = [$frm['nameofsocialmedia']];
        $res = insert($query,$value,"s");
    }else{
        $query = "INSERT INTO `social_links`(`name`,`icon_class_name`) VALUES (?,?)";
        $value = [$frm['nameofsocialmedia'],$frm['bootstrapclass']];
        $res = insert($query,$value,"ss");
    }
    echo $res;
} else if (isset($_POST['addManagement'])) {
    $frm = filteration($_POST);
    $upload_img_res = uploadImg($_FILES['manag-pic'], ABOUT_FOLDER);
    if($upload_img_res === 'not_uploaded' || $upload_img_res === 'inv_size' || $upload_img_res === 'inv_mime'){
        echo $upload_img_res;    
    }else if (strpos($upload_img_res, 'IMG_') !== false) { // checking if result contain IMG_
        $query = "INSERT INTO `team_details`(`name`, `picture`) VALUES (?,?)";
        $values = [$frm['manag-name'],$upload_img_res];
        $res = insert($query,$values,"ss");
        echo $res;
    } else{
        echo "other";
    }
} else if (isset($_POST['getManagementTeam'])) {
    $frm = filteration($_POST);
    $query = "SELECT * FROM `team_details`";
    $res = $conn->query($query);
    if($res->num_rows > 0){
        $data = "";
        while($row = mysqli_fetch_assoc($res)){
            $img_url = ABOUT_IMAGE_FOLDER_PATH.$row['picture'];
            $data .= "
                <div class='col-md-2 mb-md-0 mb-3 mb-lg-0 '>
                    <div class= 'card bg-white text-dark border-0 '>
                        <img src= '$img_url' class='card-img team-img shadow' alt='$row[picture]'>
                        <div class='card-img-overlay text-end p-2 '>
                            <button type='button' onclick=\"deleteManagTeam($row[id],'$row[picture]')\" class='btn btn-danger btn-sm shadow-none'>
                                <i class='bi bi-trash'></i> Delete
                            </button>
                        </div>
                        <p class='card-text text-center py-2 px-3'>$row[name]</p>
                    </div>
                </div>
            ";
        }
        echo $data;
    }
} else if (isset($_POST['deleteManagTeam'])) {
    $frm = filteration($_POST);
    $path = UPLOAD_IMAGES_PATH.ABOUT_FOLDER.$frm['img'];
    $delRes = deleteFile($path);
    if($delRes == "Deleted"){
        $query = "DELETE FROM `team_details` WHERE `id`=?";
        $value = [$frm['id']];
        $res = delete($query,$value,"i");
        echo $res;
    }else {
        echo $delRes;
    }
}

