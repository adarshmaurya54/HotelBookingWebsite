<?php
require("../inc/db_config.php");
require("../inc/essentials.php");
isAdminLogin();
if (isset($_POST['getUserQueries'])) {
    $query = "SELECT * FROM `user_queries` ORDER BY `id` DESC";
    $res = $conn->query($query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $row['date']);
        $dateFormatted = $datetime->format('d/m/y');
        $seen = ($row['seen'] == 0)? "<button onclick='seenQuery($row[id])' class='shadow-none btn btn-sm w-100 btn-info text-white p-0 py-2'><i class='bi bi-check-lg'></i>  Mark Seen</button>" : "";

        echo <<<data
            <tr>
                <td>$i</td>
                <td>$row[name]</td>
                <td>$row[email]</td>
                <td>$row[subject]</td>
                <td>$row[message]</td>
                <td>$dateFormatted</td>
                <td>
                    <button onclick="deleteQuery($row[id])" class='shadow-none btn btn-sm w-100 btn-danger mb-2 text-white'><i class='bi bi-trash'></i> DELETE</button>
                    $seen
                </td>
            </tr>
        data;
        $i++;
    }
}else if(isset($_POST['seenQuery'])){
    $frm = filteration($_POST);
    $query = "UPDATE `user_queries` SET `seen`=? WHERE `id`=?";
    $values = [1,$frm['id']];
    $res = update($query,$values,"ii");
    echo $res;
}else if(isset($_POST['markAllSeen'])){
    $frm = filteration($_POST);
    $query = "UPDATE `user_queries` SET `seen`=? WHERE `seen`=?";
    $values = [1,0];
    $res = update($query,$values,"ii");
    if($res >= 1){
        echo "done";
    }else if($res == 0){
        echo "no_mark";
    }else{
        echo "error";
    }
}else if(isset($_POST['deleteAll'])){
    $frm = filteration($_POST);
    $query = "DELETE FROM `user_queries`";
    $res = $conn->query($query);
    if($res){
        echo "done";
    }else{
        echo "error";
    }
}else if(isset($_POST['deleteQuery'])){
    $frm = filteration($_POST);
    $query = "DELETE FROM `user_queries` WHERE `id` = ?";
    $value = [$frm['id']];
    $res = delete($query,$value,'i');
    echo $res;
}

