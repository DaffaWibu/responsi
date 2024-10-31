<?php
session_start();
include("db.php");

if ($_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit(); 
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-4xl p-4 bg-white rounded-lg shadow-lg">
        <h1 class="text-5xl font-bold text-purple-600 text-center mb-6">Dashboard</h1>
        <p class="text-xl text-gray-700 text-center mb-6">Selamat datang, <?= htmlspecialchars($user['nama']) ?>.</p>

        <table class="min-w-full bg-white border border-purple-300">
            <thead class="bg-purple-100">
                <tr>
                    <th class="border border-purple-500 px-4 py-2 text-left">ID</th>
                    <th class="border border-purple-500 px-4 py-2 text-left">Nama</th>
                    <th class="border border-purple-500 px-4 py-2 text-left">Username</th>
                    <th class="border border-purple-500 px-4 py-2 text-left">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <tr class='hover:bg-purple-100 transition duration-150'>
                    <td class='border border-purple-500 px-4 py-2'><?= htmlspecialchars($user['id']) ?></td>
                    <td class='border border-purple-500 px-4 py-2'><?= htmlspecialchars($user['nama']) ?></td>
                    <td class='border border-purple-500 px-4 py-2'><?= htmlspecialchars($user['username']) ?></td>
                    <td class='border border-purple-500 px-4 py-2'><?= htmlspecialchars($user['nilai']) ?></td>
                </tr>
            </tbody>
        </table>

        <a href="logout.php" class="mt-6 inline-block bg-purple-600 text-white py-3 px-8 rounded-lg text-lg font-semibold hover:bg-purple-700 transition duration-200">Logout</a>
    </div>
</body>
</html>
