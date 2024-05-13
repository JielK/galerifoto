<?php
// Include the database connection file
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['user'];
    $password = $_POST['pass'];

    // Validate username and password
    if (empty($username) || empty($password)) {
        echo "Please enter a username and password.";
    } else {
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT * FROM user WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['Password'])) {
				// Authentication successful
				// Start session and store UserID and Username
				session_start();
				$_SESSION['UserID'] = $user['UserID'];
				$_SESSION['Username'] = $user['Username'];
			
				// Redirect to index.php
				header("Location: index.php");
				exit();
			} else {
				echo "Invalid username or password.";
			}
        } else {
            echo "Invalid username or password.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | Web Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body id="bg-login">
    <br><br><br>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Login</h2>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <input type="text" name="user" placeholder="Username" class="form-control" value="<?php echo isset($_GET['user']) ? $_GET['user'] : ''; ?>" required>
                            </div>
                            <div class="mb-3 position-relative">
                            <div class="input-group">
                                <input type="password" name="pass" id="password" placeholder="Password" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="bi bi-eye-slash"></i></button>
                            </div>
                            </div>
                            <div class="d-grid gap-2">
                                <input type="submit" name="submit" value="Login" class="btn btn-primary">
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p class="mb-1">Belum punya akun? <a href="registrasi.php" class="text-decoration-none fw-bold text-primary">Daftar Disini</a></p>
                            <p class="mb-0">Kembali ke <a href="index.php" class="text-decoration-none fw-bold text-primary">Beranda</a></p>
                        </div>
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