<?php
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']); // langsung simpan tanpa hash
    $level = 'user'; // default user biasa

    // Cek username
    $cek = mysqli_query($db, "SELECT * FROM tbl_user WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah terdaftar!');window.location='register.php';</script>";
    } else {
        $sql = "INSERT INTO tbl_user (username, password, level) VALUES ('$username', '$password', '$level')";
        if (mysqli_query($db, $sql)) {
            echo "<script>alert('Registrasi berhasil! Silakan login.');window.location='login.php';</script>";
        } else {
            echo "<script>alert('Gagal mendaftar.');window.location='register.php';</script>";
        }
    }
}
?>
