<?php
require_once('dbconnect.php');
session_start();

if ($_SERVER['REQUEST_METHOD']==='POST') {
if (isset($_POST['email'],$_POST['password'])){
    $email=$_POST['email'];
    $password=$_POST['password'];
    if (isAvailable($email)) {
      signUp($email,$password);
    }else {
      echo "this email already registered ";
    
    }
}else {
  echo "no params";
}
}

function isAvailable($email){
  global $dbconnect;
  $querySql="select * from users where email='$email'";
  $result=$dbconnect->query($querySql);
  return !($result->num_rows>0) ;
}

function signUp($email,$password){
  global $dbconnect;
  $insertSql="insert into users (email,password) values ('$email','$password');";
  if($dbconnect->query($insertSql) ) {
      $_SESSION['userId']=$email;
      echo "user registered successfully";
  }
}


 ?>
