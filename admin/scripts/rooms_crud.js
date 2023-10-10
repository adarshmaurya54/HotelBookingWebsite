const add_room_form = document.getElementById("add_room_form");
const edit_rooms_form = document.getElementById("edit_rooms_form");
const add_image_form = document.getElementById("add_image_form");
add_room_form.addEventListener("submit", function (e) {
    e.preventDefault();
    let isFacilitiesChecked = false;
    let isFeaturesChecked = false;
    let facilities = add_room_form.elements['facilities[]'];
    let features = add_room_form.elements['features[]'];
    let featuresarr = [];
    let facilitiesarr = [];
    for (let i = 0; i < facilities.length; i++) {
        if (facilities[i].checked) {
            facilitiesarr.push(facilities[i].value);
            isFacilitiesChecked = true;
        }
    }
    for (let i = 0; i < features.length; i++) {
        if (features[i].checked) {
            featuresarr.push(features[i].value);
            isFeaturesChecked = true;
        }
    }
    let name = add_room_form['name'].value;
    let area = add_room_form['area'].value;
    let price = add_room_form['price'].value;
    let quantity = add_room_form['quantity'].value;
    let adult = add_room_form['adult'].value;
    let children = add_room_form['children'].value;
    let desc = add_room_form['desc'].value;
    if (name.trim() == '' || area.trim() == '' || price.trim() == '' || quantity.trim() == '' || adult.trim() == '' || children.trim() == '' || desc.trim() == '') {
        custom_error("All * fields are required!", 'e');
    } else if (isFacilitiesChecked == false || isFeaturesChecked == false) {
        custom_error("At least on feature and facilitie should be checked!", 'e');
    } else {
        var myModalEl = document.getElementById('add-rooms')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please Wait", 'i', true, "l");
        let formData = new FormData();
        formData.append("name", name);
        formData.append("area", area);
        formData.append("price", price);
        formData.append("quantity", quantity);
        formData.append("adult", adult);
        formData.append("children", children);
        formData.append("desc", desc);
        formData.append("features", JSON.stringify(featuresarr));
        formData.append("facilities", JSON.stringify(facilitiesarr));
        formData.append("addroom", '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/room_crud.php");
        xhr.onload = function () {
            let response = this.responseText;
            if (response === "1") {
                custom_alert("Successfully Added", "s");
                add_room_form.reset();
                getAllRooms(false);
            } else {
                custom_alert("Something went wrong on server", "e");
            }
        }
        xhr.send(formData);
    }
})

function getAllRooms(flag) {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    let formData = new FormData();
    formData.append("getAllRooms", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/room_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true)
        }
        let response = xhr.responseText;
        document.getElementById("rooms-data").innerHTML = response;
    }
    xhr.send(formData);
}

function toggleStatus(id, value) {
    custom_alert("Please wait!", "i", true, 'l');
    let formData = new FormData();
    formData.append("toggleStatus", "");
    formData.append("id", id);
    formData.append("value", value);
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/room_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if (response == '1' && value === 0) {
            custom_alert("Room is inactive now!", "s");
            getAllRooms(false)
        } else if (response == '1' && value === 1) {
            custom_alert("Room is active now!", "s");
            getAllRooms(false);
        } else {
            custom_alert("Server down!", "e");
        }
    }
    xhr.send(formData);
}

function editRoom(id) {
    let formData = new FormData();
    formData.append("editRoom", id);
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/room_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        let jsonData = JSON.parse(response);
        edit_rooms_form['name'].value = jsonData.room_data.name;
        edit_rooms_form['area'].value = jsonData.room_data.area;
        edit_rooms_form['price'].value = jsonData.room_data.price;
        edit_rooms_form['quantity'].value = jsonData.room_data.quantity;
        edit_rooms_form['adult'].value = jsonData.room_data.adult;
        edit_rooms_form['children'].value = jsonData.room_data.children;
        edit_rooms_form['desc'].value = jsonData.room_data.description;
        edit_rooms_form['room_id'].value = jsonData.room_data.id;
        let facilities = edit_rooms_form.elements['facilities[]'];
        let features = edit_rooms_form.elements['features[]'];
        for (let i = 0; i < facilities.length; i++) {
            if (jsonData.room_facilities.includes(parseInt(facilities[i].value))) {
                facilities[i].checked = true;
            }
        }
        for (let i = 0; i < features.length; i++) {
            if (jsonData.room_features.includes(parseInt(features[i].value))) {
                features[i].checked = true;
            }
        }
    }
    xhr.send(formData);
}

edit_rooms_form.addEventListener("submit", function (e) {
    e.preventDefault();
    let isFacilitiesChecked = false;
    let isFeaturesChecked = false;
    let facilities = edit_rooms_form.elements['facilities[]'];
    let features = edit_rooms_form.elements['features[]'];
    let featuresarr = [];
    let facilitiesarr = [];
    for (let i = 0; i < facilities.length; i++) {
        if (facilities[i].checked) {
            facilitiesarr.push(facilities[i].value);
            isFacilitiesChecked = true;
        }
    }
    for (let i = 0; i < features.length; i++) {
        if (features[i].checked) {
            featuresarr.push(features[i].value);
            isFeaturesChecked = true;
        }
    }
    let name = edit_rooms_form['name'].value;
    let area = edit_rooms_form['area'].value;
    let price = edit_rooms_form['price'].value;
    let quantity = edit_rooms_form['quantity'].value;
    let adult = edit_rooms_form['adult'].value;
    let children = edit_rooms_form['children'].value;
    let desc = edit_rooms_form['desc'].value;
    if (name.trim() == '' || area.trim() == '' || price.trim() == '' || quantity.trim() == '' || adult.trim() == '' || children.trim() == '' || desc.trim() == '') {
        custom_error("All * fields are required!", 'e');
    } else if (isFacilitiesChecked == false || isFeaturesChecked == false) {
        custom_error("At least on feature and facilitie should be checked!", 'e');
    } else {
        var myModalEl = document.getElementById('edit-rooms')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please Wait", 'i', true, "l");
        let formData = new FormData();
        formData.append("room_id", edit_rooms_form['room_id'].value);
        formData.append("name", name);
        formData.append("area", area);
        formData.append("price", price);
        formData.append("quantity", quantity);
        formData.append("adult", adult);
        formData.append("children", children);
        formData.append("desc", desc);
        formData.append("features", JSON.stringify(featuresarr));
        formData.append("facilities", JSON.stringify(facilitiesarr));
        formData.append("editroom", '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/room_crud.php");
        xhr.onload = function () {
            let response = this.responseText;
            if (response === "1") {
                custom_alert("Successfully edited", "s");
                edit_rooms_form.reset();
                getAllRooms(false);
            } else {
                custom_alert("Something went wrong on server", "e");
            }
        }
        xhr.send(formData);
    }
})

add_image_form.addEventListener("submit", function (e) {
    e.preventDefault();
    if (!add_image_form['room-pic'].files[0]) {
        custom_error("Image field is required!", "e");
    } else {
        custom_error("Please Wait...", 'i', true);
        let formData = new FormData(this);
        formData.append("add_room_img", '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/room_crud.php");
        xhr.onload = function () {
            add_image_form.reset();
            let response = this.responseText;
            if (response === "1") {
                custom_error("Successfully Added", "s");
                getRoomImages(add_image_form['room_id'].value);
            } else if (response == "not_uploaded") {
                custom_error("File not uploaded! try again latter", "e");
            } else if (response == "inv_size") {
                custom_error("Large image, image should be less than 2 mb", "e");
            } else if (response == "inv_mime") {
                custom_error("Invalid type of file", "e");
            } else {
                custom_error("Something went wrong on server", "e");
            }
        }
        xhr.send(formData);
    }
})

function getRoomImages(id) {
    let formData = new FormData();
    formData.append("id", id);
    formData.append("getAllRoomsImages", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/room_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        document.getElementById("room-image-data").innerHTML = response;
    }
    xhr.send(formData);
}

function getDataOfRoomForAddImageModal(id, roomname) {
    getRoomImages(id);
    document.querySelector("#add-room-images .modal-title").innerHTML = roomname;
    add_image_form['room_id'].value = id;
}

function changeThumb(id,room_id, thumb_val) {
    let formData = new FormData();
    custom_error("Please wait...", "i");
    formData.append("img_id", id);
    formData.append("room_id", room_id);
    formData.append("thumb_val", thumb_val);
    formData.append("changeThumb", '');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/room_crud.php");
    xhr.onload = function () {
        let response = this.responseText;
        if (response === "1") {
            custom_error("Thumbnail changed", "s");
            getRoomImages(add_image_form['room_id'].value);
        } else {
            custom_error("Something went wrong on server", "e");
        }
    }
    xhr.send(formData);
}

function deleteRoomImage(id,img_name) {
    let formData = new FormData();
    custom_error("Please wait...", "i");
    formData.append("img_id", id);
    formData.append("img_name", img_name);
    formData.append("deleteRoomImage", '');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/room_crud.php");
    xhr.onload = function () {
        let response = this.responseText;
        if (response === "1") {
            custom_error("Image removed", "s");
            getRoomImages(add_image_form['room_id'].value);
        } else {
            custom_error("Something went wrong on server", "e");
        }
    }
    xhr.send(formData);
}

function removeRoom(id){
    if(confirm("Are you sure you want to remove this room?")){
        let formData = new FormData();
        custom_alert("Please Wait", 'i', true, "l");
        formData.append("id", id);
        formData.append("removeRoom", '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/room_crud.php");
        xhr.onload = function () {
            let response = this.responseText;
            if (response === "1") {
                custom_alert("Room removed", "s");
                getAllRooms(false);
            } else {
                custom_alert("Something went wrong on server", "e");
            }
        }
        xhr.send(formData);
    }
}

window.onload = getAllRooms(true);