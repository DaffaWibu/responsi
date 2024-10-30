<?php
// Bangun koneksi ke database
include 'db.php';

// Tangkap isi dari form username dan password
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Jika variabel tidak kosong
if (!empty($username) && !empty($password)) {

    // Mempersiapkan statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Cek jika username ditemukan
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $hashed_password)) {
            // Jika benar, redirect ke home.php
            header("Location: home.php");
            exit();
        } else {
            // Jika password salah
            header("Location: index.php?app=gagal");
            exit();
        }
    } else {
        // Jika username tidak ditemukan
        header("Location: index.php?app=gagal");
        exit();
    }

    $stmt->close();
} else {
    // Jika username atau password kosong, redirect ke halaman login
    header("Location: index.php?app=gagal");
    exit();
}
?>