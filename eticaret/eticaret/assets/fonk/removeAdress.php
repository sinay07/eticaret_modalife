<?php
// "mysql.php" dosyasını içe aktar
include 'mysql.php';

// POST verilerini doğrudan PDO prepared statements ile al ve veritabanına ekle
try {
    // E-posta bilgisini session'dan al
    session_start();
    $eposta = $_SESSION['EPOSTA'];

    // SQL sorgusu oluştur
    $sql = "DELETE FROM adresler WHERE EPOSTA = :eposta";

    // PDO prepared statements kullanarak sorguyu hazırla ve çalıştır
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':eposta' => $eposta
    ]);

    echo "Adresler başarıyla silindi!";
} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage();
}

// Veritabanı bağlantısını kapat
$conn = null;
?>
