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
</head>

<body id="bg-login">
     <div class="box-login">
         <h2>Login</h2>
         <form action="" method="POST">
             <input type="text" name="user" placeholder="Username" class="input-control" value="<?php echo isset($_GET['user']) ? $_GET['user'] : ''; ?>">
             <input type="password" name="pass" placeholder="Password" class="input-control">
             <input type="submit" name="submit" value="Login" class="btn">
         </form>
		 <br />
         <p>Belum punya akun? daftar <a style="color:#00C;" href="registrasi.php">DISINI</a></p>
         <p>atau klik <a style="color:#00C;" href="index.php">Kembali</a></p>
      </div>
      
</body>
</html>