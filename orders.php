<?php
header('Content-Type: application/json');
require_once('dbconnect.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
  if (isset($_POST['orderItems'],$_POST['quantity'],$_POST['userId'],$_POST['address_id'])) {
    $orderItems=$_POST['orderItems'];
    $quantity=$_POST['quantity'];
    $userId=$_POST['userId'];
    $addressId=$_POST['address_id'];
    makeOrder($orderItems,$quantity,$userId,$addressId);
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
<<<<<<< HEAD
=======
  $result = array();
>>>>>>> 5b507505176be69e609e39c98ad201443917ea2c
  if ($query->num_rows>0) {
    while ($row=$query->fetch_assoc()) {
      $temp['order_id']=$row['id'];
      $temp['order_date']=$row['date'];
      $temp['order_userId']=$row['userId'];
      $temp['order_status']=$row['status'];
      $temp['address_id']=$row['address_id'];

      // get addresses' details
      $addressQuery = "SELECT * FROM address WHERE address.user_id = $userId";
      $addresses = $dbconnect->query($addressQuery); 
      if($addresses->num_rows > 0){
<<<<<<< HEAD
      	while ($address=$addresses->fetch_assoc()){
      		$temp['address']= $address;
      	}
=======
        while ($address=$addresses->fetch_assoc()){
          $temp['address']= $address;
        }
>>>>>>> 5b507505176be69e609e39c98ad201443917ea2c
      }

      // if no addresses
      else{
<<<<<<< HEAD
      	$temp['address'] = "";
=======
        $temp['address'] = "";
>>>>>>> 5b507505176be69e609e39c98ad201443917ea2c
      }


      // get items' details
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
    
<<<<<<< HEAD
  }
}

echo json_encode($temp);
=======
    array_push($result,$temp);
    
  }
}

echo json_encode($result);
>>>>>>> 5b507505176be69e609e39c98ad201443917ea2c

}


function makeOrder($orderItems,$quantity,$userId,$addressId)
{
  global $dbconnect;
  $items=explode(",",$orderItems);
  $q=explode(",",$quantity);

if (isset($items)&& count($q)==count($items) ) {
  $orderSql="insert into orders (userId,status,address_id) values ('$userId','pending',$addressId)";
  if ($dbconnect->query($orderSql)) {
    $orderId=$dbconnect->insert_id;
    $response=array();
    $response['order_id']=$orderId;
    $response['order_userId']=$userId;
    $response['order_status']="pending";
    $response['address']=$addressId;
    for ($i=0; $i < count($items); $i++) {
      $insertSQL= "insert into order_item (product_id,quantity,order_id) values ('$items[$i]','$q[$i]','$orderId')";
      if ($dbconnect->query($insertSQL)) {
        $row['id']=$items[$i];
        $row['quantity']=$q[$i];
        $response['products'][]=$row;

      }
    }
    echo json_encode($response);
  }

  // error while inserting new order
  else{
<<<<<<< HEAD
  	$response['error'] = true;
  	$response['message'] =mysqli_error($dbconnect); 
=======
    $response['error'] = true;
    $response['message'] =mysqli_error($dbconnect); 
>>>>>>> 5b507505176be69e609e39c98ad201443917ea2c
  }
}

else {
  echo json_encode(array("error"=>"unsuccessful order "));
}

}

  ?>























