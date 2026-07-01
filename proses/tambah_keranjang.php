<?php
session_start();
header('Content-Type: application/json');

include '../config/database.php';

// Cek login
if(!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit();
}

// Cek method POST
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    exit();
}

// Ambil data
$customer_id = $_SESSION['customer_id'];
$produk_id = isset($_POST['produk_id']) ? (int)$_POST['produk_id'] : 0;
$jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1;

if($produk_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Produk tidak valid']);
    exit();
}

// Cek produk
$produk = get_produk($produk_id);
if(!$produk) {
    echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
    exit();
}

// Cek apakah sudah ada di keranjang
$check = $koneksi->prepare("SELECT id, jumlah FROM keranjang WHERE customer_id = ? AND produk_id = ?");
$check->bind_param("ii", $customer_id, $produk_id);
$check->execute();
$result = $check->get_result();

if($row = $result->fetch_assoc()) {
    $new_jumlah = $row['jumlah'] + $jumlah;
    $update = $koneksi->prepare("UPDATE keranjang SET jumlah = ? WHERE id = ?");
    $update->bind_param("ii", $new_jumlah, $row['id']);
    $success = $update->execute();
} else {
    $insert = $koneksi->prepare("INSERT INTO keranjang (customer_id, produk_id, jumlah) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $customer_id, $produk_id, $jumlah);
    $success = $insert->execute();
}

if($success) {
    echo json_encode(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan ke keranjang: ' . $koneksi->error]);
}
?>