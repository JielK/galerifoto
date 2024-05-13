<?php
include 'conn.php';
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

// Include database connection

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $album_id = $_POST['albumID'];
    
    // File upload
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    
    // Generate unique filename
    $unique_filename = uniqid('image_', true) . '.' . $file_extension;
    $file_destination = 'img/' . $unique_filename;

    if (move_uploaded_file($file_tmp, $file_destination)) {
        // Get user ID from session
        $user_id = $_SESSION['UserID'];

        // Insert data into database
        $sql = "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, LokasiFile, AlbumID, UserID)
                VALUES ('$judul', '$deskripsi', CURDATE(), '$unique_filename', '$album_id', '$user_id')";
        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Photo uploaded successfully.");</script>';
        } else {
            echo '<script>alert("Error: ' . $sql . '\n' . mysqli_error($conn) . '");</script>';
        }
    } else {
        echo "Failed to upload file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Photo</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<header>
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
<body>
    <div class="container">
        <h1 class="mt-3 mb-3">Image Upload</h1>
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" class="mb-4">
    <div class="mb-3">
        <label for="judul" class="form-label">Judul Foto :</label>
        <input type="text" id="judul" name="judul" class="form-control" required maxlength="255">
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi Foto :</label>
        <textarea id="deskripsi" name="deskripsi" class="form-control" required maxlength="255"></textarea>
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">Pilih Foto :</label>
        <div class="file-upload-container">
            <img id="imagePreview" src="img/default.png" alt="Image Preview"><br><br>
            <input type="file" id="file" name="file" class="form-control" onchange="previewFile()" required>
        </div>
    </div>
    <?php
    $userID = $_SESSION['UserID'];
    $query = "SELECT AlbumID, NamaAlbum FROM album WHERE UserID = $userID";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<select name="albumID" id="albumSelect" class="select-dropdown" required>';
            while ($row = mysqli_fetch_assoc($result)) {
                $albumID = $row['AlbumID'];
                $namaAlbum = $row['NamaAlbum'];
                echo "<option value='$albumID'>$namaAlbum</option>";
            }
            echo '</select>';
        } else {
            echo '<select name="albumID" id="albumSelect" class="select-dropdown" required>';
            echo '<option value="">No albums available</option>';
            echo '</select>';
        }
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
    mysqli_close($conn);
    ?>
    <div class="mb-5 mt-4">
        <button type="button" onclick="history.back()" class="btn btn-secondary">Cancel</button>
        <input type="submit" value="Upload" class="btn btn-primary">
    </div>
</form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
<footer class="bg-dark text-white py-4 ">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <small>&copy; 2024 Web Galeri Azriel</small>
            </div>
        </div>
    </div>
</footer>
<script>
function validateForm() {
    var selectValue = document.getElementById('albumSelect').value;
    if (selectValue === '') {
        alert('Please select an album.');
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}
// JavaScript for file preview
function previewFile() {
    var preview = document.getElementById('imagePreview');
    var file = document.getElementById('file').files[0]; // Use getElementById instead of querySelector

    var reader = new FileReader();
    reader.onloadend = function() {
        preview.src = reader.result;
        preview.hidden = false; // Show the image preview
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.hidden = true; // Hide the image preview if no file is selected
    }
}


</script>
</html>
