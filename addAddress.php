<?php

	session_start();
	require_once('dbconnect.php');
	$GLOBALS['response'] = array();


	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['state'],$_POST['city'],$_POST['address_1'],$_POST['address_2'],$_POST['zip_code'],$_POST['user_id'])){

			$state = $_POST['state'];
			$city = $_POST['city'];
			$address_1 = $_POST['address_1'];
			$address_2 = $_POST['address_2'];
			$zip_code = $_POST['zip_code'];
			$userId = $_POST['user_id'];

			addAddress($userId,$state,$city,$address_1,$address_2,$zip_code);
		}

		// missing params
		else{
			$GLOBALS['response']['error'] = true;
        	$GLOBALS['response']['message'] = "missing parameters";
		}
	}

	// post not used
	else{
		$GLOBALS['response']['error'] = true;
       	$GLOBALS['response']['message'] = "Not allowed";
	}




	// add address to database function
	function addAddress($userId,$state,$city,$address_1,$address_2,$zip_code){
		global $dbconnect;

		$addAddressQuery = "INSERT INTO address (`state`,`city`,`zip_code`,`address_1`,`address_2`) VALUES ('$state','$city',$zip_code,'$address_1','$address_2')";
		if($dbconnect->query($addAddressQuery)){
			$lastAddressId = $dbconnect->insert_id;
			$insertIntoUserAddressTable = "INSERT INTO users_address values ($userId,$lastAddressId) ";
			if($dbconnect->query($insertIntoUserAddressTable)){
				$GLOBALS['response']['error'] = false;
       			$GLOBALS['response']['message'] = "successfully inserted";
			}

			else{
				$GLOBALS['response']['error'] = true;
       			$GLOBALS['response']['message'] = "something went wrong while adding address,please try again ".mysqli_error($dbconnect);
			}

		
		}
		else{
			$GLOBALS['response']['error'] = true;
       		$GLOBALS['response']['message'] = "something went wrong while adding address,please try again ".mysqli_error($dbconnect);
		}
	
		
	}


	

	echo json_encode($GLOBALS['response']);







?>