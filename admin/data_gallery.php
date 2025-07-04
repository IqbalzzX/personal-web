<?php
include('../koneksi.php');
session_start();
if (!isset($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit;
}

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
  <title>Kelola Gallery</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Header -->
<header class="bg-blue-900 text-white text-center py-6 shadow">
  <h1 class="text-3xl font-bold">Kelola Gallery</h1>
</header>

<div class="flex max-w-7xl mx-auto mt-8 px-4">
  <!-- Sidebar -->
  <aside class="w-1/4 bg-white rounded shadow p-4">
    <h2 class="text-xl font-semibold text-blue-700 mb-4 text-center">MENU</h2>
    <ul class="space-y-2 text-gray-700">
      <li><a href="beranda_admin.php" class="block hover:text-blue-600">Beranda</a></li>
      <li><a href="data_artikel.php" class="block hover:text-blue-600">Kelola Artikel</a></li>
      <li><a href="data_berita.php" class="block hover:text-blue600">Kelola Berita</a></li>
      <li><a href="data_gallery.php" class="block font-semibold text-blue-800">Kelola Gallery</a></li>
      <li><a href="about.php" class="block hover:text-blue-600">About</a></li>
      <li><a href="admin_video.php" class="block hover:text-blue-600">Video</a></li>
      <li>
        <a href="../auth/logout.php" onclick="return confirm('Apakah anda yakin ingin keluar?');"
           class="block text-red-600 hover:underline font-medium">Logout</a>
      </li>
    </ul>
  </aside>

  <!-- Main Content -->
  <main class="w-3/4 bg-white rounded shadow p-6 ml-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-gray-800">Daftar Gallery</h2>
      <a href="add_gallery.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">+ Tambah Gambar</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <?php
      $sql = "SELECT * FROM tbl_gallery";
      $query = mysqli_query($db, $sql);
      if (mysqli_num_rows($query) === 0) {
        echo "<p class='text-gray-500'>Belum ada gambar di galeri.</p>";
      }
      while ($data = mysqli_fetch_array($query)) {
          $img = htmlspecialchars($data['foto']);
          $img_path = "../images/" . rawurlencode($img); // menangani spasi & karakter khusus
          echo "<div class='bg-gray-50 border rounded shadow overflow-hidden'>";
          echo "<img src='{$img_path}' class='w-full h-48 object-cover'>";
          echo "<div class='p-4'>";
          echo "<p class='font-semibold text-gray-800 mb-2'>" . htmlspecialchars($data['judul']) . "</p>";
          echo "<div class='flex justify-between text-sm'>";
          echo "<a href='edit_gallery.php?id_gallery={$data['id_gallery']}' class='text-blue-600 hover:underline'>Edit</a>";
          echo "<a href='delete_gallery.php?id_gallery={$data['id_gallery']}' onclick='return confirm(\"Yakin ingin menghapus?\")' class='text-red-600 hover:underline'>Hapus</a>";
          echo "</div></div></div>";
      }
      ?>
    </div>
  </main>
</div>

<!-- Footer -->
<footer class="bg-blue-800 text-white text-center py-4 mt-10">
  &copy; <?php echo date('Y'); ?> | Created by Iqbal Rizki Maulana
</footer>

</body>
</html>
