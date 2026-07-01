<?php
include '../config/database.php';

// Hanya bisa diakses dari localhost atau untuk sementara
$message = '';

// Hash password baru
$new_password = password_hash('admin123', PASSWORD_DEFAULT);

// Update semua admin
$sql = "UPDATE admin SET password = '$new_password'";
if($koneksi->query($sql)) {
    $message = "Password admin berhasil direset ke: admin123<br>";
    $message .= "Jumlah admin yang diupdate: " . $koneksi->affected_rows;
} else {
    $message = "Error: " . $koneksi->error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Reset Password Admin Selesai</h4>
            </div>
            <div class="card-body">
                <p>Silakan login dengan:</p>
                <ul>
                    <li><strong>Username:</strong> admin</li>
                    <li><strong>Password:</strong> admin123</li>
                </ul>
                <a href="login.php" class="btn btn-primary">Go to Login</a>
            </div>
        </div>
    </div>
</body>
</html>