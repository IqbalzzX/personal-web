<?php
require_once("../koneksi.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-100 to-green-300 min-h-screen flex items-center justify-center">
<div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
<h2 class="text-2xl font-bold text-center text-green-700 mb-6">Register</h2>

<form action="proses_register.php" method="post" class="space-y-5">
    <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" id="username" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
    </div>

    <input type="submit" name="register" value="Register" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 cursor-pointer">
    <div class="text-center mt-4">
        Sudah punya akun? <a href="login.php" class="text-green-600 hover:underline">Login Sekarang</a>
    </div>
</form>

<div class="text-center text-sm text-gray-600 mt-6">
&copy; <?php echo date('Y'); ?> - Iqbal Rizki Maulana
</div>
</div>
</body>
</html>
