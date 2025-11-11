<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nama_pegawai   = trim($_POST['nama_pegawai']);
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $alamat         = trim($_POST['alamat']);
    $telepon        = trim($_POST['telepon']);
    $username       = trim($_POST['username']);
    $password       = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validasi sederhana
    if ($nama_pegawai && $tanggal_lahir && $alamat && $telepon && $username && $_POST['password']) {
        $query = "INSERT INTO pegawai (nama_pegawai, tanggal_lahir, alamat, telepon, username, password)
                  VALUES ('$nama_pegawai','$tanggal_lahir','$alamat','$telepon','$username','$password')";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            header("Location: index.php?page=pegawai&pesan=tambah");
            exit;
        } else {
            $error = "Gagal menyimpan data. Silakan coba lagi.";
        }
    } else {
        $error = "Semua field wajib diisi!";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">➕ Tambah Pegawai</h4>
        </div>
        <div class="card-body p-4">

            <!-- Notifikasi error -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Pegawai</label>
                        <input type="text" name="nama_pegawai" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" pattern="[0-9]{10,15}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username login" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" required>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=pegawai" class="btn btn-secondary">
                        ⬅ Kembali
                    </a>
                    <button type="submit" name="simpan" class="btn btn-success">
                        💾 Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
