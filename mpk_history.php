<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'mpk') {
    header('Location: login.php');
    exit();
}


$koneksi = mysqli_connect("localhost", "root", "", "smsr_jaya");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Histori Absensi - MPK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1><i class="bi bi-clock-history"></i> Histori Absensi MPK</h1>
        <p>Riwayat absensi kelas <?= $_SESSION['kelas_nama'] ?> 📊</p>
    </header>

    <nav>
        <a href="mpk_dashboard.php"><i class="bi bi-house"></i> Dashboard</a>
        <a href="mpk_history.php" class="active"><i class="bi bi-clock-history"></i> Histori</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>

    <main class="fade-in">
        <div class="container-fluid">
            <!-- Filter Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-funnel"></i> Filter Data</h5>
                </div>
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Bulan</label>
                                    <select name="month" class="form-select" onchange="this.form.submit()">
                                        <?php
                                        $months = [
                                            '01' => 'Januari',
                                            '02' => 'Februari',
                                            '03' => 'Maret',
                                            '04' => 'April',
                                            '05' => 'Mei',
                                            '06' => 'Juni',
                                            '07' => 'Juli',
                                            '08' => 'Agustus',
                                            '09' => 'September',
                                            '10' => 'Oktober',
                                            '11' => 'November',
                                            '12' => 'Desember'
                                        ];
                                        $selected_month = $_GET['month'] ?? date('m');
                                        foreach ($months as $num => $name) {
                                            $selected = $selected_month == $num ? 'selected' : '';
                                            echo "<option value='$num' $selected>$name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tahun</label>
                                    <select name="year" class="form-select" onchange="this.form.submit()">
                                        <?php
                                        $current_year = date('Y');
                                        for ($year = $current_year; $year >= 2020; $year--) {
                                            $selected = ($_GET['year'] ?? $current_year) == $year ? 'selected' : '';
                                            echo "<option value='$year' $selected>$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Siswa</label>
                                    <select name="siswa" class="form-select" onchange="this.form.submit()">
                                        <option value="">Semua Siswa</option>
                                        <?php
                                        $siswa_query = "SELECT * FROM siswa WHERE id_kelas = '{$_SESSION['id_kelas']}' ORDER BY no_absen";
                                        $siswa_result = mysqli_query($koneksi, $siswa_query);
                                        while ($siswa = mysqli_fetch_assoc($siswa_result)) {
                                            $selected = (isset($_GET['siswa']) && $_GET['siswa'] == $siswa['id_siswa']) ? 'selected' : '';
                                            echo "<option value='{$siswa['id_siswa']}' $selected>{$siswa['no_absen']}. {$siswa['nama_siswa']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            
            $month = $_GET['month'] ?? date('m');
            $year = $_GET['year'] ?? date('Y');
            $siswa_filter = $_GET['siswa'] ?? '';

            $where_conditions = ["s.id_kelas = '{$_SESSION['id_kelas']}'"];

            if (!empty($siswa_filter)) {
                $where_conditions[] = "s.id_siswa = '" . mysqli_real_escape_string($koneksi, $siswa_filter) . "'";
            }

            $where_conditions[] = "MONTH(a.tanggal_absensi) = '$month'";
            $where_conditions[] = "YEAR(a.tanggal_absensi) = '$year'";

            $where_sql = "WHERE " . implode(" AND ", $where_conditions);

            $query = "SELECT a.*, s.nama_siswa, s.nis, s.no_absen,
                             DATE_FORMAT(a.tanggal_absensi, '%d/%m/%Y') as tanggal_format
                      FROM absensi a
                      JOIN siswa s ON a.id_siswa = s.id_siswa
                      $where_sql
                      ORDER BY s.no_absen ASC, a.tanggal_absensi DESC";

            $result = mysqli_query($koneksi, $query);
            $total_records = mysqli_num_rows($result);
            ?>


            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check"></i> Histori Absensi
                        <?= $months[$month] ?> <?= $year ?>
                        <?php if ($total_records > 0): ?>
                            <span class="badge bg-primary ms-2"><?= $total_records ?> records</span>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($total_records > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Siswa</th>
                                        <th>No Absen</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status_badge = match ($row['status']) {
                                            'H' => 'success',
                                            'I' => 'info',
                                            'S' => 'warning',
                                            'A' => 'danger',
                                            default => 'secondary'
                                        };
                                        $status_text = match ($row['status']) {
                                            'H' => 'Hadir',
                                            'I' => 'Izin',
                                            'S' => 'Sakit',
                                            'A' => 'Alpha',
                                            default => 'Tidak Hadir'
                                        };
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $row['tanggal_format'] ?></td>
                                            <td><?= $row['nama_siswa'] ?></td>
                                            <td><?= $row['no_absen'] ?></td>
                                            <td>
                                                <span class="badge bg-<?= $status_badge ?>">
                                                    <?= $status_text ?>
                                                </span>
                                            </td>
                                            <td><?= $row['keterangan'] ?: '-' ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Tidak ada data absensi</h5>
                            <p class="text-muted">Tidak ada absensi untuk periode yang dipilih</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Rekap Bulanan -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5><i class="bi bi-graph-up"></i> Rekap Bulanan</h5>
                </div>
                <div class="card-body">
                    <?php
                    $rekap_query = "SELECT 
                        s.id_siswa, s.nama_siswa, s.no_absen,
                        COUNT(a.id_absensi) as total_hari,
                        SUM(CASE WHEN a.status = 'H' THEN 1 ELSE 0 END) as hadir,
                        SUM(CASE WHEN a.status = 'I' THEN 1 ELSE 0 END) as izin,
                        SUM(CASE WHEN a.status = 'S' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN a.status = 'A' THEN 1 ELSE 0 END) as alpha
                        FROM siswa s
                        LEFT JOIN absensi a ON s.id_siswa = a.id_siswa 
                            AND MONTH(a.tanggal_absensi) = '$month' 
                            AND YEAR(a.tanggal_absensi) = '$year'
                        WHERE s.id_kelas = '{$_SESSION['id_kelas']}'
                        GROUP BY s.id_siswa
                        ORDER BY s.no_absen";

                    $rekap_result = mysqli_query($koneksi, $rekap_query);
                    ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Hadir</th>
                                    <th>Izin</th>
                                    <th>Sakit</th>
                                    <th>Alpha</th>
                                    <th>Total</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no_rekap = 1;
                                while ($rekap = mysqli_fetch_assoc($rekap_result)) {
                                    $total = $rekap['hadir'] + $rekap['izin'] + $rekap['sakit'] + $rekap['alpha'];
                                    $persentase = $total > 0 ? round(($rekap['hadir'] / $total) * 100, 1) : 0;
                                    $persentase_class = $persentase >= 80 ? 'text-success' : ($persentase >= 60 ? 'text-warning' : 'text-danger');
                                ?>
                                    <tr>
                                        <td><?= $no_rekap++ ?></td>
                                        <td><?= $rekap['nama_siswa'] ?></td>
                                        <td class="text-success"><?= $rekap['hadir'] ?></td>
                                        <td class="text-info"><?= $rekap['izin'] ?></td>
                                        <td class="text-warning"><?= $rekap['sakit'] ?></td>
                                        <td class="text-danger"><?= $rekap['alpha'] ?></td>
                                        <td><strong><?= $total ?></strong></td>
                                        <td class="<?= $persentase_class ?>">
                                            <strong><?= $persentase ?>%</strong>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>© <?= date('Y') ?> Sistem Absensi MPK. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>