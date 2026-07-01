<?php
session_start();
include '../config/database.php';
is_customer_login();

$customer_id = $_SESSION['customer_id'];
$pesanan = $koneksi->prepare("SELECT * FROM pesanan WHERE customer_id = ? ORDER BY created_at DESC");
$pesanan->bind_param("i", $customer_id);
$pesanan->execute();
$pesanan = $pesanan->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .order-container { padding: 100px 0 60px; }
        .order-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .badge-pending { background: #ffc107; color: #000; }
        .badge-proses { background: #17a2b8; color: #fff; }
        .badge-kirim { background: #007bff; color: #fff; }
        .badge-selesai { background: #28a745; color: #fff; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><i class="fas fa-fish"></i> Bakau no Umi</a>
            <div class="ms-auto">
                <a href="profil.php" class="btn btn-outline-light btn-sm">Profil</a>
                <a href="../index.php" class="btn btn-outline-light btn-sm">Beranda</a>
            </div>
        </div>
    </nav>
    
    <div class="order-container">
        <div class="container">
            <h3 class="mb-4"><i class="fas fa-history"></i> Riwayat Pesanan</h3>
            
            <?php if($pesanan->num_rows > 0): ?>
                <?php while($order = $pesanan->fetch_assoc()): ?>
                <div class="order-card">
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted">No. Pesanan</small>
                            <p class="fw-bold">#<?php echo $order['id']; ?></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Tanggal</small>
                            <p><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Total</small>
                            <p class="fw-bold text-primary">Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Status</small>
                            <p><?php echo get_status_badge($order['status']); ?></p>
                        </div>
                    </div>
                    <?php if($order['catatan']): ?>
                    <div class="mt-2">
                        <small class="text-muted">Catatan:</small>
                        <p class="mb-0 small"><?php echo $order['catatan']; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                    <h4>Belum ada pesanan</h4>
                    <p>Yuk, belanja hasil laut segar dari nelayan lokal!</p>
                    <a href="../index.php#feature" class="btn btn-primary">Lihat Produk</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>