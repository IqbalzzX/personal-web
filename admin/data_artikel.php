<?php
session_start();
include "../koneksi.php";

// Cek apakah pengguna sudah login dan memiliki level admin
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk admin.";
    exit;
}

// Proses hapus artikel
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($db, "DELETE FROM tbl_artikel WHERE id_artikel = $id");
    header("Location: data_artikel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Data Artikel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Penting untuk mobile -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    <!-- Header -->
    <header class="bg-blue-900 text-white text-center py-6 shadow">
        <h1 class="text-3xl font-bold">// HALAMAN ADMIN //</h1>
    </header>

    <!-- Kontainer Utama -->
    <div class="flex flex-col md:flex-row max-w-7xl mx-auto mt-8 px-4 space-y-4 md:space-y-0 md:space-x-6">
        
        <!-- Sidebar -->
        <aside class="w-full md:w-1/4 bg-white rounded shadow p-4">
            <h2 class="text-xl font-semibold text-blue-700 mb-4 text-center">MENU</h2>
            <ul class="space-y-2 text-gray-700">
                <li><a href="beranda_admin.php" class="block hover:text-blue-600">Beranda</a></li>
                <li><a href="data_artikel.php" class="block font-semibold text-blue-800">Kelola Artikel</a></li>
                <li><a href="data_berita.php" class="block hover:text-blue-600">Kelola Berita</a></li>
                <li><a href="data_gallery.php" class="block hover:text-blue-600">Kelola Gallery</a></li>
                <li><a href="about.php" class="block hover:text-blue-600">About</a></li>
                <li><a href="admin_video.php" class="block hover:text-blue-600">Video</a></li>
                <li>
                    <a href="../auth/logout.php" onclick="return confirm('Apakah anda yakin ingin keluar?');" class="block text-red-600 hover:underline font-medium">Logout</a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="w-full md:w-3/4 bg-white rounded shadow p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 space-y-2 sm:space-y-0">
                <h2 class="text-xl font-bold text-gray-800">Daftar Artikel</h2>
                <a href="add_artikel.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">+ Tambah Artikel</a>
            </div>

            <!-- Wrapper untuk scroll tabel -->
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border px-4 py-2">No</th>
                            <th class="border px-4 py-2">Judul</th>
                            <th class="border px-4 py-2">Kategori</th>
                            <th class="border px-4 py-2">Penulis</th>
                            <th class="border px-4 py-2">Tanggal</th>
                            <th class="border px-4 py-2">Views</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tbl_artikel ORDER BY id_artikel DESC";
                        $query = mysqli_query($db, $sql);
                        $no = 1;
                        while ($data = mysqli_fetch_array($query)) {
                            echo "<tr>";
                            echo "<td class='border px-4 py-2 text-center'>{$no}</td>";
                            echo "<td class='border px-4 py-2'>" . htmlspecialchars($data['nama_artikel']) . "</td>";
                            echo "<td class='border px-4 py-2'>" . htmlspecialchars($data['kategori']) . "</td>";
                            echo "<td class='border px-4 py-2'>" . htmlspecialchars($data['penulis']) . "</td>";
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
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-800 text-white text-center py-4 mt-10">
        &copy; <?php echo date('Y'); ?> | Created by Iqbal Rizki Maulana
    </footer>
</body>
</html>
