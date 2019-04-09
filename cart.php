<?php 
header('Content-Type: application/json');
require_once('dbconnect.php');


if($_SERVER['REQUEST_METHOD']=='GET'){
	  if (isset($_GET['ids'],$_GET['q'])) {
	  	$ids=$_GET['ids'];
	  	$quantity=$_GET['q'];
	  	$q=explode(",",$quantity);
	  	queryProducts(getQuerySql($ids),$ids,$q);
	  }
	
	}
 

function queryProducts($querySql,$ids,$q){
	  global $dbconnect;
	  $response;
	  $totalSql="SELECT  products.price FROM products where products.id in ($ids)";
 	  $totalQuery=$dbconnect->query($totalSql);
	  if ($totalQuery->num_rows>0) {
	        $total=0;
	        $i=0;
	      while ($r=$totalQuery->fetch_assoc()) {
	        $total+=$r['price'] * $q[$i];
	      	$i++;
	      }
	      $response['total']=$total;

	  }
	  
	  
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
	      	while($imageRow = $images->fetch_assoc()){
	      		$temp['media'][] = array(
	      					'image_id' => $imageRow['media_id'],
	      					'image_url' => $imageRow['media_url']
	      		);
	      	}
	      	array_push($result, $temp);
	    }
        $response['products']=$result;	    
	     echo json_encode($response);
	  }
	  else {
	    $result=array();
	    $response['total']=0;
	    $response['products']=$result;
	    echo json_encode($response);
	  }
	}

 function getQuerySql($ids){
 	$querySql="SELECT  products.*,markets.name marketName FROM products inner JOIN markets ON products.marketId = markets.id ";
 	$querySql.="where products.id in ($ids)"; 

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

 ?>