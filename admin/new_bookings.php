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
    <title>Admin Pannel - NEW BOOKINGS</title>
    <?php require("./inc/links.php"); ?>
</head>

<body class="bg-light">

    <?php
    require("./inc/header.php");
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4" id="admin-content">
                <h3 class="mb-4">New Bookings</h3>

                <!-- Features card-->
                <div class="card shadow mb-4 border-0 p-3">
                    <div class="mb-3 d-flex justify-content-end">
                        <input type="text" oninput="searchUser(this)" id="searchuser" style="max-width: 300px;" placeholder="Search..." class="shadow-none form-control">
                    </div>
                    <div class="table-responsive overflow-auto">
                        <table class="table table-hover" style="min-width: 1200px">
                            <thead style="z-index: 10;" class="sticky-top bg-dark text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User Details</th>
                                    <th scope="col">Room Details</th>
                                    <th scope="col">Booking Details</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-data">

                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <nav class="mt-3">
                            <ul class="pagination" id="pagination">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Assign Room number modal -->
    <div class="modal fade" id="assignRoom" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="assignRoom_form">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Room</h5>
                        <button type="reset" class="shadow-none btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Room Number <span class="text-danger">*</span></label>
                            <input spellcheck="false" name="room_number" type="text" class="shadow-none form-control">
                        </div>
                        <div class="text-center">
                            <span class="badge bg-light text-wrap lh-base text-dark mb-3">
                                Note : Assign room number only when user has been arrived.
                            </span>
                        </div>
                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="page_no">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn shadow-none custom-bg text-white">ASSIGN</button>
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
    <script src="./scripts/new_bookings.js"></script>
</body>

</html>