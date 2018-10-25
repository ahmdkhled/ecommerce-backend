<?php
header('Content-Type: application/json');
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD']=='GET') {
  $querySql="select * from categories ";
  if (isset($_GET['id'])) {
    $id=$_GET['id'];
    $querySql.="where id =$id";
  }
  getCategories($querySql);
}

function getCategories($querySql){
  global $dbconnect;
  $query=$dbconnect->query($querySql);
  if ($query->num_rows>0) {
    $result=array();
    while ($row=$query->fetch_assoc()) {
      $result[]=$row;
    }
    echo json_encode($result);
  }else{
    $result="there is no categories";
    echo json_encode(array('result' => $result ));
  }
}

 ?>
