<?php 
require("../inc/db_config.php");
require("../inc/essentials.php");
isAdminLogin();
if(isset($_POST['addFeature'])){
    $frm = filteration($_POST);
    $query = "INSERT INTO `features`(`feature_name`) VALUES (?)";
    $value = [$frm['feature-name']];
    $res = insert($query,$value,"s");
    echo $res;
}else if(isset($_POST['getFeatures'])){
    $frm = filteration($_POST);
    $table = "features";
    $res = selectAll($table);
    $i = 1;
    while($row = mysqli_fetch_assoc($res)){
        echo <<<data
            <tr class="align-middle">
                <td>$i</td>
                <td>$row[feature_name]</td>
                <td>
                    <button onclick="deleteFeature($row[id])" class="btn btn-sm btn-danger text-white shadow-none">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }
} else if(isset($_POST['deleteFeature'])){
    $frm = filteration($_POST);
    $check_q = select("SELECT * FROM `room_features` WHERE `feature_id` = ?",[$frm['id']],'i');
    if(mysqli_num_rows($check_q) == 0){
        $query = "DELETE FROM `features` WHERE `id`=?";
        $value = [$frm['id']];
        $res = delete($query,$value,"i");
        echo $res;
    }else{
        echo "roomadded";
    }
} else if(isset($_POST['addFacility'])){
    $frm = filteration($_POST);
    $upload_img_res = uploadImg($_FILES['facility_pic'], FACILITY_FOLDER);
    if($upload_img_res === 'not_uploaded' || $upload_img_res === 'inv_size' || $upload_img_res === 'inv_mime'){
        echo $upload_img_res;    
    }else if (strpos($upload_img_res, 'IMG_') !== false) { // checking if result contain IMG_
        $query = "INSERT INTO `facilities`(`name`, `picture`,`description`) VALUES (?,?,?)";
        $values = [$frm['facility_name'],$upload_img_res,$frm['facility_desc']];
        $res = insert($query,$values,"sss");
        echo $res;
    } else{
        echo "other";
    }
}else if(isset($_POST['getFacilities'])){
    $frm = filteration($_POST);
    $table = "facilities";
    $res = selectAll($table);
    $i = 1;
    while($row = mysqli_fetch_assoc($res)){
        $path = FACILITIES_IMAGE_FOLDER_PATH.$row['picture'];
        echo <<<data
            <tr class="align-middle">
                <td>$i</td>
                <td><img src="$path" width="50px"/></td>
                <td>$row[name]</td>
                <td>$row[description]</td>
                <td>
                    <button onclick="deleteFacility($row[id],'$row[picture]')" class="btn btn-sm btn-danger text-white shadow-none">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }
} else if(isset($_POST['deleteFacility'])){
    $frm = filteration($_POST);
    $check_q = select("SELECT * FROM `room_facilities` WHERE `facilities_id` = ?",[$frm['id']],'i');
    if(mysqli_num_rows($check_q) == 0){
        $path = UPLOAD_IMAGES_PATH.FACILITY_FOLDER.$frm['img'];
        $delRes = deleteFile($path);
        if($delRes == "Deleted"){
            $query = "DELETE FROM `facilities` WHERE `id`=?";
            $value = [$frm['id']];
            $res = delete($query,$value,"i");
            echo $res;
        }else {
            echo $delRes;
        }
    }else{
        echo "added";
    }
} 