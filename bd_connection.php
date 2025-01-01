<?php

$servername = "localhost";
$username = "root";
$password = "";


$conn = new mysqli($servername, $username, $password);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

echo "Connected successfully<br>";

echo "<br>";
// SQL to create a database
$sql = "CREATE DATABASE IF NOT EXISTS form";
if ($conn->query($sql) === TRUE) {
    echo "Database 'form' created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();

?>