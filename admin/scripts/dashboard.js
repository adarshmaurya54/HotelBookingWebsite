let firstValues = null;
let usersValues = null;
let bookingsAnalyticsValue = null;
let otherAnalyticsValue = null;

const usersElement = document.getElementById("users");
const basic_details = document.getElementById("basic_details");
const bookings_analytics = document.getElementById("bookingsAnalytics");
const other_analytics = document.getElementById("otheranalytics");

function NewBookings_Refund_queries_and_review_count() {
    Array.from(basic_details.querySelectorAll(".card")).forEach((e, i) => {
        e.classList.add("active");
        if (i > 0) {
            e.style.transitionDelay = 0.5 * ((i * 4) / 10) + "s";
        }
    })
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "./ajax/dashboard.php?NewBookings_Refund_queries_and_review_count", true);
    xhr.onload = function () {
        firstValues = JSON.parse(xhr.responseText)
        let basic_details = document.getElementById("basic_details").querySelectorAll(".basic_details");;
        basic_details.forEach((e, index) => {
            setinterval(e.querySelector("p"),firstValues.data[index]);
        })
    }
    xhr.send();
}

function users() {
    Array.from(usersElement.querySelectorAll(".card")).forEach((e, i) => {
        e.classList.add("active");
        if (i > 0) {
            e.style.transitionDelay = 0.5 * ((i * 4) / 10) + "s";
        }
    })
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "./ajax/dashboard.php?getUsers", true);
    xhr.onload = function () {
        usersValues = JSON.parse(xhr.responseText)
        let basic_details = document.getElementById("users").querySelectorAll(".users");
        basic_details.forEach((e, index) => {
            setinterval(e.querySelector("p"),usersValues.data[index]);
        })
    }
    xhr.send();
}

function bookingsAnalytics(period = 1) {
    Array.from(bookings_analytics.querySelectorAll(".card")).forEach((e, i) => {
        e.classList.add("active");
        if (i > 0) {
            e.style.transitionDelay = 0.5 * ((i * 4) / 10) + "s";
        }
    })
    let xhr = new XMLHttpRequest();
    let formData = new FormData();
    formData.append("bookingsAnalytics", "");
    formData.append("period", period);
    xhr.open("POST", "./ajax/dashboard.php", true);
    xhr.onload = function () {
        bookingsAnalyticsValue = JSON.parse(xhr.responseText)
        setinterval(document.getElementById("total_bookings"), bookingsAnalyticsValue.total_bookings);
        document.getElementById("total_amt").innerHTML = (bookingsAnalyticsValue.total_amt) ? "₹" + parseInt(bookingsAnalyticsValue.total_amt).toLocaleString('en-IN')  + ".00" : "₹00.00";
        setinterval(document.getElementById("cancel_bookings"), bookingsAnalyticsValue.cancel_bookings);
        document.getElementById("cancel_amt").innerHTML = (bookingsAnalyticsValue.cancel_amt)? "₹" + parseInt(bookingsAnalyticsValue.cancel_amt).toLocaleString('en-IN')  + ".00": "₹00.00";
        setinterval(document.getElementById("active_bookings"), bookingsAnalyticsValue.active_bookings);
        document.getElementById("active_amt").innerHTML = (bookingsAnalyticsValue.active_amt)?  "₹" + parseInt(bookingsAnalyticsValue.active_amt).toLocaleString('en-IN') + ".00" : "₹00.00";
    }
    xhr.send(formData);
}

function otherAnalytics(period = 1){
    Array.from(other_analytics.querySelectorAll(".card")).forEach((e, i) => {
        e.classList.add("active");
        if (i > 0) {
            e.style.transitionDelay = 0.5 * ((i * 4) / 10) + "s";
        }
    })
    let xhr = new XMLHttpRequest();
    let formData = new FormData();
    formData.append("otherAnalytics", "");
    formData.append("period", period);
    xhr.open("POST", "./ajax/dashboard.php", true);
    xhr.onload = function () {
        otherAnalyticsValue = JSON.parse(xhr.responseText)
        setinterval(document.getElementById("new_reg"), otherAnalyticsValue.new_reg);
        setinterval(document.getElementById("queries"), otherAnalyticsValue.user_queries);
        setinterval(document.getElementById("reviews"), otherAnalyticsValue.reviews);
    }
    xhr.send(formData);
}


function setinterval(element, value, timming = 150) {
    let i = 0;
    let setId = setInterval(function () {
        if (i <= value) {
            element.innerHTML = i;
        } else {
            clearInterval(setId);
        }
        i++;
    }, timming);
}
// used to show content when card comes in viewport..

const elementsToWatch = [usersElement, basic_details, bookings_analytics,other_analytics];

const callbacks = {
    users: () => users(),
    basic_details: () => NewBookings_Refund_queries_and_review_count(),
    bookingsAnalytics: () => bookingsAnalytics(),
    otheranalytics: () => otherAnalytics()
};

const intersectionObservers = elementsToWatch.map(element => {
    let hasEnteredViewport = false;

    const callback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !hasEnteredViewport) {
                const elementId = element.id;
                callbacks[elementId](); // Call the corresponding function
                hasEnteredViewport = true;
            }
        });
    };

    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.5,
    };

    const observer = new IntersectionObserver(callback, options);
    observer.observe(element);

    return observer;
});


