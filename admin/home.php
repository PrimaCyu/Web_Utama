<?php
include "koneksi.php";
?>
<div class="container mt-4">
    <h2 class="mb-3">📊 Dashboard Admin</h2>
    <p>Selamat datang di halaman admin. Gunakan menu di atas untuk mengelola data dengan cepat 🚀</p>

    <!-- Logo / Banner -->
    <div class="text-center mb-4">
        <img src="logo.png" alt="Logo" width="120" height="120" class="rounded-circle shadow-sm">
    </div>

    <!-- Statistik Card -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Siswa</h5>
                    <p class="card-text fs-3">
                        <?php
                        $res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM siswa");
                        $row = mysqli_fetch_assoc($res);
                        echo $row['total'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Guru</h5>
                    <p class="card-text fs-3">
                        <?php
                        $res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM guru");
                        $row = mysqli_fetch_assoc($res);
                        echo $row['total'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">MPK</h5>
                    <p class="card-text fs-3">
                        <?php
                        $res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mpk");
                        $row = mysqli_fetch_assoc($res);
                        echo $row['total'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Kelas</h5>
                    <p class="card-text fs-3">
                        <?php
                        $res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kelas");
                        $row = mysqli_fetch_assoc($res);
                        echo $row['total'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div class="mb-4">
        <a href="siswa_tambah.php" class="btn btn-primary me-2 mb-2">➕ Tambah Siswa</a>
        <a href="guru_tambah.php" class="btn btn-success me-2 mb-2">➕ Tambah Guru</a>
        <a href="mpk_tambah.php" class="btn btn-warning me-2 mb-2">➕ Tambah MPK</a>
    </div>

    <!-- Placeholder Grafis / Welcome -->
    <div class="text-center">
        <img src="dashboard_illustration.png" alt="Dashboard" class="img-fluid" style="max-width:400px;">
    </div>
</div>