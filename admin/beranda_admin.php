<?php
session_start();
if (!isset($_SESSION['username'])) {
header('location: ../auth/login.php');
exit;
}
require_once("../koneksi.php");
$username = $_SESSION['username'];
$sql = "SELECT * FROM tbl_user WHERE username = '$username'";
$query = mysqli_query($db, $sql);
$hasil = mysqli_fetch_array($query);

// Cek apakah pengguna sudah login dan memiliki level admin
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk admin.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<!-- Header -->
<header class="bg-blue-900 text-white text-center py-6 shadow">
<h1 class="text-3xl font-bold">Halaman Administrator</h1>
</header>
<!-- Container -->
<div class="flex max-w-7xl mx-auto mt-8 px-4">
<!-- Sidebar -->
<aside class="w-1/4 bg-white rounded shadow p-4">
<h2 class="text-xl font-semibold text-blue-700 mb-4 text-center">MENU</h2>
<ul class="space-y-2 text-gray-700">
    <li><a href="beranda_admin.php" class="block hover:text-blue-
600">Beranda</a></li>

<li><a href="data_artikel.php" class="block hover:text-blue-600">Kelola
Artikel</a></li>
<li><a href="data_berita.php" class="block hover:text-blue-600">Kelola
Berita</a></li>
<li><a href="data_gallery.php" class="block hover:text-blue-600">Kelola
Gallery</a></li>
<li><a href="about.php" class="block hover:text-blue-600">About</a></li>
<li><a href="admin_video.php" class="block hover:text-blue-600">Video</a></li>
<li>
<a href="../auth/logout.php" onclick="return confirm('Apakah anda yakin ingin

keluar?');"

class="block text-red-600 hover:underline font-medium">Logout</a>
</li>
</ul>
</aside>
<?php
// Hitung total users
$jumlah_users = mysqli_num_rows(mysqli_query($db, "SELECT id FROM tbl_user"));
// Hitung total artikel
$jumlah_artikel = mysqli_num_rows(mysqli_query($db, "SELECT id_artikel FROM
tbl_artikel"));
// Hitung total berita
$jumlah_berita = mysqli_num_rows(mysqli_query($db, "SELECT id FROM berita"));
// Hitung total gallery
$jumlah_gallery = mysqli_num_rows(mysqli_query($db, "SELECT id_gallery FROM
tbl_gallery"));
// Hitung total video
$jumlah_video = mysqli_num_rows(mysqli_query($db, "SELECT id FROM tbl_video"));
// Hitung status berita
$status_berita_publish = mysqli_num_rows(mysqli_query($db, "SELECT id FROM berita WHERE status = 'publish'"));
// Hitung status berita draft
$status_berita_draft = mysqli_num_rows(mysqli_query($db, "SELECT id FROM berita WHERE status = 'draft'"));
?>
<!-- Main Content -->
<main class="w-3/4 bg-white rounded shadow p-6 ml-6">
<div class="text-lg text-gray-800 mb-4">
Halo, <strong class="text-blue-700"><?php echo $_SESSION['username'];
?></strong>! Apa kabar? 😊
</div>
<p class="text-sm text-gray-500">Silakan gunakan menu di samping untuk
mengelola data.</p>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">

<div class="bg-white shadow rounded p-4 text-center border-t-4 border-
blue-600">

<h3 class="text-xl font-semibold text-purple-700">Users</h3>
<p class="text-3xl font-bold text-gray-800"><?php echo

$jumlah_users; ?></p>
</div>

<div class="bg-white shadow rounded p-4 text-center border-t-4 border-
blue-600">

<h3 class="text-xl font-semibold text-blue-700">Artikel</h3>
<p class="text-3xl font-bold text-gray-800"><?php echo

$jumlah_artikel; ?></p>
</div>

<div class="bg-white shadow rounded p-4 text-center border-t-4 border-
red-600">

<h3 class="text-xl font-semibold text-red-700">Berita</h3>
<p class="text-3xl font-bold text-gray-800"><?php echo
$jumlah_berita; ?></p>
</div>


<div class="bg-white shadow rounded p-4 text-center border-t-4 border-
green-600">

<h3 class="text-xl font-semibold text-green-700">Gallery</h3>
<p class="text-3xl font-bold text-gray-800"><?php echo

$jumlah_gallery; ?></p>
</div>

<div class="bg-white shadow rounded p-4 text-center border-t-4 border-
yellow-600">

<h3 class="text-xl font-semibold text-yellow-700">Video</h3>
<p class="text-3xl font-bold text-gray-800"><?php echo$jumlah_video; ?></p>
</div>

<div class="bg-white shadow rounded p-4 text-center border-t-4 border-
yellow-600">

<h3 class="text-xl font-semibold text-green-700">Status Berita Publish</h3>
<p class="text-3xl font-bold text-gray-800"><?php echo$status_berita_publish; ?></p>
</div>

<div class="bg-white shadow rounded p-4 text-center border-t-4 border-
yellow-600">

<h3 class="text-xl font-semibold text-red-700">Status Berita Draft</h3>
<p class="text-3xl font-bold text-gray-800"><?php echo$status_berita_draft; ?></p>
</div>

</div>
</main>
</div>
<!-- Footer -->
<footer class="bg-blue-800 text-white text-center py-4 mt-10">
&copy; <?php echo date('Y'); ?> | Created by Iqbal Rizki Maulana
</footer>
</body>
</html>