<?php
session_start();
include '../config/database.php';
is_customer_login();

$cart_id = $_GET['id'];
$customer_id = $_SESSION['customer_id'];

$delete = $koneksi->prepare("DELETE FROM keranjang WHERE id = ? AND customer_id = ?");
$delete->bind_param("ii", $cart_id, $customer_id);
$delete->execute();

header('Location: keranjang.php');
exit();
?>