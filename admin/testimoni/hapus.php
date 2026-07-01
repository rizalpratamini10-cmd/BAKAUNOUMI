<?php
include '../../config/database.php';
is_login();

$id = $_GET['id'];

$stmt = $koneksi->prepare("DELETE FROM testimoni WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: index.php');
exit();
?>