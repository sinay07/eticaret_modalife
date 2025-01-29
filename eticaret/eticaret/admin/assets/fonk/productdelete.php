<?php
include 'mysql.php';

if(isset($_POST['urunKodu'])){
    $urunKodu = $_POST['urunKodu'];

    // Veritabanı işlemlerini tek bir transaction içinde yaparak güvenliğini sağla
    try {
        $conn->beginTransaction();

        // urunler tablosundan sil
        $sql = "DELETE FROM urunler WHERE URUN_KODU = :urunKodu";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':urunKodu', $urunKodu);
        $stmt->execute();

        // varyant_resim tablosundan sil
        $sql = "DELETE FROM varyant_resim WHERE URUN_KODU = :urunKodu";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':urunKodu', $urunKodu);
        $stmt->execute();

        // varyant_beden tablosundan sil
        $sql = "DELETE FROM varyant_beden WHERE URUN_KODU = :urunKodu";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':urunKodu', $urunKodu);
        $stmt->execute();

        // Tüm işlemler başarılıysa commit et
        $conn->commit();
        echo json_encode(array('success' => true));
    } catch (Exception $e) {
        // Hata oluşursa rollback yap
        $conn->rollBack();
        echo json_encode(array('success' => false, 'error' => $e->getMessage()));
    }
} else {
    // İşlem başarısız ise false döndür
    echo json_encode(array('success' => false));
}
?>
