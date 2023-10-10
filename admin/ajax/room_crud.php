<?php
require("../inc/db_config.php");
require("../inc/essentials.php");
isAdminLogin();
if (isset($_POST['addroom'])) {
    $features = filteration(json_decode($_POST['features']));
    $facilites = filteration(json_decode($_POST['facilities']));
    $frm  = filteration($_POST);
    $flag = 0;
    $query = "INSERT INTO `rooms`(`name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) VALUES (?,?,?,?,?,?,?)";
    $values = [$frm['name'], $frm['area'], $frm['price'], $frm['quantity'], $frm['adult'], $frm['children'], $frm['desc']];
    if (insert($query, $values, "siiiiis")) {
        $flag = 1;
    }
    $room_id = mysqli_insert_id($conn);

    $facilites_q = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?,?)";
    if ($stmt = mysqli_prepare($conn, $facilites_q)) {
        foreach ($facilites as $value) {
            mysqli_stmt_bind_param($stmt, "ii", $room_id, $value);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die("Query cannot be prepared - Insert");
    }

    $features_q = "INSERT INTO `room_features`(`room_id`, `feature_id`) VALUES (?,?)";
    if ($stmt = mysqli_prepare($conn, $features_q)) {
        foreach ($features as $value) {
            mysqli_stmt_bind_param($stmt, "ii", $room_id, $value);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die("Query cannot be prepared - Insert");
    }

    if ($flag == 1) {
        echo 1;
    } else {
        echo 0;
    }
} else if (isset($_POST['getAllRooms'])) {
    $res = select("SELECT * FROM `rooms` WHERE `removed`=?",[0],'i');
    $i = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        $adult = ($row['adult'] > 1) ? "Adults" : "Adult";
        $children = ($row['children'] > 1) ? "Childrens" : "Children";
        $status = ($row['status'] == 1) ? "<button onclick='toggleStatus($row[id],0)' class='btn btn-sm btn-info text-white shadow-none'>Active</button>" : "<button onclick='toggleStatus($row[id],1)' class='btn shadow-none btn-sm btn-warning text-white'>Inactive</button>";

        echo <<<data
            <tr class="align-middle">
                <td>$i</td>
                <td>$row[name]</td>
                <td>$row[area] sq. ft.</td>
                <td>₹$row[price] per night</td>
                <td>
                <span class="badge rounded-pill bg-light text-dark">$row[adult] $adult</span><br/>
                <span class="badge rounded-pill bg-light text-dark">$row[children] $children</span>
                </td>
                <td>$row[quantity]</td>
                <td class='text-center'>$status</td>
                <td class="text-center">
                    <button type="button" onclick="editRoom($row[id])" class="my-2 btn shadow-none btn-primary btn-sm " data-bs-toggle="modal" data-bs-target="#edit-rooms">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" onclick="getDataOfRoomForAddImageModal($row[id],'$row[name]')" class="btn shadow-none btn-secondary btn-sm " data-bs-toggle="modal" data-bs-target="#add-room-images">
                        <i class="bi bi-images"></i>
                    </button>
                    <button type="button" onclick="removeRoom($row[id])" class="my-2 btn shadow-none btn-danger btn-sm ">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }
} else if (isset($_POST['toggleStatus'])) {
    $frm = filteration($_POST);
    $q = "UPDATE `rooms` SET `status`=? WHERE `id`=?";
    $values = [$frm['value'], $frm['id']];
    $res = update($q, $values, 'ii');
    echo $res;
} else if (isset($_POST['editRoom'])) {
    $frm = filteration($_POST);
    $res1 = select("SELECT * FROM `rooms` WHERE `id`=?", [$frm['editRoom']], 'i');
    $res2 = select("SELECT * FROM `room_features` WHERE `room_id`=?", [$frm['editRoom']], 'i');
    $res3 = select("SELECT * FROM `room_facilities` WHERE `room_id`=?", [$frm['editRoom']], 'i');

    $room_data = mysqli_fetch_assoc($res1);
    $features = [];
    while ($row = mysqli_fetch_assoc($res2)) {
        array_push($features, $row['feature_id']);
    }
    $facilities = [];
    while ($row = mysqli_fetch_assoc($res3)) {
        array_push($facilities, $row['facilities_id']);
    }

    $data = ["room_data" => $room_data, "room_features" => $features, "room_facilities" => $facilities];
    $data = json_encode($data);
    echo $data;
} else if (isset($_POST['editroom'])) {
    $features = filteration(json_decode($_POST['features']));
    $facilites = filteration(json_decode($_POST['facilities']));
    $frm  = filteration($_POST);
    $flag = 0;
    $query = "UPDATE `rooms` SET `name`=?,`area`=?,`price`=?,`quantity`=?,`adult`=?,`children`=?,`description`=? WHERE `id` = ?";
    $values = [$frm['name'], $frm['area'], $frm['price'], $frm['quantity'], $frm['adult'], $frm['children'], $frm['desc'], $frm['room_id']];
    if (update($query, $values, "siiiiisi")) {
        $flag = 1;
    }

    $del_features = delete("DELETE FROM `room_features` WHERE `room_id` = ?", [$frm['room_id']], 'i');
    $del_facilities = delete("DELETE FROM `room_facilities` WHERE `room_id` = ?", [$frm['room_id']], 'i');

    if (!($del_facilities && $del_features)) {
        $flag = 0;
    } else {
        $facilites_q = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?,?)";
        if ($stmt = mysqli_prepare($conn, $facilites_q)) {
            foreach ($facilites as $value) {
                mysqli_stmt_bind_param($stmt, "ii", $frm['room_id'], $value);
                mysqli_stmt_execute($stmt);
            }
            $flag = 1;
            mysqli_stmt_close($stmt);
        } else {
            $flag = 0;
            die("Query cannot be prepared - Insert");
        }
        
        $features_q = "INSERT INTO `room_features`(`room_id`, `feature_id`) VALUES (?,?)";
        if ($stmt = mysqli_prepare($conn, $features_q)) {
            foreach ($features as $value) {
                mysqli_stmt_bind_param($stmt, "ii", $frm['room_id'], $value);
                mysqli_stmt_execute($stmt);
            }
            $flag = 1;
            mysqli_stmt_close($stmt);
        } else {
            $flag = 0;
            die("Query cannot be prepared - Insert");
        }
    }

    if($flag == 1){
        echo 1;
    }else{
        echo 0;
    }

} else if (isset($_POST['add_room_img'])) {
    $frm = filteration($_POST);
    $upload_img_res = uploadImg($_FILES['room-pic'],ROOMS_FOLDER);
    if($upload_img_res === 'not_uploaded' || $upload_img_res === 'inv_size' || $upload_img_res === 'inv_mime'){
        echo $upload_img_res;    
    }else if (strpos($upload_img_res, 'IMG_') !== false) { // checking if result contain IMG_
        $query = "INSERT INTO `room_images`(`room_id`, `room_img`) VALUES (?,?)";
        $values = [$frm['room_id'],$upload_img_res];
        $res = insert($query,$values,"is");
        echo $res;
    } else{
        echo "other";
    }
} else if (isset($_POST['getAllRoomsImages'])) {
    $frm = filteration($_POST);
    $res = select("SELECT * FROM `room_images` WHERE `room_id`=?",[$frm['id']],'i');
    $i = 1;
    while($row = mysqli_fetch_assoc($res)){
        $path = ROOMS_IMAGE_FOLDER_PATH.$row['room_img'];
        $thumb = ($row['thumbnail']  == 0)? "<i onclick=\"changeThumb($row[id],$row[room_id],1)\" style='cursor: pointer;' class='bi bi-x-square text-info fs-3'></i>" : "<i onclick=\"changeThumb($row[id],$row[room_id],0)\" style='cursor: pointer;' class='bi bi-check-square-fill text-success fs-3'></i>";
        echo <<<data
            <tr class="align-middle">
                <td>$i</td>
                <td><img src="$path" width="200px" class="rounded shadow"/></td>
                <td class="text-center" >$thumb</td>
                <td>
                    <button onclick="deleteRoomImage($row[id],'$row[room_img]')" class="btn btn-sm btn-danger shadow-none">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }
} else if (isset($_POST['changeThumb'])) {
    $frm = filteration($_POST);
    if($frm['thumb_val'] == 1){
        $q1 = update("UPDATE `room_images` SET `thumbnail`=? WHERE `room_id`=? AND `thumbnail`=?",[0,$frm['room_id'],1],"iii");
        $q2 = update("UPDATE `room_images` SET `thumbnail`=? WHERE `id`=?",[$frm['thumb_val'],$frm['img_id']],"ii");
    }else{
        $q2 = update("UPDATE `room_images` SET `thumbnail`=? WHERE `id`=?",[$frm['thumb_val'],$frm['img_id']],"ii");
    }
    echo $q2;
} else if (isset($_POST['deleteRoomImage'])) {
    $frm = filteration($_POST);
    $path = UPLOAD_IMAGES_PATH.ROOMS_FOLDER.$frm['img_name'];
    $delRes = deleteFile($path);
    if($delRes == "Deleted"){
        $query = "DELETE FROM `room_images` WHERE `id`=?";
        $value = [$frm['img_id']];
        $res = delete($query,$value,"i");
        echo $res;
    }else {
        echo $delRes;
    }
} else if (isset($_POST['removeRoom'])) {
    $frm = filteration($_POST);
    $q1 = select("SELECT * FROM `room_images` WHERE `room_id`=?",[$frm['id']],'i');
    while($row = mysqli_fetch_assoc($q1)){
        $path = UPLOAD_IMAGES_PATH.ROOMS_FOLDER.$row['room_img'];
        deleteFile($path);
    }

    $res1 = delete("DELETE FROM `room_images` WHERE `room_id`=?",[$frm['id']],'i');
    $res2 = delete("DELETE FROM `room_features` WHERE `room_id`=?",[$frm['id']],'i');
    $res3 = delete("DELETE FROM `room_facilities` WHERE `room_id`=?",[$frm['id']],'i');

    $res4 = update("UPDATE `rooms` SET `removed`=? WHERE `id`=?",[1,$frm['id']],'ii');

    if($res1 || ($res2 && $res3)){
        echo 1;
    }else{
        echo 0;
    }
}