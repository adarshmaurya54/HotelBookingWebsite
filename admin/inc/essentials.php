<?php

// below all defined keywords are used for frontedn purpose
define("SITE_URL", "http://localhost/hbwebsite/");
define("ABOUT_IMAGE_FOLDER_PATH", SITE_URL . "images/about/");
define("FACILITIES_IMAGE_FOLDER_PATH", SITE_URL . "images/facilities/");
define("CAROUSEL_IMAGE_FOLDER_PATH", SITE_URL . "images/carousels/");
define("ROOMS_IMAGE_FOLDER_PATH", SITE_URL . "images/rooms/");
define("USERS_FOLDER_PATH", SITE_URL . "images/user_dp/");
define("LOGO", SITE_URL . "images/logo2.png");
define("LOGO1", SITE_URL . "images/2.png");
define("BG", SITE_URL . "images/bg.png");


// below all defined keywords are used for backed purpose
define("UPLOAD_IMAGES_PATH", $_SERVER['DOCUMENT_ROOT'] . '/hbwebsite/images/');
define("ABOUT_FOLDER", 'about/');
define("FACILITY_FOLDER", 'facilities/');
define("CAROUSEL_FOLDER", 'carousels/');
define("ROOMS_FOLDER", 'rooms/');
define("USER_FOLDER", 'user_dp/');

// below all constant for phpmailer
define("EMAIL_ID", "youremailID");
define("MAILER_HOST", "smtp.gmail.com");
define("EMAIL_NAME", "Yourname");
define("APP_PASSWORD", "YourAppPass");

// booking status values = booked, canceled, pending, payment failed

// to configure razorpay payment gatewaye "../../razorpay/config.php"
define("RAZORPAY_KEY_ID", "Your razorpay key id");
define("RAZORPAY_KEY_SECRET", "Your razorpay key secret");

//Note : To install mpdf library first install composer on your system then execute `composer require mpdf/mpdf` this command on terminal or cmd



function redirect($url)
{
    echo "
    <script>
        window.location.href = '$url';
    </script>
    ";
}
function isAdminLogin()
{
    session_start();
    if (!(isset($_SESSION['admin_login']) && $_SESSION['admin_login'] == true)) {
        redirect("./login.php");
    }
}
function isAdminAreadyLogin()
{
    session_start();
    if (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] == true) {
        redirect("./index.php");
    }
}
function uploadImg($file, $path)
{
    $validMime = ['image/jpg', 'image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
    $file_mime = $file['type'];
    if (!in_array($file_mime, $validMime)) {
        return 'inv_mime';
    } else if (($file['size'] / (1024 * 1024)) > 2) {
        return 'inv_size'; // calculating size of file from bits to mb 
    } else {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION); #This will return file extention (e.g 1.mp3 = mp3)
        date_default_timezone_set('Asia/Kolkata'); // Set the timezone to India (IST)

        $newfilename = "IMG_" . date("Ymd_His") . "." . $extension;

        $imgpath = UPLOAD_IMAGES_PATH . $path . $newfilename;
        if (move_uploaded_file($file['tmp_name'], $imgpath)) {
            return $newfilename;
        } else {
            return 'not_uploaded';
        }
    }
}
function deleteFile($path)
{
    if (file_exists($path)) {
        if (unlink($path)) {
            return "Deleted";
        } else {
            return "UnableToDelete";
        }
    } else {
        return "FileNotFound";
    }
}
function uploadUserPic($file)
{
    $validMime = ['image/jpg', 'image/jpeg', 'image/png', 'image/svg+xml', 'image/webp', 'image/gif'];
    $file_mime = $file['type'];
    if (!in_array($file_mime, $validMime)) {
        return 'inv_mime';
    } else {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        date_default_timezone_set('Asia/Kolkata'); // Set the timezone to India (IST)
        if ($extension !== 'gif' && $extension !== 'GIF') {
            $newfilename = "IMG_" . date("Ymd_His") . ".jpeg";
            
            $imgpath = UPLOAD_IMAGES_PATH . USER_FOLDER . $newfilename;
            if ($extension == "png" || $extension == "PNG") {
                    $img = imagecreatefrompng($file['tmp_name']);
                } else if ($extension == "webp" || $extension == "WEBP") {
                        $img = imagecreatefromwebp($file['tmp_name']);
                    } else {
                            $img = imagecreatefromjpeg($file['tmp_name']);
                        }
                        
                        if (imagejpeg($img, $imgpath, 40)) {
                return $newfilename;
            } else {
                return 'not_uploaded';
            }
        } else {
            $newfilename = "GIF_" . date("Ymd_His") . "." . $extension;

            $imgpath = UPLOAD_IMAGES_PATH . USER_FOLDER . $newfilename;
            if (move_uploaded_file($file['tmp_name'], $imgpath)) {
                return $newfilename;
            } else {
                return 'not_uploaded';
            }
        }
    }
}
