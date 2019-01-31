<?php
header('Content-Type: application/json');
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD']=='GET') {
  if (isset($_GET['userId'])) {
    $userId=$_GET['userId'];

     getFavorites($userId);
  }

}

function getFavorites($userId){
  $querySql="SELECT * FROM favorite_products
  inner join products on favorite_products.product_id =products.id
   where favorite_products.userId =$userId";
  global $dbconnect;
  $query=$dbconnect->query($querySql);
  $result=array();
  if ($query->num_rows>0) {
    while ($row=$query->fetch_assoc()) {
      $result[]=$row;
    }
  }
  echo json_encode($result);

}

?>
