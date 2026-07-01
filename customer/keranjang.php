<?php
session_start();
include '../config/database.php';
is_customer_login();

$customer_id = $_SESSION['customer_id'];

// Ambil data keranjang
$cart_items = $koneksi->prepare("
    SELECT k.*, p.nama_produk, p.harga, p.gambar 
    FROM keranjang k 
    JOIN produk p ON k.produk_id = p.id 
    WHERE k.customer_id = ?
    ORDER BY k.created_at DESC
");
$cart_items->bind_param("i", $customer_id);
$cart_items->execute();
$cart_items = $cart_items->get_result();

// Hitung total
$total = 0;
$items = [];
while($row = $cart_items->fetch_assoc()) {
    $subtotal = $row['harga'] * $row['jumlah'];
    $total += $subtotal;
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .cart-container {
            padding: 100px 0 60px;
            background: #F8F4E9;
            min-height: 100vh;
        }
        .cart-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .cart-header {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            color: white;
            padding: 20px;
        }
        .cart-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
        }
        .quantity-input {
            width: 80px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 5px;
        }
        .btn-checkout {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            border: none;
            padding: 15px;
            font-weight: 600;
        }
        .cart-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            position: sticky;
            top: 100px;
        }
        .empty-cart {
            text-align: center;
            padding: 60px;
        }
    </style>
</head>
<body>
    <!-- Navbar (copy dari index.php) -->
    <?php include '../navbar.php'; ?>
    
    <div class="cart-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-card">
                        <div class="cart-header">
                            <h4 class="mb-0"><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h4>
                        </div>
                        
                        <?php if(count($items) > 0): ?>
                            <?php foreach($items as $item): ?>
                            <div class="cart-item" id="item-<?php echo $item['id']; ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="../assets/img/produk/<?php echo $item['gambar']; ?>" alt="<?php echo $item['nama_produk']; ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <h5><?php echo $item['nama_produk']; ?></h5>
                                        <p class="text-muted mb-0">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrement')">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="qty-<?php echo $item['id']; ?>" value="<?php echo $item['jumlah']; ?>" 
                                                   class="quantity-input" min="1" 
                                                   onchange="updateQuantity(<?php echo $item['id']; ?>, 'set', this.value)">
                                            <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(<?php echo $item['id']; ?>, 'increment')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="fw-bold mb-0">Rp <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></p>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-sm btn-danger" onclick="removeItem(<?php echo $item['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-cart">
                                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                <h4>Keranjang Kosong</h4>
                                <p>Yuk, belanja hasil laut segar dari nelayan lokal!</p>
                                <a href="../produk.php" class="btn btn-primary">Lihat Produk</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h5>Ringkasan Belanja</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Harga</span>
                            <span class="fw-bold" id="total-harga">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Biaya Pengiriman</span>
                            <span>Akan dihitung</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-primary" id="grand-total">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                        </div>
                        <?php if(count($items) > 0): ?>
                            <a href="checkout.php" class="btn btn-checkout w-100 text-white">
                                <i class="fas fa-credit-card"></i> Checkout Sekarang
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>Checkout Sekarang</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer (copy dari index.php) -->
    <?php include '../footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQuantity(cartId, action, value = null) {
            let qtyInput = document.getElementById('qty-' + cartId);
            let currentQty = parseInt(qtyInput.value);
            let newQty = currentQty;
            
            if(action === 'increment') {
                newQty = currentQty + 1;
            } else if(action === 'decrement' && currentQty > 1) {
                newQty = currentQty - 1;
            } else if(action === 'set' && value >= 1) {
                newQty = parseInt(value);
            }
            
            if(newQty !== currentQty) {
                fetch('../proses/update_keranjang.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'cart_id=' + cartId + '&jumlah=' + newQty
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert('Gagal mengupdate keranjang');
                    }
                });
            }
        }
        
        function removeItem(cartId) {
            if(confirm('Hapus item dari keranjang?')) {
                fetch('../proses/hapus_keranjang.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'cart_id=' + cartId
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus item');
                    }
                });
            }
        }
    </script>
</body>
</html>