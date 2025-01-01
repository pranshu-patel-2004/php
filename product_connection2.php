
<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_form = "form";
$db_product = "product";

$conn = new mysqli($servername, $username, $password, $db_form);
if ($conn->connect_error) {
    ("Connection failed: " . $conn->connect_error);
}
?>