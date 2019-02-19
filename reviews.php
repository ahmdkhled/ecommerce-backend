<?php
header('Content-Type: application/json');
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD']=='GET') {
  getReviews();
}

function getReviews(){
  $querySql="select product_reviews.* , users.name userName
  from product_reviews inner join users
  on product_reviews.userId = users.id ";
  if (isset($_GET['productId'])) {
    $productId=$_GET['productId'];
    $querySql.="where product_reviews.productId = $productId";
  }
//echo $querySql;
  global $dbconnect;
  $query=$dbconnect->query($querySql);

  if ($query->num_rows>0) {
    while ($row=$query->fetch_assoc()) {
      $result[]=$row;
    }
    echo json_encode($result);
  }else{
    $result=array();
    echo json_encode($result);
  }

}

 ?>
