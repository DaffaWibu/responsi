<?php
session_start();
include("db.php");

if ($_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit(); 
}

// Check if user is logged in as dosen
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'dosen') {
    header("Location: login.php");
    exit();
}

// Handle update nilai form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_nilai'])) {
    $userId = $_POST['id'];
    $nilai = $_POST['nilai'];

    $stmt = $conn->prepare("UPDATE user SET nilai = ? WHERE id = ?");
    $stmt->bind_param("di", $nilai, $userId); // "di" means decimal and integer
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard_dosen.php"); // Refresh to show updated list
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-4xl p-4 bg-white rounded-lg shadow-lg">
        <h1 class="text-5xl font-bold text-purple-600 text-center mb-6">Dashboard Dosen</h1>
        <p class="text-xl text-gray-700 text-center mb-6">Selamat datang, Dosen.</p>

        <!-- Tabel Pengguna untuk mengupdate nilai -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-purple-300">
                <thead class="bg-purple-100">
                    <tr>
                        <th class="border border-purple-500 px-4 py-2 text-left">ID</th>
                        <th class="border border-purple-500 px-4 py-2 text-left">Nama</th>
                        <th class="border border-purple-500 px-4 py-2 text-left">Username</th>
                        <th class="border border-purple-500 px-4 py-2 text-left">Nilai</th>
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
                                <form action='dashboard_dosen.php' method='POST'>
                                    <input type='hidden' name='id' value='{$tampil['id']}'>
                                    <input type='number' step='0.01' name='nilai' value='{$tampil['nilai']}' class='w-full px-2 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600' required>
                            </td>
                            <td class='border border-purple-500 px-4 py-2'>
                                    <button type='submit' name='update_nilai' class='bg-purple-600 text-white py-1 px-4 rounded-lg text-sm font-semibold hover:bg-purple-700 transition duration-200'>Update</button>
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
