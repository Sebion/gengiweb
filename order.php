<?php
  $jsonData = file_get_contents('php://input');
  $data = json_decode($jsonData, true);

  $conn = pg_connect("host=postgresql.r5.websupport.sk port=5432 dbname=gengi_web_db user=gengi password=Roland2022");
  if($conn) {
    $result = pg_query($conn, "INSERT INTO orders (firstname, lastname, email, phone, city, street, postal, code, product, size, itemid) VALUES('".$data["firstName"]."', '".$data["lastName"]."', '".$data["email"]."', '".$data["phone"]."', '".$data["city"]."', '".$data["street"]."', '".$data["postal"]."', '".$data["code"]."', '".$data["product"]."', '".$data["size"]."', ".$data["item"].")");
    if($result == false){
      echo 'una problema in thera';
    }
    else{
      echo 'all gooood order sent ouje';
    }
 } else {
     echo 'somethings wrong brou';
 }
?>