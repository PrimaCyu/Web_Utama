<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
$result = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";
    header("Location: dashboard.php");
} else {
    echo "<script>alert('Login gagal! Username atau password salah.');
    window.location='login.php';</script>";
}
?>
