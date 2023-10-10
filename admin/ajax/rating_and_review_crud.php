<?php
require("../inc/db_config.php");
require("../inc/essentials.php");
isAdminLogin();
if (isset($_POST['getRatingAndReviews'])) {
    $query = "SELECT rr.*, uc.name AS uname, r.name AS rname  FROM `reviews_and_ratings` rr INNER JOIN `user_cred` uc
        ON rr.user_id = uc.id INNER JOIN `rooms` r ON rr.room_id = r.id
     ORDER BY `id` DESC";
    $res = $conn->query($query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        $datetime = date('d-m-Y h:i A', strtotime($row['date']));
        $seen = ($row['seen'] == 0)? "<button onclick='seenReview($row[id])' class='shadow-none btn btn-sm w-100 btn-info text-white p-0 py-2'><i class='bi bi-check-lg'></i>  Mark Seen</button>" : "";

        echo <<<data
            <tr>
                <td>$i</td>
                <td>$row[rname]</td>
                <td>$row[uname]</td>
                <td>$row[rating]</td>
                <td>$row[review]</td>
                <td>$datetime</td>
                <td>
                    <button onclick="deleteReview($row[id])" class='shadow-none btn btn-sm w-100 btn-danger mb-2 text-white'><i class='bi bi-trash'></i> DELETE</button>
                    $seen
                </td>
            </tr>
        data;
        $i++;
    }
}else if(isset($_POST['seenReview'])){
    $frm = filteration($_POST);
    $query = "UPDATE `reviews_and_ratings` SET `seen`=? WHERE `id`=?";
    $values = [1,$frm['id']];
    $res = update($query,$values,"ii");
    echo $res;
}else if(isset($_POST['markAllSeen'])){
    $frm = filteration($_POST);
    $query = "UPDATE `reviews_and_ratings` SET `seen`=? WHERE `seen`=?";
    $values = [1,0];
    $res = update($query,$values,"ii");
    if($res >= 1){
        echo "done";
    }else if($res == 0){
        echo "no_mark";
    }else{
        echo "error";
    }
}else if(isset($_POST['deleteReview'])){
    $frm = filteration($_POST);
    $query = "DELETE FROM `reviews_and_ratings` WHERE `id` = ?";
    $value = [$frm['id']];
    $res = delete($query,$value,'i');
    echo $res;
}

