<?php

	header('Content-Type: application/json');
	require_once('dbconnect.php');
	session_start();
  	$response = array();

  	if ($_SERVER['REQUEST_METHOD']==='POST'){
  		if(isset($_POST['user_name'],$_POST['market_name'],$_POST['email'],$_POST['password'])){	
	  		$marketName = $_POST['market_name'];
	  		$userName = $_POST['user_name'];    
	      	$email = $_POST['email'];
	      	$password = $_POST['password'];
	      	$userId = isUser($email); 
	      	if($userId != -1){
	      		if(!isMArket($userId)){
	      			// create market
	      			if(createMarket($marketName,$userId)){
	      				$response['error'] = false;
  						$response['message'] = "SUCCESSFULLY CREATED NEW MARKET";
	      			}

	      			else{
	      				$response['error'] = true;
  						$response['message'] = "SOMTHING WENT WRONG PLEASE TRY LATER";
	      			}
	      		}

	      		// user has already market
	      		else{
	      			$response['error'] = false;
  					$response['message'] = "THIS USER HAS ALREADY MARKET";
	      		}
	      }

	      // neither user nor market
	      else{
	      	$lastInsertId = createUser($userName,$email,$password);
	      	if($lastInsertId != -1){
	      		if(createMarket($marketName,$lastInsertId)){
	      				$response['error'] = false;
  						$response['message'] = "SUCCESSFULLY CREATED NEW MARKET";
	      			}

	      			else{
	      				$response['error'] = true;
  						$response['message'] = "SOMTHING WENT WRONG WHILE CREATING MARKET PLEASE TRY LATER";
	      			}
	      	}

	      	else{
	      		$response['error'] = true;
  				$response['message'] = "SOMTHING WENT WRONG WHILE CREATING USER PLEASE TRY LATER";
	      	}
	      	
	      }
	  }

	  // missing params
	  else{
	  	$response['error'] = true;
  		$response['message'] = "MISSING PARAMS";
	  }
  	}

  	else{
  		$response['error'] = true;
  		$response['message'] = "NOT ALLOWED";
  	}

  	echo json_encode($response);


  	function isUser($email){
	    global $dbconnect;
	    $id = -1;
	    $querySql="select * from users where email='$email'";
	    $result=$dbconnect->query($querySql);
	    if($result->num_rows > 0){
	    	$id = ($result->fetch_assoc())['id'];
	    }
	    return $id;
	    

  	}


  	function isMArket($userId){
  		global $dbconnect;
	    $querySql="select * from markets where userId=$userId";
	    $result=$dbconnect->query($querySql);
	    return ($result->num_rows > 0);
  	}

  	function createMarket($marketName,$userId){
  		global $dbconnect;
		$insertSql="INSERT INTO markets (name,userId) VALUES ('$marketName','$userId')";
		return ($dbconnect->query($insertSql));
	}

	function createUser($name,$email,$password){
		global $dbconnect;
		$insertedId = -1;

	    // encrypt user password
	    // $password = password_hash($password,PASSWORD_BCRYPT);

	    // create random token with 32 character
	    $token = md5(rand(0,1000));

	    $insertSql="insert into users (name,email,password,status,token)
	    values ('$name','$email','$password','0','$token');";
	    if($dbconnect->query($insertSql)){
	    	$insertedId =  $dbconnect->insert_id;
	    }

	    return $insertedId;
	}

?>  	