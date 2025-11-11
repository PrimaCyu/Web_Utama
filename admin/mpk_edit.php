<?php
include "koneksi.php";
if (!isset($_GET['id'])) {
    header("Location: index.php?page=mpk");
    exit;
}

$id = $_GET['id'];


$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mpk WHERE id_mpk=$id"));

if (!$data) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
    exit;
}


if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $id_kelas = $_POST['id_kelas'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "UPDATE mpk 
              SET id_kelas='$id_kelas', username='$username', password='$password' 
              WHERE id_mpk=$id";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=mpk&pesan=edit");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal mengupdate data: " . mysqli_error($koneksi) . "</div>";
    }
}

// Ambil daftar kelas untuk dropdown
$kelas = mysqli_query($koneksi, "SELECT * FROM kelas");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h2 class="mb-4">Edit Data MPK</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nama MPK</label>
            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($data['username']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="id_kelas" class="form-control" required>
                <option value="">Pilih Kelas</option>
                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                    <option value="<?= $k['id_kelas'] ?>" <?= $data['id_kelas'] == $k['id_kelas'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kelas']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="text" name="password" class="form-control" required>
        </div>

        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="index.php?page=mpk" class="btn btn-secondary">Kembali</a>
    </form>
</div>
