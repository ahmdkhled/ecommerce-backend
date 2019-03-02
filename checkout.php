<?php
	
	require_once('dbconnect.php');
	$GLOBALS['response'] = array();

	if ($_SERVER['REQUEST_METHOD']=='GET'){
		if (isset($_GET['user_id'])){
			$userId = $_GET['user_id'];
			getInfo($userId);
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

	function getInfo($userId){
		global $dbconnect;
		$userQuery="SELECT name,email
					FROM users 
					WHERE users.id = $userId";
		$query=$dbconnect->query($userQuery);
	  	if ($query->num_rows>0) {
	    	while ($row=$query->fetch_assoc()) {
	      		$GLOBALS['response']['name']=$row['name'];
	      		$GLOBALS['response']['email']=$row['email'];
	      	}	
	      		
	      	// get user's addresses
	      	$addressQuery = "SELECT * 
	      					FROM address
	      					WHERE address.user_id = $userId";
	      						
	      	$addresses=$dbconnect->query($addressQuery);
	      	if($addresses->num_rows > 0){
	      		while($addressRow = $addresses->fetch_assoc()){
	      			$GLOBALS['response']['addresses'][] = array(
	      						'id' => $addressRow['id'],
	      						'state' => $addressRow['state'],
	      						'city' => $addressRow['city'],
	      						'zip_code' => $addressRow['zip_code'],
	      						'address_1' => $addressRow['address_1'],
	      						'address_2' => $addressRow['address_2']
	      			);	
	      		}
	      	}
	      	// user has no addresses
	      	else{
	      		$GLOBALS['response']['addresses']= array();
	      }
	      	
	   	}
	   	 // invalid user's id
	  	else{
	  		$GLOBALS['response']['error'] = false;
        	$GLOBALS['response']['message'] = "invalid user id";
    	}
	}

	echo json_encode($GLOBALS['response']);

?>