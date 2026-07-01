<?php
include '../../config/database.php';
is_login();

$testimoni = $koneksi->query("SELECT * FROM testimoni ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimoni - Admin Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard.php">
                <i class="fas fa-fish"></i> Admin - Manajemen Testimoni
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
                <h4><i class="fas fa-star"></i> Manajemen Testimoni</h4>
            </div>
            <div class="card-body">
                <a href="tambah.php" class="btn btn-success mb-3">
                    <i class="fas fa-plus"></i> Tambah Testimoni
                </a>
                <a href="../dashboard.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Testimoni</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($testimoni && $testimoni->num_rows > 0): ?>
                                <?php while($row = $testimoni->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jabatan']); ?></td>
                                    <td><?php echo substr(htmlspecialchars($row['testimoni']), 0, 100); ?>...</td>
                                    <td>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $row['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                        <?php endfor; ?>
                                    </td>
                                    <td>
                                        <?php if($row['is_active']): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus testimoni ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data testimoni. Silakan tambah testimoni.</td>
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