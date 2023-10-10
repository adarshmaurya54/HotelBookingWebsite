const general_setting_form = document.getElementById("general_s_form");
const contact_s_form = document.getElementById("contact_s_form");
const editSocialLinksform = document.getElementById("editSocialLinksform");
const addSocialLinksform = document.getElementById("addSocialLinksform");
const management_s_form = document.getElementById("management_s_form");
let names = [];
// getting all input tags of contact_s_form form validation
let allInput = contact_s_form.getElementsByTagName("input");
// now we getting name attribut of all input tags of all input tages of the form and push in the names array
for (let i = 0; i < allInput.length; i++) {
    names.push(allInput[i].getAttribute("name"));
}

general_setting_form.addEventListener("submit", function (e) {
    e.preventDefault();
    let site_title = general_setting_form['site_title'].value;
    let site_desc = general_setting_form['site_desc'].value;
    if (site_title.trim() == '' || site_desc.trim() == '') {
        custom_error("<strong>Site Title</strong> and <strong>Site Description</strong> both are required!", "w");
    } else {
        var myModalEl = document.getElementById('general-s')
        var modal = bootstrap.Modal.getInstance(myModalEl)// Create a modal instance
        modal.hide(); // Hide the modal
        custom_alert("Please Wait", 'i', true, "l");
        let formData = new FormData();
        formData.append("site_title", site_title);
        formData.append("site_desc", site_desc);
        formData.append("change_general_s", "");
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/settings_crud.php", true);
        xhr.onload = function () {
            let response = xhr.responseText;
            if (response == "1") {
                custom_alert("Site title and Description changed successfully!", "s");
            } else if (response == "0") {
                custom_alert("No changes made!", "s");
            } else {
                custom_alert("Something went wrong on server!", "e");
            }
            getGeneralSettings();
        }
        xhr.send(formData);
    }
})

contact_s_form.addEventListener("submit", function (e) {
    e.preventDefault();

    let flag = true;
    // validating all fields if any field of the form is blank then we have to show alert to admin
    names.forEach(e => {
        if(e !== "ph2") if (contact_s_form[e].value.trim() == '') {
            custom_error("All fields are required!", "e");
            flag = false;
            return;
        }
    })

    // if all input filed are filled then we have to validate email Regular expression for email validation
    if (flag) {
        const emailRegex = /^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(contact_s_form[names[3]].value)) {
            custom_error("Invalid Email!", "e");
            flag = false;
        }
    }

    if (flag) {
        var myModalEl = document.getElementById('contact-s')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please Wait", 'i', true, "l");
        let formData = new FormData(this);
        formData.append("editContact", "");
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/settings_crud.php", true);
        xhr.onload = function () {
            let response = xhr.responseText;
            if (response == "1") {
                custom_alert("Successfully updated!", "s");
            } else if (response == "0") {
                custom_alert("No changes made!", "s");
            } else {
                custom_alert("Something went wrong on server!", "e");
            }
            getContact(false);
        }
        xhr.send(formData);
    }
})
let response1;
function getContact(flag) {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    let formData = new FormData();
    formData.append("getContact", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/settings_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true)
        }
        response1 = xhr.responseText;
        response1 = JSON.parse(response1);
        document.getElementById("address").innerHTML = response1.address;
        document.getElementById("map").innerHTML = response1.gmap;
        document.getElementById("ph1").innerHTML = response1.ph1;
        document.getElementById("ph2").innerHTML = response1.ph2;
        document.getElementById("email").innerHTML = response1.email;
        document.getElementById("map-iframe").src = response1.iframe;
        preFillAllInputsOfContactSetting();
    }
    xhr.send(formData);


}
function getManagementTeam(flag) {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    let formData = new FormData();
    formData.append("getManagementTeam", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/settings_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true);
        }
        let response = xhr.responseText;
        document.getElementById("management-team-data").innerHTML = response;
    }
    xhr.send(formData);
}
function preFillAllInputsOfContactSetting() {
    contact_s_form[names[0]].value = response1.address;
    contact_s_form[names[1]].value = response1.ph1;
    contact_s_form[names[2]].value = response1.ph2;
    contact_s_form[names[3]].value = response1.email;
    contact_s_form[names[4]].value = response1.iframe;
    contact_s_form[names[5]].value = response1.gmap;
}

function getSocialLinksContent(flag) {
    let formData1 = new FormData();
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    formData1.append("getSocial", "");
    let xhr1 = new XMLHttpRequest();
    xhr1.open("POST", "./ajax/settings_crud.php", true);
    xhr1.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true)
        }
        let getSocialResponse = xhr1.responseText;
        getSocialResponse = JSON.parse(getSocialResponse);
        document.getElementById("social-links").innerHTML = getSocialResponse.result1;
        document.getElementById("social-links-edit-inputs").innerHTML = getSocialResponse.result2;
    }
    xhr1.send(formData1);
}
window.onload = getManagementTeam(true);
window.onload = getContact(true);
window.onload = getSocialLinksContent(true);


editSocialLinksform.addEventListener("submit", function (e) {
    e.preventDefault();
    var myModalEl = document.getElementById('editSocialLinks')
    var modal = bootstrap.Modal.getInstance(myModalEl)
    modal.hide();
    custom_alert("Please Wait", 'i', true, "l");
    let numberofinputtags = parseInt(editSocialLinksform.getElementsByTagName("input").length) / 2;
    let formData1 = new FormData(this);
    formData1.append("length", numberofinputtags);
    formData1.append("updateSocialLinks", "");
    let xhr1 = new XMLHttpRequest();
    xhr1.open("POST", "./ajax/settings_crud.php", true);
    xhr1.onload = function () {
        let response = xhr1.responseText;
        // Using regular expressions to check for the presence of 1 and 0
        let res = /^[01]+$/.test(response)
        if (response.includes("0") && !response.includes("1")) {
            custom_alert("No changes made!", "i");
        } else if (res) {
            custom_alert("Successfully Updated", "s");
        } else {
            custom_alert("Something went wrong on the server!", "e");
        }
        getSocialLinksContent(false);
    }
    xhr1.send(formData1);
});

addSocialLinksform.addEventListener("submit", function (e) {
    e.preventDefault();
    let name = addSocialLinksform['nameofsocialmedia'].value;
    if (name.trim() == "") {
        custom_error("Name (*) field is required!", "e");
    } else {
        var myModalEl = document.getElementById('addSocialLinks')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please Wait", 'i', true, "l");
        let formData1 = new FormData(this);
        formData1.append("addSocialLink", '');
        let xhr1 = new XMLHttpRequest();
        xhr1.open("POST", "./ajax/settings_crud.php", true);
        xhr1.onload = function () {
            let response = xhr1.responseText;
            if (response == "1") {
                custom_alert("Successfully added new social media", "s");
            } else {
                custom_alert("Something went wrong on the server!", "e");
            }
            getSocialLinksContent();
        }
        xhr1.send(formData1);
    }

})
// custom_alert("Successfully added new social media", "s");

function toggleShutdown(value) {
    let formData = new FormData()
    formData.append("val", value);
    formData.append("changeShutdown", '');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/settings_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if (response == "1") {
            custom_alert("Shutdown mode is on!", "s");
        } else if (response == "0") {
            custom_alert("Shutdown mode is off!", "s");
        } else {
            custom_alert("Something went wrong on server!", "e");
        }
        getGeneralSettings(false);
    }
    xhr.send(formData);
}
management_s_form.addEventListener("submit", function (e) {
    e.preventDefault();
    let manag_name = management_s_form['manag-name'].value;
    let manag_pic = management_s_form['manag-pic'].files[0];
    if (manag_name.trim() == '' || !manag_pic) {
        custom_error("All * fields are required", 'e');
    } else {
        var myModalEl = document.getElementById('management_s')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please Wait", 'i', true, "l");
        let formData = new FormData(this);
        formData.append("addManagement", '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/settings_crud.php");
        xhr.onload = function () {
            management_s_form.reset();
            let response = this.responseText;
            if (response === "1") {
                custom_alert("Successfully Added", "s");
                getManagementTeam(false);
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

function deleteManagTeam(id,img) {
    
    let formData = new FormData();
    custom_alert("Please Wait", 'i', true, "l");
    formData.append("id",id);
    formData.append("img",img);
    formData.append("deleteManagTeam","");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/settings_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        
        if(response == "1"){
            custom_alert("Successfuly removed","s");
            getManagementTeam(false);
        }else if(response == "0"){
            custom_alert("No changes","i");
        }else if(response == "FileNotFound"){
            custom_alert("Oops! File not found on server!","e");
        }else if(response == "UnableToDelete"){
            custom_alert("Unable to delete! Server down!","e");
        }else {
            custom_alert("Something went wrong on server","e");
        }
    }
    xhr.send(formData);
}
