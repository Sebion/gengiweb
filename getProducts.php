<?php
  // Allow requests from any origin
  header("Access-Control-Allow-Origin: *");
  // Allow the content type header
  header("Access-Control-Allow-Headers: Content-Type");
  $jsonData = file_get_contents('php://input');
  $data = json_decode($jsonData, true);

  $conn = pg_connect("host=postgresql.r5.websupport.sk port=5432 dbname=gengi_web_db user=gengi password=Roland2022");
  if($conn) {
    $result = pg_query($conn, "select products.productID, products.name, price, sizes, figs.url  from products join figs on products.productID = figs.productID");
    if($result == false){
      echo 'Somethings wrong with code check brou';
    }
    else{
      if(pg_num_rows($result) == 0){
        echo 'Seems like wrong code brou';
      }
      else{
        $myarray = array();
        while ($row = pg_fetch_row($result)) {
          $myarray[] = $row;
        }

        echo json_encode($myarray);
      }
    }
 } else {
     echo 'Connection to DB failed brou';
 }
?>