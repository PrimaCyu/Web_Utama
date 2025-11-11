<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nama_guru = $_POST['nama_guru'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $alamat    = $_POST['alamat'];
    $telepon   = $_POST['telepon'];
    $username  = $_POST['username'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO guru (nama_guru, tgl_lahir, alamat, telepon, username, password)
              VALUES ('$nama_guru', '$tgl_lahir', '$alamat', '$telepon', '$username', '$password')";

    mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

    header("Location: index.php?page=guru&pesan=tambah");
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-person-plus"></i> Tambah Guru</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Guru</label>
                        <input type="text" name="nama_guru" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-start">
                    <button type="submit" name="simpan" class="btn btn-success me-2">💾 Simpan</button>
                    <a href="index.php?page=guru" class="btn btn-secondary">⬅ Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>