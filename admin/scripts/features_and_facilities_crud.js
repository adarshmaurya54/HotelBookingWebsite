const features_s_form = document.getElementById("features_s_form");
const facility_s_form = document.getElementById("facility_s_form");
features_s_form.addEventListener("submit", function (e) {
    e.preventDefault();
    let feature_name = features_s_form['feature-name'].value;
    if (feature_name.trim() == '') {
        custom_error("Name field is required", 'e');
    } else {
        var myModalEl = document.getElementById('features_s')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please Wait", 'i', true, "l");
        let formData = new FormData(this);
        formData.append("addFeature", '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/features_and_facilities_crud.php");
        xhr.onload = function () {
            features_s_form.reset();
            let response = this.responseText;
            if (response === "1") {
                custom_alert("Successfully Added", "s");
                getFeatures(false);
            } else {
                custom_alert("Something went wrong on server", "e");
            }
        }
        xhr.send(formData);
    }
});
facility_s_form.addEventListener("submit", function (e) {
    e.preventDefault();
    let facility_name = facility_s_form['facility-name'].value;
    let facility_desc = facility_s_form['facility-desc'].value;
    let facility_pic = facility_s_form['facility-pic'].files[0];
    if (facility_desc.trim() == '') {
        facility_desc = "No Description";
    }
    if (facility_name.trim() == '' || !facility_pic) {
        custom_error("* field are required!", 'e');
    } else if (changeCharacterLen()) {
        var myModalEl = document.getElementById('facility_s')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please Wait", 'i', true, "l");
        let formData = new FormData(this);
        formData.append("facility_name", facility_name);
        formData.append("facility_pic", facility_pic);
        formData.append("facility_desc", facility_desc);
        formData.append("addFacility", '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/features_and_facilities_crud.php");
        xhr.onload = function () {
            facility_s_form.reset();
            let response = this.responseText;
            if (response === "1") {
                custom_alert("Successfully Added", "s");
                getFacilities(false);
            } else if (response == "not_uploaded") {
                custom_alert("File not uploaded! try again latter", "e");
            } else if (response == "inv_size") {
                custom_alert("Large image, image should be less than 2 mb", "e");
            } else if (response == "inv_mime") {
                custom_alert("Invalid type of file", "e");
            } else {
                custom_alert("Something went wrong on server", "e");
            }
        }
        xhr.send(formData);
    }
});
function changeCharacterLen() {
    let desclen = facility_s_form['facility-desc'].value.length;
    document.getElementById("countCharacter").innerHTML = desclen;
    if (desclen == 0) {
        return true;
    }

    if (desclen >= 250) {
        facility_s_form['facility-desc'].classList.remove("input-focus");
        facility_s_form['facility-desc'].classList.add("border-danger");
        document.getElementById("desc_error").classList.remove("d-none");
        document.getElementById("desc_error").classList.add("d-inline-block");
        document.getElementById("desc_error").innerHTML = `
            <i class="bi bi-exclamation-circle-fill"></i> Descript should be less than 250!
        `;
        return false;
    } else {
        facility_s_form['facility-desc'].classList.add("input-focus");
        facility_s_form['facility-desc'].classList.remove("border-danger");
        document.getElementById("desc_error").classList.add("d-none");
        document.getElementById("desc_error").classList.remove("d-inline-block");
        document.getElementById("desc_error").innerHTML = ``;
        return true;
    }
}
function getFeatures(flag) {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    let formData = new FormData();
    formData.append("getFeatures", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/features_and_facilities_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true);
        }
        let response = xhr.responseText;
        document.getElementById("features-data").innerHTML = response;
    }
    xhr.send(formData);
}
function deleteFeature(id) {
    custom_alert("Please wait", 'i', true, "l")
    let formData = new FormData();
    formData.append("id", id);
    formData.append("deleteFeature", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/features_and_facilities_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if (response == '1') {
            custom_alert("Successfuly Deleted", "s");
            getFeatures(false);
        } else if (response == 'roomadded') {
            custom_alert("This feature is added in the room, you can't delete this feature!", "e");
            getFeatures(false);
        } else {
            custom_alert("Server down", "e");
        }
    }
    xhr.send(formData);
}
function getFacilities(flag) {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    let formData = new FormData();
    formData.append("getFacilities", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/features_and_facilities_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true);
        }
        let response = xhr.responseText;
        document.getElementById("facilities-data").innerHTML = response;
    }
    xhr.send(formData);
}
function deleteFacility(id, img) {
    custom_alert("Please wait", 'i', true, "l")
    let formData = new FormData();
    formData.append("id", id);
    formData.append("img", img);
    formData.append("deleteFacility", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/features_and_facilities_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if (response == '1') {
            custom_alert("Successfuly Deleted", "s");
            getFacilities(false);
        } else if (response == 'added') {
            custom_alert("This facility is added in the room, you can't delete this facility!", "e     ");
            getFacilities(false);
        } else {
            custom_alert("Server down", "e");
        }
    }
    xhr.send(formData);
}
window.onload = getFacilities(true);
window.onload = getFeatures(true);