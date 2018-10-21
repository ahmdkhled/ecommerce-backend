<?php
header('Content-Type: application/json');
session_start();
require_once('dbconnect.php');


if ($_SERVER['REQUEST_METHOD']=='POST') {
if (isset($_POST['name'],$_POST['price']
,$_POST['quantity'],$_POST['description'])) {
  $name=$_POST['name'];
  $price=$_POST['price'];
  $quantity=$_POST['quantity'];
  $description=$_POST['description'];
  addProduct($name,$price,$quantity,$description,1,1);
}else {
  echo "no parameters ";
}
}elseif($_SERVER['REQUEST_METHOD']=='GET'){
  getProducts();
}

function addProduct($name,$price,$quantity,$description,$marketId,$categoryId)
{
  global $dbconnect;
  $insertSql="insert into products (name,price,quantity,description,marketId,categoryId) values"
  ."('$name','$price','$quantity','$description','$marketId','$categoryId')";

  if($dbconnect->query($insertSql) ) {
    echo "inserted successfully";
  }else {
    echo "problem inserting ";
  }
}

function getProducts(){
  global $dbconnect;
  $querySql="select * from products";
  $query=$dbconnect->query($querySql);
  if ($query->num_rows>0) {
    $result=array();
    while ($row=$query->fetch_assoc()) {
      $result[]=$row;
    }
    echo json_encode($result);
  }
  else {
    $result="there is no products";
    echo json_encode(array('result' => $result ));
  }
}


 ?>
