<?php
include '../../config/database.php';
is_login();

$id = $_GET['id'];

// Ambil data gambar
$produk = get_produk($id);

if($produk && $produk['gambar']) {
    $file_path = "../../assets/img/produk/" . $produk['gambar'];
    if(file_exists($file_path)) {
        unlink($file_path);
    }
}

// Hapus dari database
$stmt = $koneksi->prepare("DELETE FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: index.php');
exit();
?>