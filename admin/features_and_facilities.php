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
    <title>Admin Pannel - FEATURES AND FACILITIES</title>
    <?php require("./inc/links.php"); ?>
</head>

<body class="bg-light">

    <?php
    require("./inc/header.php");
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4" id="admin-content">
                <h3 class="mb-4">Features and Facilities</h3>

                <!-- Features card-->
                <div class="card shadow mb-4 border-0 p-3">
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <h4>Features</h4>
                        <button type="button" class="btn shadow-none btn-dark btn-sm " data-bs-toggle="modal" data-bs-target="#features_s">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>
                    <div class="table-responsive-md" style="height: 350px; overflow-y: auto;">
                        <table class="table table-hover">
                            <thead style="z-index: 10;" class="sticky-top bg-dark text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" width="60%">Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="features-data">

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Facilities card-->
                <div class="card shadow mb-4 border-0 p-3">
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <h4>Facilities</h4>
                        <button type="button" class="btn shadow-none btn-dark btn-sm " data-bs-toggle="modal" data-bs-target="#facility_s">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>
                    <div class="table-responsive-md" style="height: 450px; overflow-y: auto;">
                        <table class="table table-hover">
                            <thead style="z-index: 10;" class="sticky-top bg-dark text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" >Icon</th>
                                    <th scope="col" >Name</th>
                                    <th scope="col" width="70%">Description</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="facilities-data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- fetures modal -->
    <div class="modal fade" id="features_s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="features_s_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Fetures</h5>
                        <button type="reset" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input spellcheck="false" name="feature-name" type="text" class="shadow-none form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn shadow-none custom-bg text-white">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- facility modal -->
    <div class="modal fade" id="facility_s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="facility_s_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Fetures</h5>
                        <button type="reset" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input spellcheck="false" name="facility-name" type="text" class="shadow-none form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Picture (< 2MB) <span class="text-danger">*</span></label>
                            <input spellcheck="false" type="file" name="facility-pic" accept=".svg" class="shadow-none form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description (Optional) </label>
                            <textarea oninput="changeCharacterLen()" spellcheck="false" style="resize: none;" name="facility-desc" class="shadow-none form-control" rows="5"></textarea>
                            <p class="text-muted mt-1">
                                (<span id="countCharacter">0</span>/250)
                            </p>
                            <span id="desc_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;">
                                 
                            </span>
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
    <!-- For showing alerts and errors -->
    <?php
    require("./inc/footer_links.php")
    ?>
    <script src="./scripts/features_and_facilities_crud.js"></script>
</body>

</html>