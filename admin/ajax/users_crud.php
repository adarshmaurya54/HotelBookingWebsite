<?php
require("../inc/db_config.php");
require("../inc/essentials.php");
isAdminLogin();
if (isset($_POST['getAllUsers'])) {
    $frm = filteration($_POST);
    $q = "SELECT * FROM `user_cred` WHERE `name` LIKE ? OR `email` LIKE ? OR `phone` LIKE ? OR `address` LIKE ?";
    $values = ["%$frm[search]%","%$frm[search]%","%$frm[search]%","%$frm[search]%"];
    $res = select($q,$values,"ssss");
    $limit = 4;
    $page = $frm['page'];
    $start = ($page - 1) * $limit;
    $limit_q = $q." LIMIT $start, $limit";
    $limit_res = select($limit_q,$values,"ssss");
    $user_data = "";
    if ($limit_res->num_rows > 0) {
        $i = $start + 1;
        while ($row = mysqli_fetch_assoc($limit_res)) {

            $deletePage = ($limit_res->num_rows == 1) ? ($page - 1) : $page;

            $timestamp = strtotime($row["dob"]);
            $formattedDOB = date("d-m-Y", $timestamp);
            $timestamp1 = strtotime($row["date"]);
            $formatteddate = date("m-d-Y h:i:s A", $timestamp1);
            $img_path = USERS_FOLDER_PATH . $row['profile'];
            $verified = ($row['is_verified']) ? "<span class='badge rounded-pill bg-success'><i class='bi bi-patch-check-fill'></i> VERIFIED</span>" : "<span class='badge rounded-pill bg-danger'><i class='bi bi-patch-plus-fill' style='display: inline-block;transform: rotate(45deg);'></i> NOT VERIFIED</span>";
            $status = ($row['status']) ? "<button onclick='toggleStatus($row[id],0,$deletePage)' class='shadow-none btn badge rounded-pill bg-success text-white'>ACTIVE</button>" : "<button onclick='toggleStatus($row[id],1,$page)' class='shadow-none badge rounded-pill bg-danger text-white btn'>BLOCKED</button>";
            $del = (!$row['is_verified']) ? "<button onclick='removeUser($row[id],$deletePage)' class='btn btn-sm btn-danger text-white shadow-none'>Remove</button>" : "";
            $user_data .= "
            <tr class='align-middle'>
                <td>$i</td>
                <td class='d-flex flex-column align-items-center gap-2'>
                    <img src='$img_path' style='width: 60px; height: 60px; border-radius: 50%; object-fit: cover;'/>
                    $row[name]
                </td>
                <td>$row[email]</td>
                <td>$row[address] $row[pincode]</td>
                <td>$row[phone]</td>
                <td style='white-space: nowrap;'>$formattedDOB</td>
                <td class='text-center'>$verified</td>
                <td class='text-center'>$status</td>
                <td style='white-space: nowrap;'>$formatteddate</td>
                <td>$del</td>
            </tr>
            ";
            $i++;
        }
        $pagination = "";
        $total_rows = $res->num_rows;
        // pagination
        if ($total_rows > $limit) {
            $total_number_of_pages = ceil($total_rows / $limit);
            #if we are not in the first page then only first button is shown, and when it clicked then first 5 records are shown
            if ($page != 1) {
                $pagination .= "<li  class='page-item' onclick='changePage(1)' ><button class='shadow-none page-link'>First</button></li>";
            }
            #if previous button is clicked then we have to go previous 5 records
            $prev = $page - 1;

            #if we are in the first page then we disabled previous button because no records in the -1 page
            $disabled = ($page == 1) ? "<li class='page-item disabled'><button class='shadow-none page-link'>&laquo;</button></li>" : "<li class='page-item' onclick='changePage($prev)' ><button class='page-link'>&laquo;</button></li>";

            #cancatenating previous button to our pagination variable
            $pagination .= $disabled;

            #this temp variable is use for showing only 3 numbers after the previous button
            $temp = 1;

            #This loop for showing content between previous button and next button
            for ($j = $page; $j < $total_number_of_pages; $j++) {
                #now cancatenating all middel content to the aur pagination variable
                $pagination .= "
                <li class='" . (($j == $page) ? 'active page-item' : 'page-item') . "'><button class='shadow-none page-link' onclick='changePage($j)'>$j</button></li>";
                #this is use for only printing 3 buttons after the previous button
                if ($temp == 3) {
                    break;
                }
                $temp++;
            }

            #add some extra button 
            $pagination .= "<li class='page-item'><button class='shadow-none page-link'>· · ·</button></li>";

            #now concatenating last number of the page to the pagination variable
            $pagination .= "
            <li class='" . (($j == $page) ? 'active page-item' : 'page-item') . "'><button class='shadow-none page-link' onclick='changePage(" . ($total_number_of_pages) . ")'>" . ($total_number_of_pages) . "</button></li>
            ";

            #if next button is clicked then page variable incremented and go to the next page
            $next = $page + 1;

            #if we are in the last page then we have to disabled next button because no records
            $disabled = ($page == $total_number_of_pages) ? "<li class='page-item disabled'><button class='shadow-none page-link'>&raquo;</button></li>" : "<li class='page-item' onclick='changePage($next)' ><button class='page-link'>&raquo;</button></li>";

            #now concatenating next button to the pagination variable
            $pagination .= $disabled;

            #if we are not in the last page then only last button is shown, and when it clicked then last 5 records are shown
            if ($page != $total_number_of_pages) {
                $pagination .= "<li class='page-item' onclick='changePage($total_number_of_pages)' ><button class='shadow-none page-link'>Last</button></li>";
            }
        }
        echo json_encode(['user_data' => $user_data, 'user_pagination' => $pagination]);
    }else{
        $user_data ="nodata";
        $pagination = "";
        echo json_encode(['user_data' => $user_data, 'user_pagination' => $pagination]);
    }
} else if (isset($_POST['toggleStatus'])) {
    $frm = filteration($_POST);
    $q = "UPDATE `user_cred` SET `status`=? WHERE `id`=?";
    $values = [$frm['status'], $frm['id']];
    $res = update($q, $values, 'ii');
    echo $res;
} else if (isset($_POST['removeUser'])) {
    $frm = filteration($_POST);
    $q1 = select("SELECT * FROM `user_cred` WHERE `id`=?", [$frm['id']], 'i');
    $row_assoc = mysqli_fetch_assoc($q1);
    $flag = true;
    if ($row_assoc['profile'] != 'default.png') {
        $path = UPLOAD_IMAGES_PATH . USER_FOLDER . $row_assoc['profile'];
        $del = deleteFile($path);
        if (!($del == 'Deleted')) {
            $flag = false;
        }
    }

    if ($flag) {
        $res1 = delete("DELETE FROM `user_cred` WHERE `id`=? AND `is_verified`=?", [$frm['id'],0], 'ii');
        echo $res1;
    }
}
