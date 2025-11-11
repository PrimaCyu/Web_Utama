<?php
session_start();

// Jika sudah login, redirect ke halaman sesuai role
if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header('Location: admin/index.php');
        exit();
    } elseif ($_SESSION['user_role'] === 'mpk') {
        header('Location: mpk_dashboard.php');
        exit();
    }
}

// Koneksi database
$koneksi = mysqli_connect("localhost", "root", "", "smsr_jaya");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$error = '';
$role = isset($_POST['role']) ? $_POST['role'] : 'admin';

// Fungsi untuk verifikasi password hash
function verifyPassword($input_password, $hashed_password) {
    // Jika password masih plain text (lama), kita verifikasi langsung
    // Dan sekaligus update ke hash
    if (password_verify($input_password, $hashed_password)) {
        return true;
    }
    
    // Fallback: jika password masih plain text
    if ($input_password === $hashed_password) {
        return true;
    }
    
    return false;
}

// Fungsi untuk hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        if ($role === 'admin') {
            // Login sebagai admin
            $query = "SELECT * FROM admin WHERE username = '$username'";
            $result = mysqli_query($koneksi, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $admin = mysqli_fetch_assoc($result);
                
                // Verifikasi password dengan hash
                if (verifyPassword($password, $admin['password'])) {
                    // Jika password masih plain text, update ke hash
                    if ($password === $admin['password']) {
                        $new_hashed_password = hashPassword($password);
                        $update_query = "UPDATE admin SET password = '$new_hashed_password' WHERE id_admin = {$admin['id_admin']}";
                        mysqli_query($koneksi, $update_query);
                    }
                    
                    $_SESSION['user_id'] = $admin['id_admin'];
                    $_SESSION['username'] = $admin['username'];
                    $_SESSION['user_role'] = 'admin';
                    header('Location: admin/index.php');
                    exit();
                } else {
                    $error = 'Password salah!';
                }
            } else {
                $error = 'Username admin tidak ditemukan!';
            }
        } elseif ($role === 'mpk') {
            // Login sebagai MPK
            $query = "SELECT m.*, k.nama_kelas 
                      FROM mpk m 
                      LEFT JOIN kelas k ON m.id_kelas = k.id_kelas 
                      WHERE m.username = '$username'";
            $result = mysqli_query($koneksi, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $mpk = mysqli_fetch_assoc($result);
                
                // Verifikasi password dengan hash
                if (verifyPassword($password, $mpk['password'])) {
                    // Jika password masih plain text, update ke hash
                    if ($password === $mpk['password']) {
                        $new_hashed_password = hashPassword($password);
                        $update_query = "UPDATE mpk SET password = '$new_hashed_password' WHERE id_mpk = {$mpk['id_mpk']}";
                        mysqli_query($koneksi, $update_query);
                    }
                    
                    $_SESSION['user_id'] = $mpk['id_mpk'];
                    $_SESSION['username'] = $mpk['username'];
                    $_SESSION['user_role'] = 'mpk';
                    $_SESSION['id_kelas'] = $mpk['id_kelas'];
                    $_SESSION['kelas_nama'] = $mpk['nama_kelas'];
                    header('Location: mpk_dashboard.php');
                    exit();
                } else {
                    $error = 'Password salah!';
                }
            } else {
                $error = 'Username MPK tidak ditemukan!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Absensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2><i class="bi bi-person-workspace"></i> Login Sistem</h2>
                <p class="text-muted">Silakan masuk dengan akun Anda</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST">
                <!-- Role Selector -->
                <div class="role-selector">
                    <label class="role-btn <?= $role === 'admin' ? 'active' : '' ?>">
                        <input type="radio" name="role" value="admin" <?= $role === 'admin' ? 'checked' : '' ?> hidden>
                        <i class="bi bi-person-gear"></i>
                        <div>Admin</div>
                    </label>
                    <label class="role-btn <?= $role === 'mpk' ? 'active' : '' ?>">
                        <input type="radio" name="role" value="mpk" <?= $role === 'mpk' ? 'checked' : '' ?> hidden>
                        <i class="bi bi-person-check"></i>
                        <div>MPK</div>
                    </label>
                </div>

                <!-- Username Field -->
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required
                               value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                    </div>
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="login" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">
                    <?= $role === 'admin' ? 'Login sebagai administrator sistem' : 'Login sebagai pengurus MPK' ?>
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Role selector functionality
        document.querySelectorAll('.role-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                this.querySelector('input').checked = true;
            });
        });

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.querySelector('input[name="password"]');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
</body>
</html>