<?php

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
  require_once('dbconnect.php');

 
  
  session_start();
  $response = array();

  if ($_SERVER['REQUEST_METHOD']==='POST') {

    if(isset($_POST['firstname'],$_POST['lastname'],$_POST['email']
      ,$_POST['password'])){

      $fName = $_POST['firstname'];
      $lName = $_POST['lastname'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      if(!isAvailable($email)){
        
         signUp($fName,$lName,$email,$password);

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
    $querySql="select * from users where email='$email'";
    $result=$dbconnect->query($querySql);
    return ($result->num_rows>0) ;
  }

  function signUp($fName,$lName,$email,$password){
    global $dbconnect,$response;

    // encrypt user password 
    // $password = password_hash($password,PASSWORD_BCRYPT);

    // create random token with 32 character
    $token = md5(rand(0,1000));
    
    $insertSql="insert into users (first_name,last_name,email,password,status,token) 
    values ('$fName','$lName','$email','$password','0','$token');";

    // case of successfully inserted
    if($dbconnect->query($insertSql) ) {
        $_SESSION['userId']=$email;
        $response['error'] = false;
        $response['message'] = "successfully created account";

       // user PHPMailer
      // require('PHPMailer/PHPMailer.php');
      // require('PHPMailer/Exception.php');
      // require('PHPMailer/SMTP.php');

      // $userName = 'gift@ecommerceg.000webhostapp.com'; // enter username
      // $password = '1234567'; // enter password
      
      // $mail = new PHPMailer();
      // //Server settings
      // $mail->SMTPDebug = 0;   
      // $mail->isSMTP();                                      
      // $mail->Host = 'smtp.gmail.com'; 
      // $mail->SMTPAuth = true;                                
      // $mail->Username = $userName;            
      // $mail->Password = $password;                                  
      // $mail->Port = 587;
      // $mail->SMTPSecure = 'tls';
  

      // $mail->setFrom($userName,'ibra');
      // $mail->addAddress($email);

      // //Content
      // $mail->isHTML(true);                                  
      // $mail->Subject ='Email Verefication';
      // $mail->Body = 'Thanks for registeration 
      // please click on link below to verify your account <br>
      //  <a href=http://192.168.1.105/ecommerce-backend/confirm.php?email='.$email.'&amp;token='.$token.'>click here</a>';
      

      // if mail successfulley sent
      // if($mail->send()){
      //   $response['error']=false;
      //   $response['message']="successfully created a new account please check your email to activate your account";
      // }
      // // if mail failure
      // else{
      //   $response['error'] = true;
      //   $response['message'] = "somethig went wrong during sending email please try again ".$mail->ErrorInfo;
      // } 

    }

    // case of failure of inserting into DB
    else{
      $response['error'] = true;
      $response['message'] = "somethig went wrong please try again";
    }
  }


  echo json_encode($response);


