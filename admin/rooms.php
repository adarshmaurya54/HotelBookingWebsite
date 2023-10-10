<?php
require("./inc/essentials.php");
require("./inc/db_config.php");
isAdminLogin();
session_regenerate_id(true); // this will generate a new session ID whenever the page is refreshed, which helps prevent session hijacking.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pannel - ROOMS</title>
    <?php require("./inc/links.php"); ?>
</head>

<body class="bg-light">

    <?php
    require("./inc/header.php");
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4" id="admin-content">
                <h3 class="mb-4">Rooms</h3>

                <!-- Features card-->
                <div class="card shadow mb-4 border-0 p-3">
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <h4>Rooms</h4>
                        <button type="button" class="btn shadow-none btn-dark btn-sm " data-bs-toggle="modal" data-bs-target="#add-rooms">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>
                    <div class="" style="height: 350px; overflow-y: auto;">
                        <table class="table table-hover table-responsive-lg">
                            <thead style="z-index: 10;" class="sticky-top bg-dark text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Guests</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="rooms-data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- room modal -->
    <div class="modal fade" id="add-rooms" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="add_room_form" autocomplete="off">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Room</h5>
                        <button type="reset" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="name" type="text" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Area (in sq. ft.) <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="area" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="price" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="quantity" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Adults (max.) <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="adult" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Childrens (max.) <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="children" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Features <span class="text-danger">*</span></label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('features');
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo <<<data
                                                <div class="col-md-3 mb-2">
                                                    <label>
                                                        <input class="form-check-input shadow-none" name="features[]" value="$row[id]" type="checkbox">
                                                        <span class="ms-1 text-capitalize">$row[feature_name]</span>
                                                    </label>
                                                </div>
                                            data;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Facilities <span class="text-danger">*</span></label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('facilities');
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo <<<data
                                                <div class="col-md-3 mb-2">
                                                    <label>
                                                        <input class="form-check-input shadow-none" name="facilities[]" value="$row[id]" type="checkbox">
                                                        <span class="ms-1 text-capitalize">$row[name]</span>
                                                    </label>
                                                </div>
                                            data;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea spellcheck="false" style="resize: none;" name="desc" class="shadow-none form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn shadow-none custom-bg text-white">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- edit room modal -->
    <div class="modal fade" id="edit-rooms" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="edit_rooms_form" autocomplete="off">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Room</h5>
                        <button type="reset" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="name" type="text" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Area (in sq. ft.) <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="area" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="price" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="quantity" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Adults (max.) <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="adult" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Childrens (max.) <span class="text-danger">*</span></label>
                                <input spellcheck="false" name="children" type="number" class="shadow-none form-control">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Features <span class="text-danger">*</span></label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('features');
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo <<<data
                                                <div class="col-md-3 mb-2">
                                                    <label>
                                                        <input class="form-check-input shadow-none" name="features[]" value="$row[id]" type="checkbox">
                                                        <span class="ms-1 text-capitalize">$row[feature_name]</span>
                                                    </label>
                                                </div>
                                            data;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Facilities <span class="text-danger">*</span></label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('facilities');
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo <<<data
                                                <div class="col-md-3 mb-2">
                                                    <label>
                                                        <input class="form-check-input shadow-none" name="facilities[]" value="$row[id]" type="checkbox">
                                                        <span class="ms-1 text-capitalize">$row[name]</span>
                                                    </label>
                                                </div>
                                            data;
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea spellcheck="false" style="resize: none;" name="desc" class="shadow-none form-control" rows="5"></textarea>
                            </div>
                            <input type="hidden" name="room_id">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn shadow-none custom-bg text-white">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- add Room images -->
    <div class="modal fade" id="add-room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Room Name</h5>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="pb-3 mb-3 border-secondary border-bottom border-2">
                        <form id="add_image_form">
                            <div class="mb-3">
                                <label class="form-label">Image ( < 2MB) <span class="text-danger">*</span></label>
                                <input spellcheck="false" type="file" name="room-pic" accept=".png, .jpeg, .jpg, .webp" class="shadow-none form-control">
                            </div>
                            <input type="hidden" name="room_id">
                            <button type="submit" class="btn shadow-none custom-bg text-white">SUBMIT</button>
                        </form>
                    </div>
                    <div class=" table-responsive-lg" style="height: 350px; overflow-y: auto;">
                        <table class="table table-hover">
                            <thead style="z-index: 10;" class="sticky-top bg-dark text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" width="60%">Image</th>
                                    <th scope="col">Thumbnail</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="room-image-data">
                                    <tr style="height: 80px" class="text-center align-middle">
                                        <td colspan="4">Please Wait...</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
    <script src="./scripts/rooms_crud.js"></script>
</body>

</html>