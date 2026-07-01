<?php
include '../../config/database.php';
is_login();

$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM galeri WHERE id = $id")->fetch_assoc();

if(!$data) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $gambar = $data['gambar'];
    
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../../assets/img/gallery/";
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $gambar;
        
        if(move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            if($data['gambar'] && file_exists($target_dir . $data['gambar'])) {
                unlink($target_dir . $data['gambar']);
            }
        }
    }
    
    $stmt = $koneksi->prepare("UPDATE galeri SET judul=?, kategori=?, gambar=? WHERE id=?");
    $stmt->bind_param("sssi", $judul, $kategori, $gambar, $id);
    
    if($stmt->execute()) {
        $success = "Galeri berhasil diupdate!";
        $data = $koneksi->query("SELECT * FROM galeri WHERE id = $id")->fetch_assoc();
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 1500);</script>";
    } else {
        $error = "Gagal mengupdate";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Galeri - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h4>Edit Galeri</h4>
            </div>
            <div class="card-body">
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control" value="<?php echo $data['judul']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="kategori" class="form-control">
                            <option value="produk" <?php echo $data['kategori'] == 'produk' ? 'selected' : ''; ?>>Produk</option>
                            <option value="nelayan" <?php echo $data['kategori'] == 'nelayan' ? 'selected' : ''; ?>>Nelayan</option>
                            <option value="pengolahan" <?php echo $data['kategori'] == 'pengolahan' ? 'selected' : ''; ?>>Pengolahan</option>
                            <option value="pelanggan" <?php echo $data['kategori'] == 'pelanggan' ? 'selected' : ''; ?>>Pelanggan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Gambar Saat Ini</label><br>
                        <img src="../../assets/img/gallery/<?php echo $data['gambar']; ?>" width="150" class="mb-2">
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                    </div>
                    <button type="submit" class="btn btn-warning">Update</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>