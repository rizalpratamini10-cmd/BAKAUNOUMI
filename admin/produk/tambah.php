<?php
include '../../config/database.php';
is_login();

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    
    // Upload gambar
    $gambar = '';
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../../assets/img/produk/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $gambar;
        
        if(move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            // Upload sukses
        } else {
            $error = "Gagal upload gambar";
        }
    }
    
    if(empty($error)) {
        $stmt = $koneksi->prepare("INSERT INTO produk (nama_produk, kategori, deskripsi, harga, stok, gambar) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdss", $nama_produk, $kategori, $deskripsi, $harga, $stok, $gambar);
        
        if($stmt->execute()) {
            $success = "Produk berhasil ditambahkan!";
            echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 1500);</script>";
        } else {
            $error = "Gagal menambahkan produk: " . $koneksi->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk - Admin Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-fish"></i> Admin - Tambah Produk
            </a>
            <div class="d-flex">
                <span class="text-white me-3">Halo, <?php echo $_SESSION['admin_name']; ?></span>
                <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4><i class="fas fa-plus"></i> Tambah Produk Baru</h4>
            </div>
            <div class="card-body">
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="nama_produk" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-control">
                                    <option value="segar">Segar</option>
                                    <option value="olahan">Olahan</option>
                                    <option value="beku">Beku</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Stok (kg)</label>
                                <input type="number" name="stok" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Gambar Produk</label>
                                <input type="file" name="gambar" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>