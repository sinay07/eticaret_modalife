<?php
session_start();
// assets/fonk/addToCart.php

// Veritabanı bağlantısını içe aktar
include 'mysql.php';

// POST isteğiyle gönderilen URUN_KODU'nu ve adet bilgisini al
$urun_kodu = $_POST['urun_kodu'];
$ana_resim = isset($_POST['ana_resim']) ? $_POST['ana_resim'] : '';

$adet = isset($_POST['adet']) ? $_POST['adet'] : 1; // Varsayılan olarak 1 adet ekleyin

// Ürünü "urunler" tablosundan al
$sql_urun = "SELECT URUN_KODU, RESIM, URUN_ADI, URUN_LINK, FIYAT FROM urunler WHERE URUN_KODU = :urun_kodu";
$stmt_urun = $conn->prepare($sql_urun);
$stmt_urun->bindParam(':urun_kodu', $urun_kodu);
$stmt_urun->execute();
$urun = $stmt_urun->fetch(PDO::FETCH_ASSOC);

// Eğer ürün bulunamadıysa hata mesajı döndür
if (!$urun) {
    echo "error: Ürün bulunamadı.";
    exit;
}

// Oturum açılmamış kullanıcıları kontrol et
if(!isset($_SESSION['EPOSTA'])) {
    echo "error: Oturum açınız.";
    exit; 
}

// Kaydedilenler tablosunda kullanıcının daha önce aynı ürünü ekleyip eklemediğini kontrol et
$sql_check = "SELECT * FROM kaydedilenler WHERE EPOSTA = :eposta AND URUN_KODU = :urun_kodu";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bindParam(':eposta', $_SESSION['EPOSTA']);
$stmt_check->bindParam(':urun_kodu', $urun_kodu);
$stmt_check->execute();
$existing_product = $stmt_check->fetch(PDO::FETCH_ASSOC);

// Eğer ürün zaten eklenmişse, tekrar eklenmemeli ve hata mesajı döndürülmeli
if ($existing_product) {
    echo "error: Bu ürün zaten eklenmiş.";
    exit;
}

// "kaydedilenler" tablosuna ekleme işlemini gerçekleştir
$sql_kaydet = "INSERT INTO kaydedilenler (EPOSTA, URUN_KODU, URUN_RESIM, URUN_ADI, URUN_LINK, FIYAT) VALUES (:eposta, :urun_kodu, :resim, :urun_adi, :urun_link, :fiyat)";
$stmt_kaydet = $conn->prepare($sql_kaydet);
$stmt_kaydet->bindParam(':eposta', $_SESSION['EPOSTA']);
$stmt_kaydet->bindParam(':urun_kodu', $urun['URUN_KODU']);
$stmt_kaydet->bindValue(':resim', $ana_resim ? $ana_resim : $urun['RESIM']); // Ana resim post olarak gelmezse $urun['RESIM'] varsayılan olarak atanır
$stmt_kaydet->bindParam(':urun_adi', $urun['URUN_ADI']);
$stmt_kaydet->bindParam(':urun_link', $urun['URUN_LINK']);
$stmt_kaydet->bindParam(':fiyat', $urun['FIYAT']);
$stmt_kaydet->execute();

// Sorguyu çalıştır
if ($stmt_kaydet && $stmt_kaydet->rowCount() > 0) {
    echo "success"; // Başarılı bir şekilde eklenirse 'success' mesajını döndür
} else {
    echo "error"; // Hata durumunda 'error' mesajını döndür
}

// PDO bağlantısını kapat
$stmt_urun = null;
$stmt_kaydet = null;
$conn = null;
?>
