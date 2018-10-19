
<?php
require_once('dbconnect.php');
session_start();

if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (isset($_POST['email'],$_POST['password'])) {
    $email=$_POST['email'];
    $password=$_POST['password'];
    logIn($email,$password);
  }else {
    echo "no parameters";
  }
}

  function logIn($email,$password){
    global $dbconnect;
    $querySql="select * from users where email = '$email' and password ='$password'";
    $result=$dbconnect->query($querySql);
    if ($result->num_rows>0) {
      $_SESSION['userId']=$email;
      echo "user logged in successfully";

    }else {
      http_response_code(404);
      echo "wrong credentials ";

    }
  }


 ?>
