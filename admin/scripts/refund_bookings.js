function getRefundBookings(flag, page = 1, search = '') {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    if (page === 0) {
        page = 1;
    }
    let formData = new FormData();
    formData.append("page", page);
    formData.append("search", search);
    formData.append("getRefundBookings", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/refund_bookings_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true)
        }
        let response = xhr.responseText;
        // console.log(response);
        response = JSON.parse(response);
        if (response.pagination == "no") {
            document.getElementById("table-data").innerHTML = response.refundbookings_data;
            document.getElementById("pagination").innerHTML = '';
        } else {
            document.getElementById("table-data").innerHTML = response.refundbookings_data;
            document.getElementById("pagination").innerHTML = response.pagination;
        }
    }
    xhr.send(formData);
}

function changePage(page) {
    let search = document.getElementById("searchuser").value;
    getRefundBookings(true, page, search);
}
function searchUser(e) {
    getRefundBookings(true, 1, e.value);
}

function refund_booking(id, page) {
    custom_alert("Please wait", 'i', true, 'l');
    let formData = new FormData();
    formData.append("id", id);
    formData.append("refund_booking", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/refund_bookings_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if (response == "1") {
            custom_alert("Money is refunded!", 's');
            getRefundBookings(false, page);
        } else {
            custom_alert("Something went wrong on server!", 'e');
        }
    }
    xhr.send(formData);
}

window.onload = getRefundBookings(true)