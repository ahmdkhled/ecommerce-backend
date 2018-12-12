<?php
	header('Content-Type: application/json');
	session_start();
	require_once('dbconnect.php');


	if ($_SERVER['REQUEST_METHOD']=='POST') {
		if (isset($_POST['name'],$_POST['price']
		,$_POST['quantity'],$_POST['description']
		,$_POST['marketId'],$_POST['categoryId'])) {
		  $name=$_POST['name'];
		  $price=$_POST['price'];
		  $quantity=$_POST['quantity'];
		  $description=$_POST['description'];
		  $marketId=$_POST['marketId'];
		  $categoryId=$_POST['categoryId'];
		  $product=new Products;
		  $product->addProduct($name,$price,$quantity,$description,$marketId,$categoryId);
		}else {
		  echo "no parameters ";
		}
	}elseif($_SERVER['REQUEST_METHOD']=='GET'){
	  $product=new Products;
	  $querySql=$product->getQuerySql();
	  //echo $querySql;
	  $product->queryProducts($querySql);
	}




	function contains($statement,$word){
	  if (strpos($statement,$word)!==false) {
	    return true;
	  }
	  return false;
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
	    $result="there is no products";
	    echo json_encode(array('result' => $result ));
	  }
	}

	function getQuerySql(){
	  $querySql="SELECT *
				FROM products 
				INNER JOIN product_media
				ON products.id = product_media.productId
				 ";
	  $product=new Products;

		if (isset($_GET['id'])) {
			$id=$_GET['id'];
			$querySql.="where id in ($id)";
		}

	  if (isset($_GET['categoryId'])) {
	    $categoryId=$_GET['categoryId'];
			 if (contains($querySql,"where")) {
				 $querySql.="and categoryId = $categoryId ";
			 }else{
				 $querySql.="where categoryId = $categoryId ";
			 }
	}
	  if (isset($_GET['minPrice'])) {
	    $minPrice=$_GET['minPrice'];
	    if (contains($querySql,"where")) {
	      $querySql.="and price> $minPrice ";
	    }else {
	      $querySql.="where price >$minPrice ";
	    }
	  }
	  if (isset($_GET['maxPrice'])) {
	  $maxPrice=$_GET['maxPrice'];
	  if (contains($querySql,"where")) {
	    $querySql.="and price< $maxPrice > ";
	  }else {
	    $querySql.="where price >$maxPrice ";
	  }
	}

	if (isset($_GET['orderBy'])) {
	$orderBy=$_GET['orderBy'];
	  $querySql.="order by $orderBy ";
	}
	if (isset($_GET['order'])) {
	$order=$_GET['order'];

	if(contains($querySql,"order by")){
	  $querySql.="$order ";
	}else{
	  $querySql.="order by name $order ";
	}

	}
// 	if (isset($_GET['limit'])) {
// 	$limit=$_GET['limit'];
// 	  $querySql.="limit $limit ";
// 	}
// 	if (isset($_GET['page'])) {
// 	$page=$_GET['page'];
// 	if (isset($_GET['limit'])) {
// 	  $limit=$_GET['limit'];
// 	  $offset=$limit*($page-1);
// 	  $querySql.="offset $offset ";
// 	}else {
// 	  $limit=2;
// 	  $offset=$limit*($page-1);
// 	  $querySql.="limit $limit offset $offset ";
// 	}
// }else{
// 	$page=1;
// 	if (isset($_GET['limit'])) {
// 	  $limit=$_GET['limit'];
// 	  $offset=$limit*($page-1);
// 	  $querySql.="offset $offset ";
// 	}else {
// 	  $limit=2;
// 	  $offset=$limit*($page-1);
// 	  $querySql.="limit $limit offset $offset ";
// 	}
// }
	return $querySql;
	}



	}

 ?>
