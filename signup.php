<?php
  require_once('dbconnect.php');
  session_start();
  $response = array();

  if ($_SERVER['REQUEST_METHOD']==='POST') {

    if(isset($_POST['email'],$_POST['password'])){

      $email = $_POST['email'];
      $password = $_POST['password'];
      if(!isAvailable($email)){

        signUp($email,$password);

      }else{

        $response['error'] = true;
        $response['message'] = "this email is already used by another user";
      }


    }else{
      $response['error'] = true;
      $response['message'] = "some required feilds are missing";    
    }

  
  }else {
     $response['error'] = true;
     $response['message'] = "You are not allowed to access this page :)";
  }

  function isAvailable($email){
    global $dbconnect;
    $querySql="select * from user where email='$email'";
    $result=$dbconnect->query($querySql);
    return ($result->num_rows>0) ;
  }

  function signUp($email,$password){
    global $dbconnect,$response;
    $insertSql="insert into user (email,password) values ('$email','$password');";
    if($dbconnect->query($insertSql) ) {
        // $_SESSION['userId']=$email;
        $_SESSION['userId']=$email;
        $response['error'] = false;
        $response['message'] = "successfully created a new account";
    }else{
      $response['error'] = true;
      $response['message'] = "somethig went wrong please try again";
    }
  }


  echo json_encode($response);


