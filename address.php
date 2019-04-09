<?php


	header('Content-Type: application/json');
	require_once('dbconnect.php');
	$GLOBALS['response'] = array();
	$address = new Address;

	// if method is POST so user wanna add or update or delete address.
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		// add address.
		if(isset($_POST['first_name'],$_POST['last_name'],$_POST['phone_number'],$_POST['state'],$_POST['city'],$_POST['address_1'],$_POST['address_2'],$_POST['zip_code'],$_POST['user_id'])){

			$firstName = $_POST['first_name'];
			$lastName = $_POST['last_name'];
			$phoneNumber = $_POST['phone_number'];
			$state = $_POST['state'];
			$city = $_POST['city'];
			$address_1 = $_POST['address_1'];
			$address_2 = $_POST['address_2'];
			$zip_code = $_POST['zip_code'];
			$userId = $_POST['user_id'];

			$GLOBALS['response'] = $address->addAddress($firstName,$lastName,$phoneNumber,$state,$city,$address_1,$address_2,$zip_code,$userId);
						
		}

		// edit address.
		else if(isset($_POST['id'],$_POST['first_name'],$_POST['last_name'],$_POST['phone_number'],$_POST['state'],$_POST['city'],$_POST['address_1'],$_POST['address_2'],$_POST['zip_code'])){

			$id = $_POST['id'];
			$firstName = $_POST['first_name'];
			$lastName = $_POST['last_name'];
			$phoneNumber = $_POST['phone_number'];
			$state = $_POST['state'];
			$city = $_POST['city'];
			$address_1 = $_POST['address_1'];
			$address_2 = $_POST['address_2'];
			$zip_code = $_POST['zip_code'];

			$GLOBALS['response'] = $address->editAddress($id,$firstName,$lastName,$phoneNumber,$state,$city,$address_1,$address_2,$zip_code);
		}

		// set address as default
		else if(isset($_POST['address_id'],$_POST['user_id'])){
			$addressId = $_POST['address_id'];
			$userId = $_POST['user_id'];
			$GLOBALS['response'] = $address->setDefaultAddress($userId,$addressId);
		}

		// delete address.
		else if(isset($_POST['address_id'])){
			$GLOBALS['response'] = $address->deleteAddress($_POST['address_id']);
			
		}


		else{	
				
				$GLOBALS['response']['error'] = true;
        		$GLOBALS['response']['message'] = "missing parameters not add or update or delete";
		}
	}

	
	// else if method is GET so user wanna get his addresses.
	else if($_SERVER['REQUEST_METHOD'] == 'GET'){
		// get address
		if(isset($_GET['user_id'])){
			$GLOBALS['response'] = $address->getAddress($_GET['user_id']);
			
		}


		else{
			$GLOBALS['response']['error'] = true;
        	$GLOBALS['response']['message'] = "missing parameters";
		}
	}

	
	 echo json_encode($GLOBALS['response']);



	 class Address{

	 	function userHasAddress($userId){
			global $dbconnect;
			$useHasAddressQuery = "SELECT * FROM address WHERE address.user_id = $userId";
			return(($dbconnect->query($useHasAddressQuery))->num_rows > 0);
		}

		function addAddress($firstName,$lastName,$phoneNumber,$state,$city,$address_1,$address_2,$zip_code,$userId){

			global $dbconnect;
			
			$addAddressQuery = "INSERT INTO address (`first_name`,`last_name`,`phone_number`,`state`,`city`,`zip_code`,`address_1`,`address_2`,`user_id`) VALUES ('$firstName','$lastName',$phoneNumber,'$state','$city',$zip_code,'$address_1','$address_2',$userId)";
				

			if($dbconnect->query($addAddressQuery)){
				$lastAddressId = $dbconnect->insert_id;
				$response['error'] = false;
       			$response['message'] = "successfully inserted";
       			$response['address_id'] = $lastAddressId;
			}

			else{
				$response['error'] = true;
       			$response['message'] = "something went wrong while adding address,please try again ".mysqli_error($dbconnect);
			}
	
			// echo json_encode($response);
			return $response;
		}



		


		function getAddress($userId){
			$response = array();
			global $dbconnect;
			$querySql="SELECT *
						FROM address 
						WHERE address.user_id = $userId";

			if(isset($_GET['default'])){
				$querySql = $querySql." AND isDefault = 1";
			}			

	   		$addresses=$dbconnect->query($querySql);

	   		if($addresses->num_rows >0){
	   			while ($row = $addresses->fetch_assoc()) {
	   				$response[] = $row;
	   			}


	   		}

	   		// if result is empty
	   		else{
	   			$response = array();
	   		}


	   		 // echo json_encode($response);
			 return $response;		
		}

		function deleteAddress($addressId){
			$response = array();
			global $dbconnect;
			$querySql="DELETE 
						FROM address 
						WHERE address.id = $addressId";

	   		if($dbconnect->query($querySql)){
	   			$response['error'] = false;
	        	$response['message'] = "this address is successfully deleted";

	   		}

	   		// if result is empty
	   		else{
	   			$response['error'] = true;
	        	$response['message'] = "something went wrong please try again ".mysqli_error($dbconnect);
	   		}

	   		return $response;
		}

		function editAddress($id,$firstName,$lastName,$phoneNumber,$state,$city,$address_1,$address_2,$zip_code){
			global $dbconnect;
			$response = array();
			$query = "UPDATE address
						SET `first_name` = '$firstName' , `last_name` = '$lastName'
						 , `phone_number` = $phoneNumber , `state` = '$state' , `city` = '$city'
						 , `address_1` = '$address_1' , `address_2` = '$address_2' , `zip_code` = $zip_code
						 WHERE id = $id"; 
			
			if($dbconnect->query($query)){
				if(mysqli_affected_rows($dbconnect) > 0){
					$response['error'] = false;
	        		$response['message'] = "this address is successfully updated";
				}

				// invalid address id
				else{
					$response['error'] = true;
	        		$response['message'] = "invalid address id";
				}
	   			

	   		}

	   		// if there is something wrong
	   		else{
	   			$response['error'] = true;
	        	$response['message'] = "something went wrong please try again ".mysqli_error($dbconnect);
	   		}

	   		return $response;

		}	


		function setDefaultAddress($userId,$addressId){
			global $dbconnect;
			$response = array();

			// check if user has default address to mark it not default
			$query = "SELECT id FROM address WHERE user_id = $userId AND isDefault = 1";
			$result = $dbconnect->query($query);
			if($result->num_rows > 0){
				$previousDefaultAddressId = ($result->fetch_assoc())['id'];
				 $query = "UPDATE address
						SET `isDefault` = 0
						 WHERE id = $previousDefaultAddressId"; 
				$dbconnect->query($query);		 
			}

			// set new address as default
			$query = "UPDATE address
						SET `isDefault` = 1
						 WHERE id = $addressId";

			
			if($dbconnect->query($query)){
				if(mysqli_affected_rows($dbconnect) > 0){
					$response['error'] = false;
	        		$response['message'] = "this address is successfully set as default";
				}

				// invalid address id
				else{
					$response['error'] = true;
	        		$response['message'] = "invalid address id";
				}
	   			

	   		}

	   		// if there is something wrong
	   		else{
	   			$response['error'] = true;
	        	$response['message'] = "something went wrong please try again ".mysqli_error($dbconnect);
	   		}

	   		return $response;
		}

	}


?>