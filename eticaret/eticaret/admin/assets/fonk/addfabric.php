<?php
include 'mysql.php';

// Post isteği kontrolü
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kumas_adi'])) {
    // Gelen veriyi al
    $kumasAdi = $_POST['kumas_adi'];

    try {
        // Veritabanına yeni kumaş ekleme işlemi
        $stmt = $conn->prepare("INSERT INTO kumas (KUMAS_CINSI) VALUES (:kumasAdi)");
        $stmt->bindParam(':kumasAdi', $kumasAdi);
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
