<?php
session_start();

if (isset($_SESSION['email'])) {
    header("Location: product.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    include "connection.php";
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT password FROM `signup form` WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row["password"];

        $message = '';
      
        if ($password === $storedPassword) { 
            $_SESSION['email'] = $email;
            header("Location: product.php");
            exit();
        } else {
            $message = "Invalid password."; 
        }
    } else {
        $message = "Email not found."; 
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Login Page</h1>
    <form method="post">
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login"><br><br>
        <a href="signup.php">Don't have an account? Sign up here</a>
    </form>
    <div class="message">
        <?php if (!empty($message)): ?>
            <p style="text-align: center; color:red"><?= $message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
