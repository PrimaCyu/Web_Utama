<?php
include "koneksi.php";


$cari = "";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = $_GET['cari'];
    $result = mysqli_query($koneksi, "SELECT * FROM mpk
                                      JOIN kelas ON mpk.id_kelas = kelas.id_kelas
                                      JOIN siswa ON mpk.id_siswa = siswa.id_siswa
                                      WHERE siswa.username LIKE '%$cari%'
                                      ORDER BY id_mpk DESC");
} else {
    $result = mysqli_query($koneksi, "SELECT * FROM mpk
                                      JOIN kelas ON mpk.id_kelas = kelas.id_kelas
                                      JOIN siswa ON mpk.id_siswa = siswa.id_siswa
                                      ORDER BY id_mpk DESC");
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">🏛️ Data MPK</h2>
        <a href="mpk_tambah.php" class="btn btn-success">➕ Tambah MPK</a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php
        $pesan = $_GET['pesan'];
        $text = "";
        if ($pesan == 'tambah') $text = "✅ Data MPK berhasil ditambahkan!";
        elseif ($pesan == 'edit') $text = "✏️ Data MPK berhasil diperbarui!";
        elseif ($pesan == 'hapus') $text = "🗑️ Data MPK berhasil dihapus!";
        ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?= $text ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <form class="d-flex mb-3" method="get">
        <input type="hidden" name="page" value="mpk">
        <input class="form-control me-2" type="search" name="cari" placeholder="🔍 Cari nama MPK..." value="<?= htmlspecialchars($cari) ?>">
        <button class="btn btn-outline-primary" type="submit">Cari</button>
    </form>


    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa (MPK)</th>
                    <th>Kelas</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['password']) ?></td>
                            <td>
                                <a href="mpk_edit.php?id=<?= $row['id_mpk'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="mpk_hapus.php?id=<?= $row['id_mpk'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus data MPK ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">⚠️ Data tidak ditemukan</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>