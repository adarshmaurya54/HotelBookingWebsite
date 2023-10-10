const admin_login_from = document.getElementById("admin_login");
admin_login_from.addEventListener("submit", function (e) {
    e.preventDefault();
    custom_alert("Please Wait", 'i', true, "l");
    let form = new FormData(admin_login_from);
    form.append("admin_login", "");
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "./ajax/login_crud.php", true);

    xhr.onload = function (response) {
        // Handle the response from the server here
        if (xhr.status >= 200 && xhr.status < 300) {
            let response = xhr.responseText

            if (response == "blank") {
                custom_alert("<strong>Username</strong> and <strong>Password</strong> are required!", "w");
            } else if (response == "inv_pass") {
                custom_alert("Invalid Password!", "e");
            } else if (response == "inv_user") {
                custom_alert("Invalid Username!", "e");
            } else if (response == "success") {
                window.location.href = "./index.php";
            } else {
                custom_alert("Somthing went wrong on server!", "e");
            }
        } else {
            // Request encountered an error
            console.error("Request error:", xhr.statusText);
        }
    };

    xhr.onerror = function () {
        // Handle errors that occur during the request
        console.error("Request failed");
    };

    xhr.send(form); // Send the FormData object 
});
