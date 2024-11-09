<?php
include 'config.php';

$data = null; // Inisialisasi $data
if (isset($_POST['selectPackage'])) {
    $id = $_POST['packageID'];
    $sql = "SELECT * FROM pemesanan WHERE pemesananID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // Jika paket tidak ditemukan, redirect ke pakettravel.php
    if (!$data) {
        header("Location: pemesanan.php");
        exit();
    }
}

// Proses pembaruan data setelah formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatePackage'])) {
    $id = $_POST['packageID'];
    $nama_paket = $_POST['jenisPaket'];
    $keberangkatan = $_POST['tanggalKeberangkatan'];
    $lama_hari = $_POST['lamahari'];
    $pesawat = $_POST['pesawat'];
    $hotel_makkah = $_POST['hotelMakkah'];
    $hotel_madinah = $_POST['hotelMadinah'];
    $harga = $_POST['hargaPaketTravel'];
    $gambar = $_POST['gambarLama'] ?? '';

    // Jika ada gambar baru yang diunggah
    if (!empty($_FILES['gambar']['name'])) {
        $gambar_baru = $_FILES['gambar']['name'];
        $target = "images/" . basename($gambar_baru);
        
        // Validasi dan upload gambar
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
            $gambar = $gambar_baru;
        } else {
            echo "Gagal mengupload gambar.";
            exit();
        }
    } else {
        $gambar = $_POST['gambarLama'];
    }

    // Update data paket travel di database
    $sql = "UPDATE pemesanan SET jenisPaket=?, tanggalKeberangkatan=?, lamahari=?, pesawat=?, hotelMakkah=?, hotelMadinah=?,
    hargaPaketTravel=?, gambarPemesanan=? WHERE pemesananID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissssss", $nama_paket, $keberangkatan, $lama_hari, $pesawat, $hotel_makkah, $hotel_madinah, $harga, $gambar, $id);

    if ($stmt->execute()) {
        header("Location: edit-pemesanan.php?success=1"); 
        exit();
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pemesanan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="edit-pakettravel.css">
</head>
<body>

    <div class="close-button">
        <a href="halaman-admin.html">
            <i class="fas fa-times"></i>
        </a>
    </div>

    <h1>Edit Pemesanan</h1>

    <!-- Layout untuk pilihan paket dan tombol Input Paket Travel -->
    <div class="form-container">
        <!-- Pilihan Paket -->
        <form action="" method="POST" class="form-edit">
            <label for="packageID">Pilih Pemesanan yang Ingin Diedit:</label>
            <select id="packageID" name="packageID" required>
                <option value="">--Pilih Paket--</option>
                <?php
                $sql = "SELECT pemesananID, jenisPaket FROM pemesanan";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['pemesananID'] . '">' . htmlspecialchars($row['jenisPaket']) . '</option>';
                }
                ?>
            </select>
            <button type="submit" name="selectPackage">Edit Pemesanan</button>
            <a href="input-pemesanan.php" class="btn-input">Input Pemesanan</a>
            <?php if (isset($data)): ?>
            <a href="delete-pemesanan.php?id=<?php echo $data['pemesananID']; ?>" 
            class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus pemesanan ini?');">Delete</a>
            <?php endif; ?>
        </form>

        <!-- Form untuk Update Paket Travel -->
        <?php if (isset($data)): ?>
        <form action="" method="POST" enctype="multipart/form-data" class="form-update">
            <input type="hidden" name="packageID" value="<?php echo htmlspecialchars($data['pemesananID']); ?>">
            <input type="hidden" name="gambarLama" value="<?php echo htmlspecialchars($data['gambarPemesanan']); ?>">
            <label for="nama_paket">Nama Paket:</label>
            <input type="text" id="nama_paket" name="jenisPaket" value="<?php echo htmlspecialchars($data['jenisPaket']); ?>" required>
            
            <label for="keberangkatan">Keberangkatan:</label>
            <input type="date" id="keberangkatan" name="tanggalKeberangkatan" value="<?php echo htmlspecialchars($data['tanggalKeberangkatan']); ?>" required>
            
            <label for="lama_hari">Lama Hari:</label>
            <input type="number" id="lama_hari" name="lamahari" value="<?php echo htmlspecialchars($data['lamahari']); ?>" required>
            
            <label for="pesawat">Pesawat:</label>
            <input type="text" id="pesawat" name="pesawat" value="<?php echo htmlspecialchars($data['pesawat']); ?>" required>
            
            <label for="hotel_makkah">Hotel Makkah:</label>
            <input type="text" id="hotel_makkah" name="hotelMakkah" value="<?php echo htmlspecialchars($data['hotelMakkah']); ?>" required>
            
            <label for="hotel_madinah">Hotel Madinah:</label>
            <input type="text" id="hotel_madinah" name="hotelMadinah" value="<?php echo htmlspecialchars($data['hotelMadinah']); ?>" required>
            
            <label for="harga">Harga:</label>
            <input type="text" id="harga" name="hargaPaketTravel" value="<?php echo htmlspecialchars($data['hargaPaketTravel']); ?>" required>
        
            <label for="gambar">Gambar:</label>
            <input type="file" id="gambar" name="gambar">
            <img src="images/<?php echo htmlspecialchars($data['gambarPemesanan']); ?>" alt="Current Image" width="100px">

            <button type="submit" name="updatePackage">Update Paket</button>
        </form>
        <?php endif; ?>
    </div>

</body>
</html>
