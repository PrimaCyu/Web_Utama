<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM jurnal WHERE id_jurnal='$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: index.php?page=jurnal&pesan=hapus");
        exit;
    } else {
        echo "<script>alert('Gagal menghapus data jurnal!');window.location='index.php?page=jurnal';</script>";
    }
} else {
    header("Location: index.php?page=jurnal");
    exit;
}
?>
