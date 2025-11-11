<?php
include "koneksi.php";
if (!isset($_GET['id'])) {
    header("Location: index.php?page=siswa");
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa=$id"));

if (isset($_POST['update'])) {
    $nama_siswa = $_POST['nama_siswa'];
    $no_absen   = $_POST['no_absen'];
    $tgl_lahir  = $_POST['tgl_lahir'];
    $alamat     = $_POST['alamat'];
    $telepon    = $_POST['telepon'];
    $id_kelas   = $_POST['id_kelas'];
    $nis        = $_POST['nis'];
    $nisn       = $_POST['nisn'];

    mysqli_query($koneksi, "UPDATE siswa SET nama_siswa='$nama_siswa', no_absen='$no_absen', tgl_lahir='$tgl_lahir', alamat='$alamat', telepon='$telepon', nis='$nis', nisn='$nisn' WHERE id_siswa=$id");
    header("Location: index.php?page=siswa&pesan=edit");
    exit;
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h2 class="mb-4">Edit Data Siswa</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nama Siswa</label>
            <input type="text" name="nama_siswa" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor Absen</label>
            <input type="number" name="no_absen" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" name="telepon" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">NIS</label>
            <input type="text" name="nis" class="form-control" required>
        </div>
         <div class="mb-3">
            <label class="form-label">NISN</label>
            <input type="text" name="nisn" class="form-control" required>
        </div>
        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="index.php?page=siswa" class="btn btn-secondary">Kembali</a>
    </form>
</div>