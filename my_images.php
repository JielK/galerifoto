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
    <title>My Images</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
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
                        echo '<li class="nav-item"><a class="nav-link" href="registrasi.php" style="font-size: 18px;">Register</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<body>
<div class="container mt-5">
    <div class="row">
    <?php
    // Get the current user ID
    $userID = $_SESSION['UserID'];
    
    // Query to fetch user's images
    $user_images_query = mysqli_query($conn, "SELECT * FROM foto WHERE UserID = $userID");
    while ($photo = mysqli_fetch_assoc($user_images_query)) {
    ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="image-container">
                    <a href="image_details.php?photo_id=<?php echo $photo['FotoID']; ?>">
                        <img src="img/<?php echo $photo['LokasiFile']; ?>" class="card-img-top img-fluid" alt="<?php echo $photo['JudulFoto']; ?>">
                    </a>
                </div>
                <div class="card-body d-flex flex-column">
                    <!-- Edit and delete buttons -->
                    <div class="d-flex justify-content-end align-items-center mt-auto">
                        <a href="edit_image.php?photo_id=<?php echo $photo['FotoID']; ?>" class="btn btn-primary mx-2">Edit</a>
                        <!-- Delete button with data attributes -->
                        <button type="button" class="btn btn-danger delete-btn" data-photo-id="<?php echo $photo['FotoID']; ?>">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>
<br><br><br><br><br><br>
<script>
// Function to handle delete button click
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('delete-btn')) {
        // Get the photo ID from the data attribute
        var photoID = e.target.getAttribute('data-photo-id');
        // Confirm deletion
        if (confirm('Are you sure you want to delete this image?')) {
            // Send AJAX request to delete_image.php
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_image.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Reload the page to reflect changes
                    window.location.reload();
                } else {
                    alert('Error deleting image.');
                }
            };
            xhr.send('photo_id=' + photoID);
        }
    }
});
</script>
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
</html>
