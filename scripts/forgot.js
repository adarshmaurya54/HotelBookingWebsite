const forgot_form = document.getElementById("forgot_form");
forgot_form.addEventListener("submit", (e) => {
    e.preventDefault();

    let flag = true;
    let names = [];

    // getting all input tags of contact_s_form form validation
    let allInput = forgot_form.getElementsByTagName("input");
    // now we getting name attribut of all input tags of all input tages of the form and push in the names array
    for (let i = 0; i < allInput.length; i++) {
        names.push(allInput[i].getAttribute("name"));
    }

    let errorIds = ['forg_email_error'];

    names.forEach((e, i) => {
        if (forgot_form[e].value.trim() === '') {
            forgot_form[e].classList.add("border-danger");
            document.getElementById(errorIds[i]).classList.remove("d-none");
            document.getElementById(errorIds[i]).classList.add("d-inline-block");
            document.getElementById(errorIds[i]).innerHTML = `
                        <i class="bi bi-exclamation-circle-fill"></i> Email field is required and it can't be blank!
                    `;
            flag = false;
        } else {
            forgot_form[e].classList.remove("border-danger");
            forgot_form[e].classList.add("input-focus");
            document.getElementById(errorIds[i]).classList.remove("d-inline-block");
            document.getElementById(errorIds[i]).classList.add("d-none");
            document.getElementById(errorIds[i]).innerHTML = ``;
        }
    })


    if (flag) {
        // if form is proper filled then this if block will execute...
        let formData = new FormData();
        custom_error("Please wait...", 'i', true);
        formData.append("email", forgot_form['email'].value);
        formData.append("forgot", "");
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/User_login_and_registration.php", true);
        xhr.onload = function () {
            custom_alert("Please wait", 'i', true, 'l', true);
            let res = xhr.responseText;
            console.log(res);
            if (res === '1') {
                var myModalEl = document.getElementById('forgotModal')
                var modal = bootstrap.Modal.getInstance(myModalEl)
                modal.hide();
                document.getElementById("form-error").style.display = "none";
                custom_alert("Reset link sent to your email!", "s");
                forgot_form.reset();
            } else if (res === 'invalid_email') {
                custom_error("Invalid Email!", "e");
            } else if (res === 'not_verified') {
                custom_error("Email is not verified please verify you email!", "e");
            } else if (res === 'status') {
                custom_error("Account suspended, please contact admin!", "e");
            } else if (res === 'email_error') {
                custom_error("Can't sent reset link to your email, server down!", "e");
            } else if (res === 'upd_failed') {
                custom_error("Account recovery failed!!", "e");
            } else {
                custom_error("Somthing went wrong on server!!", "e");
            }
        }
        xhr.send(formData);
    }

})

