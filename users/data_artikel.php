<?php
include('../koneksi.php');
session_start();

// Cek apakah pengguna sudah login dan levelnya user
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'user') {
    echo "Akses ditolak. Halaman ini hanya untuk pengguna biasa.";
    exit;
}

$username = $_SESSION['username']; // pastikan username disimpan saat login

// Proses hapus artikel (hanya jika artikel milik user ini)
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $cek = mysqli_query($db, "SELECT * FROM tbl_artikel WHERE id_artikel = $id AND penulis = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($db, "DELETE FROM tbl_artikel WHERE id_artikel = $id");
    }
    header("Location: data_artikel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tambah Artikel</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<!-- Header -->
<header class="bg-green-900 text-white text-center py-6 shadow">
<h1 class="text-3xl font-bold">// HALAMAN USER //</h1>
</header>
<div class="flex max-w-7xl mx-auto mt-8 px-4">
<!-- Sidebar -->
<aside class="w-1/4 bg-white rounded shadow p-4">

<h2 class="text-xl font-semibold text-green-700 mb-4 text-
center">MENU</h2>

<ul class="space-y-2 text-gray-700">

<li><a href="beranda_users.php" class="block hover:text-green-600">Beranda</a></li>
<li><a href="data_artikel.php" class="block font-semibold text-green-800">Edit Artikel</a></li>
<li>
<a href="../auth/logout.php" onclick="return confirm('Apakah anda yakin

ingin keluar?');"

class="block text-red-600 hover:underline font-
medium">Logout</a>

</li>
</ul>
</aside>
<!-- Main Content -->
<main class="w-3/4 bg-white rounded shadow p-6 ml-6">
<div class="flex justify-between items-center mb-4">
<h2 class="text-xl font-bold text-gray-800">Daftar Artikel</h2>
<a href="add_artikel.php"
class="bg-green-600 text-white px-4 py-2 rounded hover:bg-blue-700

transition">+ Tambah Artikel</a>
</div>
<table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">No</th>
                        <th class="border px-4 py-2">Judul</th>
                        <th class="border px-4 py-2">Kategori</th>
                        <th class="border px-4 py-2">Tanggal</th>
                        <th class="border px-4 py-2">Views</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM tbl_artikel WHERE penulis = '$username' ORDER BY id_artikel DESC";
                    $query = mysqli_query($db, $sql);
                    $no = 1;
                    while ($data = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td class='border px-4 py-2 text-center'>{$no}</td>";
                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($data['nama_artikel']) . "</td>";
                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($data['kategori']) . "</td>";
                        echo "<td class='border px-4 py-2'>" . date('d-m-Y', strtotime($data['tanggal_posting'])) . "</td>";
                        echo "<td class='border px-4 py-2 text-center'>{$data['jumlah_view']}</td>";
                        echo "<td class='border px-4 py-2 text-center'>
                                <a href='edit_artikel.php?id={$data['id_artikel']}' class='text-blue-600 hover:underline'>Edit</a> |
                                <a href='?hapus={$data['id_artikel']}' onclick=\"return confirm('Yakin ingin menghapus artikel ini?')\" class='text-red-600 hover:underline'>Hapus</a>
                              </td>";
                        echo "</tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
</main>
</div>
<!-- Footer -->
<footer class="bg-green-800 text-white text-center py-4 mt-10">
&copy; <?php echo date('Y'); ?> | Created by Iqbal Riki Maulana
</footer>
</body>
</html>