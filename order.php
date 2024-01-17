<?php
  $jsonData = file_get_contents('php://input');
  $data = json_decode($jsonData, true);

  $conn = pg_connect("host=postgresql.r5.websupport.sk port=5432 dbname=gengi_web_db user=gengi password=Roland2022");
  if($conn) {
    $result = pg_query($conn, "INSERT INTO orders (firstname, lastname, email, phone, city, street, postal, code, product, size, productid) VALUES('".$data["firstName"]."', '".$data["lastName"]."', '".$data["email"]."', '".$data["phone"]."', '".$data["city"]."', '".$data["street"]."', '".$data["postal"]."', '".$data["code"]."', '".$data["product"]."', '".$data["size"]."', ".$data["item"].");
    select orderid from orders where firstname= '".$data["firstName"]."' and lastname = '".$data["lastName"]."' and email = '".$data["email"]."' and phone = '".$data["phone"]."' and code = '".$data["code"]."' and product = '".$data["product"]."' and size = '".$data["size"]."' and productid = ".$data["item"]);
    if($result == false){
      echo 'una problema in thera';
    }
    else{
      echo pg_fetch_result($result, 0, 0);
    }
 } else {
     echo 'somethings wrong brou';
 }
?>