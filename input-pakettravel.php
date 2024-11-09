<?php
include 'config.php';

// Proses jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addPackage'])) {
    $nama_paket = $_POST['namaPaketTravel'];
    $keberangkatan = $_POST['tanggalkeberangkatan'];
    $lama_hari = (int) $_POST['lamahari'];
    $pesawat = $_POST['pesawat'];
    $hotel_makkah = $_POST['hotelMakkah'];
    $hotel_madinah = $_POST['hotelMadinah'];
    $harga = str_replace('.', '', $_POST['hargaPaketTravel']);

    // Untuk gambar baru
    $gambar = $_FILES['gambar']['name'];
    $target = "images/" . basename($gambar);

    // Validasi dan upload gambar
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
        // Ambil ID paket travel terakhir
        $result = $conn->query("SELECT paketTravelID FROM pakettravel ORDER BY paketTravelID DESC LIMIT 1");
        $lastID = $result->fetch_assoc();
        
        // Buat ID baru
        $newID = 'P001';
        if ($lastID) {
            $lastNumber = (int)substr($lastID['paketTravelID'], 1);
            $newID = 'P' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        // Insert data paket travel ke database
        $sql = "INSERT INTO pakettravel (paketTravelID, namaPaketTravel, tanggalkeberangkatan, lamahari, pesawat, hotelMakkah, hotelMadinah, hargaPaketTravel, gambarPaketTravel)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) { // Memastikan statement berhasil disiapkan
            $stmt->bind_param("sssisssss", $newID, $nama_paket, $keberangkatan, $lama_hari, $pesawat, $hotel_makkah, $hotel_madinah, $harga, $gambar);

            if ($stmt->execute()) {
                header("Location: edit-pakettravel.php?success=1");
                exit();
            } else {
                echo "Gagal menambahkan paket travel: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Gagal menyiapkan statement: " . $conn->error;
        }
    } else {
        echo "Gagal mengupload gambar.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Paket Travel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="input-pakettravel.css">
</head>
<body>

    <div class="close-button">
        <a href="edit-pakettravel.php">
            <i class="fas fa-times"></i>
        </a>
    </div>

    <h1>Tambah Paket Travel</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="nama_paket">Nama Paket:</label>
        <input type="text" id="nama_paket" name="namaPaketTravel" required>

        <label for="keberangkatan">Keberangkatan:</label>
        <input type="date" id="keberangkatan" name="tanggalkeberangkatan" required>

        <label for="lama_hari">Lama Hari:</label>
        <input type="number" id="lama_hari" name="lamahari" required>

        <label for="pesawat">Pesawat:</label>
        <input type="text" id="pesawat" name="pesawat" required>

        <label for="hotel_makkah">Hotel Makkah:</label>
        <input type="text" id="hotel_makkah" name="hotelMakkah" required>

        <label for="hotel_madinah">Hotel Madinah:</label>
        <input type="text" id="hotel_madinah" name="hotelMadinah" required>

        <label for="harga">Harga:</label>
        <input type="text" id="harga" name="hargaPaketTravel" required>

        <label for="gambar">Gambar:</label>
        <input type="file" id="gambar" name="gambar" accept="image/*" required>

        <button type="submit" name="addPackage">Tambah Paket</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
