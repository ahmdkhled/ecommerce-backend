
<?php
  require_once('dbconnect.php');
  session_start();
  $response = array();
  


  if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (isset($_POST['email'],$_POST['password'])) {
      $email=$_POST['email'];
      $password=$_POST['password'];
      login($email,$password);
    }
    else {
      
       $response['error'] = true;
       $response['message'] = "some required feilds are missing";

    }
  }else {
    
       $response['error'] = true;
       $response['message'] = "You are not allowed to access this page :)";
  }


  function login($email,$password){
    
     global $dbconnect,$response;
      $querySql="select * from user where email = '$email' and password ='$password'";
      $result=$dbconnect->query($querySql);
      if ($result->num_rows>0) {
        
         $response['error'] = false;
         $response['message'] = "successfully login";
        
      }else {
         
         $response['error'] = true;
         $response['message'] = "email or password is wrong please try again";

      }
  }



echo json_encode($response);

 
