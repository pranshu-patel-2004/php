<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

        include "connection.php";

        
function sanitizeInput($conn, $data) {
    
    if (is_array($data)) {
        
        foreach ($data as $key => $value) {
            $data[$key] = sanitizeInput($conn, $value);
        }
    } else {
        $data = $conn->real_escape_string(trim($data));
    }
    return $data;
}

$data = sanitizeInput($conn, $_POST);

    $name = $data["name"];
    $email = $data["email"];
    $number = $data["number"];
    $gender = $data["gender"];
    $address = $data["address"];
    $password = $data["password"];

    $photo = $_FILES["photo"]["name"]; 

        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($photo);


        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO `signup form` (name, email, mobile, gender, photo, address, password) 
                    VALUES ('$name', '$email', '$number', '$gender', '$target_file', '$address', '$password')";

            try {
                if ($conn->query($sql)) { 
                    header("Location: login.php");
                    exit();
                } 
            } catch (mysqli_sql_exception $e) {
                
                if ($conn->errno == 1062) {
                    $errorMessage = "This email is already signed up!";
                } else {
                    $errorMessage = "Error: " . $e->getMessage(); 
                }
            }
        } else {
            $errorMessage = "Error uploading photo.";
        }

        $conn->close();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Signup Form</h1>
    <form method="post" enctype="multipart/form-data">
        Name: <input type="text" name="name" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Number: <input type="number" name="number" required><br><br>
        Gender:
        <input type="radio" name="gender" value="female" required>Female♀️
        <input type="radio" name="gender" value="male" required>Male♂️
        <input type="radio" name="gender" value="other" required>Other<br><br>
        Photo: <input type="file" name="photo" accept="image/*" required><br><br>
        Address: <input type="text" name="address" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="checkbox" name="agree" value="1" required> Are you Confirmed For Signup <br><br>
        <input type="submit" name="submit" value="Submit"><br><br>
        <input type="submit" name="cancel" value="Cancel">
    </form>
<br><br>
    <?php if (!empty($errorMessage)): ?>
        <div style="text-align: center; color:red"><?= ($errorMessage) ?></div>
    <?php endif; ?>
</body>
</html>
