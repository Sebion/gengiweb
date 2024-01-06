<?php
  $jsonData = file_get_contents('php://input');
  $data = json_decode($jsonData, true);

  $conn = pg_connect("host=postgresql.r5.websupport.sk port=5432 dbname=gengi_web_db user=gengi password=Roland2022");
  if($conn) {
    $result = pg_query($conn, "SELECT expired FROM codes where code = '".$data["code"]."'");
    if($result == false){
      echo 'Somethings wrong with code check brou';
    }
    else{
      if(pg_num_rows($result) == 0){
        echo 'Seems like wrong code brou';
      }
      else{
        echo pg_fetch_assoc($result)['expired'];
      }
    }
 } else {
     echo 'Connection to DB failed brou';
 }
?>