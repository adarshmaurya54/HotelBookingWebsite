

function getAllUsers(flag,page=1,search='') {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    if(page === 0){
        page = 1;
    }
    let formData = new FormData();
    formData.append("page", page);
    formData.append("search", search);
    formData.append("getAllUsers", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/users_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true)
        }
        let response = xhr.responseText;
        response = JSON.parse(response);
        if(response.user_data == "nodata"){
            custom_alert("Not found :(","i");
            document.getElementById("users-data").innerHTML = '';
            document.getElementById("user-pagination").innerHTML = '';
        }else{
            document.getElementById("users-data").innerHTML = response.user_data;
            document.getElementById("user-pagination").innerHTML = response.user_pagination;
        }
    }
    xhr.send(formData);
}

function changePage(page){
    let search = document.getElementById("searchuser").value;
    getAllUsers(true,page,search);
}
function searchUser(e){
    getAllUsers(true,1,e.value);
}
function toggleStatus(id, status,page){
        custom_alert("Please wait!", "i", true, 'l');
        let formData = new FormData();
        formData.append("toggleStatus", "");
        formData.append("id", id);
        formData.append("status", status);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/users_crud.php", true);
        xhr.onload = function () {
            let response = xhr.responseText;
            if (response == '1' && status === 0) {
                custom_alert("The user is now blocked!", "w");
                getAllUsers(false,page);
            } else if (response == '1' && status === 1) {
                custom_alert("The user is now un-blocked!", "s");
                getAllUsers(false,page);
            } else {
                custom_alert("Server down!", "e");
            }
        }
        xhr.send(formData);
}



function removeUser(id,page){
    if(confirm("Are you sure to remove this user?")){
        let formData = new FormData();
        custom_alert("Please Wait", 'i', true, "l");
        formData.append("id", id);
        formData.append("removeUser", '');
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/users_crud.php");
        xhr.onload = function () {
            let response = this.responseText;
            console.log(response);
            if (response === "1") {
                custom_alert("User removed", "s");
                getAllUsers(false,page);
            } else {
                custom_alert("Something went wrong on server", "e");
            }
        }
        xhr.send(formData);
    }
}

window.onload = getAllUsers(true);