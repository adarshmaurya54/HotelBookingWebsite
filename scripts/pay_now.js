
const booking_form = document.getElementById("booking_form");
const pay_info = document.getElementById("pay_info");
const pay_loader = document.getElementById("pay_loader");

function check_availablility() {
    let checkin = booking_form['checkin'].value;
    let checkout = booking_form['checkout'].value;

    document.getElementById("pay_now_btn").setAttribute("disabled", true);

    if (checkin != '' && checkout != '') {
        pay_loader.classList.remove("d-none");
        pay_loader.classList.add("d-inline-block");
        let formData = new FormData();
        formData.append("checkin", checkin);
        formData.append("checkout", checkout);
        formData.append("checkavailablility", "");
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "./ajax/room_payment.php", true);
        xhr.onload = function () {
            pay_loader.classList.remove("d-inline-block");
            pay_loader.classList.add("d-none");
            let res = JSON.parse(xhr.responseText);
            pay_info.classList.remove("alert-info");
            if (res.status == "samedate") {
                pay_info.classList.add("alert-danger");
                pay_info.innerHTML = "The check-in and check-out dates are the same.";
            } else if (res.status == "outearlier") {
                pay_info.classList.add("alert-danger");
                pay_info.innerHTML = "The check-out date is earlier than the check-in date.";
            } else if (res.status == "inearlier") {
                pay_info.classList.add("alert-danger");
                pay_info.innerHTML = "The check-in date is earlier than today's date.";
            }else if (res.status == "unavailable"){
                pay_info.classList.add("alert-danger");
                pay_info.innerHTML = `
                        Unfortunately, the room cannot be booked as it is unavailable.
                        `;
            } else if (res.status == "available") {
                pay_info.classList.remove("alert-danger");
                pay_info.classList.add("alert-success");
                pay_info.innerHTML = `
                            <strong>You can reserve the room now; it's available.</strong><br>
                            Total number of days : ${res.days}<br>
                            Payment amount : ₹${res.amount}.00
                        `;
                document.getElementById("pay_now_btn").removeAttribute("disabled");
                booking_form['room_bookings'].value = true;
            }else{
                pay_info.classList.add("alert-danger");
                pay_info.innerHTML = `
                        Something went wrong on server :(
                        `;
            }
        }
        xhr.send(formData);
    }
}

booking_form.addEventListener("submit", function (e) {
    e.preventDefault();
    pay_loader.classList.remove("d-none");
    pay_loader.classList.add("d-inline-block");
    let formData = new FormData(this);
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./razorpay/pay.php", true);
    xhr.onload = function () {
        pay_loader.classList.remove("d-inline-block");
        pay_loader.classList.add("d-none");
        let jsonData = JSON.parse(xhr.responseText);
        // Checkout details as a json
        var options = jsonData;
        /**
         * The entire list of Checkout fields is available at
         * https://docs.razorpay.com/docs/checkout-form#checkout-fields
         */
        options.handler = function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;// or transaction id
            document.getElementById('razorpay_signature').value = response.razorpay_signature;

            if (response.razorpay_payment_id) {
                const paymentAmount = options.amount / 100; // Convert to a user-friendly format

                // Perform further actions, such as verification or displaying payment details
                let formData2 = new FormData();
                formData2.append("razorpay_payment_id", response.razorpay_payment_id);
                formData2.append("razorpay_signature", response.razorpay_signature);
                formData2.append("amount", paymentAmount);

                let xhr2 = new XMLHttpRequest();
                xhr2.open("POST", "./razorpay/verify.php", true);
                xhr2.onload = function () {
                    let jData = JSON.parse(xhr2.responseText);
                    if(jData.msg == "s1" || jData.msg == "e1"){
                        custom_alert("Please Wait","i",true,"l");
                        window.location = "./pay_status.php?order=" + jData.order_id;
                    }else{
                        custom_error("Something went wrong!","e");
                    }
                    console.log(xhr2.responseText);
                };
                xhr2.send(formData2);
            }
        };

        // Boolean whether to show image inside a white frame. (default: true)
        options.theme.image_padding = true;

        options.modal = {
            ondismiss: function () {
                // when modal is closed then this code will run
            },
            // Boolean indicating whether pressing escape key 
            // should close the checkout form. (default: true)
            escape: false,
            // Boolean indicating whether clicking translucent blank
            // space outside checkout form should close the form. (default: false)
            backdropclose: false
        };

        var rzp = new Razorpay(options);
        rzp.on('payment.failed', function (response) {

            // Perform further actions, such as verification or displaying payment details
            let formData2 = new FormData();
            formData2.append("erazorpay_payment_id", response.error.metadata.payment_id);
            formData2.append("erazorpay_order_id", response.error.metadata.order_id);
            formData2.append("error_desc", response.error.description);
            formData2.append("epayment_failed_3423jklsi", "");

            let xhr2 = new XMLHttpRequest();
            xhr2.open("POST", "./razorpay/verify.php", true);
            xhr2.onload = function () {
                let jData = JSON.parse(xhr2.responseText);
                if(jData.msg == "e1"){
                    custom_error("Payment failed","e");
                }else{
                    custom_error("Something went wrong!","e");
                }
            };
            xhr2.send(formData2);
        });
        rzp.open();
    }
    xhr.send(formData);
})