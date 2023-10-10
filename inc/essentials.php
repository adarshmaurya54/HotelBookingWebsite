<?php
require("../admin/inc/essentials.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

function sendMail($senderEmail, $name, $subject, $message)
{


    // Website owner's email
    $ownerEmail = EMAIL_ID;


    // Create a new PHPMailer instance
    $mail = new PHPMailer();

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = MAILER_HOST; // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL_ID;
    $mail->Password = APP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable explicit TLS encryption
    $mail->Port = 587;

    // Email content
    $mail->setFrom($senderEmail, $name);
    $mail->addAddress($ownerEmail);

    $mail->addReplyTo($senderEmail, $name);
    $mail->isHTML(true);
    $mail->Subject = 'Contact Form Submission: ' . $subject;
    $mail->Body = "
            <!DOCTYPE html>
            <html>

            <head>
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Merienda:wght@300;400;500;700;900&family=Poppins&display=swap');
                    /* Global styles */
                    body {
                        font-family: 'Poppins', sans-serif;
                        background-color: #f0f0f0;
                        margin: 0;
                        padding: 0;
                    }
            
                    /* Container styles */
                    .container {
                        border-radius: 20px;
                        border: 1px solid rgba(0,0,0,0.3);
                        max-width: 600px;
                        margin: 5px auto;
                        padding: 20px;
                        background-color: white;
                        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                    }
            
                    /* Header styles */
                    .header {
                        font-size: 24px;
                        font-weight: bold;
                        margin-bottom: 10px;
                        padding-bottom: 10px;
                        border-bottom: 2px dashed white;
                    }
            
                    /* Section styles */
                    .section {
                        margin-top: 20px;
                    }
            
                    /* Label styles */
                    .label {
                        font-weight: bold;
                        margin-bottom: 5px;
                    }
            
                    /* Content styles */
                    .content {
                        margin-bottom: 15px;
                    }
            
                    /* Message styles */
                    .message, .content{
                        padding: 10px;
                        border: 1px solid #ccc;
                        color: black;
                        background-color: white;
                        border-radius: 10px;
                    }
                </style>
            </head>

            <body>
                <div class='container'>
                    <div class='header'>Email From: <span style='font-size: 0.8em!important;'>$senderEmail</span></div>

                    <div class='section'>
                        <div class='label'>Name:</div>
                        <div class='content'>$name</div>
                        <div class='label'>Subject:</div>
                        <div class='content'>$subject</div>
                        <div class='label'>Message:</div>
                        <div class='message'>$message</div>
                    </div>
                </div>
            </body>

            </html>
        ";

    // Send the email
    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}


function sendVarificationAndForgotMail($email, $name, $token, $type)
{
    if ($type == "verification") {
        $subject = "Email varification";
        $content = "
            <p class='heading'>from Hb Website</p>
            <div class='header'>Verify Your Email Address</div>
            <p>Click the button below to verify your email</p>
            <a class='cta-button' href='" . SITE_URL . "email_confirmation.php?email_confirmation&email=$email&token=$token'>Verify Email</a>
        ";
    } else {
        $subject = "Reset Password";
        $content = "
            <p class='heading'>from Hb Website</p>
            <p>Click the button below to reset your password</p>
            <a class='cta-button' href='" . SITE_URL . "reset_pass.php?email=$email&token=$token&forgot_pass='>Reset Password</a>
        ";
    }


    // Create a new PHPMailer instance
    $mail = new PHPMailer();

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = MAILER_HOST; // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL_ID;
    $mail->Password = APP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable explicit TLS encryption
    $mail->Port = 587;

    // Email content
    $mail->setFrom(EMAIL_ID, EMAIL_NAME);
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = "
                    
            <html>

            <head>
                <style>
                    /* Define your CSS styles here */
                    .container {
                        position: relative;
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 50px 20px;
                        text-align: center;
                        font-family: Arial, Helvetica, sans-serif;
                        border: 1px solid rgba(0,0,0,0.3);
                        border-radius: 10px;
                    }
                    .container p.heading{
                        font-size: 0.8em;
                        font-weight: bolder;
                        position: absolute;
                        top: -5px;
                        color: #0056b3;
                        left: 5px;
                        letter-spacing: 2px;
                        display: inline-block;
                    }
                    .header {
                        font-size: 24px;
                        font-weight: bold;
                        margin-bottom: 20px;
                        color: #333;
                    }

                    .cta-button {
                        display: inline-block;
                        padding: 15px 30px;
                        background-color: #007bff;
                        color: #fff!important;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                        transition: background-color 0.3s ease;
                    }

                    .cta-button:hover {
                        background-color: #0056b3;
                    }
                </style>
            </head>

            <body>
                <div class='container'>
                $content
                </div>
            </body>

            </html>
           ";

    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}
