<?php
include '../config/database.php';
is_login();

// Update status pesanan
if(isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $no_resi = $_POST['no_resi'] ?? '';
    
    $stmt = $koneksi->prepare("UPDATE pesanan SET status = ?, no_resi = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $no_resi, $id);
    $stmt->execute();
    
    $success = "Status pesanan berhasil diupdate!";
}

// Ambil data pesanan
$pesanan = $koneksi->query("
    SELECT p.*, c.nama as customer_nama 
    FROM pesanan p 
    LEFT JOIN customer c ON p.customer_id = c.id 
    ORDER BY p.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - Admin Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .status-pending { background: #ffc107; color: #000; }
        .status-proses { background: #17a2b8; color: #fff; }
        .status-kirim { background: #007bff; color: #fff; }
        .status-selesai { background: #28a745; color: #fff; }
        .status-batal { background: #dc3545; color: #fff; }
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .order-card {
            border-left: 4px solid;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        .order-card:hover {
            transform: translateY(-2px);
        }
        .border-pending { border-left-color: #ffc107; }
        .border-proses { border-left-color: #17a2b8; }
        .border-kirim { border-left-color: #007bff; }
        .border-selesai { border-left-color: #28a745; }
        .border-batal { border-left-color: #dc3545; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-fish"></i> Admin - Manajemen Pesanan
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
                <h4><i class="fas fa-shopping-cart"></i> Manajemen Pesanan</h4>
            </div>
            <div class="card-body">
                <a href="dashboard.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>

                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="row">
                    <!-- Filter tombol -->
                    <div class="col-12 mb-3">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary filter-btn active" data-filter="all">Semua</button>
                            <button class="btn btn-outline-warning filter-btn" data-filter="pending">Menunggu</button>
                            <button class="btn btn-outline-info filter-btn" data-filter="proses">Diproses</button>
                            <button class="btn btn-outline-primary filter-btn" data-filter="kirim">Dikirim</button>
                            <button class="btn btn-outline-success filter-btn" data-filter="selesai">Selesai</button>
                            <button class="btn btn-outline-danger filter-btn" data-filter="batal">Dibatalkan</button>
                        </div>
                    </div>
                </div>

                <div class="row" id="order-list">
                    <?php if($pesanan && $pesanan->num_rows > 0): ?>
                        <?php while($order = $pesanan->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4 order-item" data-status="<?php echo $order['status']; ?>">
                            <div class="card order-card border-<?php echo $order['status']; ?>">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">
                                            <i class="fas fa-receipt"></i> #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>
                                        </h6>
                                        <span class="badge-status status-<?php echo $order['status']; ?>">
                                            <?php 
                                                $status_label = [
                                                    'pending' => 'Menunggu',
                                                    'proses' => 'Diproses',
                                                    'kirim' => 'Dikirim',
                                                    'selesai' => 'Selesai',
                                                    'batal' => 'Dibatalkan'
                                                ];
                                                echo $status_label[$order['status']];
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Nama Pemesan</small>
                                        <p class="mb-0 fw-bold"><?php echo htmlspecialchars($order['nama_pemesan']); ?></p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Telepon</small>
                                        <p class="mb-0"><?php echo $order['telepon'] ?: '-'; ?></p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Alamat</small>
                                        <p class="mb-0 small"><?php echo $order['alamat'] ?: '-'; ?></p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Total Harga</small>
                                        <p class="mb-0 fw-bold text-primary">Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Metode Pembayaran</small>
                                        <p class="mb-0"><?php echo $order['metode_pembayaran'] ?: '-'; ?></p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Tanggal Order</small>
                                        <p class="mb-0 small"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                                    </div>
                                    
                                    <?php if($order['catatan']): ?>
                                    <div class="mb-2">
                                        <small class="text-muted">Catatan</small>
                                        <p class="mb-0 small text-muted"><?php echo nl2br(htmlspecialchars($order['catatan'])); ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if($order['bukti_pembayaran']): ?>
                                    <div class="mb-2">
                                        <small class="text-muted">Bukti Pembayaran</small><br>
                                        <a href="../uploads/bukti_pembayaran/<?php echo $order['bukti_pembayaran']; ?>" target="_blank" class="btn btn-sm btn-info mt-1">
                                            <i class="fas fa-image"></i> Lihat Bukti
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <hr>
                                    
                                    <!-- Form Update Status -->
                                    <form method="POST" class="mt-2">
                                        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                        <div class="row g-2">
                                            <div class="col-7">
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                                                    <option value="proses" <?php echo $order['status'] == 'proses' ? 'selected' : ''; ?>>Diproses</option>
                                                    <option value="kirim" <?php echo $order['status'] == 'kirim' ? 'selected' : ''; ?>>Dikirim</option>
                                                    <option value="selesai" <?php echo $order['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                                    <option value="batal" <?php echo $order['status'] == 'batal' ? 'selected' : ''; ?>>Dibatalkan</option>
                                                </select>
                                            </div>
                                            <div class="col-5">
                                                <button type="submit" name="update_status" class="btn btn-primary btn-sm w-100">
                                                    <i class="fas fa-save"></i> Update
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <input type="text" name="no_resi" class="form-control form-control-sm" placeholder="No. Resi (untuk pengiriman)" value="<?php echo $order['no_resi']; ?>">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">Belum ada pesanan masuk.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter pesanan berdasarkan status
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Update active class
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Filter items
                document.querySelectorAll('.order-item').forEach(item => {
                    if(filter === 'all' || item.dataset.status === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <style>
        .btn-group .btn.active {
            background: #0F4C81;
            color: white;
            border-color: #0F4C81;
        }
        .filter-btn {
            transition: all 0.3s;
        }
        .filter-btn:hover {
            transform: translateY(-2px);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>