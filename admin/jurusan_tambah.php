<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nama_jurusan = $_POST['nama_jurusan'];
    $singkatan    = $_POST['singkatan'];

    mysqli_query($koneksi, "INSERT INTO jurusan (nama_jurusan, singkatan) VALUES ('$nama_jurusan','$singkatan')");
    header("Location: index.php?page=jurusan&pesan=tambah");
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-building"></i> Tambah Jurusan</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama_jurusan" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Singkatan</label>
                        <input type="text" name="singkatan" maxlength="5" class="form-control" required>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-start">
                    <button type="submit" name="simpan" class="btn btn-success me-2">💾 Simpan</button>
                    <a href="index.php?page=jurusan" class="btn btn-secondary">⬅ Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>