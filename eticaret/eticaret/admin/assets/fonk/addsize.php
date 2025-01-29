<?php
include 'mysql.php';

// Post isteği kontrolü
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['beden_adi'])) {
    // Gelen veriyi al
    $bedenAdi = $_POST['beden_adi'];

    try {
        // Veritabanına beden ekleme işlemi
        $stmt = $conn->prepare("INSERT INTO bedenler (BEDEN_ADI) VALUES (:beden_adi)");
        $stmt->bindParam(':beden_adi', $bedenAdi);
        $stmt->execute();

        // Başarılı yanıt döndür
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Hata durumunda hata mesajını döndür
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
} else {
    // Eksik veya geçersiz veri durumunda uyarı mesajı döndür
    echo json_encode(['success' => false, 'message' => 'Eksik veya geçersiz veri']);
}
?>
