<?php
session_start();
include '../config/database.php';

if(isset($_SESSION['customer_id'])) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../index.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $koneksi->prepare("SELECT * FROM customer WHERE email = ? AND is_active = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        if(password_verify($password, $row['password'])) {
            $_SESSION['customer_id'] = $row['id'];
            $_SESSION['customer_nama'] = $row['nama'];
            $_SESSION['customer_email'] = $row['email'];
            header("Location: $redirect");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            min-height: 100vh;
        }
        .login-card {
            max-width: 450px;
            margin: 100px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .login-body {
            padding: 30px;
        }
        .btn-login {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            border: none;
            padding: 12px;
            width: 100%;
            color: white;
            font-weight: 600;
            border-radius: 10px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-fish fa-2x mb-2"></i>
                <h3>Login Customer</h3>
                <p class="mb-0">Selamat datang kembali!</p>
            </div>
            <div class="login-body">
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-login">Login</button>
                </form>
                <div class="text-center mt-3">
                    Belum punya akun? <a href="register.php">Daftar di sini</a><br>
                    <a href="../index.php">← Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>