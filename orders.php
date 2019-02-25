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
} elseif ($_SERVER['REQUEST_METHOD']=='GET') {
    if (isset($_GET['userId'])) {
      $userId=$_GET['userId'];
      getOrders($userId);
    }

}

function getOrders($userId){
  global $dbconnect;
  $querySql="select * from orders where orders.userId =$userId";
  $query=$dbconnect->query($querySql);
  $result=array();
  if ($query->num_rows>0) {
    while ($row=$query->fetch_assoc()) {
      $temp['order_id']=$row['id'];
      $temp['order_date']=$row['date'];
      $temp['order_userId']=$row['userId'];
      $temp['order_status']=$row['status'];
      $itemsSql="select products.*,order_item.id orderItem_id,
      order_item.quantity orderItem_quantity
       from order_item inner join products
        on order_item.product_id=products.id
        where order_item.order_id =".$row['id'];
        //echo $itemsSql;
      $itemsQuery=$dbconnect->query($itemsSql);
      $order_total=0;
      $temp['order_total']=0;
      if ($itemsQuery->num_rows>0) {
        while ($item=$itemsQuery->fetch_assoc()) {
          $temp['orderItems'][]=$item;

          $order_total +=$item['orderItem_quantity']*$item['price'];
        }
    }else{
      $temp['orderItems']=array();
    }
    $temp['order_total']=$order_total;
    array_push($result,$temp);
  }
}
echo json_encode($result);

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
