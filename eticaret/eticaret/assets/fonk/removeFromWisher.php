<?php
// assets/fonk/removeFromCart.php

// Veritabanı bağlantısını içe aktar
include 'mysql.php';

// POST isteğiyle gönderilen URUN_KODU'nu al
$urun_kodu = $_POST['urun_kodu'];

// Sepetten ürünü kaldırmak için SQL sorgusunu hazırla
$sql = "DELETE FROM kaydedilenler WHERE URUN_KODU = :urun_kodu";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':urun_kodu', $urun_kodu);

// Sorguyu çalıştır
if ($stmt->execute()) {
    // İşlem başarılı olduysa, sadece başarılı bir yanıt döndür
    // echo json_encode(array('success' => true));
} else {
    // İşlem başarısız olduysa, hata mesajını döndür
    echo json_encode(array('error' => 'Hata: Ürün sepetten kaldırılamadı.'));
}

// PDO bağlantısını kapat
$stmt = null;
$conn = null;
?>
