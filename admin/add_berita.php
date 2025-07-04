<?php
include('../koneksi.php');
session_start();
if (!isset($_SESSION['username'])) {
header('location:login.php');
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tambah Berita</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<!-- Header -->
<header class="bg-blue-900 text-white text-center py-6 shadow">
<h1 class="text-3xl font-bold">Tambah Berita Baru</h1>
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
<!-- Form Tambah Artikel -->
            <form action="proses_add_berita.php" method="post" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block font-medium">Judul Artikel</label>
                    <input type="text" name="judul" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-medium">Slug</label>
                    <input type="text" name="slug" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-medium">Gambar</label>
                    <input type="file" name="gambar" accept="image/*" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-medium">Tag</label>
                    <input type="text" name="tag" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-medium">Penulis</label>
                    <input type="text" name="penulis" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <select class="w-full border p-2 rounded" name="status">
                        <option value="draft">Draft</option>
                        <option value="publish">Publish</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Tanggal Posting</label>
                    <input type="date" name="created_at" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block font-medium">Isi Berita</label>
                    <textarea id="editor" name="isi" rows="5" class="w-full border p-2 rounded"></textarea>
                    <!-- <textarea name="isi" rows="5" required class="w-full border p-2 rounded"></textarea> -->
                </div>
                <div>
                    <button type="submit" name="tambah_berita" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
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
        .create(document.querySelector('#editor'))
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

        // Validasi sebelum submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function (e) {
        const isi = editorInstance.getData().trim();
        if (isi === '') {
            alert('Isi artikel tidak boleh kosong!');
            e.preventDefault(); // cegah submit
        }
    });
</script>
</body>
</html>