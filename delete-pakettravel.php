<?php
include 'config.php';

if (isset($_GET['id'])) {
    $pakettravelID = $_GET['id'];

    $query = "DELETE FROM pakettravel WHERE pakettravelID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $pakettravelID);
    if ($stmt->execute()) {
        header("Location: edit-pakettravel.php?deleted=1");
    } else {
        echo "Gagal menghapus: " . $stmt->error;
    }
    exit();
} else {
    echo "ID tidak ditemukan!";
    exit();
}
?>
