<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "form";


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully<br>";


$sql = "CREATE TABLE IF NOT EXISTS `signup form` (          
    `name` VARCHAR(200) NOT NULL,                 
    `email` VARCHAR(200) NOT NULL UNIQUE,        
    `mobile` VARCHAR(15) NOT NULL,               
    `gender` ENUM('male', 'female', 'other') NOT NULL, 
    `photo` VARCHAR(200) NOT NULL,               
    `address` TEXT NOT NULL,                                         
   `password`VARCHAR(200) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'signup form' is ready.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
$conn ->close();
?>