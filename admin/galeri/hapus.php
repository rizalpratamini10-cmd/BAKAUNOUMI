<?php
include '../../config/database.php';
is_login();

$id = $_GET['id'];
$data = $koneksi->query("SELECT * FROM galeri WHERE id = $id")->fetch_assoc();

if($data && $data['gambar']) {
    $file_path = "../../assets/img/gallery/" . $data['gambar'];
    if(file_exists($file_path)) {
        unlink($file_path);
    }
}

$koneksi->query("DELETE FROM galeri WHERE id = $id");
header('Location: index.php');
exit();
?>