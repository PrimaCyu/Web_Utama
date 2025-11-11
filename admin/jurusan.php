<?php
include "koneksi.php";

// Cek apakah ada pencarian
$cari = "";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = $_GET['cari'];
    $result = mysqli_query($koneksi, "SELECT * FROM jurusan 
                                      WHERE nama_jurusan LIKE '%$cari%' 
                                         OR singkatan LIKE '%$cari%' 
                                      ORDER BY id_jurusan DESC");
} else {
    $result = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY id_jurusan DESC");
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">🏫 Data Jurusan</h2>
        <a href="jurusan_tambah.php" class="btn btn-success">
            ➕ Tambah Jurusan
        </a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php
        $pesan = $_GET['pesan'];
        $text = "";
        if ($pesan == 'tambah') $text = "✅ Data jurusan berhasil ditambahkan!";
        elseif ($pesan == 'edit') $text = "✏️ Data jurusan berhasil diperbarui!";
        elseif ($pesan == 'hapus') $text = "🗑️ Data jurusan berhasil dihapus!";
        ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?= $text ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <form class="d-flex mb-3" method="get">
        <input type="hidden" name="page" value="jurusan">
        <input class="form-control me-2" type="search" name="cari" placeholder="🔍 Cari jurusan..." value="<?= htmlspecialchars($cari) ?>">
        <button class="btn btn-outline-primary" type="submit">Cari</button>
    </form>

    <!-- Tabel Data -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Jurusan</th>
                    <th>Singkatan</th>
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
                            <td><?= htmlspecialchars($row['nama_jurusan']) ?></td>
                            <td><?= htmlspecialchars($row['singkatan']) ?></td>
                            <td>
                                <a href="jurusan_edit.php?id=<?= $row['id_jurusan'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="jurusan_hapus.php?id=<?= $row['id_jurusan'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus jurusan ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">⚠️ Data tidak ditemukan</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
