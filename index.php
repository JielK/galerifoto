<?php
include 'conn.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<header>
    <div class="container">
        <h1><a href="index.php">GALERI</a></h1>
        <ul>
            <li><a href="galeri.php">Galeri</a></li>
            <?php
            if(isset($_SESSION['Username'])){
                echo '<li><a href="upload.php">Upload</a></li>';
                echo '<li><a href="album.php">Album</a></li>';
                echo '<li><a>' . $_SESSION['Username'] . '</a></li>';
                echo '<li><a href="logout.php">Logout</a></li>';
            } else {
                echo '<li><a href="login.php">Login</a></li>';
            }
            ?>
        </ul>
    </div>
</header>
<body>
    <div class="container">
       <h3>Foto Terbaru</h3>
       <div class="box">
          <?php
              $foto = mysqli_query($conn, "SELECT * FROM foto ORDER BY FotoID DESC LIMIT 8");
			  if(mysqli_num_rows($foto) > 0){
				  while($p = mysqli_fetch_array($foto)){
		  ?>
          <a href="detail-image.php?id=<?php echo $p['FotoID'] ?>">
          <div class="col-4 box-gambar">
                <img src="img/<?php echo $p['LokasiFile'] ?>" height="220px" />
                <p class="nama"><?php echo substr($p['JudulFoto'], 0, 30)  ?></p>
                <?php
                $userID = $p['UserID'];
                $query = "SELECT Username FROM user WHERE UserID = $userID";
                $result = mysqli_query($conn, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $username = $row['Username'];
                    echo '<p class="admin">Nama User: ' . $username . '</p>';
                } else {
                    echo '<p class="admin">Nama User: [User not found]</p>';
                }
                ?>
                <p class="nama"><?php echo $p['TanggalUnggah']  ?></p>
          </div>
          </a>
          <?php }}else{ ?>
              <p>Foto tidak ada</p>
          <?php } ?>
       </div>
    </div>
</body>
<footer>
    <div class="container">
        <small>Copyright &copy; 2024 Web Galeri Azriel</small>
    </div>
</footer>
</html>