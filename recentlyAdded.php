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
  $querySql="select * from products inner join product_media
   on products.id = product_media.productId
   order by date desc ";
  if (isset($limit)) {
    $querySql.="limit $limit";
  }else {
    $querySql.="limit 10";
  }
  $query=$dbconnect->query($querySql);
  if ($query->num_rows>0) {
    $result=array();
    while ($row=$query->fetch_assoc()) {
      $temp['product_id']=$row['id'];
      $temp['product_name']=$row['name'];
      $temp['product_price']=$row['price'];
      $temp['product_quantity']=$row['quantity'];
      $temp['product_description']=$row['description'];
      $temp['product_marketId']=$row['marketId'];
      $temp['product_categoryId']=$row['categoryId'];
      $temp['media'][] = array(
              'image_id' => $row['media_id'],
              'image_url' => $row['media_url']
            );
      array_push($result, $temp);

    }
    echo json_encode($result);
  }
  else {
    $result= array();
    echo json_encode(array('result' => $result ));
  }
}

 ?>
