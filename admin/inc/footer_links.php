<script src="./scripts/essentials.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    document.getElementById("logout").addEventListener("click", function() {
        let form = new FormData();
        form.append("logout", "");
        custom_alert("Please Wait", 'i', true, "l");
        let xhr = new XMLHttpRequest();

        xhr.open("POST", "./ajax/logout.php", true);
        xhr.onload = function() {
            let response = xhr.responseText;
            if (response == "logout") {
                window.location.href = './login.php';
            } else {
                custom_alert("Something went wrong on the server!", "e");
            }
        }
        xhr.send(form);
    })

    function changeActiveClassOfNavLinks() {
        const nav = document.querySelector(".navbar");
        Array.from(nav.getElementsByTagName('a')).forEach((e) => {
            if (window.location.href.split("/")[5] != '') {
                e.classList.remove("active")
                if (window.location.href.split("/").pop() == e.getAttribute("href").split("/")[1]) {
                    e.classList.add("active")
                }
            }
        })
    }

    const collapseElement = document.getElementById('bookingLinks');

    // Function to save the collapse state to local storage
    function saveCollapseState() {
        localStorage.setItem('collapseState', collapseElement.classList.contains('show'));
    }

    document.getElementById("bookings").addEventListener("click", function() {
        document.querySelector("#bookings .bi").classList.toggle("bi-caret-up-fill");
    })
    // Function to restore the collapse state and icon state from local storage
    function restoreCollapseState() {
        const savedState = JSON.parse(localStorage.getItem('collapseState'));
        if (savedState && savedState.collapse === 'true') {
            collapseElement.classList.add('show');
        }
        if (savedState && savedState.icon === 'bi-caret-up-fill') {
            document.querySelector("#bookings .bi").classList.add('bi-caret-up-fill');
        }
    }

    // Function to save the collapse state and icon state to local storage
    function saveCollapseState() {
        localStorage.setItem('collapseState', JSON.stringify({
            collapse: collapseElement.classList.contains('show').toString(),
            icon: document.querySelector("#bookings .bi").classList.contains('bi-caret-up-fill') ? 'bi-caret-up-fill' : '',
        }));
    }

    // Add an event listener to the collapse element to save its state when toggled
    collapseElement.addEventListener('shown.bs.collapse', saveCollapseState);
    collapseElement.addEventListener('hidden.bs.collapse', saveCollapseState);

    // Restore the collapse state and icon state when the document is ready
    document.addEventListener('DOMContentLoaded', restoreCollapseState);

    window.onload = changeActiveClassOfNavLinks();




    window.onbeforeunload = () => {
        custom_alert("Please wait", 'i', true, 'l');
    };
</script>