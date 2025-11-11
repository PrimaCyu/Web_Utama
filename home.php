<?php
session_start();
// Redirect jika belum login
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Query untuk statistik
$total_guru = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM guru")->fetch_assoc()['total'];
$total_siswa = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa")->fetch_assoc()['total'];
$total_kelas = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kelas")->fetch_assoc()['total'];
$total_pegawai = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pegawai")->fetch_assoc()['total'];
$total_mpk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mpk")->fetch_assoc()['total'];
?>

<div class="container-fluid">
    <!-- Welcome Message -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="text-gradient mb-2">Selamat Datang, <?= $_SESSION['username'] ?>! 👋</h3>
                    <p class="text-muted mb-0">Anda login sebagai <strong>Administrator</strong></p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="badge bg-primary fs-6">
                        <i class="bi bi-calendar"></i> <?= date('d F Y') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-md-2 col-sm-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-primary mb-3">
                        <i class="bi bi-person-check fs-1"></i>
                    </div>
                    <h3 class="card-title"><?= number_format($total_guru) ?></h3>
                    <p class="card-text text-muted">Total Guru</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-success mb-3">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                    <h3 class="card-title"><?= number_format($total_siswa) ?></h3>
                    <p class="card-text text-muted">Total Siswa</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-warning mb-3">
                        <i class="bi bi-house-door fs-1"></i>
                    </div>
                    <h3 class="card-title"><?= number_format($total_kelas) ?></h3>
                    <p class="card-text text-muted">Total Kelas</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-info mb-3">
                        <i class="bi bi-person-badge fs-1"></i>
                    </div>
                    <h3 class="card-title"><?= number_format($total_pegawai) ?></h3>
                    <p class="card-text text-muted">Total Pegawai</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-danger mb-3">
                        <i class="bi bi-person-gear fs-1"></i>
                    </div>
                    <h3 class="card-title"><?= number_format($total_mpk) ?></h3>
                    <p class="card-text text-muted">Pengurus MPK</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="text-secondary mb-3">
                        <i class="bi bi-journal-text fs-1"></i>
                    </div>
                    <h3 class="card-title">
                        <?= mysqli_query($koneksi, "SELECT COUNT(*) as total FROM absensi WHERE tanggal_absensi = CURDATE()")->fetch_assoc()['total'] ?>
                    </h3>
                    <p class="card-text text-muted">Absen Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4><i class="bi bi-lightning"></i> Akses Cepat</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="index.php?page=mpk" class="btn btn-outline-primary text-start">
                            <i class="bi bi-calendar-check"></i> Kelola Absensi MPK
                        </a>
                        <a href="index.php?page=guru" class="btn btn-outline-success text-start">
                            <i class="bi bi-person"></i> Data Guru
                        </a>
                        <a href="index.php?page=siswa" class="btn btn-outline-info text-start">
                            <i class="bi bi-people"></i> Data Siswa
                        </a>
                        <a href="index.php?page=jurnal" class="btn btn-outline-warning text-start">
                            <i class="bi bi-journal-text"></i> Jurnal Mengajar
                        </a>
                        <a href="index.php?page=kelas" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-house-door"></i> Data Kelas
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4><i class="bi bi-graph-up"></i> Aktivitas Terbaru</h4>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php
                        $recent_absensi = mysqli_query(
                            $koneksi,
                            "SELECT a.*, s.nama_siswa, k.nama_kelas 
                             FROM absensi a 
                             JOIN siswa s ON a.id_siswa = s.id_siswa 
                             JOIN kelas k ON s.id_kelas = k.id_kelas 
                             ORDER BY a.tanggal_absensi DESC, a.id_absensi DESC 
                             LIMIT 5"
                        );

                        if (mysqli_num_rows($recent_absensi) > 0) {
                            while ($absen = mysqli_fetch_assoc($recent_absensi)) {
                                $status_badge = match ($absen['status']) {
                                    'H' => 'success',
                                    'I' => 'info',
                                    'S' => 'warning',
                                    'A' => 'danger',
                                    default => 'secondary'
                                };
                                $status_text = match ($absen['status']) {
                                    'H' => 'Hadir',
                                    'I' => 'Izin',
                                    'S' => 'Sakit',
                                    'A' => 'Alpha',
                                    default => 'Tidak Hadir'
                                };
                        ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= $absen['nama_siswa'] ?></h6>
                                        <small class="text-muted"><?= $absen['nama_kelas'] ?> • <?= date('d/m/Y', strtotime($absen['tanggal_absensi'])) ?></small>
                                    </div>
                                    <span class="badge bg-<?= $status_badge ?>"><?= $status_text ?></span>
                                </div>
                        <?php
                            }
                        } else {
                            echo '<div class="text-center py-3 text-muted">
                                    <i class="bi bi-inbox"></i><br>
                                    Belum ada aktivitas absensi
                                  </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-gradient {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>