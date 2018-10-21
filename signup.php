<?php

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
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

    // encrypt user password 
    $password = password_hash($password,PASSWORD_BCRYPT);

    // create random token with 32 character
    $token = md5(rand(0,1000));
    
    $insertSql="insert into user (email,password,token,status) values ('$email','$password','$token','0');";

    // case of successfully inserted
    if($dbconnect->query($insertSql) ) {
        $_SESSION['userId']=$email;

      // user PHPMailer
      require('PHPMailer/PHPMailer.php');
      require('PHPMailer/Exception.php');
      require('PHPMailer/SMTP.php');
      $mail = new PHPMailer();
      //Server settings
      $mail->SMTPDebug = 2;   
      $mail->isSMTP();                                      
      $mail->Host = 'smtp.gmail.com'; 
      $mail->SMTPAuth = true;                                
      $mail->Username = 'username@gmail.com';  // username              
      $mail->Password = 'xxxxxxxxxxx'; // password                                  
      $mail->Port = 587;  

      $mail->setFrom('username@gmail.com','ibra');
      $mail->addAddress($email);

      //Content
      $mail->isHTML(true);                                  
      $mail->Subject ='Email Verefication';
      $mail->Body = 'hello';
      

      // if mail successfulley sent
      if($mail->send()){
        $response['error'] = false;
        $response['message'] = "successfully created a new account check your email to verify your account";
      }
      // if mail failure
      else{
        $response['error'] = true;
        $response['message'] = "somethig went wrong during sending email please try again ".$mail->ErrorInfo;
      } 

    }

    // case of failure of inserting into DB
    else{
      $response['error'] = true;
      $response['message'] = "somethig went wrong please try again";
    }
  }


  echo json_encode($response);


