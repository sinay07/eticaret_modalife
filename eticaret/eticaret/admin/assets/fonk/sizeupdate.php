<?php
include 'mysql.php';

// Post isteği kontrolü
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mainCategory']) && isset($_POST['newMainCategoryName'])) {
    // Gelen veriyi al
    $mainCategory = $_POST['mainCategory'];
    $newMainCategoryName = $_POST['newMainCategoryName'];

    try {
        // Veritabanında beden güncelleme işlemi
        $stmt = $conn->prepare("UPDATE bedenler SET BEDEN_ADI = :newMainCategoryName WHERE BEDEN_ADI = :mainCategory");
        $stmt->bindParam(':mainCategory', $mainCategory);
        $stmt->bindParam(':newMainCategoryName', $newMainCategoryName);
        $stmt->execute();

        // Başarılı yanıt döndür
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Hata durumunda hata mesajını döndür
        echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
    }
} else {
    // Eksik veya geçersiz veri durumunda uyarı mesajı döndür
    echo json_encode(['success' => false, 'message' => 'Fehlende oder ungültige Daten']);
}
?>
