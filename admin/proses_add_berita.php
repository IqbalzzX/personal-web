<?php
include('../koneksi.php');
session_start();
if (!isset($_SESSION['username'])) {
header('location:login.php');
exit;
}

// Cek apakah pengguna sudah login dan memiliki level admin
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk admin.";
    exit;
}

// Proses tambah berita
if (isset($_POST['tambah_berita'])) {
    $judul = $_POST['judul'];
    $slug = strtolower(str_replace(' ', '-', $judul));
    $tag = $_POST['tag'];
    $penulis = $_POST['penulis'];
    $status = $_POST['status'];
    $isi = $_POST['isi'];
    $created_at = date('Y-m-d H:i:s');
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    // Pastikan folder 'images' tersedia
    $folder = '../images/';
    $target_file = $folder . basename($gambar);

// Pindahkan file gambar dari temporary folder ke folder tujuan
if (move_uploaded_file($tmp, $target_file)) {
    $sql = "INSERT INTO berita (judul, slug, gambar, tag, penulis, status, isi, created_at) 
            VALUES ('$judul', '$slug', '$target_file', '$tag', '$penulis', '$status', '$isi', '$created_at')";
    mysqli_query($db, $sql);
    header("Location: data_berita.php");
    exit;
} else {
    echo "Upload gambar gagal.";
}
}
?>