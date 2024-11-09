<?php
session_start();
include 'config.php';

// Proses jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addPackage'])) {
    $pakettravelID = $_POST['pakettravelID']; // Ambil pakettravelID dari dropdown yang dipilih
    $nama_paket = $_POST['jenisPaket'];
    $keberangkatan = $_POST['tanggalKeberangkatan'];
    $lama_hari = (int)$_POST['lamahari'];
    $pesawat = $_POST['pesawat'];
    $hotel_makkah = $_POST['hotelMakkah'];
    $hotel_madinah = $_POST['hotelMadinah'];
    $harga = str_replace('.', '', $_POST['hargaPaketTravel']);
    $gambar = $_FILES['gambar']['name'];
    $target = "images/" . basename($gambar);

    // Validasi dan upload gambar
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
       // Ambil ID pemesanan terakhir untuk membuat ID baru
    $result = $conn->query("SELECT pemesananID FROM pemesanan ORDER BY pemesananID DESC LIMIT 1");
    $lastID = $result->fetch_assoc();

// Buat ID pemesanan baru dengan format yang benar
    if ($lastID) {
    $lastNumber = (int)substr($lastID['pemesananID'], 2); // Ambil angka setelah PS
    $newID = 'PS' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
    } 
    else {
    $newID = 'PS01';
    }


        // Insert data pemesanan ke database
        $sql = "INSERT INTO pemesanan (pemesananID, pakettravelID, jenisPaket, tanggalKeberangkatan, lamahari, pesawat, hotelMakkah, hotelMadinah, hargaPaketTravel, gambarPemesanan) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssisssss", $newID, $pakettravelID, $nama_paket, $keberangkatan, $lama_hari, $pesawat, $hotel_makkah, $hotel_madinah, $harga, $gambar);

            if ($stmt->execute()) {
                // Redirect ke halaman edit-pemesanan.php setelah berhasil menambah
                header("Location: edit-pemesanan.php?success=1");
                exit();
            } else {
                echo "Gagal menambahkan pemesanan: " . $stmt->error;
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
    <title>Tambah Pemesanan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="input-pemesanan.css">
</head>
<body>

    <div class="close-button">
        <a href="edit-pemesanan.php">
            <i class="fas fa-times"></i>
        </a>
    </div>

    <h1>Tambah Pemesanan</h1>

    <?php
    // Query untuk mengambil data paket travel
    $query = "SELECT pakettravelID, namaPaketTravel FROM pakettravel";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $pakettravelOptions = [];
        while ($row = $result->fetch_assoc()) {
            $pakettravelOptions[] = $row;
        }
    } else {
        echo "Tidak ada data paket travel.";
    }
    ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="pakettravelID">Pilih Paket Travel ID:</label>
        <select id="pakettravelID" name="pakettravelID" required>
            <option value="" disabled selected>Pilih ID</option>
            <?php
            // Loop untuk menampilkan opsi paket travel
            foreach ($pakettravelOptions as $option) {
                echo "<option value='" . $option['pakettravelID'] . "'>" . $option['pakettravelID'] . "</option>";
            }
            ?>
        </select>

        <label for="nama_paket">Nama Paket:</label>
        <input type="text" id="nama_paket" name="jenisPaket" required>

        <label for="keberangkatan">Keberangkatan:</label>
        <input type="date" id="keberangkatan" name="tanggalKeberangkatan" required>

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
