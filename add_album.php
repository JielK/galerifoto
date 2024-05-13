<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['UserID'])) {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaAlbum = mysqli_real_escape_string($conn, $_POST['nama-album']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $userID = mysqli_real_escape_string($conn, $_SESSION['UserID']);

    $query = "INSERT INTO album (NamaAlbum, Deskripsi, TanggalDibuat, UserID)
    VALUES ('$namaAlbum', '$deskripsi', CURDATE(), $userID)";
    if(mysqli_query($conn, $query)){
        echo '<script>alert("Album added successfully."); window.location.href = "album.php";</script>';
        
    } else{
        echo '<script>alert("Error adding album.");</script>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Album</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<header style="margin-bottom: 20px;">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #343a40; padding-left: 45px; padding-right: 45px;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php" style="font-size: 24px;">GALERI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php
                    if(isset($_SESSION['Username'])){
                        echo '<li class="nav-item"><a class="nav-link" href="upload.php" style="font-size: 18px;">Upload</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="my_images.php" style="font-size: 18px;">My Images</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="album.php" style="font-size: 18px;">Album</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="profile.php" style="font-size: 18px;">' . $_SESSION['Username'] . '</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="logout.php" style="font-size: 18px;">Logout</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="login.php" style="font-size: 18px;">Login</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="card-title">Add Album</h1>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="nama-album" class="form-label">Nama Album:</label>
                                <input type="text" id="nama-album" name="nama-album" class="form-control" required maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi:</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" required maxlength="255"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Album</button>
                            <button type="button" onclick="window.history.back()" class="btn btn-secondary">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br><br><br><br><br>

    <footer class="bg-dark text-white py-4 ">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <small>&copy; 2024 Web Galeri Azriel</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js" integrity="sha384-OUAZxy1Yz2PJMdS+/fZjz2O3mtOxhE6FCtKEqu0WbscM/1PqU6d+/xI0sbQ+2ifJ" crossorigin="anonymous"></script>
</body>
</html>
