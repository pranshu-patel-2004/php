<?php
session_start();
$email = $_SESSION['email'];

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include "product_connection2.php";

$sql = "SELECT name, photo FROM `signup form` WHERE email = '$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$conn->select_db($db_product);

if (isset($_GET['delete_product'])) {
    $id = $_GET['delete_product'];
    $sql = "DELETE FROM product WHERE sr_no = $id AND email = '$email'";
    if ($conn->query($sql)) {
        echo "<p>Product deleted successfully.</p>";
    } else {
        echo "<p>Error deleting product: " . $conn->error . "</p>";
    }
}

$sql = "SELECT * FROM product WHERE email = '$email'";
$products = $conn->query($sql);

$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Page</title>
    <link rel="stylesheet" type="text/css" href="product.css">
</head>
<body>
    <div class="header">
        <div class="left">
            <a href="product.php" class="btn">Product</a>
        </div>
        <div class="right">
        <span>Welcome, <?= $user['name'] ?></span>
            <img src="<?= $user['photo'] ?>" alt="Photo" width="50" style="border-radius:50%">
             <form action="logout.php" method="post> "onclick="return confirm('Are you sure you want to Logout?');"></a>
                <button type="submit" class="btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="products">
        <h2>Products</h2>
        <a href="add_product.php" class="btn">Add Product</a>

        <table border="1">
            <tr>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php if ($products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <tr>
                    <td><?= $product['p_name'] ?></td>
                        <td><img src="<?= $product['p_image'] ?>" width="100"></td>
                        <td><?= $product['description'] ?></td>
                        <td>
                        <a href="edit_product.php?product_id=<?= $product['sr_no'] ?>">Edit</a><br><br>
                            <a href="product.php?delete_product=<?= $product['sr_no'] ?>"
                             onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No products found.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
