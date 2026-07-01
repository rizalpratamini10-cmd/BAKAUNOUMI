<?php
include '../../config/database.php';
is_login();

$galeri = $koneksi->query("SELECT * FROM galeri ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - Admin Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard.php">
                <i class="fas fa-fish"></i> Admin - Galeri
            </a>
            <div class="d-flex">
                <span class="text-white me-3">Halo, <?php echo $_SESSION['admin_name']; ?></span>
                <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4><i class="fas fa-images"></i> Manajemen Galeri</h4>
            </div>
            <div class="card-body">
                <a href="tambah.php" class="btn btn-success mb-3">
                    <i class="fas fa-plus"></i> Tambah Galeri
                </a>
                <a href="../dashboard.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <div class="row">
                    <?php if($galeri && $galeri->num_rows > 0): ?>
                        <?php while($row = $galeri->fetch_assoc()): ?>
                        <div class="col-md-3 mb-3">
                            <div class="card h-100">
                                <img src="../../assets/img/gallery/<?php echo $row['gambar']; ?>" 
                                     class="card-img-top" style="height: 200px; object-fit: cover;" 
                                     alt="<?php echo $row['judul']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['judul']; ?></h5>
                                    <p class="card-text">
                                        <small class="text-muted">Kategori: <?php echo $row['kategori']; ?></small>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">Belum ada data galeri. Silakan tambah galeri.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>