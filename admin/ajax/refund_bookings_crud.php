<?php
require("../inc/db_config.php");
require("../inc/essentials.php");
isAdminLogin();
if (isset($_POST['getRefundBookings'])) {
    $frm = filteration($_POST);
    $q = "SELECT * FROM `booking_order` bo 
    INNER JOIN `booking_details` bd ON bo.booking_id=bd.booking_id
     WHERE (bo.booking_status=? AND bo.refund=?)
        AND (bo.order_id LIKE ? OR bd.user_name LIKE ? OR bd.phonenum LIKE ? OR bd.address LIKE ? or bd.room_name LIKE ?)
        ORDER BY bo.booking_id
     ";

    $values = ["cancelled", 0, "%$frm[search]%", "%$frm[search]%", "%$frm[search]%", "%$frm[search]%", "%$frm[search]%"];
    $res = select($q, $values, "sisssss");

    $limit = 4;
    $page = $frm['page'];
    $start = ($page - 1) * $limit;
    $limit_q = $q . " LIMIT $start, $limit";
    $limit_res = select($limit_q, $values, "sisssss");
    $refundbookings_data = "";
    if ($limit_res->num_rows > 0) {
        $i = $start + 1;
        while ($row = mysqli_fetch_assoc($limit_res)) {

            $deletePage = ($limit_res->num_rows == 1) ? ($page - 1) : $page;
            $checkin = date("d-m-Y", strtotime($row['check_in']));
            $checkout = date("d-m-Y", strtotime($row['check_out']));
            $date_time = date("d-m-Y", strtotime($row['date_time']));
            $refundbookings_data .= "
                <tr>
                    <td>$i</td>
                    <td>
                        <span class='badge bg-success'>Order id : $row[order_id]</span>
                        <br>
                        <span><strong>Name</strong> : $row[user_name]</span>
                        <br>
                        <span><strong>Phone</strong> : $row[phonenum]</span>
                    </td>
                    <td>
                        <span><strong>Room name</strong> : $row[room_name]</span>
                        <br>
                        <span><strong>Price</strong> : ₹$row[price].00 per night</span>
                    </td>
                    <td>
                        <span><strong>Amount</strong> : ₹$row[total_pay].00</span>
                    </td>
                    <td class='text-center'>
                        <button type='button' onclick='refund_booking($row[booking_id],$deletePage)' class='btn w-75 shadow-none btn-outline-primary'>
                           <i class='bi bi-arrow-counterclockwise'></i> Refund
                        </button>
                    </td>
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
        echo json_encode(['refundbookings_data' => $refundbookings_data, 'pagination' => $pagination]);
    } else {
        $refundbookings_data = "
            <tr>
                <td colspan='5' class='text-center fs-3'>No Data Found</td>
            </tr>
        ";
        $pagination = "no";
        echo json_encode(['refundbookings_data' => $refundbookings_data, 'pagination' => $pagination]);
    }
} else if (isset($_POST['refund_booking'])) {
    $frm = filteration($_POST);

    // in future if you integrate refund api then refund api will be written here...



    $q = "UPDATE `booking_order` SET `refund`=? WHERE `booking_id`=?";
    $values = [1, $frm['id']];
    $res = update($q, $values, 'ii');   
    echo $res;
} 