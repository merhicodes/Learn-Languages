<?php
// Load Composer's autoloader if using Composer
// require 'vendor/autoload.php';

// Or manually include the PHPMailer files
require 'PHPMailer/src/PHPMailer.php';
require_once 'function.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create a new PHPMailer instance
$mail = new PHPMailer(true);                                    // TCP port to connect to

// Recipients
if (
    isset($_POST['email']) && !empty($_POST['email'])
    && isset($_POST['message']) && !empty($_POST['message'])
) {
    try {
        // Server settings
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'languagestaleek@gmail.com';                    // SMTP username (your email)
        $mail->Password = 'taleekLanguagesAcount';                              // SMTP password (your email password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;
        $user_mail = valid_input($_POST['email']);
        $msg = valid_input($_POST['message']);
        $my_mail = 'mohamad.merhi.1@isae.edu.lb';
        $my_name = "Mohamad";

        $mail->setFrom($user_mail, "user1");               // Your email and name
        $mail->addAddress($my_mail, $my_name); // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "contact users";
        $mail->Body    = $msg;
        $mail->AltBody = $msg;

        if($mail->send()){
            echo "success";
        }else{
            echo "error";
        }
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
