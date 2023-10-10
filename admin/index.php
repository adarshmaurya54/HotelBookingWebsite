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
    <title>Admin Pannel - DASHBOARD</title>
    <?php require("./inc/links.php"); ?>
    <style>
        .hover:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .card .total {
            font-size: 4em;
        }

        .basic_details {
            transition: transform 0.5s ease;
        }

        .basic_details:hover {
            transform: scale(1.05);
        }

        .card {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.5s ease,
                transform 0.8s ease;
            user-select: none;
        }

        .card.active {
            transition: opacity 0.5s ease,
                transform 0.8s ease;
            opacity: 1;
            transform: translateY(0px);
        }
        .badge{
            white-space: normal;
            line-height: 16px;
        }
    </style>
</head>

<body class="bg-light">

    <?php
    require("./inc/header.php");
    ?>
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4" id="admin-content">
                <div class="d-flex align-items-center gap-2 justify-content-between mb-5">
                    <h2 class="m-0">DASHBOARD</h2>
                    <?php
                    $general = mysqli_fetch_assoc($conn->query("SELECT * FROM `general_settings` WHERE `id`=1"));
                    if ($general['shutdown']) {
                        echo <<<data
                            <span sytle="white-space: normal;" class="badge bg-danger py-2 px-3">Shutdown mode is on!</span>
                        data;
                    }
                    ?>
                </div>
                <div class="row mb-5" id="basic_details">
                    <div class="col-md-3 mb-3 basic_details">
                        <a href="./new_bookings.php" class="text-decoration-none">
                            <div class="card shadow p-3 h-100 bg-white text-success d-flex flex-column align-items-center justify-content-between">
                                <h3 class="m-0 fs-6">New Bookings</h3>
                                <p class="p-0 total m-0 mt-3"></p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3 basic_details">
                        <a href="./refund_bookings.php" class="text-decoration-none">
                            <div class="card shadow p-3 h-100 bg-white text-warning d-flex flex-column align-items-center justify-content-between">
                                <h3 class="m-0 fs-6">Refund Bookings</h3>
                                <p class="p-0 total m-0 mt-3"></p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3 basic_details">
                        <a href="./user_queries.php" class="text-decoration-none">
                            <div class="card shadow p-3 h-100 bg-white text-secondary d-flex flex-column align-items-center justify-content-between">
                                <h3 class="m-0 fs-6">User Queries</h3>
                                <p class="p-0 total m-0 mt-3"></p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3 basic_details">
                        <a href="./rating_and_review.php" class="text-decoration-none">
                            <div class="card shadow p-3 bg-white text-info h-100 d-flex flex-column align-items-center justify-content-between">
                                <h3 class="m-0 fs-6">Ratings & Reviews</h3>
                                <p class="p-0 total m-0 mt-3"></p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- booking analytics -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="m-0">BOOKINGS ANALYTICS</h3>
                    <select class="form-select shadow-none w-auto" onchange="bookingsAnalytics(this.value)">
                        <option value="1">Past 7 days</option>
                        <option value="2">Past 30 days</option>
                        <option value="3">Past 90 days</option>
                        <option value="4">All</option>
                    </select>
                </div>
                <div class="row mb-5" id="bookingsAnalytics">
                    <div class="col-md-4 mb-3">
                        <div class="card shadow p-3 h-100 bg-white text-primary d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">Total Bookings</h3>
                            <p class="p-0 total m-0 mt-3" id="total_bookings">0</p>
                            <p class="p-0 fs-4 m-0 mt-1" id="total_amt">₹0</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow p-3 h-100 bg-white text-success d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">Active Bookings</h3>
                            <p class="p-0 total m-0 mt-3" id="active_bookings">0</p>
                            <p class="p-0 fs-4 m-0 mt-1" id="active_amt">₹0</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card shadow p-3 h-100 bg-white text-danger d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">Cancelled Bookings</h3>
                            <p class="p-0 total m-0 mt-3" id="cancel_bookings">0</p>
                            <p class="p-0 fs-4 m-0 mt-1" id="cancel_amt">₹0</p>
                        </div>
                    </div>
                </div>

                <!-- user,queries,reviews analytics -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 class="m-0">USERS, QUERIES AND REVIEWS ANALYTICS</h3>
                    <select class="form-select shadow-none w-auto" onchange="otherAnalytics(this.value)">
                        <option value="1">Past 7 days</option>
                        <option value="2">Past 30 days</option>
                        <option value="3">Past 90 days</option>
                        <option value="4">All</option>
                    </select>
                </div>
                <div class="row mb-5" id="otheranalytics">
                    <div class="col-md-4 mb-3">
                        <div style="color: #520dc2;" class="card shadow p-3 h-100 bg-white d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">New Registration</h3>
                            <p class="p-0 total m-0 mt-3" id="new_reg">5</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="color: #ca6510;" class="card shadow p-3 h-100 bg-white d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">Queries</h3>
                            <p class="p-0 total m-0 mt-3" id="queries">5</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="color: #561435;" class="card shadow p-3 h-100 bg-white d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">Reviews</h3>
                            <p class="p-0 total m-0 mt-3" id="reviews">5</p>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <h3 class="m-0  mb-4">USERS</h3>
                <div class="row mb-5" id="users">
                    <div class="col-md-3 mb-3 users">
                        <div style="color: #71609b;" class="card shadow p-3 h-100 bg-white d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">Total</h3>
                            <p class="p-0 total m-0 mt-3">0</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 users">
                        <div style="color: #17aa63;" class="card shadow p-3 h-100 bg-white d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">Active</h3>
                            <p class="p-0 total m-0 mt-3">0</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 users">
                        <div style="color: #17284d;" class="card shadow p-3 h-100 bg-white d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">Inactive</h3>
                            <p class="p-0 total m-0 mt-3">0</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 users">
                        <div style="color: #740948;" class="card shadow p-3 h-100 bg-white d-flex flex-column align-items-center justify-content-between">
                            <h3 class="m-0 fs-6">Un-verified</h3>
                            <p class="p-0 total m-0 mt-3">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="alert"></div>
    <?php
    require("./inc/footer_links.php")
    ?>
    <script src="./scripts/dashboard_crud.js"></script>
    <script src="./scripts/dashboard.js"></script>
</body>

</html>