<?php 
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'user') {
    header('location:../auth/login.php');
    exit;
}
require_once("../koneksi.php");

$username = $_SESSION['username'];
$sql = "SELECT * FROM tbl_user WHERE username = '$username'";
$query = mysqli_query($db, $sql);
$hasil = mysqli_fetch_array($query);

// Hitung total artikel (misal untuk statistik)
$jumlah_artikel = mysqli_num_rows(mysqli_query($db, "SELECT id_artikel FROM tbl_artikel"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<!-- Header -->
<header class="bg-green-800 text-white text-center py-6 shadow">
    <h1 class="text-3xl font-bold">Selamat Datang, User</h1>
</header>

<!-- Container -->
<div class="flex max-w-7xl mx-auto mt-8 px-4">
    <!-- Sidebar -->
    <aside class="w-1/4 bg-white rounded shadow p-4">
        <h2 class="text-xl font-semibold text-green-700 mb-4 text-center">MENU</h2>
        <ul class="space-y-2 text-gray-700">
            <li><a href="beranda_users.php" class="block hover:text-green-600">Beranda</a></li>
            <li><a href="data_artikel.php" class="block hover:text-green-600">Artikel</a></li>
            <li>
                <a href="../auth/logout.php" onclick="return confirm('Apakah anda yakin ingin keluar?');"
                   class="block text-red-600 hover:underline font-medium">Logout</a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="w-3/4 bg-white rounded shadow p-6 ml-6">
        <div class="text-lg text-gray-800 mb-4">
            Halo, <strong class="text-green-700"><?php echo $_SESSION['username']; ?></strong>! Selamat datang kembali 😊
        </div>
        <p class="text-sm text-gray-500">Gunakan menu di samping untuk mengelola artikel.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <div class="bg-white shadow rounded p-4 text-center border-t-4 border-green-600">
                <h3 class="text-xl font-semibold text-green-700">Jumlah Artikel</h3>
                <p class="text-3xl font-bold text-gray-800"><?php echo $jumlah_artikel; ?></p>
            </div>
        </div>
    </main>
</div>

<!-- Footer -->
<footer class="bg-green-800 text-white text-center py-4 mt-10">
    &copy; <?php echo date('Y'); ?> | Created by Iqbal Rizki Maulana
</footer>
</body>
</html>
