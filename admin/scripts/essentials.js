

let timeoutId = null; // Variable to store the timeout ID
let timeoutId2 = null; // Variable to store the timeout ID

function custom_alert(msg, type, isWaiting = false, loading = 'none', isRemove = false) {
    // Clear the previous timeout, if any
    if (timeoutId !== null) {
        clearTimeout(timeoutId);
    }

    if (type.trim() == "s") {
        type = "alert-success";
    } else if (type.trim() == "i") {
        type = "alert-info";
    } else if (type.trim() == "e") {
        type = "alert-danger";
    } else if (type.trim() == "w") {
        type = "alert-warning";
    }
    if (loading == 'l') {
        document.getElementById("alert").innerHTML = `
        <div class="pb-0 alert shadow custom-alert ${type} alert-dismissible fade show" role="alert">
            <div class='me-3 m-0 p-0'>${msg} 
                <p class="loading">
                    <span class="one">.</span>
                     <span class="two">.</span>
                      <span class="three">.</span>
                </p>
            </div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;
    }else{
        document.getElementById("alert").innerHTML = `
        <div class="alert shadow custom-alert ${type} alert-dismissible fade show" role="alert">
            <p class='me-3 m-0 p-0'>${msg}</p>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        `;
    }
    document.getElementById("alert").style.transform = "translateX(0%)";
    if (isRemove == true) {
        document.getElementById("alert").style.transform = "translateX(110%)";
    } else if (!isWaiting) {
        timeoutId = setTimeout(function () {
            document.getElementById("alert").style.transform = "translateX(110%)";
            timeoutId = null; // Reset the timeout ID
        }, 10000);
    }
}
function custom_error(msg, type, isWaiting = false) {
    // Clear the previous timeout, if any
    if (timeoutId2 !== null) {
        clearTimeout(timeoutId2);
    }

    if (type.trim() == "s") {
        type = "alert-success";
    } else if (type.trim() == "i") {
        type = "alert-info";
    } else if (type.trim() == "e") {
        type = "alert-danger";
    } else if (type.trim() == "w") {
        type = "alert-warning";
    }

    document.getElementById("form-error").innerHTML = `
        <div class="alert shadow custom-alert ${type} alert-dismissible fade show" role="alert">
            <p class='me-3 m-0 p-0'>${msg}</p>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    document.getElementById("form-error").style.transform = "translate(-50%,0%)";

    if (!isWaiting) {
        timeoutId2 = setTimeout(function () {
            document.getElementById("form-error").style.transform = "translate(-50%,-125%)";
            timeoutId2 = null; // Reset the timeout ID
        }, 10000);
    }
}

