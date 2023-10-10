<div id="logout_and_name_section" class="container-fluid sticky-top border-1 bg-dark text-white p-4 d-flex align-items-center justify-content-between">
    <h3 class="h-font p-0 m-0">HB WEBSITE</h3>
    <button type="button" id="logout" class="shadow-none btn bg-white fw-bold">Log Out</button>
</div>

<div class="col-lg-2 border-top border-secondary border-2 bg-dark" id="dashbord-menu">
    <nav class="navbar p-2 navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid p-0 flex-lg-column align-items-stretch">
            <div class="navbar-brand fs-4">Admin Panel</div>
            <button id="hamb" class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-nav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse py-3" id="admin-nav">
                <ul class="nav nav-pills flex-column w-100 admin-nav-links overflow-auto" id="admin-nav-links">
                    <li class="nav-item">
                        <a class="nav-link text-white hover active mb-2" href="./index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <button id="bookings" style="padding: 8px 14px!important; background-color: #212529; border: 1px solid rgba(255, 255, 255, 0.1);" class="p-0 shadow-none d-flex justify-content-between align-items-center btn text-white w-100 hover mb-2 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#bookingLinks">
                            <span>Bookings</span>
                            <span><i class="bi bi-caret-down-fill"></i></span>
                        </button>
                        <div class="collapse border rounded mb-2 border-secondary p-2" id="bookingLinks">
                            <ul id="booking-sub-menu-links" class="nav  small nav-pills">
                                <li class="nav-item w-100">
                                    <a class="nav-link text-white hover mb-2" href="./new_bookings.php">New Bookings</a>
                                </li>
                                <li class="nav-item w-100">
                                    <a class="nav-link text-white hover mb-2" href="./refund_bookings.php">Refund Bookings</a>
                                </li>
                                <li class="nav-item w-100">
                                    <a class="nav-link text-white hover" href="./booking_records.php">Bookings Records</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover mb-2" href="./users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover mb-2" href="./user_queries.php">User Queries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover mb-2" href="./rating_and_review.php">Ratings and Reviews</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover mb-2" href="./carousel.php">Carousels</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover mb-2" href="./settings.php">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover mb-2" href="./features_and_facilities.php">Features & Facilities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover" href="./rooms.php">Rooms</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>