<!-- Footer-->
<footer class="container-fluid py-5 px-3 mt-5 bg-white m-0">
    <div class="row">
        <div class="col-md-4 mb-lg-0 mb-md-0 mb-4">
            <h3 class="h-font fw-bold fs-2 mb-3"><?php echo $general_assoc['site_title'] ?></h3>
            <p class="fs-5"><?php echo $general_assoc['site_desc'] ?></p>
        </div>
        <div class="col-md-4  mb-lg-0 mb-md-0 mb-4">
            <h6 class="fs-3 mb-4 border-bottom fw-normal">Links</h6>
            <a href="./index.php" class="text-decoration-none d-inline-block mb-1 text-dark">Home</a><br>
            <a href="./rooms.php" class="text-decoration-none d-inline-block mb-1 text-dark">Rooms</a><br>
            <a href="./facilities.php" class="text-decoration-none d-inline-block mb-1 text-dark">Facilities</a><br>
            <a href="./contact.php" class="text-decoration-none d-inline-block mb-1 text-dark">Contact Us</a><br>
            <a href="./about.php" class="text-decoration-none d-inline-block mb-1 text-dark">About</a><br>
            
            <a href="./admin/login.php" class="btn shadow-none text-decoration-none mt-3 btn-dark">Admin Login</a>            
        </div>
        <div class="col-md-4">
            <h6 class="fs-3 mb-3 border-bottom fw-normal">Follow Us</h6>
            <?php
            $social_q = "SELECT * FROM `social_links`";
            $social_res = $conn->query($social_q);
            while ($row = mysqli_fetch_assoc($social_res)) {
                if ($row['social_link'] != '') {
                    echo <<<social
                        <a href="$row[social_link]" class="badge text-decoration-none d-inline-block rounded-pill fs-6 bg-light text-dark mb-3">
                            <i class="bi $row[icon_class_name]"></i> $row[name]
                        </a></br>
                    social;
                }
            }
            ?>
        </div>
    </div>
</footer>
<!-- Footer end-->

<!-- Designed by name -->
<div class="bg-dark text-white p-4 text-center fs-4 design-by">
    Designed and Developed by <a href="https://adarshmaurya54.github.io/Portfolio/" class="text-decoration-none" style="color: orange;">Adarsh Maurya</a>
</div>
<!-- For showing alerts and errors -->
<div id="alert"></div>
<div id="form-error"></div>
<!-- Designed by name end -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="./admin/scripts/essentials.js"></script>
<script src="./scripts/essentials.js"></script>
<script src="./scripts/registration.js"></script>
<script src="./scripts/login.js"></script>
<script src="./scripts/forgot.js"></script>