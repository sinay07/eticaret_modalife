<?php
session_start();
include 'mysql.php';

// POST isteğiyle gönderilen URUN_KODU'nu al
$urun_kodu = $_POST['urun_kodu'];

// Oturum açılmamış kullanıcılar için sepet işlemleri
if (!isset($_SESSION['EPOSTA'])) {
    if (isset($_SESSION['sepet']) && isset($_SESSION['sepet'][$urun_kodu])) {
        // Ürünü $_SESSION['sepet']'ten kaldır
        unset($_SESSION['sepet'][$urun_kodu]);

        // Sepet boşsa sepeti sıfırla
        if (empty($_SESSION['sepet'])) {
            unset($_SESSION['sepet']);
        }

        // Sepet toplamını yeniden hesapla
        $total_toplam = 0;
        if (isset($_SESSION['sepet'])) {
            foreach ($_SESSION['sepet'] as $item) {
                $total_toplam += $item['TOPLAM'];
            }
        }

        // Yanıtı JSON formatında döndür
        $response = array(
            'success' => true,
            'totalToplam' => $total_toplam
        );
        echo json_encode($response);
    } else {
        // Ürün bulunamadıysa hata mesajı döndür
        echo json_encode(array('error' => 'Hata: Ürün sepetinizde bulunamadı.'));
    }
} else {
    // Oturum açılmış kullanıcılar için sepet işlemleri
    $sql = "DELETE FROM sepet WHERE EPOSTA = :eposta AND URUN_KODU = :urun_kodu";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':eposta', $_SESSION['EPOSTA']);
    $stmt->bindParam(':urun_kodu', $urun_kodu);

    // Sorguyu çalıştır
    if ($stmt->execute()) {
        // Sepetten ürün kaldırma başarılı olduysa, toplam fiyatı hesapla
        $sql_toplam = "SELECT SUM(TOPLAM) AS total_toplam FROM sepet WHERE EPOSTA = :eposta";
        $stmt_toplam = $conn->prepare($sql_toplam);
        $stmt_toplam->bindParam(':eposta', $_SESSION['EPOSTA']);
        $stmt_toplam->execute();
        $row_toplam = $stmt_toplam->fetch(PDO::FETCH_ASSOC);
        $total_toplam = $row_toplam['total_toplam'];

        // Yanıtı JSON formatında döndür
        $response = array(
            'success' => true,
            'totalToplam' => $total_toplam
        );
        echo json_encode($response);
    } else {
        // İşlem başarısız olduysa, hata mesajını döndür
        echo json_encode(array('error' => 'Hata: Ürün sepetten kaldırılamadı.'));
    }
}

// PDO bağlantısını kapat
$stmt = null;
$stmt_toplam = null;
$conn = null;
?>
