<?php
header('Content-Type: application/json');
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD']=='GET') {
  if (isset($_GET['userId'])) {
    $userId=$_GET['userId'];

     getFavorites($userId);
  }

}elseif ($_SERVER['REQUEST_METHOD']=='DELETE') {
  parse_str(file_get_contents('php://input'), $_DELETE);
  if (isset($_DELETE['userId'],$_DELETE['productId'])) {
    $productId=$_DELETE['productId'];
    $userId=$_DELETE['userId'];
     deleteProduct($productId,$userId);
  }else{
    http_response_code(404);

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
      $temp = array();
      $temp['product_id']=$row['id'];
      $temp['product_name']=$row['name'];
      $temp['product_price']=$row['price'];
      $temp['product_quantity']=$row['quantity'];
      $temp['product_description']=$row['description'];
      $temp['product_marketId']=$row['marketId'];
      $temp['product_categoryId']=$row['categoryId'];

      $imagesQuery="select * from product_media where product_media.productId =".$row['id'] ;
      $images=$dbconnect->query($imagesQuery);
      while($imageRow = $images->fetch_assoc()){
        $temp['media'][] = array(
              'image_id' => $imageRow['media_id'],
              'image_url' => $imageRow['media_url']
        );
      }
      array_push($result, $temp);
    }
  }
  echo json_encode($result);

}

function deleteProduct($productId,$userId){
  global $dbconnect;
  $deleteSql="DELETE from favorite_products where userId=$userId and product_id=$productId";
  if ($dbconnect->query($deleteSql)==true) {
    http_response_code(200);
  }else{
    http_response_code(402);

  }


}

?>
