<?php
  $jsonData = file_get_contents('php://input');
  $data = json_decode($jsonData, true);

  $conn = pg_connect("host=postgresql.r5.websupport.sk port=5432 dbname=gengi_web_db user=gengi password=Roland2022");
  if($conn) {
    $result = pg_query($conn, "SELECT * FROM codes where code = '".$data["code"]."'");
    if($result == false){
      echo 'somethings wrong brou';
    }
    else{
      if(pg_num_rows($result) == 0){
        echo 'wrong koud brout';
      }
      else{
        echo 'lesgo';
      }
    }
 } else {
     echo 'somethings wrong brou';
 }
?>