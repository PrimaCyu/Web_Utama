<?php
include "koneksi.php";
if (!isset($_GET['id'])) {
    header("Location: index.php?page=jurusan");
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM jurusan WHERE id_jurusan=$id"));

if (isset($_POST['update'])) {
    $nama_jurusan = $_POST['nama_jurusan'];
    $singkatan    = $_POST['singkatan'];

    mysqli_query($koneksi, "UPDATE jurusan SET nama_jurusan='$nama_jurusan', singkatan='$singkatan' WHERE id_jurusan=$id");
    header("Location: index.php?page=jurusan&pesan=edit");
    exit;
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h2 class="mb-4">Edit Jurusan</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nama Jurusan</label>
            <input type="text" name="nama_jurusan" class="form-control" value="<?= $data['nama_jurusan'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Singkatan</label>
            <input type="text" name="singkatan" maxlength="5" class="form-control" value="<?= $data['singkatan'] ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="index.php?page=jurusan" class="btn btn-secondary">Kembali</a>
    </form>
</div>