<?php
include 'conn.php';

$errors = array();

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores";
    } else {
        // Check if username already exists
        $check_username_query = "SELECT 1 FROM user WHERE Username = ?";
        $stmt = $conn->prepare($check_username_query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Username already exists";
        }
        $stmt->close();
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        // Check if email already exists
        $check_email_query = "SELECT 1 FROM user WHERE Email = ?";
        $stmt = $conn->prepare($check_email_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "Email already exists";
        }
        $stmt->close();
    }

    // Validate nama
    if (empty($nama)) {
        $errors[] = "Nama is required";
    }

    // Validate alamat
    if (empty($alamat)) {
        $errors[] = "Alamat is required";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 4) {
        $errors[] = "Password must be at least 4 characters long";
    }

    // Sanitize input
    $username = htmlspecialchars(trim($username));
    $email = htmlspecialchars(trim($email));
    $nama = htmlspecialchars(trim($nama));
    $alamat = htmlspecialchars(trim($alamat));

    // Check if there are any validation errors
    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $hashedPassword, $email, $nama, $alamat);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Registration successful
            header("Location: login.php?user=" . $username);
            exit();
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }

        // Close statement
        $stmt->close();
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registration | Web Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="style.css">
<style>
    .card {
        width: 300px;
        margin: auto;
        margin-top: 120px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .card h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .card form {
        margin-bottom: 0;
    }
</style>
</head>

<body id="bg-registration">
     <div class="card">
         <h2>Registrasi</h2>
         <form action="" method="POST">
             <input type="text" name="username" placeholder="Username" class="input-control" style="margin-bottom: 10px;">
             <input type="password" name="password" placeholder="Password" class="input-control" style="margin-bottom: 10px;">
             <input type="email" name="email" placeholder="Email" class="input-control" style="margin-bottom: 10px;">
             <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" style="margin-bottom: 10px;">
             <input type="text" name="alamat" placeholder="Alamat" class="input-control" style="margin-bottom: 10px;">
             <input type="submit" name="submit" value="Register" class="btn" style="width: 100%;">
         </form>
         <?php
         ?><br />
         <p style="text-align: center;">Sudah punya akun? <a style="color:#00C;" href="login.php">Login disini</a></p>
         <p style="text-align: center;">Atau kembali ke <a style="color:#00C;" href="index.php">Home</a></p>
      </div>
</body>
</html>
