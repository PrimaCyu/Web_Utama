<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_kelas = $_POST['id_kelas'];
    $id_siswa = $_POST['id_siswa'];

    $query = "INSERT INTO mpk (username, password, id_kelas, id_siswa) 
              VALUES ('$username', '$password', '$id_kelas', '$id_siswa')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=mpk&pesan=tambah");
        exit;
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>Gagal menambah data: " . mysqli_error($koneksi) . "</div>";
    }
}


$kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$siswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY nama_siswa ASC");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Tambah Data MPK</h2>

    <form method="post" class="shadow p-4 rounded bg-light">
        <div class="container mt-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Tambah MPK</h4>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Username MPK</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan username MPK" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password default" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <select name="id_kelas" class="form-select" required>
                                    <option value="">Pilih Kelas</option>
                                    <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                        <option value="<?= $k['id_kelas'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
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
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="index.php?page=mpk" class="btn btn-secondary">⬅ Kembali</a>
                            <button type="submit" name="simpan" class="btn btn-success">💾 Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>