<?php
// Veritabanı bağlantısını dahil edin
include 'mysql.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POST isteğiyle gelen urunKodu'nu al
    $urunKodu = $_POST['urunKodu'];

    try {
        // Mevcut durumu kontrol et
        $stmt = $conn->prepare("SELECT DURUM FROM siparisler WHERE SIPARIS_NO = ?");
        $stmt->execute([$urunKodu]);
        $siparis = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($siparis && $siparis['DURUM'] === 'TESLİM EDİLDİ') {
            $response = [
                'status' => 'error',
                'message' => 'Bu sipariş zaten teslim edilmiş.'
            ];
        } else {
            // siparisler tablosunda ilgili ürünün DURUM sütununu güncelle
            $stmt = $conn->prepare("UPDATE siparisler SET DURUM = 'TESLİM EDİLDİ' WHERE SIPARIS_NO = ?");
            $stmt->execute([$urunKodu]);

            // Güncellemenin başarılı olduğunu kontrol edin
            if ($stmt->rowCount() > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Sipariş teslim edildi olarak güncellendi.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Teslim işlemi başarısız oldu!'
                ];
            }
        }
    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Hata oluştu: ' . $e->getMessage()
        ];
    }

    // JSON formatında yanıt döndür
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
