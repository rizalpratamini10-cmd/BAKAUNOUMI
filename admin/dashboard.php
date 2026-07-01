<?php
include '../config/database.php';
is_login();

// Get statistics
$total_produk = $koneksi->query("SELECT COUNT(*) as total FROM produk")->fetch_assoc()['total'];
$total_pesanan = $koneksi->query("SELECT COUNT(*) as total FROM pesanan")->fetch_assoc()['total'];
$total_testimoni = $koneksi->query("SELECT COUNT(*) as total FROM testimoni")->fetch_assoc()['total'];
$total_galeri = $koneksi->query("SELECT COUNT(*) as total FROM galeri")->fetch_assoc()['total'];
$total_customer = $koneksi->query("SELECT COUNT(*) as total FROM customer")->fetch_assoc()['total'];

// Get recent orders
$recent_orders = $koneksi->query("SELECT * FROM pesanan ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background: #1a252f;
        }
        .sidebar .nav-link.active {
            background: #0F4C81;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            transition: all 0.3s;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-fish"></i> Bakau no Umi - Admin Panel
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">
                    <i class="fas fa-user"></i> Halo, <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?>
                </span>
                <a href="logout.php" class="btn logout-btn text-white">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <div class="mt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="produk/">
                                <i class="fas fa-box me-2"></i> Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="galeri/">
                                <i class="fas fa-images me-2"></i> Galeri
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="testimoni/">
                                <i class="fas fa-star me-2"></i> Testimoni
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pesanan.php">
                                <i class="fas fa-shopping-cart me-2"></i> Pesanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="customer.php">
                                <i class="fas fa-users me-2"></i> Customer
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="logout.php" style="background: #c0392b;">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2>Dashboard</h2>
                <p>Selamat datang di panel administrator Bakau no Umi!</p>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Produk</h5>
                                <h3><?php echo $total_produk; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Pesanan</h5>
                                <h3><?php echo $total_pesanan; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Testimoni</h5>
                                <h3><?php echo $total_testimoni; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Customer</h5>
                                <h3><?php echo $total_customer; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-clock"></i> Pesanan Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($recent_orders && $recent_orders->num_rows > 0): ?>
                                    <?php while($order = $recent_orders->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo $order['nama_pemesan']; ?></td>
                                        <td>Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></td>
                                        <td><?php echo get_status_badge($order['status']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <a href="pesanan_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada pesanan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>