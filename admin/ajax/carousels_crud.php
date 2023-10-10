<?php 
require("../inc/db_config.php");
require("../inc/essentials.php");
isAdminLogin();
if(isset($_POST['addCarousel'])){
    $upload_img_res = uploadImg($_FILES['carousel-pic'],CAROUSEL_FOLDER);
    if($upload_img_res === 'not_uploaded' || $upload_img_res === 'inv_size' || $upload_img_res === 'inv_mime'){
        echo $upload_img_res;    
    }else if (strpos($upload_img_res, 'IMG_') !== false) { // checking if result contain IMG_
        $query = "INSERT INTO `carousels`(`picture`) VALUES (?)";
        $values = [$upload_img_res];
        $res = insert($query,$values,"s");
        echo $res;
    } else{
        echo "other";
    }
} else if(isset($_POST['getCarousels'])){
    $frm = filteration($_POST);
    $query = "SELECT * FROM `carousels`";
    $res = $conn->query($query);
    if($res->num_rows > 0){
        $data = "";
        while($row = mysqli_fetch_assoc($res)){
            $img_url = CAROUSEL_IMAGE_FOLDER_PATH.$row['picture'];
            $data .= "
                <div class='col-lg-4 col-md-6 mb-3'>          
                    <div class='card bg-dark border-0 text-white'>
                        <img src='$img_url' class='shadow card-img' alt='$row[picture]'>
                        <div class='card-img-overlay  p-2 text-end'>
                            <button onclick=\"deleteCarousel($row[id],'$row[picture]')\" class='shadow-none btn-danger btn btn-sm'>
                                <i class='bi bi-trash'></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            ";
        }
        echo $data;
    }
} else if(isset($_POST['deleteCarousel'])){
    $frm = filteration($_POST);
    $path = UPLOAD_IMAGES_PATH.CAROUSEL_FOLDER.$frm['img'];
    $delRes = deleteFile($path);
    if($delRes == "Deleted"){
        $query = "DELETE FROM `carousels` WHERE `id`=?";
        $value = [$frm['id']];
        $res = delete($query,$value,"i");
        echo $res;
    }else {
        echo $delRes;
    }
}
