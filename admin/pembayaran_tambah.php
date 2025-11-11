<?php
include "koneksi.php";
if (isset($_POST['simpan'])) {
    $tanggal_pembayaran = $_POST['tanggal_pembayaran'];
    $bulan = $_POST['bulan'];
    $nominal = $_POST['nominal'];
    $metode = $_POST['metode'];
    $id_pegawai = $_POST['id_pegawai'];
    $id_siswa = $_POST['id_siswa'];

    $query = "INSERT INTO pembayaran ( tanggal_pembayaran, bulan, nominal, metode, id_pegawai, id_siswa) 
              VALUES ('$tanggal_pembayaran', '$bulan', '$nominal', '$metode', '$id_pegawai', '$id_siswa')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=pembayaran&pesan=tambah");
        exit;
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>Gagal menambah data: " . mysqli_error($koneksi) . "</div>";
    }
}


$pegawai = mysqli_query($koneksi, "SELECT * FROM pegawai ORDER BY nama_pegawai ASC");
$siswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY nama_siswa ASC");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Tambah Data Pembayaran</h2>

    <form method="post" class="shadow p-4 rounded bg-light">

        <div class="container mt-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-cash-stack"></i> Tambah Pembayaran</h4>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Pegawai</label>
                                <select name="id_pegawai" class="form-select" required>
                                    <option value="">Pilih Pegawai</option>
                                    <?php while ($k = mysqli_fetch_assoc($pegawai)) { ?>
                                        <option value="<?= $k['id_pegawai'] ?>"><?= htmlspecialchars($k['nama_pegawai']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Siswa</label>
                                <select name="id_siswa" class="form-select" required>
                                    <option value="">Pilih Siswa</option>
                                    <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                        <option value="<?= $s['id_siswa'] ?>"><?= htmlspecialchars($s['nama_siswa']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Pembayaran</label>
                                <input type="date" name="tanggal_pembayaran" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bulan</label>
                                <input type="number" name="bulan" class="form-control" placeholder="1–12" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nominal</label>
                                <input type="number" name="nominal" class="form-control" placeholder="100000" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Metode</label>
                                <input type="text" name="metode" class="form-control" placeholder="Transfer / Tunai" required>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="index.php?page=pembayaran" class="btn btn-secondary">⬅ Kembali</a>
                            <button type="submit" name="simpan" class="btn btn-success">💾 Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>