<?php
include 'config.php';

if (isset($_GET['id'])) {
    $pemesananID = $_GET['id'];

    $query = "DELETE FROM pemesanan WHERE pemesananID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $pemesananID);
    if ($stmt->execute()) {
        header("Location: edit-pemesanan.php?deleted=1");
    } else {
        echo "Gagal menghapus: " . $stmt->error;
    }
    exit();
} else {
    echo "ID tidak ditemukan!";
    exit();
}
?>
