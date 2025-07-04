<?php
session_start();
include('../koneksi.php');

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit;
}

$username = $_SESSION['username'];

// Jika form disubmit (method POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_artikel = $_POST['id_artikel'];
    $judul = mysqli_real_escape_string($db, $_POST['nama_artikel']);
    $isi = mysqli_real_escape_string($db, $_POST['isi_artikel']);

    // Cek apakah artikel memang milik user
    $cek = mysqli_query($db, "SELECT * FROM tbl_artikel WHERE id_artikel = '$id_artikel' AND penulis = '$username'");
    if (mysqli_num_rows($cek) == 0) {
        echo "<script>alert('Artikel tidak ditemukan atau bukan milik Anda.'); window.location='data_artikel.php';</script>";
        exit;
    }

    // Lakukan update
    $update = mysqli_query($db, "UPDATE tbl_artikel SET nama_artikel = '$judul', isi_artikel = '$isi' WHERE id_artikel = '$id_artikel' AND penulis = '$username'");

    if ($update) {
        echo "<script>alert('Artikel berhasil diperbarui!'); window.location='data_artikel.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui artikel.'); window.location='data_artikel.php';</script>";
    }
    exit;
}

// Jika bukan POST, berarti GET untuk ambil data
$id_artikel = $_GET['id'];
$sql = "SELECT * FROM tbl_artikel WHERE id_artikel = '$id_artikel' AND penulis = '$username'";
$query = mysqli_query($db, $sql);

if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Artikel tidak ditemukan atau bukan milik Anda.'); window.location='data_artikel.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Artikel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-green-900 text-white text-center py-6 shadow">
        <h1 class="text-3xl font-bold">Edit Artikel</h1>
    </header>

    <div class="flex max-w-6xl mx-auto mt-8 px-4">
        <!-- Sidebar -->
        <aside class="w-1/4 bg-white rounded shadow p-4">
            <h2 class="text-xl font-semibold text-green-700 mb-4 text-center">MENU</h2>
            <ul class="space-y-2 text-gray-700">
                <li><a href="beranda_users.php" class="block hover:text-green-600">Beranda</a></li>
                <li><a href="data_artikel.php" class="block font-semibold text-green-800">Artikel Saya</a></li>
                <li>
                    <a href="../auth/logout.php" onclick="return confirm('Apakah anda yakin ingin keluar?');"
                        class="block text-red-600 hover:underline font-medium">Logout</a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="w-3/4 bg-white rounded shadow p-6 ml-6">
            <form action="edit_artikel.php" method="post" class="space-y-6">
                <input type="hidden" name="id_artikel" value="<?php echo $data['id_artikel']; ?>">
                
                <div>
                    <label for="nama_artikel" class="block text-sm font-medium text-gray-700 mb-1">Judul Artikel</label>
                    <input type="text" id="nama_artikel" name="nama_artikel" required
                        value="<?php echo htmlspecialchars($data['nama_artikel']); ?>"
                        class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-500">
                </div>

                <div>
                    <label class="block font-medium">Isi Artikel</label>
                    <textarea id="editor" name="isi" rows="5" class="w-full border p-2 rounded"><?php echo htmlspecialchars($data['isi_artikel']); ?></textarea>
                    <!-- <textarea name="isi" rows="5" required class="w-full border p-2 rounded"></textarea> -->
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="submit"
                        class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 transition">Update</button>
                    <a href="data_artikel.php"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition">Batal</a>
                </div>
            </form>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-green-800 text-white text-center py-4 mt-10">
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
