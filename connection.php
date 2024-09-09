<?php
 
error_reporting(0);

$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "employee1";

$conn = mysqli_connect($servername,$username,$password,$dbname);

if($conn)
{
//echo "Connection Ok";
}
else
{
	echo "Connection failed".mysqli_connect_error();
}

?>