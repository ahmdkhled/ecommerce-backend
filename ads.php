<?php
header('Content-Type: application/json');
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD']=='GET') {
  $querySql="select * from ads";
  getAds($querySql);
}

function getAds($querySql){
  global $dbconnect;
  $query=$dbconnect->query($querySql);
  if ($query->num_rows>0) {
    $result=array();
    while ($row=$query->fetch_assoc()) {
      $result[]=$row;
    }
    echo json_encode($result);
  }else{
    $result=array();
    echo json_encode(array('result' => $result ));
  }
}

 ?>
