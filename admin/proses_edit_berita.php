<?php
include('../koneksi.php');
session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $judul = mysqli_real_escape_string($db, $_POST['judul']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    $tag = mysqli_real_escape_string($db, $_POST['tag']);
    $penulis = mysqli_real_escape_string($db, $_POST['penulis']);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    $isi = mysqli_real_escape_string($db, $_POST['isi']);

    $update_gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $folder = '../images/';
        $target_file = $folder . basename($gambar);

        // Pindahkan file gambar
        if (move_uploaded_file($tmp, $target_file)) {
            $update_gambar = ", gambar = '$target_file'";
        } else {
            echo "<script>alert('Gagal mengupload gambar baru.'); history.back();</script>";
            exit;
        }
    }

    // Query update
    $sql = "UPDATE berita 
            SET judul = '$judul', slug = '$slug', tag = '$tag', penulis = '$penulis', status = '$status', isi = '$isi' $update_gambar 
            WHERE id = '$id'";

    if (mysqli_query($db, $sql)) {
        echo "<script>alert('Berita berhasil diperbarui.'); window.location='data_berita.php';</script>";
    } else {
        echo "<script>alert('Gagal mengedit berita.'); history.back();</script>";
    }
} else {
    echo "Metode tidak diperbolehkan.";
}
?>
