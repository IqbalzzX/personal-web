<?php
include('../koneksi.php');
session_start();
if (!isset($_SESSION['username'])) {
header('location:login.php');
exit;
}
$id = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

if (!$id) {
    echo "<script>alert('ID Berita tidak ditemukan.'); window.location='data_berita.php';</script>";
    exit;
}

$sql = "SELECT * FROM berita WHERE id = '$id'";
$query = mysqli_query($db, $sql);
$data = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Berita</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
<header class="bg-blue-900 text-white text-center py-6 shadow">
<h1 class="text-3xl font-bold">Edit Berita</h1>
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

<li><a href="data_berita.php" class="block font-semibold text-blue-
800">Kelola Berita</a></li>

<li><a href="data_gallery.php" class="block hover:text-blue-
600">Kelola Gallery</a></li>

<li><a href="about.php" class="block hover:text-blue-
600">About</a></li>

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

<form action="proses_edit_berita.php" method="post" enctype="multipart/form-data" class="space-y-6">

<input type="hidden" name="id" value="<?php echo
$data['id']; ?>">
<div>

<label for="nama_artikel" class="block text-sm font-medium text-
gray-700 mb-1">Judul Berita</label>

<input type="text" id="judul" name="judul" required
value="<?php echo htmlspecialchars($data['judul']); ?>"
class="w-full p-2 border rounded focus:outline-none focus:ring

focus:border-blue-500">
</div>
<div>
<label for="slug" class="block text-sm font-medium text-
gray-700 mb-1">Slug</label>
<input type="text" id="slug" name="slug" required
value="<?php echo htmlspecialchars($data['slug']); ?>"
class="w-full p-2 border rounded focus:outline-none focus:ring
focus:border-blue-500">
</div>
<div>
    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-1">Ganti Gambar (Opsional)</label>
    <input type="file" id="gambar" name="gambar" accept="image/*" class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-500">
    <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($data['gambar']); ?>">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Saat Ini</label>
    <img src="../images/<?php echo htmlspecialchars($data['gambar']); ?>" alt="Gambar Saat Ini" class="w-32 h-32 object-cover mb-2 rounded">
</div>
<div>
<label for="tag" class="block text-sm font-medium text-
gray-700 mb-1">Tag</label>
<input type="text" id="tag" name="tag" required
value="<?php echo htmlspecialchars($data['tag']); ?>"
class="w-full p-2 border rounded focus:outline-none focus:ring
focus:border-blue-500">
</div>
<div>
<label for="penulis" class="block text-sm font-medium text-
gray-700 mb-1">Penulis</label>
<input type="text" id="penulis" name="penulis" required
value="<?php echo htmlspecialchars($data['penulis']); ?>" 
class="w-full p-2 border rounded focus:outline-none focus:ring
focus:border-blue-500">
</div>
<div>
<label for="status" class="block text-sm font-medium text-
gray-700 mb-1">Status</label>
<select id="status" name="status" required class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-500">
  <option value="draft" <?= $data['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
  <option value="publish" <?= $data['status'] == 'publish' ? 'selected' : '' ?>>Publish</option>
</select>

</div>
<div>
<label for="isi" class="block text-sm font-medium text-
gray-700 mb-1">Isi Artikel</label>
<textarea id="editor" name="isi" rows="5" class="w-full p-2 border rounded focus:outline-none focus:ring
focus:border-blue-500"><?php echo htmlspecialchars($data['isi']);
?></textarea>
</div>
<br>
<div class="flex justify-end space-x-4">
<button type="submit"

class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-
800 transition">Update</button>

<a href="data_artikel.php"

class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-
gray-400 transition">Batal</a>

</div>
</form>
</main>
</div>
<!-- Footer -->
<footer class="bg-blue-800 text-white text-center py-4 mt-10">
&copy; <?php echo date('Y'); ?> | Created by Iqbal Rizki Maulana
</footer>
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    let editorInstance;
    ClassicEditor
    .create(document.querySelector('#editor'), {
        ckfinder: {
            uploadUrl: 'upload_image.php'
        }
    })
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });
</script>
</body>
</html>