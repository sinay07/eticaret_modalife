<?php
session_start();
include 'mysql.php';

// Oturum e-posta adresini al
$eposta = isset($_SESSION['EPOSTA']) ? $_SESSION['EPOSTA'] : null;

// POST verilerini al
$urun_kodu = $_POST['urun_kodu'];
$adet = $_POST['adet'];

// Veritabanından ürün fiyatını al
$sql_fiyat = "SELECT FIYAT FROM urunler WHERE URUN_KODU = :urun_kodu";
$stmt_fiyat = $conn->prepare($sql_fiyat);
$stmt_fiyat->bindParam(':urun_kodu', $urun_kodu);
$stmt_fiyat->execute();
$row_fiyat = $stmt_fiyat->fetch(PDO::FETCH_ASSOC);
$fiyat = $row_fiyat['FIYAT'];

// Toplam fiyatı hesapla
$toplam_fiyat = $adet * $fiyat;

// Eğer oturum açılmamışsa, session sepetini kullanarak güncelleme yap
if (!$eposta) {
    if (!isset($_SESSION['sepet'])) {
        $_SESSION['sepet'] = [];
    }

    $sepet = &$_SESSION['sepet'];
    
    if (isset($sepet[$urun_kodu])) {
        $sepet[$urun_kodu]['ADET'] = $adet;
        $sepet[$urun_kodu]['TOPLAM'] = $toplam_fiyat;
    } else {
        $sepet[$urun_kodu] = [
            'URUN_KODU' => $urun_kodu,
            'FIYAT' => $fiyat,
            'ADET' => $adet,
            'TOPLAM' => $toplam_fiyat
        ];
    }

    $total_toplam = 0;
    foreach ($sepet as $item) {
        $total_toplam += $item['TOPLAM'];
    }

    $response = [
        'toplamFiyat' => number_format($toplam_fiyat, 2) . ' ₺',
        'totalToplam' => number_format($total_toplam, 2) . ' ₺'
    ];

    echo json_encode($response);
} else {
    // Oturum açılmış kullanıcılar için veritabanı sepetini güncelle
    $sql_guncelle = "UPDATE sepet SET ADET = :adet, TOPLAM = :toplam_fiyat WHERE EPOSTA = :eposta AND URUN_KODU = :urun_kodu";
    $stmt_guncelle = $conn->prepare($sql_guncelle);
    $stmt_guncelle->bindParam(':adet', $adet);
    $stmt_guncelle->bindParam(':toplam_fiyat', $toplam_fiyat);
    $stmt_guncelle->bindParam(':eposta', $eposta);
    $stmt_guncelle->bindParam(':urun_kodu', $urun_kodu);
    $stmt_guncelle->execute();

    $sql_toplam = "SELECT SUM(TOPLAM) AS total_toplam FROM sepet WHERE EPOSTA = :eposta";
    $stmt_toplam = $conn->prepare($sql_toplam);
    $stmt_toplam->bindParam(':eposta', $eposta);
    $stmt_toplam->execute();
    $row_toplam = $stmt_toplam->fetch(PDO::FETCH_ASSOC);
    $total_toplam = $row_toplam['total_toplam'];

    $response = [
        'toplamFiyat' => number_format($toplam_fiyat, 2) . ' ₺',
        'totalToplam' => number_format($total_toplam, 2) . ' ₺'
    ];

    echo json_encode($response);
}

// PDO bağlantısını kapat
$stmt_fiyat = null;
$stmt_guncelle = null;
$stmt_toplam = null;
$conn = null;
?>
