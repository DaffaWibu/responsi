<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php?pesan=belum_login");
    exit(); 
}
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
        <h1 class="text-5xl font-bold text-purple-600 text-center mb-6">Selamat Datang di Dashboard</h1>
        <p class="text-xl text-gray-700 text-center mb-6">Selamat Anda sudah berhasil login.</p>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-purple-300">
                <thead class="bg-purple-100">
                    <tr>
                        <th class="border border-purple-500 px-4 py-2 text-left">ID</th>
                        <th class="border border-purple-500 px-4 py-2 text-left">Nama</th>
                        <th class="border border-purple-500 px-4 py-2 text-left">Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    include "db.php"; // connect to the database

                    $no = 1;
                    $ambildata = mysqli_query($conn, "SELECT * FROM user"); 
                    while ($tampil = mysqli_fetch_array($ambildata)) {
                        $rowClass = ($no % 2 == 0) ? 'bg-purple-50' : ''; 
                        echo "
                        <tr class='hover:bg-purple-100 transition duration-150 $rowClass'>
                            <td class='border border-purple-500 px-4 py-2'>$no</td>
                            <td class='border border-purple-500 px-4 py-2'>{$tampil['nama']}</td>
                            <td class='border border-purple-500 px-4 py-2'>{$tampil['username']}</td>
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
