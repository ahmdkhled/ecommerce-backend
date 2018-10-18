<?php
$host='localhost';
$username='root';
$password='';
$db='movies';

$dbconnect=new mysqli($host,$username,$password,$db);

if ($dbconnect->connect_error) {
    die("there is problem in connection".$dbconnect->connect_error);
}

 ?>
