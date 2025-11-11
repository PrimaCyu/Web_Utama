<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
  </header>

  <nav>
    <a href="dashboard.php?page=home">Home</a>
    <a href="dashboard.php?page=guru">Guru</a>
    <a href="dashboard.php?page=pegawai">Pegawai</a>
    <a href="dashboard.php?page=jurusan">Jurusan</a>
    <a href="logout.php">Logout</a>
  </nav>

  <main>
    <?php 
    $file = "admin/" . $page . ".php";

    if (file_exists($file)) {
        include $file;
    } else {
        echo "<div class='container'><h2>Halaman tidak ditemukan!</h2></div>";
    }
    ?>
  </main>
</body>
</html>
