function getBookingRecords(flag,page=1,search='') {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    if(page === 0){
        page = 1;
    }
    let formData = new FormData();
    formData.append("page", page);
    formData.append("search", search);
    formData.append("getBookingRecords", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/booking_records_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true)
        }
        let response = xhr.responseText;
        // console.log(response);
        response = JSON.parse(response);
        if(response.pagination == "no"){
            document.getElementById("table-data").innerHTML = response.bookingrecords_data;
            document.getElementById("pagination").innerHTML = '';
        }else{
            document.getElementById("table-data").innerHTML = response.bookingrecords_data;
            document.getElementById("pagination").innerHTML = response.pagination;
        }
    }
    xhr.send(formData);
}

function changePage(page){
    let search = document.getElementById("searchuser").value;
    getBookingRecords(true,page,search);
}
function searchBooking(e){
    getBookingRecords(true,1,e.value);
}
function generate_pdf(bid){
    window.location = "./generate_booking_pdf.php?gen_pdf&id=" + bid;
}
window.onload = getBookingRecords(true)