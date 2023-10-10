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
    <title>Admin Pannel - RATINGS AND REVIEWS</title>
    <?php require("./inc/links.php"); ?>
</head>

<body class="bg-light">

    <?php
    require("./inc/header.php");
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4" id="admin-content">
                <h3 class="mb-4">Rating & Reviews</h3>

                <!-- User queries card-->
                <div class="card shadow mb-4 border-0 p-3">
                    <h4 class="text-end mb-3">
                        <button type="button" onclick="markAllSeen()" class="btn shadow-none btn-sm btn-dark text-white">
                            <i class="bi bi-check2-all"></i>
                            Mark all as seen
                        </button>
                    </h4>
                    <div class="table-responsive-md" style="height: 400px;  overflow-y: auto;">
                        <table class="table table-hover" style="min-width: 1000px;">
                            <thead style="z-index: 10;" class="sticky-top bg-dark text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" width="15%">Room Name</th>
                                    <th scope="col">User Name</th>
                                    <th scope="col">Ratings</th>
                                    <th scope="col" width="35%">Reviews</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="rating_and_review-data">

                            </tbody>
                        </table>
                    </div>
                </div>
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
    <script src="./scripts/rating_and_review.js"></script>
</body>

</html>