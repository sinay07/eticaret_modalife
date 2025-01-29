<?php
session_start();
// assets/fonk/addToCart.php

// Veritabanı bağlantısını içe aktar
include 'mysql.php';

// POST isteğiyle gönderilen URUN_KODU'nu ve adet bilgisini al
$urun_kodu = $_POST['urun_kodu'];
$adet = isset($_POST['adet']) ? $_POST['adet'] : 1; // Varsayılan olarak 1 adet ekleyin

// Ürünü veritabanından al
$sql_urun = "SELECT * FROM urunler WHERE URUN_KODU = :urun_kodu";
$stmt_urun = $conn->prepare($sql_urun);
$stmt_urun->bindParam(':urun_kodu', $urun_kodu);
$stmt_urun->execute();
$urun = $stmt_urun->fetch(PDO::FETCH_ASSOC);

// Eğer ürün bulunamadıysa hata mesajı döndür
if (!$urun) {
    echo "error: Ürün bulunamadı.";
    exit;
}

// Kaydedilenler tablosundan ürünü kaldır
$sql_kaldir = "DELETE FROM kaydedilenler WHERE URUN_KODU = :urun_kodu";
$stmt_kaldir = $conn->prepare($sql_kaldir);
$stmt_kaldir->bindParam(':urun_kodu', $urun_kodu);
$stmt_kaldir->execute();

// Sepette ürünün olup olmadığını kontrol et
$sql_check = "SELECT * FROM sepet WHERE EPOSTA = :eposta AND URUN_KODU = :urun_kodu";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bindParam(':eposta', $_SESSION['EPOSTA']);
$stmt_check->bindParam(':urun_kodu', $urun_kodu);
$stmt_check->execute();
$existing_product = $stmt_check->fetch(PDO::FETCH_ASSOC);

if ($existing_product) {
    // Ürün sepette zaten varsa adet ve toplam fiyatı güncelle
    $new_adet = $existing_product['ADET'] + $adet;
    $new_toplam = $new_adet * $urun['FIYAT'];

    $sql_update = "UPDATE sepet SET ADET = :adet, TOPLAM = :toplam WHERE EPOSTA = :eposta AND URUN_KODU = :urun_kodu";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':adet', $new_adet);
    $stmt_update->bindParam(':toplam', $new_toplam);
    $stmt_update->bindParam(':eposta', $_SESSION['EPOSTA']);
    $stmt_update->bindParam(':urun_kodu', $urun_kodu);
    $stmt_update->execute();
} else {
    // Ürün sepette yoksa yeni kayıt ekle
    $sql_sepet = "INSERT INTO sepet (EPOSTA, URUN_KODU, RESIM, URUN_ADI, URUN_LINK, FIYAT, ADET, TOPLAM) VALUES (:eposta, :urun_kodu, :resim, :urun_adi, :urun_link, :fiyat, :adet, :toplam)";
    $stmt_sepet = $conn->prepare($sql_sepet);
    $stmt_sepet->bindParam(':eposta', $_SESSION['EPOSTA']);
    $stmt_sepet->bindParam(':urun_kodu', $urun_kodu);
    $stmt_sepet->bindParam(':resim', $urun['RESIM']);
    $stmt_sepet->bindParam(':urun_adi', $urun['URUN_ADI']);
    $stmt_sepet->bindParam(':urun_link', $urun['URUN_LINK']); // URUN_LINK eklendi
    $stmt_sepet->bindParam(':fiyat', $urun['FIYAT']);
    $stmt_sepet->bindParam(':adet', $adet);
    $stmt_sepet->bindValue(':toplam', $urun['FIYAT'] * $adet); // Toplam fiyatı hesaplayın
    $stmt_sepet->execute();
}

// Sorguyu çalıştır
if (($stmt_sepet && $stmt_sepet->rowCount() > 0) || ($stmt_update && $stmt_update->rowCount() > 0)) {
    echo json_encode(array('success' => true));
} else {
    echo "error"; // Hata durumunda 'error' mesajını döndür
}

// PDO bağlantısını kapat
$stmt_urun = null;
$stmt_sepet = null;
$stmt_update = null;
$stmt_check = null;
$conn = null;
?>
