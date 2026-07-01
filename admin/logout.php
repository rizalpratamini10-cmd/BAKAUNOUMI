<?php
session_start();

// Hapus semua session admin
session_destroy();

// Redirect ke halaman login admin
header('Location: login.php');
exit();
?>