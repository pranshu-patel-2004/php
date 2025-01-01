<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: product.php");
    exit();
}

$email = $_SESSION['email'];

if (isset($_POST['add_product'])) {
    include "product_connection.php";
    $name = $_POST['p_name'];
    $desc = $_POST['description']; 
    $image = '';

    if ($_FILES['p_image']['error'] == 0) { 
        $dir = "uploads/"; 
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $image = $dir . basename($_FILES['p_image']['name']);
        move_uploaded_file($_FILES['p_image']['tmp_name'], $image); 
    }

    $sql = "INSERT INTO product (p_name, p_image, description, email) VALUES ('$name', '$image', '$desc', '$email')";

    if ($conn->query($sql)) { 
        $_SESSION['email'] = $email;
        header("Location: product.php");
        exit();
    } else {
        echo "Error: " . $conn->error; 
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="product.css">
</head>
<body>
    <h2>Add Product</h2>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        Name: <input type="text" name="p_name" required><br>
        Image: <input type="file" name="p_image" required><br>
        Description: <textarea name="description" required></textarea><br>
        <button type="submit" name="add_product">Add Product</button>
    </form>
</body>
</html>