<?php
include "koneksi.php";

// Cek apakah ada pencarian
$cari = "";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = $_GET['cari'];
    $result = mysqli_query($koneksi, "
        SELECT * FROM pembayaran
        JOIN siswa ON pembayaran.id_siswa = siswa.id_siswa
        JOIN pegawai ON pembayaran.id_pegawai = pegawai.id_pegawai
        WHERE metode LIKE '%$cari%'
        ORDER BY id_pembayaran DESC
    ");
} else {
    $result = mysqli_query($koneksi, "
        SELECT * FROM pembayaran
        JOIN siswa ON pembayaran.id_siswa = siswa.id_siswa
        JOIN pegawai ON pembayaran.id_pegawai = pegawai.id_pegawai
        ORDER BY id_pembayaran DESC
    ");
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <!-- Header dan tombol tambah -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">💰 Data Pembayaran</h2>
        <a href="pembayaran_tambah.php" class="btn btn-success">➕ Tambah Pembayaran</a>
    </div>


    <?php if (isset($_GET['pesan'])): ?>
        <?php
        $pesan = $_GET['pesan'];
        $text = "";
        if ($pesan == 'tambah') $text = "✅ Data pembayaran berhasil ditambahkan!";
        elseif ($pesan == 'edit') $text = "✏️ Data pembayaran berhasil diperbarui!";
        elseif ($pesan == 'hapus') $text = "🗑️ Data pembayaran berhasil dihapus!";
        ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?= $text ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <form class="d-flex mb-3" method="get">
        <input type="hidden" name="page" value="pembayaran">
        <input class="form-control me-2" type="search" name="cari" placeholder="🔍 Cari metode pembayaran..." value="<?= htmlspecialchars($cari) ?>">
        <button class="btn btn-outline-primary" type="submit">Cari</button>
    </form>

    <!-- Tabel Data -->
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-striped table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>Tanggal Pembayaran</th>
                    <th>Nama Siswa</th>
                    <th>Nominal</th>
                    <th>Metode</th>
                    <th>Bulan</th>
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
                            <td><?= htmlspecialchars($row['nama_pegawai']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_pembayaran']) ?></td>
                            <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                            <td><?= htmlspecialchars($row['nominal']) ?></td>
                            <td><?= htmlspecialchars($row['metode']) ?></td>
                            <td><?= htmlspecialchars($row['bulan']) ?></td>
                            <td>
                                <a href="pembayaran_hapus.php?id=<?= $row['id_pembayaran'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus pembayaran ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">⚠️ Data tidak ditemukan</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>