<?php
include 'conn.php';
session_start();

if(isset($_POST['photo_id'])) {
    $photoID = $_POST['photo_id'];
    
    // Delete associated comments from the komentarfoto table
    $delete_comments_query = "DELETE FROM komentarfoto WHERE FotoID = $photoID";
    mysqli_query($conn, $delete_comments_query);

    // Query to fetch the image file name
    $query = "SELECT LokasiFile FROM foto WHERE FotoID = $photoID";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $filename = $row['LokasiFile'];

    // Delete the image from the folder
    if(file_exists("img/$filename")) {
        unlink("img/$filename");
    }

    // Delete the image record from the database
    $delete_query = "DELETE FROM foto WHERE FotoID = $photoID";
    mysqli_query($conn, $delete_query);

    // Redirect back to my_images.php after deletion
    header("Location: my_images.php");
    exit();
} else {
    // Redirect to index.php if photo_id is not set
    header("Location: index.php");
    exit();
}
?>
