<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("DELETE FROM siswa WHERE id_siswa=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?page=siswa&pesan=hapus");
    exit;
} else {
    header("Location: index.php?page=siswa");
    exit;
}
