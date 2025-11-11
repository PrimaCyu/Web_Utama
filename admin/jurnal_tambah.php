<?php
include "koneksi.php";


if (isset($_POST['simpan'])) {
    $id_guru = $_POST['id_guru'];
    $id_kelas = $_POST['id_kelas'];
    $tanggal_mengajar = $_POST['tanggal_mengajar'];
    $materi = $_POST['materi'];
    $keterangan = $_POST['keterangan'];

    $query = "INSERT INTO jurnal (id_guru, id_kelas, tanggal_mengajar, materi, keterangan)
              VALUES ('$id_guru', '$id_kelas', '$tanggal_mengajar', '$materi', '$keterangan')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: index.php?page=jurnal&pesan=tambah");
        exit;
    } else {
        echo "<script>alert('Gagal menambah data jurnal!');</script>";
    }
}

// Ambil data guru & kelas untuk dropdown
$guru = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY nama_guru ASC");
$kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-journal-plus"></i> Tambah Data Jurnal</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Guru</label>
                        <select name="id_guru" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php while ($g = mysqli_fetch_assoc($guru)) { ?>
                                <option value="<?= $g['id_guru'] ?>"><?= $g['nama_guru'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Kelas</label>
                        <select name="id_kelas" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                <option value="<?= $k['id_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Mengajar</label>
                        <input type="date" name="tanggal_mengajar" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Materi</label>
                        <input type="text" name="materi" class="form-control" placeholder="Masukkan materi yang diajarkan" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan..."></textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <a href="index.php?page=jurnal" class="btn btn-secondary">⬅ Kembali</a>
                    <button type="submit" name="simpan" class="btn btn-success">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>