<?php
include "koneksi.php";

// Cek apakah ada pencarian
$cari = "";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = $_GET['cari'];
    $result = mysqli_query($koneksi, "SELECT * FROM jurnal
                                      JOIN guru ON jurnal.id_guru = guru.id_guru
                                      JOIN kelas ON jurnal.id_kelas = kelas.id_kelas
                                      WHERE jurnal.materi LIKE '%$cari%' 
                                      ORDER BY id_jurnal DESC");
} else {
    $result = mysqli_query($koneksi, "SELECT * FROM jurnal
                                      JOIN guru ON jurnal.id_guru = guru.id_guru
                                      JOIN kelas ON jurnal.id_kelas = kelas.id_kelas
                                      ORDER BY id_jurnal DESC");
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">📘 Data Jurnal</h2>
        <a href="jurnal_tambah.php" class="btn btn-success">
            ➕ Tambah Jurnal
        </a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php
        $pesan = $_GET['pesan'];
        $text = "";
        if ($pesan == 'tambah') $text = "✅ Data jurnal berhasil ditambahkan!";
        elseif ($pesan == 'edit') $text = "✏️ Data jurnal berhasil diperbarui!";
        elseif ($pesan == 'hapus') $text = "🗑️ Data jurnal berhasil dihapus!";
        ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?= $text ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <form class="d-flex mb-3" method="get">
        <input type="hidden" name="page" value="jurnal">
        <input class="form-control me-2" type="search" name="cari" placeholder="🔍 Cari jurnal..." value="<?= htmlspecialchars($cari) ?>">
        <button class="btn btn-outline-primary" type="submit">Cari</button>
    </form>

    <!-- Tabel Data -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Guru</th>
                    <th>Kelas</th>
                    <th>Tanggal Mengajar</th>
                    <th>Materi</th>
                    <th>Keterangan</th>
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
                            <td><?= htmlspecialchars($row['nama_guru']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_mengajar']) ?></td>
                            <td><?= htmlspecialchars($row['materi']) ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td>
                                <a href="jurnal_edit.php?id=<?= $row['id_jurnal'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="jurnal_hapus.php?id=<?= $row['id_jurnal'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus jurnal ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">⚠️ Data tidak ditemukan</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
