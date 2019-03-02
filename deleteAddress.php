<?php

	session_start();
	require_once('dbconnect.php');
	$GLOBALS['response'] = array();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['id'])){
			$addressId = $_POST['id'];
			if(addressIsValid($addressId)){
			deleteAddress($addressId);
		}

		// this address is not valid
		else{
			$GLOBALS['response']['error'] = true;
        	$GLOBALS['response']['message'] = "Invalid Address";
		}
		}

		// if id dosen't sent
		else{
			$GLOBALS['response']['error'] = true;
        	$GLOBALS['response']['message'] = "missing parameters";
		}
	}


	// post method isn't used
	else {
		$GLOBALS['response']['error'] = true;
        $GLOBALS['response']['message'] = "Not allowed";
	}



	function addressIsValid($addressId){
		global $dbconnect;
		$querySql="SELECT id 
					FROM address 
					WHERE address.id = $addressId";
		return (($dbconnect->query($querySql))->num_rows > 0);			
	}

	function deleteAddress($addressId){
		global $dbconnect;
		$querySql="DELETE 
					FROM address 
					WHERE address.id = $addressId";

   		if($dbconnect->query($querySql)){
   			$GLOBALS['response']['error'] = false;
        	$GLOBALS['response']['message'] = "this address is successfully deleted";

   		}

   		// if result is empty
   		else{
   			$GLOBALS['response']['error'] = true;
        	$GLOBALS['response']['message'] = "something went wrong please try again";
   		}
	}

	echo json_encode($GLOBALS['response']);
?>