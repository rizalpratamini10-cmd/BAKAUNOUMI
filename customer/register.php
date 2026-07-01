<?php
session_start();
include '../config/database.php';

if(isset($_SESSION['customer_id'])) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    
    if(empty($nama) || empty($email) || empty($password)) {
        $error = "Semua field wajib diisi!";
    } elseif($password !== $konfirmasi_password) {
        $error = "Password dan konfirmasi password tidak sama!";
    } elseif(strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        $check = $koneksi->prepare("SELECT id FROM customer WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if($check->get_result()->num_rows > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("INSERT INTO customer (nama, email, password, telepon, alamat) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama, $email, $hashed_password, $telepon, $alamat);
            
            if($stmt->execute()) {
                $_SESSION['customer_id'] = $stmt->insert_id;
                $_SESSION['customer_nama'] = $nama;
                header('Location: ../index.php');
                exit();
            } else {
                $error = "Registrasi gagal, silakan coba lagi!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            min-height: 100vh;
        }
        .register-card {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            padding: 25px;
            text-align: center;
            color: white;
        }
        .register-body {
            padding: 30px;
        }
        .btn-register {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            border: none;
            padding: 12px;
            width: 100%;
            color: white;
            font-weight: 600;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-card">
            <div class="register-header">
                <i class="fas fa-fish fa-2x mb-2"></i>
                <h3>Daftar Akun</h3>
                <p class="mb-0">Bergabung dengan Bakau no Umi</p>
            </div>
            <div class="register-body">
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required value="<?php echo $_POST['nama'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required value="<?php echo $_POST['email'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="konfirmasi_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="tel" name="telepon" class="form-control" value="<?php echo $_POST['telepon'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"><?php echo $_POST['alamat'] ?? ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn-register">Daftar Sekarang</button>
                </form>
                <div class="text-center mt-3">
                    Sudah punya akun? <a href="login.php">Login di sini</a><br>
                    <a href="../index.php">← Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>