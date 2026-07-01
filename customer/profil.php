<?php
session_start();
include '../config/database.php';
is_customer_login();

$customer_id = $_SESSION['customer_id'];
$customer = get_customer_data($customer_id);

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    
    $update = $koneksi->prepare("UPDATE customer SET nama = ?, telepon = ?, alamat = ? WHERE id = ?");
    $update->bind_param("sssi", $nama, $telepon, $alamat, $customer_id);
    
    if($update->execute()) {
        $_SESSION['customer_nama'] = $nama;
        $success = "Profil berhasil diupdate";
        $customer = get_customer_data($customer_id);
    } else {
        $error = "Gagal mengupdate profil";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .profile-container { padding: 100px 0 60px; }
        .profile-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .profile-header {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .profile-body { padding: 30px; }
        .btn-update {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            border: none;
            padding: 10px 30px;
            color: white;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><i class="fas fa-fish"></i> Bakau no Umi</a>
            <div class="ms-auto">
                <a href="pesanan_saya.php" class="btn btn-outline-light btn-sm">Pesanan</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="profile-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="profile-card">
                        <div class="profile-header">
                            <i class="fas fa-user-circle fa-3x mb-2"></i>
                            <h4><?php echo htmlspecialchars($customer['nama']); ?></h4>
                            <p class="mb-0"><?php echo htmlspecialchars($customer['email']); ?></p>
                        </div>
                        <div class="profile-body">
                            <?php if($success): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php endif; ?>
                            <?php if($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($customer['nama']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($customer['email']); ?>" disabled>
                                    <small class="text-muted">Email tidak dapat diubah</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nomor WhatsApp</label>
                                    <input type="tel" name="telepon" class="form-control" value="<?php echo htmlspecialchars($customer['telepon']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3"><?php echo htmlspecialchars($customer['alamat']); ?></textarea>
                                </div>
                                <button type="submit" class="btn-update">Simpan Perubahan</button>
                                <a href="../index.php" class="btn btn-secondary">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>