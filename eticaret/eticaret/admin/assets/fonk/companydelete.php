<?php
// Veritabanı bağlantısını içe aktar
include 'mysql.php';

// Başlangıçta başarıyı varsayalım
$response = array(
    'success' => true,
    'message' => ''
);

// URUN_KODU değerini POST isteği ile al
if(isset($_POST['urunKodu'])) {
    $urunKodu = $_POST['urunKodu'];

    try {
        // Silme işlemini gerçekleştirecek sorguyu hazırla
        $stmt = $conn->prepare("DELETE FROM indirimli_urunler WHERE URUN_KODU = :urunKodu");

        // Değişkenleri bağla
        $stmt->bindParam(':urunKodu', $urunKodu);

        // Sorguyu çalıştır
        $stmt->execute();

        // Başarılı bir şekilde silindiğini döndür
        $response['message'] = "Kampanya kaldırıldı.";
    } catch (PDOException $e) {
        // Hata durumunda hata mesajını döndür
        $response['success'] = false;
        $response['message'] = "Datenbankfehler: " . $e->getMessage();
    }
} else {
    // URUN_KODU POST isteğiyle alınmazsa hata mesajı döndür
    $response['success'] = false;
    $response['message'] = "URUN_KODU parametresi eksik.";
}

// JSON formatında dönütü yazdır
header('Content-Type: application/json');
echo json_encode($response);
?>
