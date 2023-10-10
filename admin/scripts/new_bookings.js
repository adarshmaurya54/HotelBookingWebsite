const assignRoom_form = document.getElementById("assignRoom_form");

function getNewBookings(flag,page=1,search='') {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    let formData = new FormData();
    formData.append("page", page);
    formData.append("search", search);
    formData.append("getNewBookings", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/new_bookings_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true)
        }
        let response = xhr.responseText;
        // console.log(response);
        response = JSON.parse(response);
        if(response.pagination == "no"){
            document.getElementById("table-data").innerHTML = response.newbookings_data;
            document.getElementById("pagination").innerHTML = '';
        }else{
            document.getElementById("table-data").innerHTML = response.newbookings_data;
            document.getElementById("pagination").innerHTML = response.pagination;
        }
    }
    xhr.send(formData);
}

function changePage(page){
    let search = document.getElementById("searchuser").value;
    getNewBookings(true,page,search);
}
function searchUser(e){
    getNewBookings(true,1,e.value);
}

function assing_booking_id(id,page){
    assignRoom_form['booking_id'].value = id;
    assignRoom_form['page_no'].value = page;
}

assignRoom_form.addEventListener("submit",(e)=>{
    e.preventDefault();
    if(assignRoom_form['room_number'].value.trim() == ''){
        custom_error("Room Number can't be empty!",'e');
    }else{
        var myModalEl = document.getElementById('assignRoom')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please Wait", 'i', true, "l");
        let formData = new FormData();
        formData.append('room_no',assignRoom_form['room_number'].value)
        formData.append('booking_id',assignRoom_form['booking_id'].value)
        formData.append("assingRoom",'')
        let xhr = new XMLHttpRequest();
        xhr.open("POST","./ajax/new_bookings_crud.php",true);
        xhr.onload = function(){
            let response = xhr.responseText
            if(response == "2"){
                custom_alert("Room number alloted! Booking finalized",'s');
            }else{
                custom_alert("Server down",'e');
            }
            if(assignRoom_form['page_no'].value === '0'){
                getNewBookings(false);
            }else{
                getNewBookings(false,assignRoom_form['page_no'].value);
            }
        }
        xhr.send(formData);
    }
})

function cancelBooking(id,page){
    if(confirm("Are you sure, you want to cancel this booking?")){
        let formData = new FormData();
        custom_alert("Please Wait", 'i', true, "l");
        formData.append("id", id);
        formData.append("cancelBooking", '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/new_bookings_crud.php");
        xhr.onload = function () {
            let response = this.responseText;
            if (response === "1") {
                custom_alert("Booking cancelled", "s");
                if(page === 0){
                    getNewBookings(false);
                }else{
                    getNewBookings(false,page);
                }
            } else {
                custom_alert("Something went wrong on server", "e");
            }
        }
        xhr.send(formData);
    }
}
window.onload = getNewBookings(true)