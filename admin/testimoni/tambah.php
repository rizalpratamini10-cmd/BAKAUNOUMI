<?php
include '../../config/database.php';
is_login();

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $testimoni = $_POST['testimoni'];
    $rating = $_POST['rating'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $stmt = $koneksi->prepare("INSERT INTO testimoni (nama, jabatan, testimoni, rating, is_active) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $nama, $jabatan, $testimoni, $rating, $is_active);
    
    if($stmt->execute()) {
        $success = "Testimoni berhasil ditambahkan!";
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 1500);</script>";
    } else {
        $error = "Gagal menambahkan testimoni: " . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Testimoni - Admin Bakau no Umi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 30px;
            color: #ddd;
            cursor: pointer;
        }
        .rating input:checked ~ label {
            color: #ffc107;
        }
        .rating label:hover,
        .rating label:hover ~ label {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-fish"></i> Admin - Tambah Testimoni
            </a>
            <div class="d-flex">
                <span class="text-white me-3">Halo, <?php echo $_SESSION['admin_name']; ?></span>
                <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4><i class="fas fa-plus"></i> Tambah Testimoni Baru</h4>
            </div>
            <div class="card-body">
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Pelanggan, Restoran, dll">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Testimoni <span class="text-danger">*</span></label>
                                <textarea name="testimoni" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <div class="rating">
                                    <input type="radio" name="rating" value="5" id="star5" checked><label for="star5">★</label>
                                    <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                                    <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                                    <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                                    <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">Aktif (ditampilkan di website)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>