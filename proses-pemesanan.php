<?php
session_start();
include 'config.php';

if (!isset($_SESSION['pelangganID'])) {
    die("Pelanggan ID tidak ditemukan di session. Pastikan pelanggan sudah login.");
}

if (isset($_GET['pakettravelID'])) {
    $pakettravelID = $_GET['pakettravelID'];
    $pelangganID = $_SESSION['pelangganID'];
    $waktuPemesanan = date("Y-m-d H:i:s");

    

    // Ambil data paket travel dari tabel pakettravel
    $sql = "SELECT namaPaketTravel, tanggalKeberangkatan, lamahari, pesawat, hotelMakkah, hotelMadinah, hargaPaketTravel, gambarPaketTravel 
            FROM pakettravel WHERE pakettravelID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pakettravelID);
    $stmt->execute();
    $stmt->bind_result($namaPaketTravel, $tanggalKeberangkatan, $lamahari, $pesawat, $hotelMakkah, $hotelMadinah, $hargaPaketTravel, $gambarPaket);

    // Periksa apakah data paket ditemukan
    if ($stmt->fetch()) {
        // Tutup statement pertama sebelum membuka yang baru
        $stmt->close();

        // Insert data ke tabel pemesanan tanpa pemesananID karena akan dibuat otomatis
        $sql_insert = "INSERT INTO pemesanan (pelangganID, pakettravelID, jenisPaket, waktuPemesanan, tanggalKeberangkatan, lamahari, pesawat, hotelMakkah, hotelMadinah, hargaPaketTravel, gambarPemesanan) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssssisssss", $pelangganID, $pakettravelID, $namaPaketTravel, $waktuPemesanan, $tanggalKeberangkatan, $lamahari, $pesawat, $hotelMakkah, $hotelMadinah, $hargaPaketTravel, $gambarPaket);

        if ($stmt_insert->execute()) {
            header("Location: formpembayaran.html?status=success");
            exit();
        } else {
            echo "Gagal memesan: " . $stmt_insert->error;
        }
        $stmt_insert->close();
    } else {
        echo "Data paket travel tidak ditemukan.";
    }
    
    if (isset($stmt)) {
        $stmt->close();
    }
} else {
    echo "ID paket tidak ditemukan. Pastikan pakettravelID dikirim dengan benar.";
}

$conn->close();
?>
