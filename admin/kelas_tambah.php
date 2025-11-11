<?php
include "koneksi.php";
$jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY nama_jurusan ASC");
$guru = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY nama_guru ASC");

if (isset($_POST['simpan'])) {
    $nama_kelas = $_POST['nama_kelas'];
    $id_jurusan = $_POST['id_jurusan'];
    $id_guru = $_POST['id_guru'];

    $query = "INSERT INTO kelas (nama_kelas, id_jurusan, id_guru) VALUES ('$nama_kelas', '$id_jurusan', '$id_guru')";
    mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

    header("Location: index.php?page=kelas&pesan=tambah");
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-building-fill"></i> Tambah Kelas</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jurusan</label>
                        <select name="id_jurusan" class="form-select" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <?php while ($j = mysqli_fetch_assoc($jurusan)) { ?>
                                <option value="<?= $j['id_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Guru</label>
                        <select name="id_guru" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php while ($g = mysqli_fetch_assoc($guru)) { ?>
                                <option value="<?= $g['id_guru'] ?>"><?= $g['nama_guru'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-start">
                    <button type="submit" name="simpan" class="btn btn-success me-2">💾 Simpan</button>
                    <a href="index.php?page=kelas" class="btn btn-secondary">⬅ Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>