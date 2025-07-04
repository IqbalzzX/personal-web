<?php
session_start();
include('../koneksi.php');

$username = mysqli_real_escape_string($db, $_POST['username']);
$password = mysqli_real_escape_string($db, $_POST['password']);

// Ambil user berdasarkan username saja
$sql = "SELECT * FROM tbl_user WHERE username='$username'";
$query = mysqli_query($db, $sql);

if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);

    // Karena password disimpan dalam plaintext, langsung bandingkan
    if ($user['password'] === $password) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];

        // Arahkan berdasarkan level
        if ($user['level'] == 'admin') {
            header('Location: ../admin/beranda_admin.php');
        } else if ($user['level'] == 'user') {
            header('Location: ../users/beranda_users.php');
        }
        exit;
    }
}

// Jika gagal
echo "<script>alert('Login gagal! Username atau password salah.');window.location='login.php';</script>";
?>
