<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - ROOMS</title>
    <?php require("./inc/links.php") ?>
</head>

<body class="bg-light">

    <?php
    require("./inc/header.php");
    $checkin_def = "";
    $checkout_def = "";
    $adults_def = "";
    $childrens_def = "";
    if (isset($_GET['checkavail'])) {
        // echo "yes";
        $frm = filteration($_GET);
        if ($frm['adults'] > 0) {
            $adults_def = $frm['adults'];
        }
        if ($frm['childrens'] > 0) {
            $childrens_def = $frm['childrens'];
        }
        $checkin_def = $frm['checkin'];
        $checkout_def = $frm['checkout'];
    }
    ?>


    <!-- our facilities -->
    <div class="my-4 text-center">
        <h4 class="h-font fw-bold fs-2">OUT ROOMS</h4>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container-fluid mt-5">
        <div class="row">
            <!-- room filter  -->
            <div class="col-lg-3 mb-4 mb-lg-0 ps-3">
                <nav class="navbar rounded navbar-expand-lg navbar-light bg-white shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-between">
                            <span>FILTERS</span>
                            <button type="reset" onclick="reset_all()" id="resetall" class="d-none mt-lg-0 mt-2 btn btn-sm border btn-white shadow-none text-dark">Reset All</button>
                        </h4>
                        <button class="border-0 shadow-none navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#filterNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch" id="filterNav">
                            <div class="bg-light rounded p-3 shadow-sm mb-3 mt-3">
                                <h5 class="mb-3 d-flex align-items-center justify-content-between">
                                    <span>Check Availabilities</span>
                                    <button onclick='chk_reset_btn()' type="reset" class="d-none btn border  btn-sm shadow-none btn-light" id="chk_reset_btn">Reset</button>
                                </h5>
                                <div class="mb-2">
                                    <label class="form-label">Check-in</label>
                                    <input spellcheck="false" value="<?php echo $checkin_def ?>" type="date" class="shadow-none form-control" id="checkin" onchange="chk_avail_fun()">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Check-out</label>
                                    <input spellcheck="false" type="date" value="<?php echo $checkout_def ?>" class="shadow-none form-control" id="checkout" onchange="chk_avail_fun()">
                                </div>
                                <div id="chk_errors" class="text-danger px-3 rounded text-center mt-2 d-none" style="background-color: #f8d7da;font-size: 0.8em;"></div>
                            </div>
                            <div class="bg-light rounded p-3 shadow-sm mb-3">
                                <h5 class="mb-3 d-flex align-items-center justify-content-between">
                                    <span>Facilities</span>
                                    <button onclick='faci_reset_btn()' type="reset" class="d-none btn border btn-sm shadow-none btn-light" id="faci_reset_btn">Reset</button>
                                </h5>
                                <?php
                                $q = selectAll("facilities");
                                while ($row = mysqli_fetch_assoc($q)) {
                                    echo <<<facilities
                                            <div class="mb-2">
                                                <input spellcheck="false" onclick="facility_search()" name="facilities" value="$row[id]" type="checkbox" id="$row[id]" class="shadow-none form-check-input">
                                                <label class="form-label" style="user-select : none;" for="$row[id]">$row[name]</label>
                                            </div>
                                        facilities;
                                }
                                ?>
                            </div>
                            <div class="bg-light rounded p-3 shadow-sm mb-3">
                                <h5 class="mb-3 d-flex align-items-center justify-content-between">
                                    <span>Guests</span>
                                    <button onclick='guest_reset_btn()' type="reset" class="d-none btn border  btn-sm shadow-none btn-light" id="guest_reset_btn">Reset</button>
                                </h5>
                                <div class="d-flex gap-2">
                                    <div>
                                        <label class="form-label">Adult</label>
                                        <input spellcheck="false" min="1" value="<?php echo $adults_def ?>" oninput="search_by_guest()" type="number" id="adult" class="shadow-none form-control">
                                    </div>
                                    <div>
                                        <label class="form-label">Children</label>
                                        <input spellcheck="false" min="1" value="<?php echo $childrens_def ?>" oninput="search_by_guest()" type="number" id="children" class="shadow-none form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- rooms -->
            <div class="col-lg-9" id="room_data">

            </div>
        </div>
    </div>
    <!-- our facilities end -->

    <?php require("./inc/footer.php") ?>
    <script>
        let checkin = document.getElementById("checkin");
        let checkout = document.getElementById("checkout");
        let adult = document.getElementById("adult");
        let children = document.getElementById("children");

        function filter_room() {
            let chk_avail_data = JSON.stringify({
                chkin: checkin.value,
                chkout: checkout.value
            });
            let guests = JSON.stringify({
                adult: adult.value,
                children: children.value
            })
            let checkedFacility = {
                "facilities": []
            };
            Array.from(document.querySelectorAll("input[name=facilities]:checked")).forEach(e => {
                checkedFacility.facilities.push(e.value);
            })
            let facility_json = JSON.stringify(checkedFacility);

            let timeoutId2 = null;
            document.getElementById("room_data").innerHTML = `
            <div class="text-center mt-5" style="z-index: -1; position: relative;">
                <div class="spinner-border my-2 d-none" id="loader" role="status">
                    <span class="visually-hidden">Loaidng...</span>
                </div>
                <p id="loading-msg" class="d-none">Please wait...</p>
            </div>
            `;
            document.getElementById("loader").classList.remove("d-none");
            document.getElementById("loading-msg").classList.remove("d-none");
            let i = 0;
            timeoutId2 = setInterval(function() {
                if (i % 2 == 0) {
                    document.getElementById("loading-msg").innerHTML = "Almost there...";
                } else {
                    document.getElementById("loading-msg").innerHTML = "Please wait...";
                }
                i++;
            }, 1000);
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "./ajax/filter_rooms.php?filter_room&chk_avail=" + chk_avail_data + "&guests=" + guests + "&facilities=" + facility_json, true)
            xhr.onload = function() {
                clearTimeout(timeoutId2);
                let data = JSON.parse(xhr.responseText);
                document.getElementById("loader").classList.add("d-none");
                document.getElementById("loading-msg").classList.add("d-none");
                if (data.flag == "chkinerl") {
                    checkin.classList.add("border-danger");
                    document.getElementById("chk_errors").innerHTML = "The check-in date is earlier than today's date.";
                    document.getElementById("chk_errors").classList.remove("d-none");
                } else if (data.flag == "chkouterl") {
                    checkout.classList.add("border-danger");
                    document.getElementById("chk_errors").innerHTML = "The check-out date is earlier than the check-in date.";
                    document.getElementById("chk_errors").classList.remove("d-none");
                } else if (data.flag == "same") {
                    checkout.classList.add("border-danger");
                    checkin.classList.add("border-danger");
                    document.getElementById("chk_errors").innerHTML = "The check-in and check-out dates are the same.";
                    document.getElementById("chk_errors").classList.remove("d-none");
                } else if (data.flag == "rooms") {
                    checkin.classList.remove("border-danger");
                    checkout.classList.remove("border-danger");
                    document.getElementById("chk_errors").innerHTML = "";
                    document.getElementById("chk_errors").classList.add("d-none");
                    document.getElementById("room_data").innerHTML = data.data;
                } else if (data.flag == "noData") {
                    document.getElementById("room_data").innerHTML = data.data;
                }
            }
            xhr.send();
        }

        function facility_search() {
            document.getElementById("resetall").classList.remove("d-none")
            document.getElementById("faci_reset_btn").classList.remove("d-none");
            filter_room();
        }

        function faci_reset_btn() {
            Array.from(document.querySelectorAll("input[name=facilities]:checked")).forEach(e => {
                e.checked = false;
            })
            document.getElementById("faci_reset_btn").classList.add("d-none");
            filter_room();
        }

        function chk_avail_fun() {
            document.getElementById("resetall").classList.remove("d-none")
            document.getElementById("chk_reset_btn").classList.remove("d-none");
            if (checkin.value != '' && checkout.value != '') {
                filter_room()
            }
        }

        function chk_reset_btn() {
            checkin.classList.remove("border-danger");
            checkout.classList.remove("border-danger");
            document.getElementById("chk_errors").innerHTML = "";
            document.getElementById("chk_errors").classList.add("d-none");
            checkin.value = '';
            checkout.value = '';
            document.getElementById("chk_reset_btn").classList.add("d-none");
            filter_room()
        }

        function search_by_guest() {
            document.getElementById("resetall").classList.remove("d-none")
            if (adult.value > 0 || children.value > 0) {
                document.getElementById("guest_reset_btn").classList.remove("d-none");
                filter_room();
            }
        }

        function guest_reset_btn() {
            adult.value = "";
            children.value = "";
            document.getElementById("guest_reset_btn").classList.add("d-none");
            filter_room();
        }


        function reset_all() {
            // guest reset
            adult.value = "";
            children.value = "";
            document.getElementById("guest_reset_btn").classList.add("d-none");

            // checkin and checkout reset
            checkin.classList.remove("border-danger");
            checkout.classList.remove("border-danger");
            document.getElementById("chk_errors").innerHTML = "";
            document.getElementById("chk_errors").classList.add("d-none");
            checkin.value = '';
            checkout.value = '';
            document.getElementById("chk_reset_btn").classList.add("d-none");

            // facilities reset
            Array.from(document.querySelectorAll("input[name=facilities]:checked")).forEach(e => {
                e.checked = false;
            })
            document.getElementById("faci_reset_btn").classList.add("d-none");
            document.getElementById("resetall").classList.add("d-none")
            filter_room();
        }
        window.onbeforeunload = function() {
            document.getElementById("room_data").innerHTML = `
            <div class="text-center mt-5" style="z-index: -1; position: relative;">
                <div class="spinner-border my-2 d-none" id="loader" role="status">
                    <span class="visually-hidden">Loaidng...</span>
                </div>
                <p id="loading-msg" class="d-none">Please wait...</p>
            </div>
            `;
            document.getElementById("loader").classList.remove("d-none");
            document.getElementById("loading-msg").classList.remove("d-none");
        }
        window.onload = filter_room();
    </script>
</body>

</html>