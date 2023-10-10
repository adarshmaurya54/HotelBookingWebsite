function changeActiveClassOfNavLinks() {
    const nav = document.querySelector(".navbar");
    Array.from(nav.getElementsByClassName('nav-link')).forEach((e) => {
        if (window.location.pathname.split("/")[2] != '') {
            e.classList.remove("active")
            if (window.location.pathname.split("/")[2] == e.getAttribute("href").split("/")[1]) {
                e.classList.add("active");
            }
        }
    })
}
function validateEmail(email) {
    const pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return pattern.test(email);
}

function checkLoginToBook(flag,id){
    if(flag == 0){
        var myModalEl = document.getElementById('loginmodal')
        var modal = bootstrap.Modal.getInstance(myModalEl)
        modal.hide();
        custom_error("Please Login to book room!",'e');
    }else{
        window.location = `./room_booking.php?id=${id}`;
    }
}


window.onload = changeActiveClassOfNavLinks();