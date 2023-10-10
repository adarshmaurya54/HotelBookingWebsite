<!-- Navigation bar start -->
<div class="sticky-top">
    <?php
    if ($general_assoc['shutdown']) {
        echo <<<data
            <div class="bg-danger p-2 text-white text-center fw-bold">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Booking are temporarily closed!
            </div>
        data;
    }
    ?>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm px-lg-3 py-lg-2 py-3">
        <div class="container-fluid">
            <a class="navbar-brand me-5 fs-2 h-font fw-bold" href="./index.php"><?php echo $general_assoc['site_title'] ?></a>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item me-2">
                        <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link" href="./rooms.php">Rooms</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link" href="./facilities.php">Facilities</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link" href="./contact.php">Contact us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./about.php">About</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php
                    
                    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
                        echo <<<log_reg
                        <!-- Login Button trigger modal -->
                        <button type="button" class="btn btn-outline-dark me-lg-3 me-2 shadow-none" data-bs-toggle="modal" data-bs-target="#loginmodal">
                            Login
                        </button>
                        <button type="button" class="btn btn-outline-dark shadow-none" data-bs-toggle="modal" data-bs-target="#registermodal">
                            Registration
                        </button>
                    log_reg;
                    } else {
                        $q = "SELECT `name`,`profile` FROM `user_cred` WHERE `id`=$_SESSION[uId]";
                        $res = $conn->query($q);
                        $res_assoc = mysqli_fetch_assoc($res);
                        $path = USERS_FOLDER_PATH . $res_assoc['profile'];
                        echo <<<login
                        <div class="btn-group">
                            <button type="button" class="btn shadow-none rounded-4 btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                <img id="login_user_profile" src="$path" class="me-1" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;"/>
                                <span id="user_name">$res_assoc[name]</span>
                            </button>
                            <ul class="dropdown-menu rounded-4 dropdown-menu-lg-end">
                                <li><a class="dropdown-item" href="./profile.php"><i class="bi bi-person-square"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="./bookings.php"><i class="bi bi-journal-check"></i> Bookings</a></li>
                                <li><a class="dropdown-item" href="./logout.php"><i class="bi bi-box-arrow-right"></i> Log Out</a></li>
                            </ul>
                        </div>
                    login;
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>
</div>
<!-- Navigation bar end -->
<!-- Login modal -->
<div class="modal fade" id="loginmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form novalidate autocomplete="off" id="login_form">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-fill fs-3 me-3"></i> Login
                    </h5>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Email / Mobile</label>
                        <input spellcheck="false" type="text" name="email_mob" class="shadow-none form-control">
                        <span id="email_mob_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input spellcheck="false" type="password" name="password" class="shadow-none form-control">
                        <span id="password_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                    </div>
                    <div class="mb-2 d-flex align-items-center justify-content-between">
                        <button class="shadow-none btn btn-dark">Login</button>
                        <button type="button" class="btn text-secondary shadow-none" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal">
                            Forgot Password?
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Login modal end -->

<!-- registration modal -->
<div class="modal fade" id="registermodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form novalidate method="post" id="registration-form">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-plus-fill fs-3 me-3"></i> Registration
                    </h5>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <span class="badge bg-light text-wrap lh-base text-dark mb-3">
                        Note : Your details must match with your ID (Aadhar Card, Driving Licence, Passport etc.) that will be required during check-in.
                    </span>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name<span class="text-danger">*</span></label>
                                <input spellcheck="false" name="name" type="text" class="shadow-none form-control">
                                <span id="name_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email<span class="text-danger">*</span></label>
                                <input spellcheck="false" type="email" name="email" class="shadow-none form-control">
                                <span id="email_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;font-size: 0.8em;"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone<span class="text-danger">*</span></label>
                                <input spellcheck="false" type="number" name="phone" class="shadow-none form-control">
                                <span id="phone_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;font-size: 0.8em;"></span>
                            </div>
                            <div class="col-md-6 mb-3 ">
                                <label class="form-label">Picture (optional)</label>
                                <input spellcheck="false" type="file" name="picture" accept=".gif, .png , .jpeg , .jpg , .webp" class="shadow-none form-control">
                            </div>
                            <div class="col-md-12 mb-3 ">
                                <label class="form-label">Address<span class="text-danger">*</span></label>
                                <textarea spellcheck="false" style="resize: none;" name="address" class="shadow-none form-control" rows="5"></textarea>
                                <span id="address_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;font-size: 0.8em;"></span>
                            </div>
                            <div class="col-md-6 mb-3 ">
                                <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                <input spellcheck="false" type="number" name="pincode" class="shadow-none form-control">
                                <span id="pincode_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;font-size: 0.8em;"></span>
                            </div>
                            <div class="col-md-6 mb-3 ">
                                <label class="form-label">Date of Birth<span class="text-danger">*</span></label>
                                <input spellcheck="false" type="date" name="date-of-birth" class="shadow-none form-control">
                                <span id="date-of-birth_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;font-size: 0.8em;"></span>
                            </div>
                            <div class="col-md-6 mb-3 ">
                                <label class="form-label">Password<span class="text-danger">*</span></label>
                                <input spellcheck="false" type="password" name="password" class="shadow-none form-control">
                                <span id="pass_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;font-size: 0.8em;"></span>
                            </div>
                            <div class="col-md-6 mb-3 ">
                                <label class="form-label">Confirm Password<span class="text-danger">*</span></label>
                                <input spellcheck="false" type="password" name="c-password" class="shadow-none form-control">
                                <span id="cpass_error" class="text-danger px-3 rounded mt-2 d-none" style="background-color: #f8d7da;font-size: 0.8em;"></span>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="shadow-none btn btn-dark my-1">Registration</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- registration modal end -->

<!-- forgot password modal -->
<div class="modal fade" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form novalidate autocomplete="off" id="forgot_form">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-question-circle-fill me-3"></i> Forgot Password
                    </h5>
                    <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <span class="badge bg-light text-wrap lh-base text-dark mb-3">
                            Note : A link will be sent to your email to reset your password.
                        </span>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input spellcheck="false" type="text" name="email" class="shadow-none form-control">
                        <span id="forg_email_error" class="text-danger px-3 rounded mt-2 d-none" style="font-size: 0.8em;background-color: #f8d7da;"></span>
                    </div>
                    <div class="mb-2 text-end">
                        <button type="button" class="me-2 btn text-secondary shadow-none" data-bs-toggle="modal" data-bs-target="#loginmodal" data-bs-dismiss="modal">
                            CANCEL
                        </button>
                        <button type="submit" class="shadow-none btn btn-dark">SEND MAIL</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- forgot password modal end -->