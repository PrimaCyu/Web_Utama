<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nama_siswa = $_POST['nama_siswa'];
    $no_absen   = $_POST['no_absen'];
    $tgl_lahir  = $_POST['tgl_lahir'];
    $alamat     = $_POST['alamat'];
    $telepon    = $_POST['telepon'];
    $id_kelas   = $_POST['id_kelas'];
    $nis        = $_POST['nis'];
    $nisn       = $_POST['nisn'];

    $query = "INSERT INTO siswa (nama_siswa, no_absen, tgl_lahir, alamat, telepon, id_kelas, nis, nisn) 
              VALUES ('$nama_siswa', '$no_absen', '$tgl_lahir', '$alamat', '$telepon', '$id_kelas', '$nis', '$nisn')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=siswa&pesan=tambah");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambah data: " . mysqli_error($koneksi) . "</div>";
    }
}

$kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-people-fill"></i> Tambah Siswa</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" name="nama_siswa" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">No Absen</label>
                        <input type="number" name="no_absen" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kelas</label>
                        <select name="id_kelas" class="form-select" required>
                            <option value="">Pilih Kelas</option>
                            <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                <option value="<?= $k['id_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" class="form-control" required>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-start">
                    <button type="submit" name="simpan" class="btn btn-success me-2">💾 Simpan</button>
                    <a href="index.php?page=siswa" class="btn btn-secondary">⬅ Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>