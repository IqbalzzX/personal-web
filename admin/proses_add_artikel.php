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

// Proses tambah artikel
if (isset($_POST['tambah_artikel'])) {
    $judul = mysqli_real_escape_string($db, $_POST['judul']);
    $kategori = mysqli_real_escape_string($db, $_POST['kategori']);
    $isi = mysqli_real_escape_string($db, $_POST['isi']);
    $penulis = mysqli_real_escape_string($db, $_POST['penulis']);
    $tanggal = mysqli_real_escape_string($db, $_POST['tanggal']);

    $sql = "INSERT INTO tbl_artikel (nama_artikel, kategori, isi_artikel, penulis, tanggal_posting) 
            VALUES ('$judul', '$kategori', '$isi', '$penulis', '$tanggal')";
    mysqli_query($db, $sql);
    header("Location: data_artikel.php");
    exit;
}
?>