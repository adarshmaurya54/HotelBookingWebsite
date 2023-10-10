const login_form = document.getElementById("login_form");
login_form.addEventListener("submit",(e)=>{
    e.preventDefault();

    let flag = true;
    let names = [];

    // getting all input tags of contact_s_form form validation
    let allInput = login_form.getElementsByTagName("input");
    // now we getting name attribut of all input tags of all input tages of the form and push in the names array
    for (let i = 0; i < allInput.length; i++) {
        names.push(allInput[i].getAttribute("name"));
    }

    let errorIds = ['email_mob_error', 'password_error'];

    names.forEach((e,i) => {
        let msg = '';
        if(e == "email_mob"){
            msg = "Email / Mobile ";
        }else{
            msg = "Password";
        }
            if (login_form[e].value.trim() === '') {
                login_form[e].classList.add("border-danger");
                document.getElementById(errorIds[i]).classList.remove("d-none");
                document.getElementById(errorIds[i]).classList.add("d-inline-block");
                document.getElementById(errorIds[i]).innerHTML = `
                    <i class="bi bi-exclamation-circle-fill"></i> ${msg} field is required and it can't be blank!
                `;
                flag = false;
            } else {
                login_form[e].classList.remove("border-danger");
                login_form[e].classList.add("input-focus");
                document.getElementById(errorIds[i]).classList.remove("d-inline-block");
                document.getElementById(errorIds[i]).classList.add("d-none");
                document.getElementById(errorIds[i]).innerHTML = ``;
            }
    })


    if (flag) {
        // if form is proper filled then this if block will execute...
        let formData = new FormData();
        var myModalEl = document.getElementById('loginmodal')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_alert("Please wait",'i',true,'l');
        formData.append("email_mob",login_form['email_mob'].value);
        formData.append("password",login_form['password'].value);
        formData.append("login", "");
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/User_login_and_registration.php", true);
        xhr.onload = function () {
            let res = xhr.responseText;
            if (res === '1') {
                window.location = window.location.href;
            }else if (res === 'invalid_email_mob') {
                custom_alert("Invalid Email or Mobile!", "e");
            }else if (res === 'not_verified') {
                custom_alert("You email is not verified, please varify your email first!", "e");
            }else if (res === 'status') {
                custom_alert("Account suspended, please contact admin!", "e");
            }else if (res === 'not_matched') {
                custom_alert("Incorrect password!", "e");
            }else{
                custom_alert("Something went wrong on server!", "e");
            }
        }
        xhr.send(formData);
    }
})
function checkLoginToBook(flag,id){
    if(flag == 0){
        custom_error("Please Login to book room!",'e');
    }else{
        window.location = `./room_booking.php?id=${id}`;
    }
}