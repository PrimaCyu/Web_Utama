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


function getSiswaByKelas($koneksi, $id_kelas)
{
    $query = "SELECT s.*, k.nama_kelas 
              FROM siswa s 
              LEFT JOIN kelas k ON s.id_kelas = k.id_kelas 
              WHERE s.id_kelas = '$id_kelas' 
              ORDER BY s.no_absen";
    return mysqli_query($koneksi, $query);
}


function getAbsensiHariIni($koneksi, $id_siswa)
{
    $query = "SELECT * FROM absensi 
              WHERE id_siswa = '$id_siswa' 
              AND tanggal_absensi = CURDATE() 
              LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    return mysqli_fetch_assoc($result);
}


function simpanAbsensi($koneksi, $data)
{
    $id_siswa = $data['id_siswa'];
    $status = $data['status'];
    $keterangan = $data['keterangan'];

    
    $cek = mysqli_query(
        $koneksi,
        "SELECT * FROM absensi 
         WHERE id_siswa = '$id_siswa' 
         AND tanggal_absensi = CURDATE()"
    );

    if (mysqli_num_rows($cek) > 0) {
        
        $query = "UPDATE absensi SET 
                  status = '$status', 
                  keterangan = '$keterangan'
                  WHERE id_siswa = '$id_siswa' 
                  AND tanggal_absensi = CURDATE()";
    } else {
        
        $query = "INSERT INTO absensi 
                  (id_siswa, tanggal_absensi, status, keterangan) 
                  VALUES 
                  ('$id_siswa', CURDATE(), '$status', '$keterangan')";
    }

    return mysqli_query($koneksi, $query);
}


if (isset($_POST['simpan_absen'])) {
    $success_count = 0;
    foreach ($_POST['absensi'] as $id_siswa => $data) {
        $data_absen = [
            'id_siswa' => $id_siswa,
            'status' => $data['status'],
            'keterangan' => $data['keterangan']
        ];
        if (simpanAbsensi($koneksi, $data_absen)) {
            $success_count++;
        }
    }
    $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <i class='bi bi-check-circle'></i> Absensi berhasil disimpan untuk $success_count siswa!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard MPK - Sistem Absensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1><i class="bi bi-person-check"></i> Dashboard MPK</h1>
        <p>Selamat datang, <?= $_SESSION['username'] ?> - Kelas <?= $_SESSION['kelas_nama'] ?> 🎯</p>
    </header>

    <nav>
        <a href="mpk_dashboard.php" class="active"><i class="bi bi-house"></i> Dashboard</a>
        <a href="mpk_history.php" class="active"><i class="bi bi-clock-history"></i> Histori</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>

    <main class="fade-in">
        <div class="container-fluid">
            <?= $message ?? '' ?>

           
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-gradient mb-2">Absensi Harian</h4>
                            <p class="text-muted mb-0">Kelas <?= $_SESSION['kelas_nama'] ?> - <?= date('d F Y') ?></p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="badge bg-primary fs-6">
                                <i class="bi bi-people"></i>
                                <?= mysqli_num_rows(getSiswaByKelas($koneksi, $_SESSION['id_kelas'])) ?> Siswa
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Daftar Absensi Siswa</h5>
                </div>
                <form method="POST">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">No Absen</th>
                                        <th width="15%">NIS</th>
                                        <th width="30%">Nama Siswa</th>
                                        <th width="15%">Kelas</th>
                                        <th width="15%">Status</th>
                                        <th width="20%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $siswa_result = getSiswaByKelas($koneksi, $_SESSION['id_kelas']);
                                    $no = 1;
                                    if (mysqli_num_rows($siswa_result) > 0) {
                                        while ($siswa = mysqli_fetch_assoc($siswa_result)) {
                                            $absensi = getAbsensiHariIni($koneksi, $siswa['id_siswa']);
                                    ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $siswa['no_absen'] ?></td>
                                                <td><?= $siswa['nis'] ?></td>
                                                <td><?= $siswa['nama_siswa'] ?></td>
                                                <td><?= $siswa['nama_kelas'] ?? '-' ?></td>
                                                <td>
                                                    <select name="absensi[<?= $siswa['id_siswa'] ?>][status]"
                                                        class="form-select status-select"
                                                        onchange="updateRowColor(this)">
                                                        <option value="H" <?= ($absensi['status'] ?? '') == 'H' ? 'selected' : '' ?>>Hadir</option>
                                                        <option value="I" <?= ($absensi['status'] ?? '') == 'I' ? 'selected' : '' ?>>Izin</option>
                                                        <option value="S" <?= ($absensi['status'] ?? '') == 'S' ? 'selected' : '' ?>>Sakit</option>
                                                        <option value="A" <?= ($absensi['status'] ?? '') == 'A' ? 'selected' : '' ?>>Alpha</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="absensi[<?= $siswa['id_siswa'] ?>][keterangan]"
                                                        class="form-control"
                                                        placeholder="Opsional"
                                                        value="<?= $absensi['keterangan'] ?? '' ?>">
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                                                <h5 class="mt-3">Tidak ada siswa ditemukan di kelas ini</h5>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (mysqli_num_rows($siswa_result) > 0): ?>
                            <div class="text-center mt-4">
                                <button type="submit" name="simpan_absen" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle"></i> Simpan Absensi
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Rekap Absensi -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5><i class="bi bi-graph-up"></i> Rekap Absensi Hari Ini</h5>
                </div>
                <div class="card-body">
                    <?php
                    $id_kelas = $_SESSION['id_kelas'];
                    $query_rekap = "SELECT 
                        COUNT(*) as total_siswa,
                        SUM(CASE WHEN a.status = 'H' THEN 1 ELSE 0 END) as hadir,
                        SUM(CASE WHEN a.status = 'I' THEN 1 ELSE 0 END) as izin,
                        SUM(CASE WHEN a.status = 'S' THEN 1 ELSE 0 END) as sakit,
                        SUM(CASE WHEN a.status = 'A' THEN 1 ELSE 0 END) as alpha
                        FROM siswa s 
                        LEFT JOIN absensi a ON s.id_siswa = a.id_siswa AND a.tanggal_absensi = CURDATE()
                        WHERE s.id_kelas = '$id_kelas'";

                    $rekap = mysqli_fetch_assoc(mysqli_query($koneksi, $query_rekap));
                    $belum_absen = $rekap['total_siswa'] - ($rekap['hadir'] + $rekap['izin'] + $rekap['sakit'] + $rekap['alpha']);
                    ?>
                    <div class="row text-center">
                        <div class="col-md-2 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <h3><?= $rekap['total_siswa'] ?></h3>
                                    <p>Total Siswa</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h3><?= $rekap['hadir'] ?></h3>
                                    <p>Hadir</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h3><?= $rekap['izin'] ?></h3>
                                    <p>Izin</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h3><?= $rekap['sakit'] ?></h3>
                                    <p>Sakit</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h3><?= $rekap['alpha'] ?></h3>
                                    <p>Alpha</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h3><?= $belum_absen ?></h3>
                                    <p>Belum Absen</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>© <?= date('Y') ?> Sistem Absensi MPK. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        function updateRowColor(select) {
            const row = select.closest('tr');
            const status = select.value;

            
            row.classList.remove('table-success', 'table-info', 'table-warning', 'table-danger');

            
            switch (status) {
                case 'H':
                    row.classList.add('table-success');
                    break;
                case 'I':
                    row.classList.add('table-info');
                    break;
                case 'S':
                    row.classList.add('table-warning');
                    break;
                case 'A':
                    row.classList.add('table-danger');
                    break;
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-select').forEach(select => {
                updateRowColor(select);
            });
        });
    </script>
</body>

</html>