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
    $userID = $_SESSION['UserID'] ?? null;
    $result = likePhoto($conn, $photoID, $userID);
    echo $result;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto</title>
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
<div class="container">
        <!-- Featured Image -->
        <?php
$featured_photo_query = mysqli_query($conn, "SELECT f.*, COUNT(l.FotoID) AS like_count 
                                                FROM foto f 
                                                LEFT JOIN likefoto l ON f.FotoID = l.FotoID 
                                                GROUP BY f.FotoID 
                                                ORDER BY like_count DESC LIMIT 1");
if (mysqli_num_rows($featured_photo_query) > 0) {
    $fp = mysqli_fetch_assoc($featured_photo_query);
?>
    <div class="container mb-4">
        <a href="image_details.php?photo_id=<?php echo $fp['FotoID']; ?>" class="card-link"> <!-- Added anchor tag here -->
            <div class="jumbotron jumbotron-fluid featured-image" style="background-image: url('<?php echo isset($fp) ? 'img/'.$fp['LokasiFile'] : ''; ?>');">
                <div class="container">
                    <div class="featured-text">
                        <h1 class="display-4"><?php echo $fp['JudulFoto']; ?></h1>
                        <p class="lead">&nbspMost Liked Image</p>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </a> <!-- Closing anchor tag -->
    </div>
<?php } else { ?>
    <div class="container">
        <p class="lead">Foto tidak ada</p>
    </div>
<?php } ?>

</div>

<!-- Cards for other images -->
<div class="row mx-5">
    <?php
    // Get the current user ID
    $userID = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;

    
    // Query to fetch other images
    $other_photos_query = mysqli_query($conn, "SELECT * FROM foto WHERE FotoID != {$fp['FotoID']} ORDER BY FotoID DESC LIMIT 16");
    while ($photo = mysqli_fetch_assoc($other_photos_query)) {
        // Query to check if the user has liked the photo
        $userIDCondition = isset($_SESSION['UserID']) ? "AND UserID = $userID" : "";
        $liked_query = mysqli_query($conn, "SELECT COUNT(*) AS liked FROM likefoto WHERE FotoID = {$photo['FotoID']} $userIDCondition");
        $liked_result = mysqli_fetch_assoc($liked_query);
        $isLiked = ($liked_result && isset($liked_result['liked']) && $liked_result['liked'] > 0);
        
        
        // Get like count
        $like_count_query = mysqli_query($conn, "SELECT COUNT(*) AS like_count FROM likefoto WHERE FotoID = {$photo['FotoID']}");
        $like_count = mysqli_fetch_assoc($like_count_query)['like_count'];

        // Get comment count
        $comment_count_query = mysqli_query($conn, "SELECT COUNT(*) AS comment_count FROM komentarfoto WHERE FotoID = {$photo['FotoID']}");
        $comment_count = mysqli_fetch_assoc($comment_count_query)['comment_count'];
    ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 mb-4">
    <a href="image_details.php?id=<?php echo $photo['FotoID']; ?>" class="card-link">
        <div class="card h-100">
                <div class="image-container">
                    <a href="image_details.php?photo_id=<?php echo $photo['FotoID']; ?>">
                        <img src="img/<?php echo $photo['LokasiFile']; ?>" class="card-img-top img-fluid" alt="<?php echo $photo['JudulFoto']; ?>">
                    </a>
                </div>
                <div class="card-body d-flex flex-column">
                    <!-- Like and comment icons with numbers -->
                    <div class="d-flex justify-content-end align-items-baseline mt-auto">
                        <?php if(isset($_SESSION['UserID'])): ?>
                        <div class="like-button">
                            <span class="like-count photo-<?php echo $photo['FotoID']; ?>"><?php echo $like_count; ?></span>&nbsp;
                            <i class="bi <?php echo $isLiked ? 'bi-heart-fill' : 'bi-heart'; ?>" onclick="likePhoto(<?php echo $photo['FotoID']; ?>)"></i>
                        </div>
                        <?php else: ?>
                        <div class="like-button">
                            <span class="like-count photo-<?php echo $photo['FotoID']; ?>"><?php echo $like_count; ?></span>&nbsp;
                            <a href="login.php" style="text-decoration: none; color:red;"><i class="bi bi-heart"></i></a>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['UserID'])): ?>
                        <a href="image_details.php?photo_id=<?php echo $photo['FotoID']; ?>#comment" class="comment-count"><?php echo $comment_count; ?>&nbsp;<i class="bi bi-chat-dots"></i></a>
                        <?php else: ?>
                        <a href="image_details.php?photo_id=<?php echo $photo['FotoID']; ?>#comment" class="comment-count"><?php echo $comment_count; ?>&nbsp;<i class="bi bi-chat-dots"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php } ?>
</div>

<script>
 function likePhoto(photoID) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            location.reload();
        }
    };
    xhr.send('like_photo=1&photo_id=' + photoID);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
<br><br><br><br><br><br><br><br><br><br>
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