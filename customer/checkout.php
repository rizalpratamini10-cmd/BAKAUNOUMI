<?php
session_start();
include '../config/database.php';
is_customer_login();

$customer_id = $_SESSION['customer_id'];
$customer = get_customer_data($customer_id);

// Ambil keranjang
$cart_items = get_cart_items($customer_id);
$items = [];
$total = 0;
while($row = $cart_items->fetch_assoc()) {
    $subtotal = $row['harga'] * $row['jumlah'];
    $total += $subtotal;
    $items[] = $row;
}

if(empty($items)) {
    header('Location: keranjang.php');
    exit();
}

$metode_bayar = get_metode_pembayaran();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .checkout-container { padding: 100px 0 60px; }
        .checkout-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .payment-method {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-method:hover, .payment-method.selected {
            border-color: #0F4C81;
            background: rgba(15,76,129,0.05);
        }
        .btn-process {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            border: none;
            padding: 15px;
            font-weight: 600;
            width: 100%;
            color: white;
            border-radius: 10px;
        }
        .btn-process:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php"><i class="fas fa-fish"></i> Bakau no Umi</a>
            <div class="ms-auto">
                <a href="keranjang.php" class="btn btn-outline-light btn-sm">← Kembali</a>
            </div>
        </div>
    </nav>
    
    <div class="checkout-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout-card">
                        <h5><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</h5>
                        <form id="checkout-form">
                            <div class="mb-3">
                                <label>Nama Penerima</label>
                                <input type="text" name="nama_penerima" class="form-control" value="<?php echo htmlspecialchars($customer['nama']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Nomor WhatsApp</label>
                                <input type="tel" name="telepon" class="form-control" value="<?php echo htmlspecialchars($customer['telepon']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="3" required><?php echo htmlspecialchars($customer['alamat']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Catatan (opsional)</label>
                                <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Tolong pisahkan kerang dan kepiting"></textarea>
                            </div>
                        </form>
                    </div>
                    
                    <div class="checkout-card">
                        <h5><i class="fas fa-credit-card"></i> Metode Pembayaran</h5>
                        <div id="payment-methods">
                            <?php 
                            $first = true;
                            while($bank = $metode_bayar->fetch_assoc()): 
                            ?>
                            <div class="payment-method <?php echo $first ? 'selected' : ''; ?>" data-bank-id="<?php echo $bank['id']; ?>" data-bank-name="<?php echo $bank['nama_bank']; ?>" data-account="<?php echo $bank['no_rekening']; ?>" data-name="<?php echo $bank['atas_nama']; ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo $bank['nama_bank']; ?></strong>
                                        <div class="text-muted small">No. Rekening: <?php echo $bank['no_rekening']; ?></div>
                                        <div class="text-muted small">a.n. <?php echo $bank['atas_nama']; ?></div>
                                    </div>
                                    <div>
                                        <input type="radio" name="payment_method" value="<?php echo $bank['id']; ?>" <?php echo $first ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            $first = false;
                            endwhile; 
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="checkout-card">
                        <h5><i class="fas fa-receipt"></i> Ringkasan Pesanan</h5>
                        <?php foreach($items as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?php echo $item['nama_produk']; ?> x<?php echo $item['jumlah']; ?></span>
                            <span>Rp <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></span>
                        </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="text-primary">Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                        </div>
                        <hr>
                        <button type="button" class="btn-process" onclick="processCheckout()">
                            <i class="fas fa-check-circle"></i> Konfirmasi Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Upload Bukti -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-credit-card"></i> Detail Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-building-columns fa-3x text-primary"></i>
                    </div>
                    <div class="alert alert-info">
                        <strong>Silakan transfer ke rekening berikut:</strong>
                    </div>
                    <table class="table table-bordered">
                        <tr><th>Bank</th><td id="bank-name">-</td></tr>
                        <tr><th>No. Rekening</th><td id="bank-account">-</td></tr>
                        <tr><th>Atas Nama</th><td id="bank-owner">-</td></tr>
                        <tr><th>Total Transfer</th><td class="fw-bold text-primary" id="transfer-amount">-</td></tr>
                    </table>
                    <div class="alert alert-warning mt-3">
                        <small><i class="fas fa-info-circle"></i> Setelah melakukan transfer, upload bukti pembayaran untuk konfirmasi pesanan Anda.</small>
                    </div>
                    <form id="upload-bukti-form" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Transfer</label>
                            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitOrder()">Kirim Pesanan</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Sukses -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Pesanan Berhasil!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h5>Terima kasih telah berbelanja!</h5>
                    <p>Pesanan Anda sedang diproses. Kami akan menghubungi Anda segera.</p>
                    <p class="text-muted">Nomor Pesanan: <strong id="order-number"></strong></p>
                </div>
                <div class="modal-footer">
                    <a href="pesanan_saya.php" class="btn btn-primary">Lihat Pesanan Saya</a>
                    <a href="../index.php" class="btn btn-secondary">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedBank = null;
        
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                if(radio) radio.checked = true;
            });
        });
        
        function processCheckout() {
            const selectedRadio = document.querySelector('input[name="payment_method"]:checked');
            if(!selectedRadio) {
                alert('Pilih metode pembayaran terlebih dahulu');
                return;
            }
            
            const selectedMethod = document.querySelector('.payment-method.selected');
            const bankName = selectedMethod.querySelector('strong').innerText;
            const bankAccount = selectedMethod.querySelectorAll('.text-muted')[0].innerText.split(': ')[1];
            const bankOwner = selectedMethod.querySelectorAll('.text-muted')[1].innerText.split('a.n. ')[1];
            
            document.getElementById('bank-name').innerText = bankName;
            document.getElementById('bank-account').innerText = bankAccount;
            document.getElementById('bank-owner').innerText = bankOwner;
            document.getElementById('transfer-amount').innerText = 'Rp <?php echo number_format($total, 0, ',', '.'); ?>';
            
            selectedBank = selectedRadio.value;
            
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }
        
        function submitOrder() {
            const fileInput = document.querySelector('#upload-bukti-form input[type="file"]');
            if(!fileInput.files[0]) {
                alert('Silakan upload bukti transfer terlebih dahulu');
                return;
            }
            
            const file = fileInput.files[0];
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if(!validTypes.includes(file.type)) {
                alert('Format file harus JPG atau PNG');
                return;
            }
            if(file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                return;
            }
            
            const formData = new FormData();
            formData.append('nama_penerima', document.querySelector('input[name="nama_penerima"]').value);
            formData.append('email', document.querySelector('input[name="email"]').value);
            formData.append('telepon', document.querySelector('input[name="telepon"]').value);
            formData.append('alamat', document.querySelector('textarea[name="alamat"]').value);
            formData.append('catatan', document.querySelector('textarea[name="catatan"]').value);
            formData.append('metode_pembayaran', selectedBank);
            formData.append('total_harga', '<?php echo $total; ?>');
            formData.append('bukti_pembayaran', fileInput.files[0]);
            
            fetch('../proses/proses_pembayaran.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
                    document.getElementById('order-number').innerText = data.order_id;
                    new bootstrap.Modal(document.getElementById('successModal')).show();
                    setTimeout(() => { window.location.href = 'pesanan_saya.php'; }, 3000);
                } else {
                    alert('Gagal memproses pesanan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan, silakan coba lagi');
            });
        }
    </script>
</body>
</html>