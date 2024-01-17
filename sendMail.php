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
    $result = pg_query($conn, "UPDATE codes SET expired = true where code = '".$data["code"]."'; select products.price from orders join products on orders.productid = products.productid where code = '".$data["code"]."'");
    if($result == false){
    }
    else{
      $am = pg_fetch_result($result, 0, 0);
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
    $mail->Subject = 'Your GENGI Order';
    $mail->Body    = 'Wop Wop<br><br> 
    Prave si spravil najlepšiu objednávku tohto roku. Treba ju ešte zaplatiť aby sme mali začo si ďalej užívať.<br> 
    Spôsob platby je <b>prevod na účet</b>. Na nasledujúcom linku sa nachádzajú potrebné informácie k platbe (IBAN, Variabilny symbol, Suma) <br><br><br>
    https://payme.sk/?V=1&IBAN=SK8809000000005206752294&AM='.$am.'&CC=EUR&DT=20240118&PI=%2FVS'.$data["order"].'%2FSS'.$data["order"].'%2FKS'.$data["order"].'&MSG='.$data["code"].'&CN=GENGI+shop';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message sent aight';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}