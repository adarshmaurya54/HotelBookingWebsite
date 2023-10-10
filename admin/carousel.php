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
    <title>Admin Pannel - Carousels</title>
    <?php require("./inc/links.php"); ?>
</head>

<body class="bg-light">

    <?php
    require("./inc/header.php");
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4" id="admin-content">
                <h3 class="mb-4">Carousel Images</h3>

                <!-- Carousels card-->
                <div class="card shadow mb-4 border-0">
                    <div class="card-body">
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <h4>Carousels</h4>
                            <button type="button" class="btn shadow-none btn-dark btn-sm " data-bs-toggle="modal" data-bs-target="#carousel_s">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>
                        <div class="row" id="carousel-data">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Carousels modal -->
    <div class="modal fade" id="carousel_s" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="carousel_s_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Image</h5>
                        <button type="reset" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Picture (< 2MB) <span class="text-danger">*</span></label>
                            <input spellcheck="false" name="carousel-pic" accept=".jpeg, .jpg, .png, .webp" type="file" class="shadow-none form-control" />
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
    <script src="./scripts/carousel_crud.js"></script>
</body>

</html>