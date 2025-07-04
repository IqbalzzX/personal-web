<?php
$koneksi = new mysqli("localhost", "root", "", "db_iqbal_d1a240073");

// Ambil data video dari database
$data = $koneksi->query("SELECT * FROM tbl_video LIMIT 1")->fetch_assoc();
$url_video = $data ? $data['url_video'] : '';
$id_video = $data ? $data['id'] : '';

// Tambah jika belum ada dan form disubmit
if (isset($_POST['simpan']) && !$data) {
    $url_baru = $_POST['url_video'];
    $koneksi->query("INSERT INTO tbl_video (url_video) VALUES ('$url_baru')");
    header("Location: admin_video.php");
    exit;
}

// Update video jika sudah ada
if (isset($_POST['update']) && $data) {
    $url_baru = $_POST['url_video'];
    $koneksi->query("UPDATE tbl_video SET url_video='$url_baru' WHERE id=$id_video");
    header("Location: admin_video.php");
    exit;
}

// Hapus video
if (isset($_GET['hapus']) && $data) {
    $koneksi->query("DELETE FROM tbl_video WHERE id=$id_video");
    header("Location: admin_video.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Video YouTube</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<!-- Header -->
<header class="bg-blue-900 text-white text-center py-6 shadow">
<h1 class="text-3xl font-bold">Kelola Halaman Video</h1>
</header>
<div class="flex max-w-7xl mx-auto mt-8 px-4">
<!-- Sidebar -->
<aside class="w-1/4 bg-white rounded shadow p-4">

<h2 class="text-xl font-semibold text-blue-700 mb-4 text-
center">MENU</h2>

<ul class="space-y-2 text-gray-700">

<li><a href="beranda_admin.php" class="block hover:text-blue-
600">Beranda</a></li>

<li><a href="data_artikel.php" class="block hover:text-blue-
600">Kelola Artikel</a></li>

<li><a href="data_berita.php" class="block hover:text-blue-
600">Kelola Berita</a></li>

<li><a href="data_gallery.php" class="block hover:text-blue-
600">Kelola Gallery</a></li>

<li><a href="about.php" class="block hover:text-blue-
600">About</a></li>

<li><a href="admin_video.php" class="block font-semibold text-blue-
800">Video</a></li>

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
    <h2 class="text-xl font-semibold text-blue-700 mb-6">Kelola Video YouTube</h2>

    <?php if ($url_video): ?>
        <div class="mb-6">
            <iframe class="w-full h-64 rounded-lg shadow" src="<?= $url_video ?>" allowfullscreen></iframe>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
    <div>
        <label for="url_video" class="block text-sm font-medium text-gray-700">Embed URL <span class="text-red-500">*</span></label>
        <input type="text" name="url_video" id="url_video" value="<?= htmlspecialchars($url_video) ?>"
               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2" required>
        <p class="text-sm text-gray-500 mt-1">Contoh: https://www.youtube.com/embed/abcd</p>
    </div>

    <div class="flex flex-wrap gap-4">
        <button type="submit" name="<?= $data ? 'update' : 'simpan' ?>"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow">
            <?= $data ? 'Update' : 'Simpan' ?>
        </button>

        <?php if ($data): ?>
            <a href="<?= $url_video ?>" target="_blank"
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-lg shadow inline-block">
                Preview
            </a>

            <a href="?hapus=1" onclick="return confirm('Yakin ingin menghapus video ini?')"
               class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-lg shadow inline-block">
                Hapus Video
            </a>
        <?php endif; ?>
    </div>
</form>
</main>
</div>
<!-- Footer -->
<footer class="bg-blue-800 text-white text-center py-4 mt-10">
&copy; <?php echo date('Y'); ?> | Created by Iqbal Rizki Maulana
</footer>
</body>
</html>
