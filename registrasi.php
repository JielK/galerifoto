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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body id="bg-registration">
    <div class="container">
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card" style="width: 450px;">
                <div class="card-body">
                    <h2 class="text-center">Registrasi</h2>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <input type="text" name="username" placeholder="Username" class="form-control" required autocomplete="off" minlength="4" maxlength="255">
                        </div>
                        <div class="mb-3 position-relative input-group">
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control" required minlength="8" maxlength="255">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="bi bi-eye-slash"></i></button>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" placeholder="Email" class="form-control" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="nama" placeholder="Nama Lengkap" class="form-control" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="alamat" placeholder="Alamat" class="form-control" required maxlength="255">
                        </div>
                        <div class="d-grid gap-2">
                            <input type="submit" name="submit" value="Register" class="btn btn-primary" required>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <p class="mb-1">Sudah punya akun? <a href="login.php" class="text-decoration-none fw-bold text-primary">Login Disini</a></p>
                        <p class="mb-0">Kembali ke <a href="index.php" class="text-decoration-none fw-bold text-primary">Beranda</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye-slash');
        this.querySelector('i').classList.toggle('bi-eye');
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
