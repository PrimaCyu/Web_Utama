<?php
include "koneksi.php";

// Inisialisasi variabel pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : "";

// Query utama
if (!empty($cari)) {
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM pegawai WHERE nama_pegawai LIKE CONCAT('%', ?, '%') ORDER BY id_pegawai DESC");
    mysqli_stmt_bind_param($stmt, "s", $cari);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($koneksi, "SELECT * FROM pegawai ORDER BY id_pegawai DESC");
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">📋 Data Pegawai</h2>
        <a href="pegawai_tambah.php" class="btn btn-success">
            ➕ Tambah Pegawai
        </a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['pesan'])): ?>
        <?php
        $pesan = $_GET['pesan'];
        $text = "";
        if ($pesan == 'tambah') $text = "✅ Data pegawai berhasil ditambahkan!";
        elseif ($pesan == 'edit') $text = "✏️ Data pegawai berhasil diperbarui!";
        elseif ($pesan == 'hapus') $text = "🗑️ Data pegawai berhasil dihapus!";
        ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?= $text ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <form class="d-flex mb-3" method="get">
        <input type="hidden" name="page" value="pegawai">
        <input class="form-control me-2" type="search" name="cari" placeholder="🔍 Cari pegawai..." value="<?= htmlspecialchars($cari) ?>">
        <button class="btn btn-outline-primary" type="submit">Cari</button>
    </form>

    <!-- Tabel Data -->
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Pegawai</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Username</th>
                    <th>Password (Hash)</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_pegawai']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['telepon']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>
                                <span class="text-muted small fst-italic">
                                    (disembunyikan demi keamanan)
                                </span>
                            </td>
                            <td>
                                <a href="pegawai_edit.php?id=<?= $row['id_pegawai'] ?>" class="btn btn-sm btn-warning me-1">
                                    ✏️ Edit
                                </a>
                                <a href="pegawai_hapus.php?id=<?= $row['id_pegawai'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus data pegawai ini?')">
                                   🗑️ Hapus
                                </a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="8" class="text-muted text-center py-3">
                            ⚠️ Tidak ada data pegawai ditemukan.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Auto close alert setelah 3 detik
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 3000);
</script>
