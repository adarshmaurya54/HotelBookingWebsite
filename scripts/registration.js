const registration_form = document.getElementById("registration-form");

registration_form.addEventListener("submit", function (e) {
    e.preventDefault();
    let flag = true;
    let names = [];

    function capitalizeWords(str) {
        return str.replace(/_error/g, '').replace(/\b\w/g, char => char.toUpperCase());
    }
    // getting all input tags of contact_s_form form validation
    let allInput = registration_form.getElementsByTagName("input");
    // now we getting name attribut of all input tags of all input tages of the form and push in the names array
    for (let i = 0; i < allInput.length; i++) {
        names.push(allInput[i].getAttribute("name"));
    }


    names.push("address");
    let errorIds = ['name_error', 'email_error', 'phone_error', 'pincode_error', 'date-of-birth_error', 'pass_error', 'address_error'];
    let i = 0;
    names.forEach((e) => {
        if (e !== "picture" && e !== "c-password") {
            if (registration_form[e].value.trim() === '') {
                registration_form[e].classList.add("border-danger");
                document.getElementById(errorIds[i]).classList.remove("d-none");
                document.getElementById(errorIds[i]).classList.add("d-inline-block");
                document.getElementById(errorIds[i]).innerHTML = `
                    <i class="bi bi-exclamation-circle-fill"></i> ${capitalizeWords(errorIds[i].replace("_error", ""))} field is required and it can't be blank!
                `;
                flag = false;
            } else {
                registration_form[e].classList.remove("border-danger");
                registration_form[e].classList.add("input-focus");
                document.getElementById(errorIds[i]).classList.remove("d-inline-block");
                document.getElementById(errorIds[i]).classList.add("d-none");
                document.getElementById(errorIds[i]).innerHTML = ``;
            }
            i++;
        }
    })

    if (flag) {
        if (!validateEmail(registration_form['email'].value)) {
            document.getElementById(errorIds[1]).classList.remove("d-none");
            document.getElementById(errorIds[1]).classList.add("d-inline-block");
            document.getElementById(errorIds[1]).innerHTML = `
                <i class="bi bi-exclamation-circle-fill"></i> Invalid Email, make sure you have entered a valid email address!
            `;
            flag = false;
        }
    }

    if (registration_form['password'].value.trim() != '' && flag) {
        var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
        if (registration_form['password'].value.match(passw)) {
            registration_form['password'].classList.remove("border-danger");
            registration_form['password'].classList.add("input-focus");
            document.getElementById(errorIds[5]).classList.remove("d-inline-block");
            document.getElementById(errorIds[5]).classList.add("d-none");
            document.getElementById(errorIds[5]).innerHTML = ``;

            if (registration_form['c-password'].value.trim() == '') {
                registration_form['c-password'].classList.add("border-danger");
                document.getElementById('cpass_error').classList.remove("d-none");
                document.getElementById('cpass_error').classList.add("d-inline-block");
                document.getElementById('cpass_error').innerHTML = `
                        <i class="bi bi-exclamation-circle-fill"></i> Confirm password can't blank!
                    `;
                flag = false;
            } else if (registration_form['password'].value !== registration_form['c-password'].value) {
                registration_form['c-password'].classList.add("border-danger");
                document.getElementById('cpass_error').classList.remove("d-none");
                document.getElementById('cpass_error').classList.add("d-inline-block");
                document.getElementById('cpass_error').innerHTML = `
                        <i class="bi bi-exclamation-circle-fill"></i> The confirmed password does not match your original password.
                    `;
                flag = false;
            } else {
                registration_form['c-password'].classList.remove("border-danger");
                registration_form['c-password'].classList.add("input-focus");
                document.getElementById('cpass_error').classList.remove("d-inline-block");
                document.getElementById('cpass_error').classList.add("d-none");
                document.getElementById('cpass_error').innerHTML = ``;
                flag = true;
            }
        } else {
            registration_form['password'].classList.add("border-danger");
            document.getElementById(errorIds[5]).classList.remove("d-none");
            document.getElementById(errorIds[5]).classList.add("d-inline-block");
            document.getElementById(errorIds[5]).innerHTML = `
            <i class="bi bi-exclamation-circle-fill"></i> Your password should be between 6 to 20 characters and include at least one numeric digit, one uppercase letter, and one lowercase letter.
            `;
            flag = false;
        }
    }


    if (flag) {
        // if form is proper filled then this if block will execute...
        let formData = new FormData(this);
        var myModalEl = document.getElementById('registermodal')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please wait",'i',true,'l');
        formData.append("registration", "");
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/User_login_and_registration.php", true);
        xhr.onload = function () {
            console.log(xhr.responseText)
            let res = xhr.responseText;
            if (res === '1') {
                custom_alert("Registration Successfull, varification link sent to your mail address", "s");
                registration_form.reset();
            }else if (res === 'inv_pass') {
                custom_alert("Password Mismatch", "e");
            }else if (res === 'not_uploaded') {
                custom_alert("Unable to upload your profile! try again latter", "e");
            }else if (res === 'phone_already') {
                custom_alert("Phone number is already registered!", "e");
            }else if (res === 'email_already') {
                custom_alert("Email id is already registered!", "e");
            }else if (res === 'inv_mime') {
                custom_alert("Invalid type of file (.png, .jpg or .webp are allowed)!", "e");
            }else if (res === 'email_send_error') {
                custom_alert("Unable to send varification link to your email address!", "e");
            }else if (res === 'ins_failed') {
                custom_alert("Registration unsuccessful!", "e");
            }else{
                custom_alert("Something went wrong on server!", "e");
            }
        }
        xhr.send(formData);
    }
})