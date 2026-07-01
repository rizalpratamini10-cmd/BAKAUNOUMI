<?php
include '../config/database.php';
is_login();

// Update status customer
if(isset($_POST['toggle_status'])) {
    $id = $_POST['id'];
    $is_active = $_POST['is_active'];
    
    $stmt = $koneksi->prepare("UPDATE customer SET is_active = ? WHERE id = ?");
    $stmt->bind_param("ii", $is_active, $id);
    $stmt->execute();
    
    $success = "Status customer berhasil diupdate!";
}

// Hapus customer
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Hapus keranjang dulu
    $koneksi->query("DELETE FROM keranjang WHERE customer_id = $id");
    // Hapus pesanan
    $koneksi->query("DELETE FROM pesanan WHERE customer_id = $id");
    // Hapus customer
    $koneksi->query("DELETE FROM customer WHERE id = $id");
    
    $success = "Customer berhasil dihapus!";
    header('Location: customer.php');
    exit();
}

// Ambil data customer
$customer = $koneksi->query("SELECT * FROM customer ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer - Admin Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .customer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
        .customer-card {
            transition: transform 0.2s;
            margin-bottom: 20px;
        }
        .customer-card:hover {
            transform: translateY(-3px);
        }
        .status-active {
            color: #28a745;
        }
        .status-inactive {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-fish"></i> Admin - Manajemen Customer
            </a>
            <div class="d-flex">
                <span class="text-white me-3">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['admin_name']; ?>
                </span>
                <a href="logout.php" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4><i class="fas fa-users"></i> Manajemen Customer</h4>
            </div>
            <div class="card-body">
                <a href="dashboard.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>

                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="customerTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Avatar</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($customer && $customer->num_rows > 0): ?>
                                <?php while($row = $customer->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td>
                                        <div class="customer-avatar">
                                            <?php echo strtoupper(substr($row['nama'], 0, 1)); ?>
                                        </div>
                                    </div>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['nama']); ?></strong>
                                    </div>
                                    <td><?php echo htmlspecialchars($row['email']); ?></div>
                                    <td><?php echo $row['telepon'] ?: '-'; ?></div>
                                    <td>
                                        <?php if($row['alamat']): ?>
                                            <?php echo substr(htmlspecialchars($row['alamat']), 0, 50); ?>...
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </div>
                                    <td>
                                        <?php if($row['is_active']): ?>
                                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="fas fa-ban"></i> Nonaktif</span>
                                        <?php endif; ?>
                                    </div>
                                    <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></div>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-toggle-on"></i> Status
                                            </button>
                                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus customer ini? Semua data terkait (keranjang, pesanan) akan ikut terhapus.')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </div>

                                        <!-- Modal Update Status -->
                                        <div class="modal fade" id="statusModal<?php echo $row['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form method="POST">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title">Update Status Customer</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <div class="mb-3">
                                                                <label>Nama Customer</label>
                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['nama']); ?>" disabled>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Status</label>
                                                                <select name="is_active" class="form-control">
                                                                    <option value="1" <?php echo $row['is_active'] ? 'selected' : ''; ?>>Aktif</option>
                                                                    <option value="0" <?php echo !$row['is_active'] ? 'selected' : ''; ?>>Nonaktif</option>
                                                                </select>
                                                                <small class="text-muted">Customer nonaktif tidak bisa login</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" name="toggle_status" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Detail Customer -->
                                        <div class="modal fade" id="detailModal<?php echo $row['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title">Detail Customer</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-borderless">
                                                            <tr><th width="120">Nama</th><td><?php echo htmlspecialchars($row['nama']); ?></td> '</tr>
                                                            <tr><th>Email</th><td><?php echo htmlspecialchars($row['email']); ?></td> '</tr>
                                                            <tr><th>Telepon</th><td><?php echo $row['telepon'] ?: '-'; ?></td> '</tr>
                                                            <tr><th>Alamat</th><td><?php echo nl2br(htmlspecialchars($row['alamat'])) ?: '-'; ?></td> '</tr>
                                                            <tr><th>Status</th><td><?php echo $row['is_active'] ? 'Aktif' : 'Nonaktif'; ?></td> '</tr>
                                                            <tr><th>Bergabung</th><td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td> '</tr>
                                                            <tr><th>Terakhir Update</th><td><?php echo date('d/m/Y H:i', strtotime($row['updated_at'])); ?></td> '</tr>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                　
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada customer yang terdaftar.</td>
                                　
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Customer -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Customer</h5>
                        <h2><?php echo $customer->num_rows; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Customer Aktif</h5>
                        <h2><?php 
                            $koneksi->query("SELECT * FROM customer WHERE is_active = 1");
                            echo $koneksi->affected_rows;
                        ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5>Customer Nonaktif</h5>
                        <h2><?php 
                            $koneksi->query("SELECT * FROM customer WHERE is_active = 0");
                            echo $koneksi->affected_rows;
                        ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Bulan Ini</h5>
                        <h2><?php 
                            $result = $koneksi->query("SELECT COUNT(*) as total FROM customer WHERE MONTH(created_at) = MONTH(NOW())");
                            $row = $result->fetch_assoc();
                            echo $row['total'];
                        ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>