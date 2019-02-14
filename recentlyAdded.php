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
  $querySql="select * from products ";
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
      // get produc's images
      $imageQuery = "SELECT media_id,media_url
              FROM product_media
              WHERE
              product_media.productId =".$row['id']."";
      $images=$dbconnect->query($imageQuery);
      unset($temp['media']);
      while($imageRow = $images->fetch_assoc()){
        $temp['media'][] = array(
              'image_id' => $imageRow['media_id'],
              'image_url' => $imageRow['media_url']
        );
      }
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
