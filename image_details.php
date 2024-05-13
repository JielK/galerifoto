<?php
include 'conn.php';
session_start();

function likePhoto($conn, $photoID, $userID) {
    if (!$userID) {
        header("Location: login.php");
        exit();
    }

    $check_query = "SELECT * FROM likefoto WHERE FotoID = $photoID AND UserID = $userID";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        $insert_query = "INSERT INTO likefoto (FotoID, UserID, TanggalLike) VALUES ($photoID, $userID, NOW())";
        if (mysqli_query($conn, $insert_query)) {
            return 'liked';
        } else {
            return 'error';
        }
    } else {
        $delete_query = "DELETE FROM likefoto WHERE FotoID = $photoID AND UserID = $userID";
        if (mysqli_query($conn, $delete_query)) {
            return 'unliked';
        } else {
            return 'error';
        }
    }
}

if (isset($_POST['like_photo'])) {
    $photoID = $_POST['photo_id'];
    $userID = $_SESSION['UserID'];
    $result = likePhoto($conn, $photoID, $userID);
    echo $result;
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION['UserID'];
    $photoID = $_GET['photo_id'];
    $comment = $_POST['comment'];

    $insert_query = "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES ('$photoID', '$userID', '$comment', NOW())";
    if (mysqli_query($conn, $insert_query)) {
        // Redirect to the same page to prevent form resubmission
        header("Location: image_details.php?photo_id=$photoID");
        exit();
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }
}

$photoID = $_GET['photo_id'];
$photo_query = mysqli_query($conn, "SELECT * FROM foto WHERE FotoID = $photoID");
$photo = mysqli_fetch_assoc($photo_query);

$albumID = $photo['AlbumID'];
$album_query = mysqli_query($conn, "SELECT * FROM album WHERE AlbumID = $albumID");
$album = mysqli_fetch_assoc($album_query);

$comment_query = mysqli_query($conn, "SELECT * FROM komentarfoto WHERE FotoID = $photoID ORDER BY TanggalKomentar DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - <?php echo $photo['JudulFoto'];?></title>
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
                            echo '<li class="nav-item logout"><a class="nav-link" href="logout.php" style="font-size: 18px;">Logout</a></li>';
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
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <img src="img/<?php echo $photo['LokasiFile']; ?>" class="img-fluid" alt="<?php echo $photo['JudulFoto']; ?>">
            </div>
            <div class="col-md-4">
    <div class="image-details">
        <h2 class="image-title"><?php echo $photo['JudulFoto']; ?></h2>
        <p class="image-description"><?php echo $photo['DeskripsiFoto']; ?></p>
        <p class="album-name">Album: <?php echo $album['NamaAlbum']; ?></p>
        <p class="upload-date">Uploaded on <?php echo date('F j, Y', strtotime($photo['TanggalUnggah'])); ?></p>
        
        <?php
        $like_count_query = mysqli_query($conn, "SELECT COUNT(*) AS like_count FROM likefoto WHERE FotoID = $photoID");
        $like_count_result = mysqli_fetch_assoc($like_count_query);
        $like_count = $like_count_result['like_count'];

        $liked = false;
        if (isset($_SESSION['UserID'])) {
            $userID = $_SESSION['UserID'];
            $check_like_query = mysqli_query($conn, "SELECT * FROM likefoto WHERE FotoID = $photoID AND UserID = $userID");
            if (mysqli_num_rows($check_like_query) > 0) {
                $liked = true;
            }
        }
        ?>
        
        <div class="like-button" onclick="likePhoto(<?php echo $photoID; ?>)">
            <i id="like-icon" class="bi <?php echo $liked ? 'bi-heart-fill text-danger' : 'bi-heart'; ?>"></i>&nbsp;<span class="like-count"><?php echo $like_count; ?></span>
        </div>



        
        <!-- Comment Form -->
        <?php if (isset($_SESSION['UserID'])) : ?>
            <form class="comment-form mt-4" method="post" action="">
                <div class="mb-3">
                    <label for="comment" class="form-label" required>Add a Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        <?php else : ?>
            <div class="comment-form mt-4">
                <p>You must be <a href="login.php">logged in</a> to leave a comment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

        </div>
        <div class="row mt-4">
            <div class="col-md-8">
                <h3>Comments</h3>
                <?php while ($comment = mysqli_fetch_assoc($comment_query)) : ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php 
                                // Fetch the username associated with the user ID
                                $userID = $comment['UserID'];
                                $user_query = mysqli_query($conn, "SELECT Username FROM user WHERE UserID = $userID");
                                $user = mysqli_fetch_assoc($user_query);
                                echo $user['Username'];
                                ?>
                            </h5>
                            <p class="card-text"><?php echo $comment['IsiKomentar']; ?></p>
                        </div>
                        <div class="card-footer text-muted"><?php echo $comment['TanggalKomentar']; ?></div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <br><br><br><br>
    </div>

    <script>
        function likePhoto(photoID) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var result = xhr.responseText;
                    var likeCountSpan = document.querySelector('.like-count');
                    var likeIcon = document.getElementById('like-icon');
                    if (result === 'liked') {
                        likeCountSpan.textContent = parseInt(likeCountSpan.textContent) + 1;
                        likeIcon.classList.remove('bi-heart');
                        likeIcon.classList.add('bi-heart-fill');
                    } else if (result === 'unliked') {
                        likeCountSpan.textContent = parseInt(likeCountSpan.textContent) - 1;
                        likeIcon.classList.remove('bi-heart-fill');
                        likeIcon.classList.add('bi-heart');
                    }
                }
            };
            xhr.send('like_photo=1&photo_id=' + photoID);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <small>&copy; 2024 Web Galeri Azriel</small>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
