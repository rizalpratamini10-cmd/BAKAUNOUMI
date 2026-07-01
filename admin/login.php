<?php
include '../config/database.php';

if(isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = $koneksi->prepare("SELECT * FROM admin WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    
    if($row = $result->fetch_assoc()) {
        if(password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['nama_lengkap'];
            header('Location: dashboard.php');
            exit();
        }
    }
    
    $error = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin - Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Login Admin</h3>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>