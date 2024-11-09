<?php
session_start(); // Pastikan session dimulai

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa login sebagai admin
    $sql = "SELECT * FROM admin WHERE emailAdmin = ? AND passwordAdmin = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login berhasil sebagai admin
        $_SESSION['username'] = $username;
        header("Location: halaman-admin.html"); // Ganti dengan halaman utama yang sesuai
        exit(); // Pastikan untuk keluar setelah header
    }

    // Query untuk memeriksa login sebagai pelanggan
    $sql_pelanggan = "SELECT * FROM pelanggan WHERE emailPelanggan = ? AND passwordPelanggan = ?";
    $stmt_pelanggan = $conn->prepare($sql_pelanggan);
    $stmt_pelanggan->bind_param("ss", $username, $password);
    $stmt_pelanggan->execute();
    $result_pelanggan = $stmt_pelanggan->get_result();

    if ($result_pelanggan->num_rows > 0) {
        // Login berhasil sebagai pelanggan
        $pelanggan = $result_pelanggan->fetch_assoc(); // Ambil data pelanggan
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'pelanggan';
        $_SESSION['pelangganID'] = $pelanggan['pelangganID']; // Tambahkan pelangganID ke session
        header("Location: halamanutama-pelanggan.html"); // Ganti dengan halaman pelanggan yang sesuai
        exit(); // Pastikan untuk keluar setelah header
    } else {
        // Jika login gagal untuk keduanya, set variabel untuk menampilkan popup
        $showPopup = true; // Set untuk menunjukkan popup
    }

    $stmt->close();
    $stmt_pelanggan->close();
}

$conn->close();
?>
