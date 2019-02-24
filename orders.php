<?php
header('Content-Type: application/json');
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
  if (isset($_POST['orderItems'],$_POST['quantity'],$_POST['userId'])) {
    $orderItems=$_POST['orderItems'];
    $quantity=$_POST['quantity'];
    $userId=$_POST['userId'];
    makeOrder($orderItems,$quantity,$userId);
  }
}


function makeOrder($orderItems,$quantity,$userId)
{
  global $dbconnect;
  $items=explode(",",$orderItems);
  $q=explode(",",$quantity);

if (isset($items)&& count($q)==count($items) ) {
  $orderSql="insert into orders (userId) values ('$userId')";
  if ($dbconnect->query($orderSql)) {
    $orderId=$dbconnect->insert_id;
    $response=array();
    $response['orderId']=$orderId;
    $response['userId']=$userId;
    for ($i=0; $i < count($items); $i++) {
      $insertSQL= "insert into order_item (product_id,quantity,order_id) values ('$items[$i]','$q[$i]','$orderId')";
      if ($dbconnect->query($insertSQL)) {
        $row['productId']=$items[$i];
        $row['quantity']=$q[$i];
        $response['products'][]=$row;

      }
    }
    echo json_encode($response);
  }
}else {
  echo json_encode(array("error"=>"unsuccessful order "));
}

}

  ?>
