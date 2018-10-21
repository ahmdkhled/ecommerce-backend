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
  $product=new Products;
  $product->addProduct($name,$price,$quantity,$description,1,1);
}else {
  echo "no parameters ";
}
}elseif($_SERVER['REQUEST_METHOD']=='GET'){
  $product=new Products;
  if (isset($_GET['limit'])) {
    $limit=$_GET['limit'];
    $product->getProductsWithLimit($limit);
  }else {
    $product->getProducts();
  }
}

class Products {

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

function queryProducts($querySql){
  global $dbconnect;
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

function getProducts(){
  $querySql="select * from products";
  $this->queryProducts($querySql);
}
function getProductsWithLimit($limit){
  $querySql="select * from products limit $limit";
  $this->queryProducts($querySql);
}



}

 ?>
