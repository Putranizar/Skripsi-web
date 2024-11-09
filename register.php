<?php
// Include konfigurasi database
include 'config.php';

// Ambil data dari form
$email = $_POST['email'];
$password = $_POST['password']; // Tanpa enkripsi (tidak aman)
$nama = $_POST['nama'];
$notelepon = $_POST['notelepon'];

// Cek ID terakhir yang ada di database
$sql = "SELECT pelangganID FROM pelanggan ORDER BY pelangganID DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastId = $row['pelangganID'];
    $number = (int)substr($lastId, 1);
    $number++;
    $newId = 'A' . str_pad($number, 3, '0', STR_PAD_LEFT);
} else {
    $newId = 'A001';
}

// Query untuk memasukkan data ke tabel pelanggan
$sql = "INSERT INTO pelanggan (pelangganID, emailPelanggan, passwordPelanggan, namaPelanggan, noteleponPelanggan) 
VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Persiapan query gagal: " . $conn->error);
}

$stmt->bind_param("sssss", $newId, $email, $password, $nama, $notelepon);

if ($stmt->execute()) {
    header("Location: loginpage.html");
    exit();
} else {
    echo "Registrasi gagal: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
