<?php

header('Content-Type: application/json');
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD']=='GET') {
$limit=null;
if (isset($_GET['limit'])) {
  $limit=$_GET['limit'];
}
getRecentlyAddedProducts($limit);


}

function getRecentlyAddedProducts($limit){
  global $dbconnect;
  $querySql="select * from products order by date desc ";
  if (isset($limit)) {
    $querySql.="limit $limit";
  }else {
    $querySql.="limit 10";
  }
  $query=$dbconnect->query($querySql);
  if ($query->num_rows>0) {
    $result=array();
    while ($row=$query->fetch_assoc()) {
      $result[]=$row;
    }
    echo json_encode($result);
  }
  else {
    $result= array();
    echo json_encode(array('result' => $result ));
  }
}

 ?>
