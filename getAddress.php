<?php

	session_start();
	require_once('dbconnect.php');
	$GLOBALS['response'] = array();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['id'])){
			getAddress($_POST['id']);
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




	function getAddress($userId){
		global $dbconnect;
		$querySql="SELECT *
					FROM address 
					INNER JOIN users_address
					ON users_address.address_id = address.id
					WHERE users_address.user_id = $userId";
   		$query=$dbconnect->query($querySql);

   		if($query->num_rows>0){
   			while ($row = $query->fetch_assoc()) {

   				$GLOBALS['response'][]=array(
   									'id' => $row['id'],
   									'state' => $row['state'],
   									'city' => $row['city'],
   									'zip_code' => $row['zip_code'],
   									'address1' => $row['address_1'],			
   									'address2' => $row["address_2"]
   				);
   			}


   		}

   		// if result is empty
   		// else{
   		// 	$GLOBALS['response']['error'] = false;
     //    	$GLOBALS['response']['message'] = "this user hasn't addresses yet";
   		// }
	}

	echo json_encode($GLOBALS['response']);
	  
?>