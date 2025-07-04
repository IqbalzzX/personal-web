<?php
session_start();
include("../koneksi.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$old_username = $_SESSION['username'];

// Ambil data user
$query = mysqli_query($db, "SELECT * FROM tbl_user WHERE username = '$old_username'");
$user = mysqli_fetch_assoc($query);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = mysqli_real_escape_string($db, $_POST['username']);
    $password     = $_POST['password'];

    // Validasi input
    if (empty($new_username)) {
        $error = "Username tidak boleh kosong.";
    } else {
        // Cek apakah password ingin diubah
        $sql = "UPDATE tbl_user SET username = '$new_username'";
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = '$hashed'";
        }
        $sql .= " WHERE username = '$old_username'";

        if (mysqli_query($db, $sql)) {
            $_SESSION['username'] = $new_username;
            $success = "Profil berhasil diperbarui.";
            $old_username = $new_username;
            $query = mysqli_query($db, "SELECT * FROM tbl_user WHERE username = '$old_username'");
            $user = mysqli_fetch_assoc($query);
        } else {
            $error = "Gagal memperbarui profil.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold text-center mb-4">Edit Profil</h2>

        <?php if ($success): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required
                    class="w-full border border-gray-300 p-2 rounded mt-1">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Password Baru (Opsional)</label>
                <input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah"
                    class="w-full border border-gray-300 p-2 rounded mt-1">
            </div>

            <div class="flex justify-between">
                <a href="dashboard.php" class="text-blue-600 hover:underline">← Kembali</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</body>
</html>
