<?php
require("./inc/essentials.php");
isAdminLogin();
session_regenerate_id(true); // this will generate a new session ID whenever the page is refreshed, which helps prevent session hijacking.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pannel - SETTINGS</title>
    <?php require("./inc/links.php"); ?>
    <style>
        #management-team-data .team-img{
            width: 100%;
            height: 200px;
            object-fit: contain;
        }
    </style>
</head>

<body class="bg-light">

    <?php
    require("./inc/header.php");
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4" id="admin-content">
                <h3 class="mb-4">SETTINGS</h3>
                <!-- general setting card-->
                <div class="card shadow mb-4 border-0">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <h4>General Settings</h4>
                            <button type="button" class="btn shadow-none btn-dark btn-sm " data-bs-toggle="modal" data-bs-target="#general-s">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>

                        </div>
                        <h5 class="card-title">Site title</h5>
                        <p class="card-text site_title"></p>
                        <h5 class="card-title">Description</h5>
                        <p class="card-text site_desc"></p>
                    </div>
                </div>

                <!-- Shutdown card -->
                <div class="card shadow mb-4 border-0">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <h4 class="m-0">Shutdown Website</h4>
                            <div class="form-check form-switch">
                                <input onchange="toggleShutdown(this.value)" class="form-check-input shadow-none" id="shutdown-toggle" type="checkbox" checked>
                            </div>
                        </div>
                        <p class="card-text">
                            No customer will be allowed to book hotel rooms, when shutdown mode is turned on.
                        </p>
                    </div>
                </div>

                <!-- Contact setting card -->
                <div class="card shadow mb-4 border-0">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <h4>Contact Settings</h4>
                            <button type="button" class="btn shadow-none btn-dark btn-sm " data-bs-toggle="modal" data-bs-target="#contact-s">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h5>Address</h5>
                                    <p>
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span id="address"></span>
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <h5>Google Map</h5>
                                    <p id="map"></p>
                                </div>
                                <div class="mb-4">
                                    <h5>Phone Numbers</h5>
                                    <p class="mb-1">
                                        <i class="bi bi-telephone-fill"></i>
                                        +91 <span id="ph1"></span>
                                    </p>
                                    <p>
                                        <i class="bi bi-telephone-fill"></i>
                                        +91 <span id="ph2"></span>
                                    </p>
                                </div>
                                <div class="">
                                    <h5>Email</h5>
                                    <p>
                                        <i class="bi bi-envelope-fill"></i>
                                        <span id="email">adf</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class=" d-flex align-items-center justify-content-between">
                                    <h5>Social Links</h5>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn shadow-none btn-dark btn-sm " data-bs-toggle="modal" data-bs-target="#editSocialLinks">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <button type="button" class="btn shadow-none btn-dark btn-sm " data-bs-toggle="modal" data-bs-target="#addSocialLinks">
                                            <i class="bi bi-plus-square"></i> Add
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-4" id="social-links">

                                    <p class="mb-1">
                                        <i class="bi bi-twitter"></i>
                                        <span id="tw"></span>
                                    </p>
                                    <p>
                                        <i class="bi bi-linkedin"></i>
                                        <span id="ln"></span>
                                    </p>
                                </div>
                                <div class="p-2">
                                    <iframe id="map-iframe" loading="lazy" class="shadow-sm rounded w-100" src="" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management setting card-->
                <div class="card shadow mb-4 border-0">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <h4>Management Team</h4>
                            <button type="button" class="btn shadow-none btn-dark btn-sm " data-bs-toggle="modal" data-bs-target="#management_s">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>
                        <div class="row" id="management-team-data">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- General Setting's modal -->
    <div class="modal fade" id="general-s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="general_s_form">
                    <div class="modal-header">
                        <h5 class="modal-title">General Settings</h5>
                        <button type="button" onclick="site_title.value = general_data.site_title; site_desc.value = general_data.site_desc" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Site Title</label>
                            <input spellcheck="false" name="site_title" id="site_title" type="text" class="shadow-none form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea spellcheck="false" name="site_desc" id="site_desc" style="resize: none;" class="shadow-none form-control" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn shadow-none custom-bg text-white">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Social link edit Modal -->
    <div class="modal fade" id="editSocialLinks" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editSocialLinksform" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Social Link</h5>
                        <button type="button" onclick="getSocialLinksContent()" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid" id="social-links-edit-inputs">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="shadow-none btn custom-bg text-white">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Contact Setting's Modal -->
    <div class="modal fade" id="contact-s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="contact_s_form" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title">Contact Settings</h5>
                        <button type="button" onclick="preFillAllInputsOfContactSetting()" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 mb-3 ps-0">
                                    <label class="form-label fw-bold">Address <span class="text-danger">*</span></label>
                                    <input spellcheck="false" name="address" type="text" class="shadow-none form-control">
                                </div>
                                <div class="col-md-6 mb-3 p-0">
                                    <label class="form-label fw-bold">Phone 1 <span class="text-danger">*</span></label>
                                    <div class='input-group mb-3'>
                                        <div class='input-group-prepend h-100'>
                                            <span class='input-group-text'>
                                                +91
                                            </span>
                                        </div>
                                        <input spellcheck="false" name="ph1" type="number" class="shadow-none form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 ps-0">
                                    <label class="form-label fw-bold">Phone 2</label>
                                    <div class='input-group mb-3'>
                                        <div class='input-group-prepend h-100'>
                                            <span class='input-group-text'>
                                                +91
                                            </span>
                                        </div>
                                        <input spellcheck="false" name="ph2" type="number" class="shadow-none form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 p-0">
                                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                    <input spellcheck="false" name="email" type="email" class="shadow-none form-control">
                                </div>
                                <div class="col-md-6 mb-3 ps-0">
                                    <label class="form-label fw-bold">iFrame <span class="text-danger">*</span></label>
                                    <input spellcheck="false" name="iframe" type="text" class="shadow-none form-control">
                                </div>
                                <div class="col-md-6 mb-3 p-0">
                                    <label class="form-label fw-bold">Google Map <span class="text-danger">*</span></label>
                                    <input spellcheck="false" name="gmap" type="text" class="shadow-none form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="shadow-none btn custom-bg text-white">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- New Social Links add -->
    <div class="modal fade" id="addSocialLinks" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addSocialLinksform" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Social Media Link</h5>
                        <button type="button" onclick="" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class='mb-3'>
                                <label class="form-label">Name of Social Media <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="nameofsocialmedia" type="text" class="shadow-none form-control">
                            </div>
                            <div class='mb-3'>
                                <label class="form-label">Bootstrap icon class (Optional)</label>
                                <input spellcheck="false" name="bootstrapclass" type="text" class="shadow-none form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="shadow-none btn custom-bg text-white">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Mananegement team modal -->
    <div class="modal fade" id="management_s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="management_s_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Management Team</h5>
                        <button type="reset" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input spellcheck="false" name="manag-name" type="text" class="shadow-none form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Picture (< 2MB) <span class="text-danger">*</span></label>
                            <input spellcheck="false" name="manag-pic" accept=".jpeg, .jpg, .png, .webp" type="file" class="shadow-none form-control" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn shadow-none custom-bg text-white">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- For showing alerts and errors -->
    <div id="alert"></div>
    <div id="form-error"></div>
    <?php
    require("./inc/footer_links.php")
    ?>
    <script src="./scripts/setting_crud.js"></script>
    <script>
        let general_data;

        function getGeneralSettings(flag) {
            if (flag) {
                custom_alert("Loading", 'i', true, "l")
            }
            let shutdown_btn = document.getElementById("shutdown-toggle");
            let formData = new FormData();
            formData.append("get_general_s", "");
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "./ajax/settings_crud.php", true);
            xhr.onload = function() {
                if (flag) {
                    custom_alert("Loading", 'i', true, "l",true);
                }
                general_data = JSON.parse(xhr.responseText);
                if (general_data.site_title === "no_data") {
                    document.querySelector(".site_title").innerHTML = "No Title";
                    document.querySelector(".site_desc").innerHTML = "No Description";
                } else {
                    document.querySelector(".site_title").innerHTML = general_data.site_title;
                    document.querySelector(".site_desc").innerHTML = general_data.site_desc;
                    document.getElementById("site_title").value = general_data.site_title;
                    document.getElementById("site_desc").value = general_data.site_desc;
                    if (general_data.shutdown == '1') {
                        shutdown_btn.checked = true;
                        shutdown_btn.value = 0;

                    } else {
                        shutdown_btn.checked = false;
                        shutdown_btn.value = 1;
                    }
                }
            }
            xhr.send(formData);
        }
        window.onload = getGeneralSettings(true);
    </script>
</body>

</html>