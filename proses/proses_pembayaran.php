<?php
session_start();
header('Content-Type: application/json');

include '../config/database.php';

if(!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['customer_id'];
    
    // Upload file bukti pembayaran
    $bukti_pembayaran = '';
    if(isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] == 0) {
        $target_dir = '../uploads/bukti_pembayaran/';
        
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['bukti_pembayaran']['name'], PATHINFO_EXTENSION);
        $bukti_pembayaran = 'bukti_' . time() . '_' . $customer_id . '.' . $file_extension;
        $target_file = $target_dir . $bukti_pembayaran;
        
        if(!move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $target_file)) {
            echo json_encode(['success' => false, 'message' => 'Gagal upload bukti pembayaran']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Bukti pembayaran wajib diupload']);
        exit();
    }
    
    $nama_penerima = $_POST['nama_penerima'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];
    $catatan = $_POST['catatan'] ?? '';
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $total_harga = $_POST['total_harga'];
    
    $koneksi->begin_transaction();
    
    try {
        $stmt = $koneksi->prepare("
            INSERT INTO pesanan (
                customer_id, nama_pemesan, email, telepon, alamat, 
                total_harga, catatan, metode_pembayaran, bukti_pembayaran, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->bind_param("issssdsss", 
            $customer_id, $nama_penerima, $email, $telepon, $alamat,
            $total_harga, $catatan, $metode_pembayaran, $bukti_pembayaran
        );
        $stmt->execute();
        $order_id = $koneksi->insert_id;
        
        // Kosongkan keranjang
        $clear = $koneksi->prepare("DELETE FROM keranjang WHERE customer_id = ?");
        $clear->bind_param("i", $customer_id);
        $clear->execute();
        
        $koneksi->commit();
        
        echo json_encode([
            'success' => true, 
            'order_id' => $order_id,
            'message' => 'Pesanan berhasil dibuat'
        ]);
        
    } catch(Exception $e) {
        $koneksi->rollback();
        echo json_encode(['success' => false, 'message' => 'Gagal memproses pesanan: ' . $e->getMessage()]);
    }
}
?>