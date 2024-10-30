<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "db_backend_4992"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Tidak dapat terhubung ke database: " . $e->getMessage());
// }

?>