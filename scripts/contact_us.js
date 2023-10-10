const contact_us_form = document.getElementById("contact_us_form");
contact_us_form.addEventListener("submit", function (e) {
    e.preventDefault();
    let flag = true;
    let names = [];
    let errorIds = ['name_error', 'email_error', 'subject_error', 'message_error'];

    function capitalizeWords(str) {
        return str.replace(/_error/g, '').replace(/\b\w/g, char => char.toUpperCase());
    }
    // getting all input tags of contact_s_form form validation
    let allInput = contact_us_form.getElementsByTagName("input");
    // now we getting name attribut of all input tags of all input tages of the form and push in the names array
    for (let i = 0; i < allInput.length; i++) {
        names.push(allInput[i].getAttribute("name"));
    }
    names.push("cont_msg");
    names.forEach((e, i) => {
        if (contact_us_form[e].value.trim() === '') {
            contact_us_form[e].classList.add("border-danger");
            document.getElementById(errorIds[i]).classList.remove("d-none");
            document.getElementById(errorIds[i]).classList.add("d-inline-block");
            document.getElementById(errorIds[i]).innerHTML = `
                <i class="bi bi-exclamation-circle-fill"></i> ${capitalizeWords(errorIds[i].replace("_error",""))} field is required and it can't be blank!
            `;
            flag = false;
        } else {
            contact_us_form[e].classList.remove("border-danger");
            contact_us_form[e].classList.add("input-focus");
            document.getElementById(errorIds[i]).classList.remove("d-inline-block");
            document.getElementById(errorIds[i]).classList.add("d-none");
            document.getElementById(errorIds[i]).innerHTML = ``;
        }
    })

    if(flag){
        if(!validateEmail(contact_us_form['cont_email'].value)){
            document.getElementById(errorIds[1]).classList.remove("d-none");
            document.getElementById(errorIds[1]).classList.add("d-inline-block");
            document.getElementById(errorIds[1]).innerHTML = `
                <i class="bi bi-exclamation-circle-fill"></i> Invalid Email, make sure you have entered a valid email address!
            `;
            flag = false;
        }
    }

    if(flag){
        // if form is proper filled then this if block will execute...
        custom_alert("Please Wait","i",true,'l');
        let formData = new FormData(this);
        formData.append("submitContactUsForm","");
        let xhr = new XMLHttpRequest();
        xhr.open("POST","./ajax/contact_form.php",true);
        xhr.onload = function(){
            contact_us_form.reset();
            console.log(xhr.responseText)
            if(xhr.responseText === 'send'){
                custom_alert("Successfully submitted","s");
            }else{
                custom_alert("Server Down!","e");
            }
        }
        xhr.send(formData);
    }

})