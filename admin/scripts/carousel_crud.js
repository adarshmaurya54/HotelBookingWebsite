
const carousel_s_form = document.getElementById("carousel_s_form");

function getCarousels(flag) {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    let formData = new FormData();
    formData.append("getCarousels", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/carousels_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true);
        }
        let response = xhr.responseText;
        document.getElementById("carousel-data").innerHTML = response;
    }
    xhr.send(formData);
}

window.onload = getCarousels(true);


carousel_s_form.addEventListener("submit", function (e) {
    e.preventDefault();
    let carousel_pic = carousel_s_form['carousel-pic'].files[0];
    if (!carousel_pic) {
        custom_error("All * fields are required", 'e');
    } else {
        var myModalEl = document.getElementById('carousel_s')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please Wait", 'i', true, "l");
        let formData = new FormData(this);
        formData.append("addCarousel", '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/carousels_crud.php");
        xhr.onload = function () {
            carousel_s_form.reset();
            let response = this.responseText;
            console.log(response);
            if (response === "1") {
                custom_alert("Successfully Added", "s");
                getCarousels(false);
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

function deleteCarousel(id,img) {
    let formData = new FormData();
    custom_alert("Please Wait", 'i', true, "l");
    formData.append("id",id);
    formData.append("img",img);
    formData.append("deleteCarousel","");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/carousels_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if(response == "1"){
            custom_alert("Successfuly removed","s");
            getCarousels(false);
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
