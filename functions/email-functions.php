<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

function sendEmail($toemail, $subject, $body){
    
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    //Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
    
    $mail->Port = 587;
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "northumbriatimesheets@gmail.com";
    //Password to use for SMTP authentication
    $mail->Password = "testtest1!";
    //Set who the message is to be sent from
    $mail->setFrom('northumbriatimesheets@gmail.com', 'Northmbria Timesheets');
    //Set an alternative reply-to address
    $mail->addReplyTo('northumbriatimesheets@gmail.com', 'Northmbria Timesheets');
    //Set who the message is to be sent to
    $mail->addAddress($toemail);
    //Set the subject line
    $mail->Subject = $subject;
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->Body = $body;
    //Replace the plain text body with one created manually
    
    
    //send the message, check for errors
    if (!$mail->send()) {
        echo "<p>Mailer Error: " . $mail->ErrorInfo ."</p>";
        
    } else {
        echo "<p>Message sent.</p>";
        
    }
    
}