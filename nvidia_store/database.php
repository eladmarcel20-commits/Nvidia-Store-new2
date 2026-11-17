<?php  

// Database connection settings 

$servername = "localhost";
$username = "root";
$password = "12345678";
$database = "nvidia_store";


// Connect to MYSQL

$conn = new mysqli( 
    $servername,
    $username,
    $password,
    $database
    
);


// Check if connection was successful

if ($conn->connect_error) {

die("Connection failed: " .$conn->connect_error);

}



// set character encoding 

$conn->set_charset("utf8mb4");

?>