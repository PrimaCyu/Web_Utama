<?php
include "koneksi.php";

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: index.php?page=pegawai");
    exit;
}

$id = intval($_GET['id']); // pastikan ID berupa angka

// Ambil data pegawai
$stmt = mysqli_prepare($koneksi, "SELECT * FROM pegawai WHERE id_pegawai = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$data) {
    echo "<div class='alert alert-danger text-center mt-5'>⚠️ Data pegawai tidak ditemukan.</div>";
    exit;
}

// Proses update
if (isset($_POST['update'])) {
    $nama_pegawai  = $_POST['nama_pegawai'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat        = $_POST['alamat'];
    $telepon       = $_POST['telepon'];
    $username      = $_POST['username'];
    $password      = $_POST['password'];

    if (!empty($password)) {
        // Jika password diisi, update semuanya termasuk password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $update = mysqli_prepare($koneksi, "UPDATE pegawai SET nama_pegawai=?, tanggal_lahir=?, alamat=?, telepon=?, username=?, password=? WHERE id_pegawai=?");
        mysqli_stmt_bind_param($update, "ssssssi", $nama_pegawai, $tanggal_lahir, $alamat, $telepon, $username, $password_hash, $id);
    } else {
        // Jika password kosong, tidak diubah
        $update = mysqli_prepare($koneksi, "UPDATE pegawai SET nama_pegawai=?, tanggal_lahir=?, alamat=?, telepon=?, username=? WHERE id_pegawai=?");
        mysqli_stmt_bind_param($update, "sssssi", $nama_pegawai, $tanggal_lahir, $alamat, $telepon, $username, $id);
    }

    mysqli_stmt_execute($update);
    header("Location: index.php?page=pegawai&pesan=edit");
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-primary text-white fw-bold">
            ✏️ Edit Data Pegawai
        </div>
        <div class="card-body">
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Nama Pegawai</label>
                    <input type="text" name="nama_pegawai" class="form-control" value="<?= htmlspecialchars($data['nama_pegawai']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($data['tanggal_lahir']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($data['alamat']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($data['telepon']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru (kosongkan jika tidak diganti)</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?page=pegawai" class="btn btn-secondary">← Kembali</a>
                    <button type="submit" name="update" class="btn btn-success">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
