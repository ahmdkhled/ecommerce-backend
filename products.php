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
	    	$temp = array();
	     	$temp['product_id']=$row['id'];
	      	$temp['product_name']=$row['name'];
	      	$temp['product_price']=$row['price'];
	      	$temp['product_quantity']=$row['quantity'];
	      	$temp['product_description']=$row['description'];
	      	$temp['marketId']=$row['marketId'];
					$temp['marketName']=$row['marketName'];
	      	$temp['product_categoryId']=$row['categoryId'];

	      	// get produc's images
	      	$imageQuery = "SELECT media_id,media_url
	      					FROM product_media
	      					WHERE
	      					product_media.productId =".$row['id']."";
	      	$images=$dbconnect->query($imageQuery);
	      	if($images->num_rows > 0){
	      	while($imageRow = $images->fetch_assoc()){
	      		$temp['media'][] =$images;
	      	}
	      }else $temp['media']=array();

	      	array_push($result, $temp);
	    }
	     echo json_encode($result);
	  }
	  else {
	    $result=array();
	    echo json_encode($result);
	  }
	}



	function getQuerySql(){
	  $querySql="SELECT products.*,markets.name marketName FROM products inner JOIN markets ON products.marketId = markets.id  ";

	  $product=new Products;

		if (isset($_GET['id'])) {
			$id=$_GET['id'];
			$querySql.="where products.id in ($id)";
		}

	  if (isset($_GET['categoryId'])) {
	    $categoryId=$_GET['categoryId'];
			 if (contains($querySql,"where")) {
				 $querySql.="and products.categoryId = $categoryId ";
			 }else{
				 $querySql.="where products.categoryId = $categoryId ";
			 }
	}
	  if (isset($_GET['minPrice'])) {
	    $minPrice=$_GET['minPrice'];
	    if (contains($querySql,"where")) {
	      $querySql.="and products.price> $minPrice ";
	    }else {
	      $querySql.="where products.price >$minPrice ";
	    }
	  }
	  if (isset($_GET['maxPrice'])) {
	  $maxPrice=$_GET['maxPrice'];
	  if (contains($querySql,"where")) {
	    $querySql.="and products.price< $maxPrice > ";
	  }else {
	    $querySql.="where products.price >$maxPrice ";
	  }
	}

	if (isset($_GET['orderBy'])) {
	$orderBy=$_GET['orderBy'];
	  $querySql.="order by products.$orderBy ";
	}
	if (isset($_GET['order'])) {
	$order=$_GET['order'];

	if(contains($querySql,"order by")){
	  $querySql.="$order ";
	}else{
	  $querySql.="order by name $order ";
	}

	}

	if (isset($_GET['page'])) {
	$page=$_GET['page'];
	if (isset($_GET['limit'])) {
	  $limit=$_GET['limit'];
	  $offset=$limit*($page-1);
	  $querySql.="offset $offset ";
	}else {
	  $limit=10;
	  $offset=$limit*($page-1);
	  $querySql.="limit $limit offset $offset ";
	}
}else{
	$page=1;
	if (isset($_GET['limit'])) {
	  $limit=$_GET['limit'];
	  $offset=$limit*($page-1);
	  $querySql.="offset $offset ";
	}else {
	  $limit=10;
	  $offset=$limit*($page-1);
	  $querySql.="limit $limit offset $offset ";
	}
}
	return $querySql;
	}



	}



 ?>
