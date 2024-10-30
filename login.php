<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek jika username dan password adalah 'admin'
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['username'] = 'admin';
        $_SESSION['status'] = "login";
        header("Location: dashboard_admin.php");
        exit();
    }

    // Cek username dan password di database untuk pengguna biasa
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session untuk pengguna biasa
            $_SESSION['username'] = $user['username'];
            $_SESSION['status'] = "login";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['status'] = "Belum-login";
            $error_message = "Password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="flex h-screen w-full">

        <!-- Bagian kiri (sidebar) -->
        <div class="hidden md:flex md:w-1/2 bg-cover bg-center text-white flex-col justify-center items-center p-12" style="background-image: url('logo.jpg')">
            <h1 class="text-5xl font-bold mb-4">Halo Selamat Datang!</h1>
            <p class="text-xl">Harap login terlebih dahulu.</p>
            <p class="mt-6 text-sm opacity-75">Semoga anda terbantu dengan web yang saya buat.</p>
            <div class="mt-8">
                <p class="text-xs opacity-75 italic">“Hiduplah Seperti Lery”</p>
            </div>
        </div>

        <!-- Bagian kanan (form login) -->
<div class="flex items-center justify-center w-full md:w-1/2 bg-white p-12">
    <div class="w-full max-w-md">
        <h2 class="text-4xl font-semibold text-center mb-6">Masukan akun anda!</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-lg font-medium mb-2" for="username">Username</label>
                <input class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" name="username" id="username" placeholder="Masukan Username" required />
            </div>
            <div>
                <label class="block text-gray-700 text-lg font-medium mb-2" for="password">Password</label>
                <input class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password" id="password" placeholder="Masukan password" required />
            </div>
            <button type="submit" class="w-full bg-purple-600 text-white py-4 rounded-lg text-lg font-semibold hover:bg-purple-700 transition duration-200">Login</button>
        </form>

        <!-- Menampilkan pesan error jika login gagal -->
        <?php if (isset($error_message)): ?>
            <p class="text-red-500 mt-4 text-center"><?= $error_message ?></p>
        <?php endif; ?>

        <!-- Tautan ke halaman registrasi -->
        <p class="mt-4 text-center">
            Belum punya akun? <a href="register.php" class="text-purple-600 hover:underline">Daftar di sini</a>
        </p>
    </div>
</div>

</body>
</html>
