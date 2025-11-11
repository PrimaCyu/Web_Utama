<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Prima</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1><i class="bi bi-person-workspace"></i> Admin Absen</h1>
        <p>Kelola data dengan mudah dan cepat 🚀</p>
    </header>

    <nav>
        <?php
        $menu = [
            'home' => 'Home',
            'guru' => 'Guru',
            'jurnal' => 'Jurnal',
            'pegawai' => 'Pegawai',
            'jurusan' => 'Jurusan',
            'kelas' => 'Kelas',
            'mpk' => 'MPK',
            'siswa' => 'Siswa',
            'pembayaran' => 'Pembayaran',
            'logout' => 'Logout'
        ];
        foreach ($menu as $key => $label) {
            $active = ($page == $key) ? 'active' : '';
            echo "<a href='index.php?page=$key' class='$active'>$label</a>";
        }
        ?>
    </nav>

    <main id="content" class="fade-in">
        <?php include $page . ".php"; ?>
    </main>

    <footer>
        <p>© <?= date('Y') ?> Admin Prima. All rights reserved.</p>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="script.js"></script>
</body>

</html>