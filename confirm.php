<?php

	require_once('dbconnect.php');
	$response = array();

	if($_SERVER['REQUEST_METHOD']==='GET'){
		if(isset($_GET['email'],$_GET['token'])){
			$email = $_GET['email'];
			$token = $_GET['token'];
			isAvailableToken($email,$token);
		}

		else{

			$response['error'] = true;
			$response['message'] = 'missing paramaters';
		}
	}



	function isAvailableToken($email,$token){
		global $dbconnect,$response;
    
    	$confirmQuery="select token from user where email='$email' AND status=0";
    	// case of successfully inserted
    	$result = $dbconnect->query($confirmQuery);
     	if($result->num_rows>0) {
         	while ($row = $result->fetch_assoc()) {
    			if($row['token'] === $token){
    				updateUserStatus($email,$token);
    			}

    			else {
    				$response['error'] = true;
					$response['message'] = 'tokens are not match';
    			}

         	}

         }

         else{

         	$response['error'] = false;
			$response['message'] = 'this email has already verified';
         }
	}

	function updateUserStatus($email,$token){
		global $dbconnect,$response;

		$updateStatusQuery  = "update user set status = 1 where 
							email = '$email' AND token = '$token' AND status=0";

		if($dbconnect->query($updateStatusQuery)){

			$response['error'] = false;
			$response['message'] = 'status successfully updated';
			


		}else {
			$response['error'] = true;
			$response['message'] = 'something went wrong please try again :)';

		}					

	}


	echo json_encode($response);

