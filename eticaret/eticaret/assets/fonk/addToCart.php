<?php
session_start();

// Veritabanı bağlantısını içe aktar
include 'mysql.php';

// POST isteğiyle gönderilen URUN_KODU'nu ve adet bilgisini al
$urun_kodu = isset($_POST['urun_kodu']) ? $_POST['urun_kodu'] : '';
$ana_resim = isset($_POST['ana_resim']) ? $_POST['ana_resim'] : '';
$adet = isset($_POST['adet']) ? (int)$_POST['adet'] : 1; // Varsayılan olarak 1 adet ekleyin

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

// Sepeti güncellemek için bir yardımcı fonksiyon
function updateCartSession($urun_kodu, $adet, $urun, $ana_resim = '') {
    if (!isset($_SESSION['sepet'])) {
        $_SESSION['sepet'] = [];
    }

    $sepet = &$_SESSION['sepet'];

    if (isset($sepet[$urun_kodu])) {
        $sepet[$urun_kodu]['ADET'] += $adet;
        $sepet[$urun_kodu]['TOPLAM'] = $sepet[$urun_kodu]['FIYAT'] * $sepet[$urun_kodu]['ADET'];
    } else {
        $sepet[$urun_kodu] = [
            'URUN_KODU' => $urun_kodu,
            'RESIM' => $ana_resim ? $ana_resim : $urun['RESIM'],
            'URUN_ADI' => $urun['URUN_ADI'],
            'URUN_LINK' => $urun['URUN_LINK'],
            'FIYAT' => $urun['FIYAT'],
            'ADET' => $adet,
            'TOPLAM' => $urun['FIYAT'] * $adet
        ];
    }
}

// Oturum açılmamış kullanıcılar için sepete ekleme işlemi
if (!isset($_SESSION['EPOSTA'])) {
    updateCartSession($urun_kodu, $adet, $urun, $ana_resim);
    echo "success";
} else {
    // Oturum açılmış kullanıcılar için sepete ekleme işlemini gerçekleştir
    $sql_check = "SELECT * FROM sepet WHERE EPOSTA = :eposta AND URUN_KODU = :urun_kodu";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':eposta', $_SESSION['EPOSTA']);
    $stmt_check->bindParam(':urun_kodu', $urun_kodu);
    $stmt_check->execute();
    $existing_product = $stmt_check->fetch(PDO::FETCH_ASSOC);

    // Eğer sepete aynı ürün koduna sahip bir ürün eklenmişse ADET sütununu artır ve TOPLAM sütununu güncelle 
    if ($existing_product) {
        $new_quantity = $existing_product['ADET'] + $adet;
        $new_total = $new_quantity * $urun['FIYAT']; // Yeni toplam fiyatı hesapla
        
        // ADET ve TOPLAM sütunlarını güncelle
        $sql_update = "UPDATE sepet SET ADET = :adet, TOPLAM = :toplam WHERE EPOSTA = :eposta AND URUN_KODU = :urun_kodu";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':adet', $new_quantity);
        $stmt_update->bindParam(':toplam', $new_total);
        $stmt_update->bindParam(':eposta', $_SESSION['EPOSTA']);
        $stmt_update->bindParam(':urun_kodu', $urun_kodu);
        $stmt_update->execute();
        
        // Sorguyu çalıştır
        if ($stmt_update && $stmt_update->rowCount() > 0) {
            echo "success"; // Başarılı bir şekilde güncellenirse 'success' mesajını döndür
        } else {
            echo "error"; // Hata durumunda 'error' mesajını döndür
        }
    } else {
        // Eğer sepete aynı ürün koduna sahip bir ürün eklenmemişse yeni bir kayıt ekle
        $sql_sepet = "INSERT INTO sepet (EPOSTA, URUN_KODU, RESIM, URUN_ADI, URUN_LINK, FIYAT, ADET, TOPLAM) VALUES (:eposta, :urun_kodu, :resim, :urun_adi, :urun_link, :fiyat, :adet, :toplam)";
        $stmt_sepet = $conn->prepare($sql_sepet);
        $stmt_sepet->bindParam(':eposta', $_SESSION['EPOSTA']);
        $stmt_sepet->bindParam(':urun_kodu', $urun_kodu);
        $stmt_sepet->bindValue(':resim', $ana_resim ? $ana_resim : $urun['RESIM']); // Ana resim post olarak gelmezse $urun['RESIM'] varsayılan olarak atanır
        $stmt_sepet->bindParam(':urun_adi', $urun['URUN_ADI']);
        $stmt_sepet->bindParam(':urun_link', $urun['URUN_LINK']); // URUN_LINK eklendi
        $stmt_sepet->bindParam(':fiyat', $urun['FIYAT']);
        $stmt_sepet->bindParam(':adet', $adet);
        $stmt_sepet->bindValue(':toplam', $urun['FIYAT'] * $adet); // Toplam fiyatı hesaplayın
        $stmt_sepet->execute();
        
        // Sorguyu çalıştır
        if ($stmt_sepet && $stmt_sepet->rowCount() > 0) {
            echo "success"; // Başarılı bir şekilde eklenirse 'success' mesajını döndür
        } else {
            echo "error"; // Hata durumunda 'error' mesajını döndür
        }
    }
}

// PDO bağlantısını kapat
$stmt_urun = null;
$stmt_check = null;
$stmt_update = null;
$stmt_sepet = null;
$conn = null;
?>
