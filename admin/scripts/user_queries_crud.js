function getUserQueries(flag) {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    let formData = new FormData();
    formData.append("getUserQueries", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/user_queries_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true)
        }
        let response = xhr.responseText;
        document.getElementById("user-queries-data").innerHTML = response;
    }
    xhr.send(formData);
}
function seenQuery(id) {
    custom_alert("Please wait", 'i', true, "l")
    let formData = new FormData();
    formData.append("id",id);
    formData.append("seenQuery", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/user_queries_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if(response === '1'){
            custom_alert("Marked as seen", 's');
            getUserQueries(false);
        }else {
            custom_alert("Server is down!", 'e');
        }
    }
    xhr.send(formData);
}
function markAllSeen(){
    custom_alert("Please wait", 'i', true, "l")
    let formData = new FormData();
    formData.append("markAllSeen", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/user_queries_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if(response === 'done'){
            custom_alert("All queries marked as seen", 's');
            getUserQueries(false);
        }else if(response === 'no_mark'){
            custom_alert("No unseen queries!", 'i');
        }else {
            custom_alert("Server is down!", 'e');
        }
    }
    xhr.send(formData);
}
function deleteAll(){
    custom_alert("Please wait", 'i', true, "l")
    let formData = new FormData();
    formData.append("deleteAll", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/user_queries_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if(response === 'done'){
            custom_alert("All queries query deleted", 's');
            getUserQueries(false);
        }else {
            custom_alert("Server is down!", 'e');
        }
    }
    xhr.send(formData);
}
function deleteQuery(id){
    custom_alert("Please wait", 'i', true, "l")
    let formData = new FormData();
    formData.append("id", id);
    formData.append("deleteQuery", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/user_queries_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if(response === '1'){
            custom_alert("Query is deleted", 's');
            getUserQueries(false);
        }else {
            custom_alert("Server is down!", 'e');
        }
    }
    xhr.send(formData);
}
window.onload = getUserQueries(true);