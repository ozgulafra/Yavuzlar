<?php
// Veritabanı dosyasının yolunu belirt
$dbFile = __DIR__ . '/database.db';

try {
    // PDO ile SQLite veritabanına bağlan
    $conn = new PDO("sqlite:" . $dbFile);

    // Hata modunu aktif et (hataları yakalayabilmek için)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Veritabanı bağlantısı başarılıysa mesaj göster
    // (Bunu silmek istersen, bu satırı kaldırabilirsin)
    // echo "Veritabanına başarıyla bağlanıldı!";
} catch (PDOException $e) {
    // Bağlantı hatası durumunda hata mesajını göster
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    exit();
}
?>
