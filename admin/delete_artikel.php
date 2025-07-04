<?php
include('../koneksi.php');
session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM berita WHERE id = $id";
    $query = mysqli_query($db, $sql);
    
    if ($query) {
        echo "<script>alert('Berita berhasil dihapus.'); window.location='data_berita.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus berita.'); history.back();</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan.'); history.back();</script>";
}
?>
