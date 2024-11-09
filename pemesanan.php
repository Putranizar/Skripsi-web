<?php
include 'config.php';
$sql = "SELECT * FROM pemesanan";
$result = $conn->query($sql);

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="k2 - logo new.png">
    <title>Pemesanan</title>
    <link rel="stylesheet" href="pemesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.css" />
</head>
<body>
    <div class="container">
        <h1 data-aos="fade-up">Pemesanan</h1>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="package" data-aos="fade-up" data-aos-duration="800">
                <div class="package-image">
                    <img src="images/<?php echo htmlspecialchars($row['gambarPemesanan']); ?>" alt="Gambar Paket" onerror="this.onerror=null; this.src='images/default.jpg';">
                </div>
                <div class="package-text">
                    <h2><?php echo htmlspecialchars($row['jenisPaket']); ?></h2>
                    <p><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($row['tanggalKeberangkatan']); ?></p>
                    <p><i class="fas fa-clock"></i> <?php echo htmlspecialchars($row['lamahari']); ?> Hari</p>
                    <p><i class="fas fa-plane"></i> <?php echo htmlspecialchars($row['pesawat']); ?></p>
                    <p><i class="fas fa-hotel"></i> Makkah: <?php echo htmlspecialchars($row['hotelMakkah']); ?></p>
                    <p><i class="fas fa-hotel"></i> Madinah: <?php echo htmlspecialchars($row['hotelMadinah']); ?></p>
                    <p><i class="fas fa-tag"></i> Harga: <strong>Rp. <?php echo htmlspecialchars($row['hargaPaketTravel']); ?></strong></p>
                    <a href="formpembayaran.php?pemesananID=<?php echo htmlspecialchars($row['pemesananID']); ?>" class="pesan-btn">Pesan</a>

                </div>
            </div>
        <?php } ?>

        <div class="back-button">
            <a href="halamanutama-pelanggan.html">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
