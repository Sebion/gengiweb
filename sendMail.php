<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

$conn = pg_connect("host=postgresql.r5.websupport.sk port=5432 dbname=gengi_web_db user=gengi password=Roland2022");
  if($conn) {
    $result = pg_query($conn, "UPDATE codes SET expired = true where code = '".$data["code"]."'");
    if($result == false){
    }
    else{
    }
  } else {
  }

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.m1.websupport.sk';                //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'orders@gengi.eu';                      //SMTP username
    $mail->Password   = 'Roland2022';                           //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('orders@gengi.eu');
    $mail->addAddress($data["email"]);                          //Add a recipient 
    $mail->addReplyTo('orders@gengi.eu');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is automatic message bitces ouje hreskovy smrdi cikula a markovy smrdza pazuchy a tukacovy smrdi z <b>papuly</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message sent aight';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}