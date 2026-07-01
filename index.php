<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/database.php';

// Ambil data untuk ditampilkan
$produk_unggulan = $koneksi->query("SELECT * FROM produk ORDER BY id LIMIT 6");
$testimoni = $koneksi->query("SELECT * FROM testimoni WHERE is_active = 1 ORDER BY id DESC LIMIT 3");
$galeri = $koneksi->query("SELECT * FROM galeri ORDER BY id LIMIT 6");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bakau no Umi - Hasil Laut Segar dari Nelayan Lokal</title>
    <meta name="description" content="Nikmati lokan, siput hisap, ranjungan, dan hasil laut pesisir berkualitas dari nelayan lokal">
    
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" />
    
    <!-- IcoFont -->
    <link rel="stylesheet" href="assets/css/icofont.min.css">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
    <!-- Animate CSS -->
    <link href="assets/css/animate.min.css" rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="assets/css/swiper.min.css">
    
    <!-- Theme Style -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/typography/poppins-quciksland.css">
    
    <style>
        /* ======================================================
           CUSTOM WARNA BAKAU NO UMI - OVERRIDE TEMPLATE APPIYA
        ====================================================== */
        
        /* Warna Utama */
        :root {
            --primary: #0F4C81;
            --primary-dark: #0a3a5e;
            --secondary: #2E8B57;
            --secondary-dark: #236b43;
            --accent: #FFD700;
            --text-dark: #1F2937;
            --bg-light: #F8F4E9;
        }
        
        /* Override gradient background */
        .gredient-bg, a.comment-reply-link:hover, .single-widget ul.tags-cloud li a:hover, 
        .app-pagenation .page-item.active .page-link, .blog-tag a, #page-header, 
        .social-network .social-icon:hover, .read-more, .featured-2, 
        .pricing-head .wave:nth-of-type(3), .pricing-head .wave, .single-pricing:before, 
        .swiper-pagination-bullet-active, .screenshot-swiper .swiper-button-prev:hover,
        .screenshot-swiper .swiper-button-next:hover, .testimonials, .filled-circle, 
        .bordered-box, .bordered-circle, .bordered-circle2, .hero-area, .loader:before, 
        .circle, .btn-default:hover, .btn-filled {
            background: linear-gradient(135deg, #0F4C81, #2E8B57) !important;
            background-color: #0F4C81 !important;
        }
        
        /* Override gradient color (text gradient) */
        .gredient-color, .single-feature:hover h4, .how-it-box i, .address-box i, 
        .how_works_arrow, .service-box:hover i, .single-feature:hover i, 
        .how-it-box:hover i, .address-box:hover i {
            background: linear-gradient(135deg, #0F4C81, #2E8B57) !important;
            -webkit-background-clip: text !important;
            background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
        }
        
        /* Dark purple color */
        .dark-purple-color, .single-widget ul a:hover, .post-meta ul li a:hover, 
        .single-blog h2:hover, .page-content h2:hover, .post-comments h2:hover, 
        #testimonials .rotate-heading h2 {
            color: #0F4C81 !important;
        }
        
        /* Primary border color */
        .primary-border-color, .single-widget .form-control:focus, .single-widget h3, 
        .contact-form input:focus, .contact-form textarea:focus, 
        .screenshot-swiper .swiper-button-prev, .screenshot-swiper .swiper-button-next {
            border-color: #0F4C81 !important;
        }
        
        /* Dark purple background */
        .dark-purple, .header-nav.fixed-header, 
        .header-nav .navbar-nav > li .sub-menu li > a:hover {
            background: #0F4C81 !important;
        }
        
        /* Navbar Custom */
        .navbar {
            background: linear-gradient(135deg, #0F4C81, #2E8B57) !important;
        }
        
        .navbar-brand span {
            background: linear-gradient(135deg, #fff, #FFD700);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Button Custom */
        .btn-default {
            background: transparent;
            border-color: #fff;
        }
        
        .btn-default:hover {
            background: linear-gradient(135deg, #0F4C81, #2E8B57) !important;
            border-color: transparent;
        }
        
        .btn-filled {
            background: linear-gradient(135deg, #0F4C81, #2E8B57) !important;
        }
        
        /* Product Card */
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .product-img {
            height: 220px;
            overflow: hidden;
        }
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .product-card:hover .product-img img {
            transform: scale(1.05);
        }
        .product-info {
            padding: 20px;
        }
        .product-info h4 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            font-weight: 700;
        }
        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0F4C81;
            margin-bottom: 15px;
        }
        .btn-add-cart {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            border: none;
            padding: 10px;
            border-radius: 30px;
            color: white;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }
        .btn-add-cart:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }
        
        /* Hero Stats */
        .hero-stats {
            display: flex;
            gap: 30px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #FFD700;
            display: block;
        }
        .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        /* Cart Badge */
        .cart-badge {
            position: relative;
            color: white !important;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        .cart-count {
            position: absolute;
            top: -8px;
            right: -12px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* User Dropdown */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        .user-btn {
            background: rgba(255,255,255,0.2);
            border-radius: 30px;
            padding: 8px 16px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .user-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            min-width: 200px;
            padding: 8px 0;
            margin-top: 10px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .user-dropdown:hover .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
        }
        .dropdown-menu-custom a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.3s;
        }
        .dropdown-menu-custom a:hover {
            background: #f0f0f0;
        }
        .dropdown-menu-custom hr {
            margin: 8px 0;
            border-color: #eee;
        }
        
        /* Toast Notification */
        .toast-notif {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #2E8B57;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            z-index: 9999;
            transform: translateX(400px);
            transition: transform 0.3s;
        }
        .toast-notif.show {
            transform: translateX(0);
        }
        
        /* Fresh Seafood Banner */
        .fresh-seafood-banner {
            background: linear-gradient(135deg, #0F4C81, #2E8B57);
            padding: 60px 0;
            text-align: center;
            color: white;
        }
        
        /* Hero Area Custom */
        .hero-area h2 {
            font-size: 48px;
            font-weight: 800;
        }
        
        @media (max-width: 768px) {
            .hero-area h2 {
                font-size: 32px;
            }
            .hero-stats {
                justify-content: center;
            }
        }
    </style>
</head>
<body data-spy="scroll" data-target="#navbarCodeply" data-offset="70">

<!-- Preloader -->
<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<!-- Header -->
<header id="home">
    <nav class="navbar navbar-inverse navbar-expand-lg header-nav fixed-top light-header">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/logo.png" alt="logo" style="height: 40px;">
                <span>Bakau no Umi</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCodeply">
                <i class="icofont-navigation-menu"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarCodeply">
                <ul class="nav navbar-nav ml-auto">
                    <li><a class="nav-link" href="#home">Home</a></li>
                    <li><a class="nav-link" href="#about">Tentang</a></li>
                    <li><a class="nav-link" href="#feature">Produk</a></li>
                    <li><a class="nav-link" href="#testimonials">Testimoni</a></li>
                    <li><a class="nav-link" href="#screenshots">Galeri</a></li>
                    <li><a class="nav-link" href="#pricing">Harga</a></li>
                    <li><a class="nav-link" href="#blog">Blog</a></li>
                    <li><a class="nav-link" href="#contact">Kontak</a></li>
                </ul>
                
                <!-- Cart & User -->
                <div class="ml-3 d-flex align-items-center">
                    <a href="customer/keranjang.php" class="cart-badge">
                        <i class="icofont-shopping-cart"></i>
                        <?php if(isset($_SESSION['customer_id'])): 
                            $cart_count = get_cart_count($_SESSION['customer_id']);
                            if($cart_count > 0): ?>
                            <span class="cart-count"><?php echo $cart_count; ?></span>
                        <?php endif; endif; ?>
                    </a>
                    
                    <?php if(isset($_SESSION['customer_id'])): ?>
                    <div class="user-dropdown">
                        <div class="user-btn">
                            <i class="icofont-user"></i> <?php echo $_SESSION['customer_nama']; ?>
                            <i class="icofont-rounded-down"></i>
                        </div>
                        <div class="dropdown-menu-custom">
                            <a href="customer/profil.php"><i class="icofont-id-card"></i> Profil</a>
                            <a href="customer/pesanan_saya.php"><i class="icofont-history"></i> Pesanan</a>
                            <a href="customer/keranjang.php"><i class="icofont-shopping-cart"></i> Keranjang</a>
                            <hr>
                            <a href="customer/logout.php"><i class="icofont-logout"></i> Logout</a>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="user-dropdown">
                        <div class="user-btn">
                            <i class="icofont-user"></i> Akun
                            <i class="icofont-rounded-down"></i>
                        </div>
                        <div class="dropdown-menu-custom">
                            <a href="customer/login.php"><i class="icofont-login"></i> Login</a>
                            <a href="customer/register.php"><i class="icofont-user-alt-5"></i> Daftar</a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero-area circle-wrap">
    <div class="circle x1"></div>
    <div class="circle x2"></div>
    <div class="circle x3"></div>
    <div class="circle x4"></div>
    <div class="circle x5"></div>
    <div class="circle x6"></div>
    <div class="circle x7"></div>
    <div class="circle x8"></div>
    <div class="circle x9"></div>
    <div class="circle x10"></div>
    
    <div class="container">
        <div class="row full-height align-items-center">
            <div class="col-md-6 p-100px-t p-50px-b md-p-10px-b">
                <h2 class="text-capitalize m-25px-b">
                    HASIL LAUT SEGAR<br>
                    <span class="gredient-color">DARI NELAYAN LOKAL</span>
                </h2>
                <p class="m-25px-b">Nikmati lokan, siput hisap, ranjungan, dan hasil laut pesisir berkualitas yang ditangkap langsung oleh nelayan lokal setiap hari.</p>
                <div class="hero-btn-wrapper">
                    <a href="#feature" class="btn btn-default animated-btn">Pesan Sekarang</a>
                    <a href="#feature" class="btn btn-default btn-default-outline animated-btn">Lihat Produk</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Nelayan Mitra</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label">Pelanggan Puas</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Layanan</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-100px-t p-50px-b md-p-10px-t">
                <img class="hero-mock" src="assets/img/hero-mock.png" alt="Hero mockup"/>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="p-100px-tb sm-p-50px-b">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <div class="section-title text-center m-50px-b">
                    <h2>Tentang <span class="gredient-color">Bakau no Umi</span></h2>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="position-relative md-m-50px-b">
                    <div class="bordered-circle"></div>
                    <img class="moveUpDown" src="assets/img/preface.png" alt="Tentang Kami">
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="m-15px-b">Hasil Laut Segar dari Nelayan Lokal</h3>
                <p class="m-25px-b">Bakau no Umi merupakan UMKM yang bergerak di bidang perikanan dan hasil olahan laut pesisir. Usaha ini berfokus pada pemasaran hasil tangkapan nelayan lokal yang berasal dari kawasan bakau dan perairan pesisir.</p>
                <p>Didirikan untuk membantu meningkatkan kesejahteraan nelayan lokal sekaligus menyediakan produk laut segar dan berkualitas bagi masyarakat.</p>
                <div class="apps-buttons mt-4">
                    <a href="https://wa.me/6281234567890" class="btn btn-default btn-filled animated-btn">
                        <i class="icofont-brand-whatsapp"></i> WhatsApp<br>Pesan Sekarang
                    </a>
                    <a href="https://instagram.com/bakau.no.umi" class="btn btn-default btn-filled animated-btn">
                        <i class="icofont-brand-instagram"></i> Instagram<br>@bakau.no.umi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produk Section -->
<section id="feature" class="p-80px-tb position-relative">
    <div class="filled-circle"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <div class="section-title text-center m-50px-b">
                    <h2>Produk <span class="gredient-color">Unggulan</span></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if($produk_unggulan && $produk_unggulan->num_rows > 0): ?>
                <?php while($produk = $produk_unggulan->fetch_assoc()): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div class="product-img">
                            <img src="assets/img/produk/<?php echo $produk['gambar'] ?? 'default.jpg'; ?>" alt="<?php echo $produk['nama_produk']; ?>">
                        </div>
                        <div class="product-info">
                            <h4><?php echo $produk['nama_produk']; ?></h4>
                            <p class="text-muted small"><?php echo substr($produk['deskripsi'] ?? '', 0, 70); ?>...</p>
                            <div class="product-price"><?php echo format_rupiah($produk['harga']); ?>/kg</div>
                            <button class="btn-add-cart" onclick="addToCart(<?php echo $produk['id']; ?>)">
                                <i class="icofont-cart-alt"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>Belum ada produk. Silakan tambah produk melalui admin panel.</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="produk.php" class="btn btn-default animated-btn">Lihat Semua Produk</a>
        </div>
    </div>
</section>

<!-- Fresh Seafood Banner -->
<section class="fresh-seafood-banner">
    <div class="container">
        <h2 class="m-15px-b">Makanan Laut Segar</h2>
        <p class="m-25px-b">Kualitas terbaik, langsung dari nelayan ke meja Anda</p>
    </div>
</section>

<!-- Testimoni Section -->
<section id="testimonials" class="p-175px-tb md-p-80px-tb position-relative testimonials">
    <div class="container">
        <div class="row">
            <div class="rotate-heading">
                <h2>Testimoni<br>Pelanggan</h2>
            </div>
            <div class="col-lg-8 offset-lg-2 swiper-container testimonialSwiper p-50px-b">
                <div class="swiper-wrapper">
                    <?php if($testimoni && $testimoni->num_rows > 0): ?>
                        <?php while($t = $testimoni->fetch_assoc()): ?>
                        <div class="single-testimonial swiper-slide">
                            <div class="row">
                                <div class="col-lg-2 col-md-3">
                                    <img class="rounded-circle" src="assets/img/avater1.jpeg" alt="">
                                </div>
                                <div class="col-lg-10 col-md-9">
                                    <p>“ <?php echo $t['testimoni']; ?> ”</p>
                                    <h5><?php echo $t['nama']; ?></h5>
                                    <p class="designation"><?php echo $t['jabatan']; ?></p>
                                    <p class="ratings">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="icofont-ui-rating"></i>
                                        <?php endfor; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</section>

<!-- Galeri Section -->
<section id="screenshots" class="p-80px-tb position-relative">
    <div class="circle x1"></div>
    <div class="circle x2"></div>
    <div class="circle x3"></div>
    <div class="circle x4"></div>
    <div class="circle x7"></div>
    <div class="circle x8"></div>
    <div class="circle x9"></div>
    <div class="circle x10"></div>
    
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <div class="section-title text-center m-50px-b">
                    <h2>Galeri <span class="gredient-color">Produk</span></h2>
                </div>
            </div>
        </div>
        <div class="row swiper-container screenshot-swiper p-50px-b">
            <div class="swiper-wrapper">
                <?php if($galeri && $galeri->num_rows > 0): ?>
                    <?php while($g = $galeri->fetch_assoc()): ?>
                    <div class="swiper-slide col-sm-4">
                        <img src="assets/img/gallery/<?php echo $g['gambar']; ?>" alt="<?php echo $g['judul']; ?>">
                    </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next">
                <i class="icofont-stylish-right"></i>
            </div>
            <div class="swiper-button-prev">
                <i class="icofont-stylish-left"></i>
            </div>
        </div>
    </div>
</section>

<!-- Pricing / Harga -->
<section id="pricing" class="p-80px-tb parallax bg-overlay opacity-5" style="background-image:url(assets/img/pricing-bg.jpg)">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <div class="section-title text-center m-50px-b">
                    <h2>Paket <span class="gredient-color">Pembelian</span></h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-lg-4 col-md-6">
                <div class="single-pricing text-center m-10px-tb">
                    <div class="pricing-head p-60px-lr lg-p-30px-lr">
                        <div class="pricing-head-text">
                            <div class="package-price">
                                <span class="price">1-5</span><span class="validity"> kg</span>
                            </div>
                            <div class="package-name">
                                <h5>Paket Hemat</h5>
                            </div>
                        </div>
                        <span class="wave"></span>
                        <span class="wave"></span>
                        <span class="wave"></span>
                    </div>
                    <div class="pricing-body p-60px-lr lg-p-30px-lr">
                        <ul>
                            <li>Pilih 2 jenis produk</li>
                            <li>Gratis kemasan es</li>
                            <li>Pengiriman reguler</li>
                        </ul>
                    </div>
                    <div class="pricing-footer p-60px-lr lg-p-30px-lr">
                        <a href="#contact" class="btn btn-default btn-filled animated-btn">Pesan</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-pricing text-center featured-pricing m-10px-tb">
                    <div class="pricing-head p-60px-lr lg-p-30px-lr">
                        <div class="pricing-head-text">
                            <div class="package-price">
                                <span class="price">6-20</span><span class="validity"> kg</span>
                            </div>
                            <div class="package-name">
                                <h5>Paket Keluarga</h5>
                            </div>
                        </div>
                        <span class="wave"></span>
                        <span class="wave"></span>
                        <span class="wave"></span>
                    </div>
                    <div class="pricing-body p-60px-lr lg-p-30px-lr">
                        <ul>
                            <li>Pilih 4 jenis produk</li>
                            <li>Gratis kemasan + es kering</li>
                            <li>Pengiriman prioritas</li>
                            <li>Diskon 5%</li>
                        </ul>
                    </div>
                    <div class="pricing-footer p-60px-lr lg-p-30px-lr">
                        <a href="#contact" class="btn btn-default btn-filled animated-btn">Pesan</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-pricing text-center m-10px-tb">
                    <div class="pricing-head p-60px-lr lg-p-30px-lr">
                        <div class="pricing-head-text">
                            <div class="package-price">
                                <span class="price">20+</span><span class="validity"> kg</span>
                            </div>
                            <div class="package-name">
                                <h5>Paket Grosir</h5>
                            </div>
                        </div>
                        <span class="wave"></span>
                        <span class="wave"></span>
                        <span class="wave"></span>
                    </div>
                    <div class="pricing-body p-60px-lr lg-p-30px-lr">
                        <ul>
                            <li>Semua jenis produk</li>
                            <li>Kemasan premium</li>
                            <li>Pengiriman ekspres</li>
                            <li>Diskon 10%</li>
                        </ul>
                    </div>
                    <div class="pricing-footer p-60px-lr lg-p-30px-lr">
                        <a href="#contact" class="btn btn-default btn-filled animated-btn">Pesan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section id="blog" class="p-100px-tb gray-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <div class="section-title text-center m-50px-b">
                    <h2>Berita & <span class="gredient-color">Artikel</span></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <article class="page-content">
                    <div class="blog-post-img">
                        <img src="assets/img/blog/4.jpg" alt="">
                        <div class="blog-tag">
                            <a href="#">Resep</a>
                        </div>
                    </div>
                    <div class="blog-content">
                        <div class="post-meta m-15px-b">
                            <ul>
                                <li><a href="#"><i class="icofont-calendar"></i> 03 Juni 2026</a></li>
                            </ul>
                        </div>
                        <a href="#">
                            <h2 class="m-25px-b">Resep Olahan Kepiting Bakau Saus Padang</h2>
                        </a>
                        <p>Nikmati kelezatan kepiting bakau dengan bumbu saus Padang yang pedas.</p>
                        <a class="read-more" href="#">Selengkapnya</a>
                    </div>
                </article>
            </div>
            <div class="col-lg-4 col-md-6">
                <article class="page-content">
                    <div class="blog-post-img">
                        <img src="assets/img/blog/5.jpeg" alt="">
                        <div class="blog-tag">
                            <a href="#">Tips</a>
                        </div>
                    </div>
                    <div class="blog-content">
                        <div class="post-meta m-15px-b">
                            <ul>
                                <li><a href="#"><i class="icofont-calendar"></i> 01 Juni 2026</a></li>
                            </ul>
                        </div>
                        <a href="#">
                            <h2 class="m-25px-b">Tips Memilih Hasil Laut yang Segar</h2>
                        </a>
                        <p>Panduan memilih lokan, kepiting, dan hasil laut lainnya yang segar.</p>
                        <a class="read-more" href="#">Selengkapnya</a>
                    </div>
                </article>
            </div>
            <div class="col-lg-4 col-md-6">
                <article class="page-content">
                    <div class="blog-post-img">
                        <img src="assets/img/blog/1.jpeg" alt="">
                        <div class="blog-tag">
                            <a href="#">Berita</a>
                        </div>
                    </div>
                    <div class="blog-content">
                        <div class="post-meta m-15px-b">
                            <ul>
                                <li><a href="#"><i class="icofont-calendar"></i> 28 Mei 2026</a></li>
                            </ul>
                        </div>
                        <a href="#">
                            <h2 class="m-25px-b">Bakau no Umi Dukung Program Ketahanan Pangan</h2>
                        </a>
                        <p>Bekerja sama dengan pemerintah menyediakan hasil laut berkualitas.</p>
                        <a class="read-more" href="#">Selengkapnya</a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="p-80px-tb">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <div class="section-title text-center m-50px-b">
                    <h2>Hubungi <span class="gredient-color">Kami</span></h2>
                </div>
            </div>
        </div>
        <div class="row row-eq-height">
            <div class="col-lg-4 col-md-6 contact-address p-30px">
                <div class="address-box text-center p-15px m-15px-b">
                    <i class="icofont-google-map"></i>
                    <h5>Alamat</h5>
                    <p>Batam, Kepulauan Riau</p>
                </div>
                <div class="address-box text-center p-15px m-15px-b">
                    <i class="icofont-whatsapp"></i>
                    <h5>WhatsApp</h5>
                    <p>08xxxxxxxxxx</p>
                </div>
                <div class="address-box text-center p-15px">
                    <i class="icofont-instagram"></i>
                    <h5>Instagram</h5>
                    <p>@bakau.no.umi</p>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 contact-form p-30px">
                <h3 class="m-25px-b">Kirim Pesan</h3>
                <p class="m-25px-b">Ada pertanyaan? Hubungi kami melalui form di bawah.</p>
                <form id="contact-form" method="post">
                    <div class="mb13">
                        <input name="name" class="contact-name" type="text" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="mb13">
                        <input name="email" class="contact-email" type="email" placeholder="Email" required>
                    </div>
                    <div class="mb13">
                        <input name="subject" class="contact-subject" type="text" placeholder="Subject" required>
                    </div>
                    <div class="mb30">
                        <textarea name="message" class="contact-message" placeholder="Pesan Anda" required></textarea>
                    </div>
                    <button class="btn btn-default btn-filled animated-btn">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="footer" class="p-70px-t p-30px-b footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="footer-top text-center p-30px-tb">
                    <a class="footer-logo" href="#"><img src="assets/img/logo.png" alt="Bakau no Umi"></a>
                    <p>"Hasil Laut Segar dari Nelayan Lokal"</p>
                    <div class="social-network">
                        <a href="#"><i class="social-icon icofont-facebook"></i></a>
                        <a href="#"><i class="social-icon icofont-instagram"></i></a>
                        <a href="#"><i class="social-icon icofont-whatsapp"></i></a>
                        <a href="#"><i class="social-icon icofont-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-copyright p-30px-tb text-center">
                    <p>Copyright &copy; 2026 Bakau no Umi | Design: <a href="#">ThemeAtelier</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="toastNotif" class="toast-notif"></div>

<!-- Scripts -->
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/jquery-migrate-3.0.0.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.textillate.js"></script>
<script src="assets/js/jquery.lettering.js"></script>
<script src="assets/js/jquery.fittext.js"></script>
<script src="assets/js/jquery.ajaxchimp.min.js"></script>
<script src="assets/js/swiper.min.js"></script>
<script src="assets/js/custom.js"></script>

<script>
function showToast(message) {
    var toast = document.getElementById('toastNotif');
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(function() { toast.classList.remove('show'); }, 3000);
}

function addToCart(produkId) {
    <?php if(!isset($_SESSION['customer_id'])): ?>
        if(confirm('Silakan login terlebih dahulu untuk berbelanja.')) {
            window.location.href = 'customer/login.php?redirect=' + encodeURIComponent(window.location.href);
        }
        return;
    <?php endif; ?>
    
    fetch('proses/tambah_keranjang.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'produk_id=' + produkId + '&jumlah=1'
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showToast('Produk ditambahkan ke keranjang!');
            setTimeout(function() { location.reload(); }, 1500);
        } else {
            showToast(data.message || 'Gagal menambahkan');
        }
    });
}

document.getElementById('contact-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Terima kasih! Pesan Anda akan segera kami balas.');
    this.reset();
});
</script>
</body>
</html>