<?php
include '../../config/database.php';
is_login();

$id = $_GET['id'];
$produk = get_produk($id);

if(!$produk) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $produk['gambar'];
    
    // Upload gambar baru jika ada
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../../assets/img/produk/";
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $gambar;
        
        if(move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            // Hapus gambar lama
            if($produk['gambar'] && file_exists($target_dir . $produk['gambar'])) {
                unlink($target_dir . $produk['gambar']);
            }
        } else {
            $error = "Gagal upload gambar baru";
        }
    }
    
    if(empty($error)) {
        $stmt = $koneksi->prepare("UPDATE produk SET nama_produk=?, kategori=?, deskripsi=?, harga=?, stok=?, gambar=? WHERE id=?");
        $stmt->bind_param("sssdssi", $nama_produk, $kategori, $deskripsi, $harga, $stok, $gambar, $id);
        
        if($stmt->execute()) {
            $success = "Produk berhasil diupdate!";
            $produk = get_produk($id);
            echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 1500);</script>";
        } else {
            $error = "Gagal mengupdate produk";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk - Admin Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-fish"></i> Admin - Edit Produk
            </a>
            <div class="d-flex">
                <span class="text-white me-3">Halo, <?php echo $_SESSION['admin_name']; ?></span>
                <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h4><i class="fas fa-edit"></i> Edit Produk</h4>
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
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" value="<?php echo htmlspecialchars($produk['nama_produk']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-control">
                                    <option value="segar" <?php echo $produk['kategori'] == 'segar' ? 'selected' : ''; ?>>Segar</option>
                                    <option value="olahan" <?php echo $produk['kategori'] == 'olahan' ? 'selected' : ''; ?>>Olahan</option>
                                    <option value="beku" <?php echo $produk['kategori'] == 'beku' ? 'selected' : ''; ?>>Beku</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="4"><?php echo htmlspecialchars($produk['deskripsi']); ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control" value="<?php echo $produk['harga']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Stok (kg)</label>
                                <input type="number" name="stok" class="form-control" value="<?php echo $produk['stok']; ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Gambar Saat Ini</label><br>
                                <?php if($produk['gambar']): ?>
                                    <img src="../../assets/img/produk/<?php echo $produk['gambar']; ?>" width="100" class="mb-2">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada gambar</span>
                                <?php endif; ?>
                                <input type="file" name="gambar" class="form-control mt-2" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update
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