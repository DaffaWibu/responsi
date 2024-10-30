<?php
session_start();
include("db.php");


if ($_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit(); 
}

// purpleirect to login if not logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle add user form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    $stmt = $conn->prepare("INSERT INTO user (nama, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $username, $password);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard_admin.php"); // Refresh to clear POST data and show the updated list
    exit();
}

// Handle delete user request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $userId = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard_admin.php"); // Refresh to update list after deletion
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-4xl p-4 bg-white rounded-lg shadow-lg">
        <h1 class="text-5xl font-bold text-purple-600 text-center mb-6">Dashboard Admin</h1>
        <p class="text-xl text-gray-700 text-center mb-6">Selamat datang, Admin.</p>

        <!-- Form Tambah Pengguna -->
        <form action="dashboard_admin.php" method="POST" class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Tambah Pengguna</h2>
            <div class="flex flex-wrap -mx-3 mb-4">
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nama">Nama</label>
                    <input name="nama" type="text" id="nama" requipurple class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div class="w-full md:w-1/3 px-3 mb-4 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                    <input name="username" type="text" id="username" requipurple class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                    <input name="password" type="password" id="password" requipurple class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
            </div>
            <button type="submit" name="add_user" class="bg-purple-600 text-white py-2 px-4 rounded-lg text-lg font-semibold hover:bg-purple-700 transition duration-200">Tambah Pengguna</button>
        </form>

        <!-- Tabel Pengguna -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-purple-300">
                <thead class="bg-purple-100">
                    <tr>
                        <th class="border border-purple-500 px-4 py-2 text-left">ID</th>
                        <th class="border border-purple-500 px-4 py-2 text-left">Nama</th>
                        <th class="border border-purple-500 px-4 py-2 text-left">Username</th>
                        <th class="border border-purple-500 px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $ambildata = mysqli_query($conn, "SELECT * FROM user"); 
                    while ($tampil = mysqli_fetch_array($ambildata)) {
                        $rowClass = ($no % 2 == 0) ? 'bg-purple-50' : ''; 
                        echo "
                        <tr class='hover:bg-purple-100 transition duration-150 $rowClass'>
                            <td class='border border-purple-500 px-4 py-2'>$no</td>
                            <td class='border border-purple-500 px-4 py-2'>{$tampil['nama']}</td>
                            <td class='border border-purple-500 px-4 py-2'>{$tampil['username']}</td>
                            <td class='border border-purple-500 px-4 py-2'>
                                <form action='dashboard_admin.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$tampil['id']}'>
                                    <button type='submit' name='delete_user' class='text-purple-600 hover:text-purple-800 font-bold'>❌</button>
                                </form>
                            </td>
                        </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <a href="logout.php" class="mt-6 inline-block bg-purple-600 text-white py-3 px-8 rounded-lg text-lg font-semibold hover:bg-purple-700 transition duration-200">Logout</a>
    </div>
</body>
</html>
