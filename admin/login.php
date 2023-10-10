<?php
require("./inc/essentials.php");
isAdminAreadyLogin();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Login</title>
    <?php require("./inc/links.php"); ?>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            position: relative;
        }

        .admin-login-form {
            background: #00000013;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-login-form form {
            overflow: hidden;
            box-shadow: rgba(255, 255, 255, 0.1) 0px 1px 1px 0px inset, rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
            width: 450px;
            height: 350px;
            margin: 0 20px;
            background: white;
            border: 2px solid rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .admin-login-form form h2 {
            user-select: none;
            width: 100%;
            text-align: center;
            color: white;
            padding: 10px 0;
        }

        .admin-login-form form .input-fields {
            width: 80%;
        }

        .admin-login-form form .input {
            position: relative;
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .admin-login-form form .input:first-child {
            margin-bottom: 30px;
        }

        .admin-login-form form .input input {
            position: relative;
            border-radius: 4px;
            width: 100%;
            height: 40px;
            outline: none;
            border: none;
            padding: 0 10px;
            display: block;
            background: transparent;
        }

        .admin-login-form form .input div {
            pointer-events: none;
            user-select: none;
            color: #5f6368;
            position: absolute;
            top: 47%;
            left: 5px;
            padding: 0 5px;
            transform: translateY(-50%);
            transition: top 0.3s ease, font-size 0.3s ease;
            line-height: 10px;
        }

        .input input:focus~div,
        .input input:valid~div {
            background: white;
            top: -3%;
            font-size: 0.7em;
        }

        .admin-login-form form button {
            display: block;
            outline: none;
            border: none;
            width: 30%;
            height: 40px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 1.3em;
            color: white;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        button.left {
            transform: translateX(-100%) !important;
        }

        button.right {
            transform: translateX(100%) !important;
        }

        #alert {
            top: 15px !important;
        }
    </style>
</head>

<body>
    <div class="admin-login-form">
        <form novalidate method="POST" id="admin_login">
            <h2 class="bg-dark">Admin Login</h2>
            <div class="input-fields">
                <div class="input">
                    <input type="text" id="adminname" name="adminname" required>
                    <div>Username</div>
                </div>
                <div class="input">
                    <input type="password" id="adminpass" name="adminpass" required>
                    <div>Password</div>
                </div>
            </div>
            <button type="submit" class="custom-bg" id="submit-btn" name="submit-btn">LOGIN</button>
        </form>
        <div class="result"></div>
    </div>
    <div id="alert"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="./scripts/essentials.js"></script>
    <script src="./scripts/login_crud.js"></script>
</body>

</html>