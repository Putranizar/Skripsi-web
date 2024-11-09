<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['pelangganID'], $_POST['pengirim'], $_POST['bank'], $_FILES['bukti'], $_POST['pemesananID'])) {
        $pelangganID = $_SESSION['pelangganID'];
        $pemesananID = $_POST['pemesananID'];
        $namaPengirim = $_POST['pengirim'];
        $namaBank = $_POST['bank'];
        $bukti = $_FILES['bukti']['name'];
        $target = "images/" . basename($bukti);

        // Upload file bukti transfer
        if (move_uploaded_file($_FILES['bukti']['tmp_name'], $target)) {
            // Ambil ID pembayaran terakhir untuk membuat ID baru
            $result = $conn->query("SELECT pembayaranID FROM pembayaran ORDER BY pembayaranID DESC LIMIT 1");
            $lastID = $result->fetch_assoc();
            
            // Buat ID pembayaran baru
            $newID = 'PB01';
            if ($lastID) {
                $lastNumber = (int)substr($lastID['pembayaranID'], 2);
                $newID = 'PB' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
            }

            // Simpan data pembayaran ke database
            $sql = "INSERT INTO pembayaran (pembayaranID, pelangganID, pemesananID, namaPengirim, namaBank, buktiPembayaran)
             VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $newID, $pelangganID, $pemesananID, $namaPengirim, $namaBank, $bukti);

            if ($stmt->execute()) {
                header("Location: success.html"); // Redirect ke halaman sukses
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Gagal upload bukti pembayaran.";
        }
    } else {
        echo "Data tidak lengkap.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="k2 - logo new.png">
    <title>Form Bukti Pembayaran</title>
    <link rel="stylesheet" href="formpembayaran.css">
</head>
<body>
    <h2>Isi Form Pembayaran</h2>

    <div class="form-container">
        <div class="form-header">
            <span class="close" onclick="history.back();">&times;</span>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <h3>Pembayaran</h3>
            <p>No Rekening Khadijatul Kubra: 12345678 - Bank BSI - Atas Nama PT. ADITA AL TAHIR</p>
            <input type="hidden" name="pemesananID" value="<?php echo htmlspecialchars($_GET['pemesananID']); ?>">

            <div class="form-group">
                <label for="pengirim">Nama Pengirim:</label>
                <input type="text" id="pengirim" name="pengirim" placeholder="Nama Pengirim" required>
            </div>
            <div class="form-group">
                <label for="bank">Nama Bank:</label>
                <input type="text" id="bank" name="bank" placeholder="Nama Bank" required>
            </div>
            <div class="form-group">
                <label for="bukti">Bukti Transfer:</label>
                <input type="file" id="bukti" name="bukti" accept="image/*" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn confirm">Konfirmasi</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
