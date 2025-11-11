<?php
include "koneksi.php";
$jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan");
$guru = mysqli_query($koneksi, "SELECT * FROM guru");

if (!isset($_GET['id'])) {
    header("Location: index.php?page=kelas");
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas=$id"));

if (isset($_POST['update'])) {
    $nama_kelas = $_POST['nama_kelas'];
    $id_jurusan = $_POST['id_jurusan'];
    $id_guru = $_POST['id_guru'];

    mysqli_query($koneksi, "UPDATE kelas SET nama_kelas='$nama_kelas', id_jurusan='$id_jurusan', id_guru = '$id_guru' WHERE id_kelas=$id");
    header("Location: index.php?page=kelas&pesan=edit");
    exit;
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h2 class="mb-4">Edit Kelas</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jurusan</label>
            <select name="id_jurusan" class="form-control" required>
                <option value="">-- Pilih Jurusan --</option>
                <?php while ($j = mysqli_fetch_assoc($jurusan)) { ?>
                    <option value="<?= $j['id_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Guru</label>
            <select name="id_guru" class="form-control" required>
                <option value="">-- Pilih Guru --</option>
                <?php while ($g = mysqli_fetch_assoc($guru)) { ?>
                    <option value="<?= $g['id_guru'] ?>"><?= $g['nama_guru'] ?></option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="index.php?page=kelas" class="btn btn-secondary">Kembali</a>
    </form>
</div>