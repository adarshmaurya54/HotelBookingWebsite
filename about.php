<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require("./inc/comman.php") ?>
    <title><?php echo $general_assoc['site_title'] ?> - ABOUT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <?php require("./inc/links.php") ?>
    <style>
        .border-color-custom {
            border-color: var(--teal) !important;
        }

        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            user-select: none;
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 450px;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-light">

    <?php require("./inc/header.php") ?>


    <!-- our facilities -->
    <div class="my-4 text-center">
        <h4 class="h-font fw-bold fs-2">ABOUT US</h4>
        <div class="h-line bg-dark"></div>
        <p class="mt-3">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Magnam numquam illum deserunt?<br> In error blanditiis voluptatum nesciunt, neque consectetur maiores.</p>
    </div>

    <div class="container my-5 px-4">
        <div class="row bg-white align-items-center justify-content-center rounded shadow-sm py-3">
            <div class="col-lg-6 col-md-5 order-lg-1 order-md-1 order-2">
                <h3 class="mt-lg-0 mt-md-0 mt-4">Lorem ipsum dolor sit amet</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor eligendi quod consectetur praesentium rem odit, asperiores aliquam consequuntur veniam dolorum quis soluta deleniti dolores totam illum natus quos fuga, facilis corrupti voluptate! Earum totam, placeat dolores vitae tempore ut temporibus.</p>
            </div>
            <div class="col-lg-5 col-md-5 order-lg-2 order-md-2 order-1">
                <img src="./images/about/IMG_20230829_12132.jpg" class="h-100 w-100 rounded">
            </div>
        </div>
    </div>
    <div class="container px-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 px-2 mb-3">
                <div class="d-flex align-items-center flex-column justify-content-between bg-white rounded border-color-custom border-top border-4 shadow-sm p-4">
                    <img src="./images/about/staff.svg" width="80px">
                    <h5 class="mt-3">100+ Staffs</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 px-2 mb-3">
                <div class="d-flex align-items-center flex-column justify-content-between bg-white rounded border-color-custom border-top border-4 shadow-sm p-4">
                    <img src="./images/about/customers.svg" width="80px">
                    <h5 class="mt-3">300+ Customers</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 px-2 mb-3">
                <div class="d-flex align-items-center flex-column justify-content-between bg-white rounded border-color-custom border-top border-4 shadow-sm p-4">
                    <img src="./images/about/hotel.svg" width="80px">
                    <h5 class="mt-3">50+ Hotels</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 px-2 mb-3">
                <div class="d-flex align-items-center flex-column justify-content-between bg-white rounded border-color-custom border-top border-4 shadow-sm p-4">
                    <img src="./images/about/rating.svg" width="80px">
                    <h5 class="mt-3">350+ Ratings</h5>
                </div>
            </div>

        </div>
    </div>


    <?php
    $team_q = "SELECT * FROM `team_details`";
    $team_res = $conn->query($team_q);
    ?>
    <h4 class="my-5 h-font fw-bold fs-2 text-center">MANEGEMENT TEAM</h4>
    <div class="container px-4 mb-5   rounded">
        <div class="text-center bg-white p-2 rounded shadow-sm">
            <!-- Swiper -->
            <div class="swiper mySwiper ">
                <div class="swiper-wrapper">
                    <?php
                    while ($row = mysqli_fetch_assoc($team_res)) {
                        $path = ABOUT_IMAGE_FOLDER_PATH.$row['picture'];
                        echo <<<teams
                            <div class="swiper-slide flex-column gap-4">
                                <img src="$path" class="rounded shadow">
                                <h5 class="mb-5">$row[name]</h5>
                            </div>
                        teams;
                    }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

    <!-- our facilities end -->
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 4,
            spaceBetween: 40,
            freeMode: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    </script>

    <?php require("./inc/footer.php") ?>
</body>

</html>