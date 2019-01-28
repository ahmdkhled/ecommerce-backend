<?php

	session_start();
	require_once('dbconnect.php');
	$response = array();

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['id'])){
			getAddress($_POST['id'],$response);
		}

		// if id dosen't sent
		else{
			$response['error'] = true;
        	$response['message'] = "missing parameters";
        	echo json_encode($response);
		}
	}


	// post method isn't used
	else {
		$response['error'] = true;
        $response['message'] = "Not allowed";
        echo json_encode($response);
	}




	function getAddress($userId,$response){
		global $dbconnect;
		$querySql="SELECT address.address_1,address.address_2
					FROM address 
					INNER JOIN users_address
					ON users_address.address_id = address.id
					WHERE users_address.user_id = $userId";
   		$query=$dbconnect->query($querySql);

   		if($query->num_rows>0){
   			while ($row = $query->fetch_assoc()) {
   				$addresses['address'][]=array(
   									'address1' => $row['address_1'],			
   									'address2' => $row["address_2"]
   				);
   			}

   		 	echo json_encode($addresses);

   		}

   		// if result is empty
   		else{
   			$response['error'] = false;
        	$response['message'] = "this user hasn't addresses yet";
        	echo json_encode($response);
   		}
	}


	  
?>