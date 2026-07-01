<?php
// config/database.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_bakau_umi');

// Buat koneksi dengan penanganan error
$koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error . " - Pastikan database 'db_bakau_umi' sudah dibuat");
}

// Set charset
if(!$koneksi->set_charset("utf8mb4")) {
    die("Error loading character set utf8mb4: " . $koneksi->error);
}

// Mulai session jika belum
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============ FUNGSI HELPER ============
function base_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $folder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    return $protocol . $host . $folder . '/' . ltrim($path, '/');
}

// ============ FUNGSI ADMIN ============
function is_login() {
    if(!isset($_SESSION['admin_id'])) {
        header('Location: ' . base_url('admin/login.php'));
        exit();
    }
}

// ============ FUNGSI CUSTOMER ============
function is_customer_login() {
    if(!isset($_SESSION['customer_id'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . base_url('customer/login.php'));
        exit();
    }
}

function get_customer_data($customer_id = null) {
    global $koneksi;
    if($customer_id === null && isset($_SESSION['customer_id'])) {
        $customer_id = $_SESSION['customer_id'];
    }
    if($customer_id) {
        $query = $koneksi->prepare("SELECT * FROM customer WHERE id = ?");
        if($query) {
            $query->bind_param("i", $customer_id);
            $query->execute();
            return $query->get_result()->fetch_assoc();
        }
    }
    return null;
}

function get_cart_count($customer_id = null) {
    global $koneksi;
    if($customer_id === null && isset($_SESSION['customer_id'])) {
        $customer_id = $_SESSION['customer_id'];
    }
    if($customer_id) {
        $query = $koneksi->prepare("SELECT SUM(jumlah) as total FROM keranjang WHERE customer_id = ?");
        if($query) {
            $query->bind_param("i", $customer_id);
            $query->execute();
            $result = $query->get_result()->fetch_assoc();
            return $result['total'] ?? 0;
        }
    }
    return 0;
}

function get_cart_items($customer_id = null) {
    global $koneksi;
    if($customer_id === null && isset($_SESSION['customer_id'])) {
        $customer_id = $_SESSION['customer_id'];
    }
    if($customer_id) {
        $query = $koneksi->prepare("
            SELECT k.*, p.nama_produk, p.harga, p.gambar, p.stok
            FROM keranjang k 
            JOIN produk p ON k.produk_id = p.id 
            WHERE k.customer_id = ?
            ORDER BY k.created_at DESC
        ");
        if($query) {
            $query->bind_param("i", $customer_id);
            $query->execute();
            return $query->get_result();
        }
    }
    return [];
}

function get_cart_total($customer_id = null) {
    global $koneksi;
    if($customer_id === null && isset($_SESSION['customer_id'])) {
        $customer_id = $_SESSION['customer_id'];
    }
    if($customer_id) {
        $query = $koneksi->prepare("
            SELECT SUM(k.jumlah * p.harga) as total 
            FROM keranjang k 
            JOIN produk p ON k.produk_id = p.id 
            WHERE k.customer_id = ?
        ");
        if($query) {
            $query->bind_param("i", $customer_id);
            $query->execute();
            $result = $query->get_result()->fetch_assoc();
            return $result['total'] ?? 0;
        }
    }
    return 0;
}

function add_to_cart($produk_id, $jumlah = 1, $customer_id = null) {
    global $koneksi;
    if($customer_id === null && isset($_SESSION['customer_id'])) {
        $customer_id = $_SESSION['customer_id'];
    }
    if(!$customer_id) return false;
    
    $check = $koneksi->prepare("SELECT id, jumlah FROM keranjang WHERE customer_id = ? AND produk_id = ?");
    if($check) {
        $check->bind_param("ii", $customer_id, $produk_id);
        $check->execute();
        $result = $check->get_result();
        
        if($row = $result->fetch_assoc()) {
            $new_jumlah = $row['jumlah'] + $jumlah;
            $update = $koneksi->prepare("UPDATE keranjang SET jumlah = ? WHERE id = ?");
            if($update) {
                $update->bind_param("ii", $new_jumlah, $row['id']);
                return $update->execute();
            }
        } else {
            $insert = $koneksi->prepare("INSERT INTO keranjang (customer_id, produk_id, jumlah) VALUES (?, ?, ?)");
            if($insert) {
                $insert->bind_param("iii", $customer_id, $produk_id, $jumlah);
                return $insert->execute();
            }
        }
    }
    return false;
}

function get_produk($id) {
    global $koneksi;
    $query = $koneksi->prepare("SELECT * FROM produk WHERE id = ?");
    if($query) {
        $query->bind_param("i", $id);
        $query->execute();
        return $query->get_result()->fetch_assoc();
    }
    return null;
}

function get_metode_pembayaran() {
    global $koneksi;
    return $koneksi->query("SELECT * FROM metode_pembayaran WHERE is_active = 1");
}

function format_rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function get_status_badge($status) {
    $badges = ['pending' => 'warning', 'proses' => 'info', 'kirim' => 'primary', 'selesai' => 'success', 'batal' => 'danger'];
    $labels = ['pending' => 'Menunggu Konfirmasi', 'proses' => 'Diproses', 'kirim' => 'Dikirim', 'selesai' => 'Selesai', 'batal' => 'Dibatalkan'];
    $color = $badges[$status] ?? 'secondary';
    $label = $labels[$status] ?? $status;
    return "<span class='badge bg-$color'>$label</span>";
}

// Test koneksi
// echo "Database connected successfully";
?>