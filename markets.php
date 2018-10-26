<?php
header('Content-Type: application/json');
require_once('dbconnect.php');

if($_SERVER['REQUEST_METHOD']=="GET"){
  getMarkets();
}


function getMarkets(){
  $querySql="select * from markets ";
  if (isset($_GET['id'])) {
    $id=$_GET['id'];
    $querySql.="where id =$id ";
  } if (isset($_GET['limit'])) {
      $limit=$_GET['limit'];
      $querySql.="limit $limit ";
    }if (isset($_GET['page'])) {
      if (isset($_GET['limit'])) {
          $limit=$_GET['limit'];
        }else{
          $limit=2;
        }
        $page=$_GET['page'];
        $offset=$limit*(page-1);
        $querySql.="limit $limit offset $offset ";
      }

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
        $result="there is no markets";
        echo json_encode(array('result' => $result ));
      }
}
 ?>
