<?php
    include 'conn.php';
    session_start();
    if (!isset($_SESSION['UserID'])) {
        // Redirect user to login page if not logged in
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
<div class="container album-container">
    <div class="row">
        <div class="col">
            <h1 class="float-start">My Albums</h1>
            <?php if(isset($_SESSION['Username'])): ?>
                <a href="add_album.php" class="btn btn-primary btn-sm add-album-btn d-inline-flex align-items-center float-end">
                    <i class="bi bi-plus me-1"></i> Add Album
                </a>
            <?php endif; ?>
        </div>
    </div>




    <?php
        $conn = $conn;
        $userID = $_SESSION['UserID']; // Get UserID from session
        $query = "SELECT album.*, IFNULL(foto.LokasiFile, 'default.png') AS LokasiFile FROM album LEFT JOIN (SELECT AlbumID, MAX(LokasiFile) AS LokasiFile FROM foto GROUP BY AlbumID) AS foto ON album.AlbumID = foto.AlbumID WHERE album.UserID = $userID";
        $result = mysqli_query($conn, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="row">';
            echo '<div class="col">';
            echo '<div class="album-info">';
            echo '<img src="img/' . $row['LokasiFile'] . '" alt="' . $row['NamaAlbum'] . '">';
            echo '<div>';
            echo '<h2>' . $row['NamaAlbum'] . '</h2>';
            echo '<p>' . $row['Deskripsi'] . ' - Created on ' . $row['TanggalDibuat'] . '</p>';
            
            $photoCountQuery = "SELECT COUNT(*) AS count FROM foto WHERE AlbumID = " . $row['AlbumID'];
            $photoCountResult = mysqli_query($conn, $photoCountQuery);
            $photoCount = mysqli_fetch_assoc($photoCountResult)['count'];
            echo '<p class="photo-count">Total Photos: ' . $photoCount . '</p>';
            
            echo '</div>'; // closing div for album info
            echo '</div>'; // closing div for album-info
            echo '</div>'; // closing div for col
            echo '</div>'; // closing div for row
        }
        
        mysqli_close($conn);
    ?>
</div>
</body>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<footer class="bg-dark text-white py-4 ">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <small>&copy; 2024 Web Galeri Azriel</small>
            </div>
        </div>
    </div>
</footer>
</html>
