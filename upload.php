<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

// Include database connection
include 'conn.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $album_id = $_POST['album_id'];
    
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
                VALUES ('$judul', '$deskripsi', NOW(), '$unique_filename', '$album_id', '$user_id')";
        if (mysqli_query($conn, $sql)) {
            echo "Photo uploaded successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
</head>
<body>
    <h1>Upload Photo</h1>
    <form  action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <label for="judul">Judul Foto:</label>
        <input type="text" id="judul" name="judul" required>
        <br>
        <label for="deskripsi">Deskripsi Foto:</label>
        <textarea id="deskripsi" name="deskripsi" required></textarea>
        <br>
        <label for="file">Pilih Foto:</label>
        <input type="file" id="file" name="file" required>
        <br>
        <?php

        $userID = $_SESSION['UserID'];

        $query = "SELECT AlbumID, NamaAlbum FROM album WHERE UserID = $userID";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Start the form
            echo '<form method="POST">';

            // Start the dropdown menu
            if (mysqli_num_rows($result) > 0) {
                // Albums available, enable dropdown
                echo '<select name="albumID" required>';

                // Loop through the results and create an option for each album
                while ($row = mysqli_fetch_assoc($result)) {
                    $albumID = $row['AlbumID'];
                    $namaAlbum = $row['NamaAlbum'];
                    echo "<option value='$albumID'>$namaAlbum</option>";
                }

                // End the dropdown menu
                echo '</select>';
            } else {
                // No albums available, disable dropdown and submit button
                echo '<select name="albumID" disabled required>';
                echo '<option value="">No albums available</option>'; // Default option
                echo '</select>';
            }

            // End the form
            echo '</form>';
        } else {
            // Display an error message if the query fails
            echo 'Error: ' . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
        ?>

        <br>
        <button type="button" onclick="history.back()">Cancel</button>
        <input type="submit" value="Upload">
    </form>
</body>
<script>
function validateForm() {
    var selectValue = document.getElementById('albumSelect').value;
    if (selectValue === '0') {
        alert('Please select an album.');
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}
</script>
</html>
