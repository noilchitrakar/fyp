<?php 
$servername="localhost";
$username="root";
$password="";
$dbname="testing";

$con=mysqli_connect($servername,$username,$password,$dbname);

if(mysqli_connect_error()){
    echo "<script>alert('can't connect to the database)</script>";
    exit();
}

?>

