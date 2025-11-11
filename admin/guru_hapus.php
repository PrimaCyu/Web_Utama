<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("DELETE FROM guru WHERE id_guru=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?page=guru&pesan=hapus");
    exit;
} else {
    header("Location: index.php?page=guru");
    exit;
}
