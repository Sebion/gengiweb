<?php
  // Allow requests from any origin
  header("Access-Control-Allow-Origin: *");
  // Allow the content type header
  header("Access-Control-Allow-Headers: Content-Type");
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
    $mail->setFrom('orders@gengi.eu', 'GENGI');
    $mail->addAddress($data["email"]);  
    $mail->AddCC('gengibrand@gmail.com');                        //Add a recipient 
    $mail->addReplyTo('orders@gengi.eu');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    $datestr = date('y-m-d');
    $date = "20".str_replace("-","",$datestr);

    $color = "'#0066cc'";
    $color2 = "'#add8e6'";
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Your GENGI Order';
    $mail->AddEmbeddedImage('fig/gengifinal.gif', 'logo_2u');
    $mail->Body    = '
    <body>
    
    <div style="text-align:center">
    <p style = "font-family:Tahoma">Wop Wop<br><br> 
Prave si spravil najlepšiu objednávku tohto roku! Ale tvoj produkt od nás poputuje až po vykonaní platby ( budu fettzz ).<br>
Spôsob platby je <b>prevod na účet</b>. Kliknutím na tlačidlo sa dostaneš k potrebným informáciam k platbe (IBAN, Variabilný symbol, Suma) <br><br><br>
<a href="https://payme.sk/?V=1&IBAN=SK8809000000005206752294&AM='.$am.'&CC=EUR&DT='.$date.'&PI=%2FVS'.$data["order"].'%2FSS'.$data["order"].'%2FKS'.$data["order"].'&MSG='.$data["code"].'&CN=GENGI+shop" style="display: inline-block; padding: 10px 20px; font-size: 16px; text-align: center; text-decoration: none; cursor: pointer; border-radius: 10px; background-color: rgb(64, 73, 255); color: #fff; border: none; outline: none; transition: background-color 0.3s;">Pokračuj k platbe</a><br><br><br>
Svoj gengi balíček očakávaj v priebehu 3 dní od zaplatenia na svojej pošte!<br><br>
V prípade akýchkoľvek informácií nás neváhajte kontaktovať spatným emailom.<br><br>
Ak ti nefunguje tlačidlo na platbu, tu sú údaje ktoré trebá zadať pri platbe:<br>
IBAN: SK8809000000005206752294<br>
Suma: '.$am.'€ <br>
Variabilný symbol: '.$data["order"].'<br>
Správa pre prijímateľa: '.$data["code"].'<br>


Pozdravuje, Tím
</p>
<br>

<img alt="vazap" src="cid:logo_2u">
</div> 
    
    </body>
    </html>
    ';
    $mail->AltBody = 'Wop Wop. Prave si spravil najlepšiu objednávku tohto roku. Treba ju ešte zaplatiť aby sme mali začo si ďalej užívať. 
    Spôsob platby je prevod na účet. Na nasledujúcom linku sa nachádzajú potrebné informácie k platbe (IBAN, Variabilný symbol, Suma). 
    https://payme.sk/?V=1&IBAN=SK8809000000005206752294&AM='.$am.'&CC=EUR&DT='.$date.'&PI=%2FVS'.$data["order"].'%2FSS'.$data["order"].'%2FKS'.$data["order"].'&MSG='.$data["code"].'&CN=GENGI+shop . 
    Svoj gengi balíček očakávaj v priebehu 3 dní od zaplatenia na svojej pošte!';

    $mail->send();
    echo 'Message sent aight';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}