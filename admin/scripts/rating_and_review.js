function getRatingAndReviews(flag) {
    if (flag) {
        custom_alert("Loading", 'i', true, "l")
    }
    let formData = new FormData();
    formData.append("getRatingAndReviews", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/rating_and_review_crud.php", true);
    xhr.onload = function () {
        if (flag) {
            custom_alert("Loading", 'i', true, "l", true)
        }
        let response = xhr.responseText;
        document.getElementById("rating_and_review-data").innerHTML = response;
    }
    xhr.send(formData);
}
function seenReview(id) {
    custom_alert("Please wait", 'i', true, "l")
    let formData = new FormData();
    formData.append("id",id);
    formData.append("seenReview", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/rating_and_review_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if(response === '1'){
            custom_alert("Marked as seen", 's');
            getRatingAndReviews(false);
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
    xhr.open("POST", "./ajax/rating_and_review_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if(response === 'done'){
            custom_alert("All queries marked as seen", 's');
            getRatingAndReviews(false);
        }else if(response === 'no_mark'){
            custom_alert("No unseen queries!", 'i');
        }else {
            custom_alert("Server is down!", 'e');
        }
    }
    xhr.send(formData);
}
function deleteReview(id){
    custom_alert("Please wait", 'i', true, "l")
    let formData = new FormData();
    formData.append("id", id);
    formData.append("deleteReview", "");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./ajax/rating_and_review_crud.php", true);
    xhr.onload = function () {
        let response = xhr.responseText;
        if(response === '1'){
            custom_alert("Query is deleted", 's');
            getRatingAndReviews(false);
        }else {
            custom_alert("Server is down!", 'e');
        }
    }
    xhr.send(formData);
}
window.onload = getRatingAndReviews(true);