<?php

require_once('dbconnect.php');
session_start();

//echo $_SESSION['userId'];


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

 ?>
