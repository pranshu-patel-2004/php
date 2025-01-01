<?php
session_start();

// Check if the user is logged in

$email = $_SESSION['email'];
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Redirect if product_id is not provided

$id = $_GET['product_id'];
if (!isset($_GET['product_id'])) {
    header("Location: product.php");
    exit();
}

include "product_connection.php";

$stmt = $conn->prepare("SELECT * FROM product WHERE sr_no = ? AND email = ?");
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['p_name']; 
    $desc = $_POST['description']; 
    $image = $product['p_image'];

    if ($_FILES['p_image']['error'] == 0) { 
        $dir = "uploads/";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $image = $dir . basename($_FILES['p_image']['name']);
        move_uploaded_file($_FILES['p_image']['tmp_name'], $image);

        
        if (file_exists($product['p_image']) && is_file($product['p_image'])) {
            unlink($product['p_image']);
        }
    }

    
    $stmt = $conn->prepare("UPDATE product SET p_name = ?, description = ?, p_image = ? WHERE sr_no = ? AND email = ?");
    $stmt->bind_param("sssis", $name, $desc, $image, $id, $email);
    if ($stmt->execute()) {
        header("Location: product.php");
        exit();
    } else {
        echo "Error updating: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" type="text/css" href="product.css">
</head>
<body>
    <h2>Edit Product</h2>
    <form method="POST" action="edit_product.php?product_id=<?= $id ?>" enctype="multipart/form-data">
        Name: <input type="text" name="p_name" value="<?= $product['p_name'] ?>" required><br>
        Image: <br><br>
        <img src="<?= $product['p_image'] ?>" alt="Current Image" style="max-width: 200px; max-height: 200px;"><br><br>
        Upload Image: <br><br><input type="file" name="p_image"><br>
        Description: <textarea name="description" required><?= $product['description'] ?></textarea><br>
        <button type="submit">Save</button>
    </form>
</body>
</html>
