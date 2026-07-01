<?php
include '../../config/database.php';
is_login();

$produk = $koneksi->query("SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Admin Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .table-img {
            width: 60px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard.php">
                <i class="fas fa-fish"></i> Admin - Manajemen Produk
            </a>
            <div class="d-flex">
                <span class="text-white me-3">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['admin_name']; ?>
                </span>
                <a href="../logout.php" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4><i class="fas fa-box"></i> Manajemen Produk</h4>
            </div>
            <div class="card-body">
                <a href="tambah.php" class="btn btn-success mb-3">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
                <a href="../dashboard.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok (kg)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($produk && $produk->num_rows > 0): ?>
                                <?php while($row = $produk->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td>
                                        <?php if($row['gambar']): ?>
                                            <img src="../../assets/img/produk/<?php echo $row['gambar']; ?>" class="product-img">
                                        <?php else: ?>
                                            <span class="text-muted">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $row['kategori'] == 'segar' ? 'info' : ($row['kategori'] == 'olahan' ? 'warning' : 'secondary'); 
                                        ?>">
                                            <?php echo ucfirst($row['kategori']); ?>
                                        </span>
                                    </td>
                                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo $row['stok']; ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data produk. Silakan tambah produk.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>