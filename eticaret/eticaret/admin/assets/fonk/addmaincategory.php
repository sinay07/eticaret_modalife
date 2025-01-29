 <?php
include 'mysql.php';

// Post isteği kontrolü
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ana_kategori'])) {
    // Gelen veriyi al
    $anaKategori = $_POST['ana_kategori'];

    try {
        // Veritabanına ana kategori ekleme işlemi
        $stmt = $conn->prepare("INSERT INTO ana_kategori (ANA_KATEGORI_ADI) VALUES (:ana_kategori)");
        $stmt->bindParam(':ana_kategori', $anaKategori);
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
