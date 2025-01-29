<?php
include 'mysql.php';

// Post isteği kontrolü
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ana_kategori']) && isset($_POST['alt_kategori'])) {
    // Gelen verileri al
    $anaKategori = $_POST['ana_kategori'];
    $altKategori = $_POST['alt_kategori'];

    try {
        // Mükerrer kayıt kontrolü
        $stmt_check = $conn->prepare("SELECT COUNT(*) AS count FROM alt_kategori WHERE ANA_KATEGORI_ADI = :ana_kategori AND ALT_KATEGORI_ADI = :alt_kategori");
        $stmt_check->bindParam(':ana_kategori', $anaKategori);
        $stmt_check->bindParam(':alt_kategori', $altKategori);
        $stmt_check->execute();
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($result_check['count'] > 0) {
            // Eğer kayıt zaten varsa başarısız yanıt döndür
            echo json_encode(['success' => false, 'message' => 'Bu alt kategori zaten mevcut']);
        } else {
            // Veritabanına alt kategori ekleme işlemi
            $stmt = $conn->prepare("INSERT INTO alt_kategori (ANA_KATEGORI_ADI, ALT_KATEGORI_ADI) VALUES (:ana_kategori, :alt_kategori)");
            $stmt->bindParam(':ana_kategori', $anaKategori);
            $stmt->bindParam(':alt_kategori', $altKategori);
            $stmt->execute();

            // Başarılı yanıt döndür
            echo json_encode(['success' => true]);
        }
    } catch (PDOException $e) {
        // Hata durumunda hata mesajını döndür
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
} else {
    // Eksik veya geçersiz veri durumunda uyarı mesajı döndür
    echo json_encode(['success' => false, 'message' => 'Eksik veya geçersiz veri']);
}
?>
