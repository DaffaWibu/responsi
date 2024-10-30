<?php
include("db.php");

// Ambil data dari form
$nama = $_POST['nama'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Cek apakah data tidak kosong
if (!empty($nama) && !empty($username) && !empty($password)) {
    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username sudah digunakan, silakan coba username lain.";
    } else {
        // Hash password untuk penyimpanan yang aman
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Siapkan query untuk memasukkan data
        $stmt = $conn->prepare("INSERT INTO user (nama, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $username, $hashed_password);

        // Eksekusi query
        if ($stmt->execute()) {
            $success_message = "Pendaftaran berhasil! Silakan <a href='login.php' class='text-purple-600 underline'>login</a>.";
        } else {
            $error_message = "Pendaftaran gagal: " . $stmt->error;
        }
    }

    // Tutup statement
    $stmt->close();
} else {
    $error_message = "Semua field harus diisi.";
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center text-purple-600 mb-6">Pendaftaran Akun</h1>

        <!-- Menampilkan pesan error atau sukses -->
        <?php if (isset($error_message)): ?>
            <p class="text-red-500 mb-4 text-center"><?= $error_message ?></p>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <p class="text-gray-500 mb-4 text-center"><?= $success_message ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1" for="nama">Nama</label>
                <input class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" type="text" name="nama" id="nama" placeholder="Masukkan Nama" required />
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1" for="username">Username</label>
                <input class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" type="text" name="username" id="username" placeholder="Masukkan Username" required />
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1" for="password">Password</label>
                <input class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600" type="password" name="password" id="password" placeholder="Masukkan Password" required />
            </div>
            <button type="submit" class="w-full bg-purple-700 text-white py-3 rounded-lg text-lg font-semibold hover:bg-purple-500 transition duration-200">Daftar</button>
        </form>

        <p class="mt-4 text-center text-sm">
            Sudah punya akun? <a href="login.php" class="text-purple-600 underline">Login di sini</a>
        </p>
    </div>
</body>
</html>
