<?php
include '../../config/database.php';
is_login();

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    
    // Upload gambar
    $gambar = '';
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../../assets/img/gallery/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $gambar;
        
        if(move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            $stmt = $koneksi->prepare("INSERT INTO galeri (judul, gambar, kategori) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $judul, $gambar, $kategori);
            
            if($stmt->execute()) {
                $success = "Galeri berhasil ditambahkan!";
                echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 1500);</script>";
            } else {
                $error = "Gagal menyimpan ke database";
            }
        } else {
            $error = "Gagal upload gambar";
        }
    } else {
        $error = "Gambar wajib diupload";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Galeri - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4>Tambah Galeri</h4>
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
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="kategori" class="form-control">
                            <option value="produk">Produk</option>
                            <option value="nelayan">Nelayan</option>
                            <option value="pengolahan">Pengolahan</option>
                            <option value="pelanggan">Pelanggan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Gambar</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>