<?php
// Veritabanı bağlantısını yap
include 'mysql.php';

// POST yöntemiyle gönderilen verileri al
$urunKodu = $_POST['urunKodu'];

// Mükerrer kayıt kontrolü için sorguyu hazırla
$checkQuery = "SELECT COUNT(*) AS count FROM indirimli_urunler WHERE URUN_KODU = :urunKodu";

// Başlangıçta hata olmadığını varsayalım
$response = array(
    'success' => true,
    'message' => ''
);

try {
    // Mükerrer kayıt kontrolü için sorguyu hazırla ve çalıştır
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':urunKodu', $urunKodu);
    $checkStmt->execute();
    $rowCount = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Eğer daha önce aynı ürün kodu ile kayıt yapılmışsa hata döndür
    if ($rowCount > 0) {
        $response['success'] = false;
        $response['message'] = "Es gibt bereits einen Eintrag mit diesem Produktcode.";
    } else {
        // POST yöntemiyle gönderilen diğer verileri al
        $urunAdi = $_POST['urunAdi'];
        $urunResim = $_POST['urunResim'];
        $mevcutFiyat = $_POST['mevcutFiyat'];
        $indirimliFiyat = $_POST['indirimliFiyat'];

        // Kontrol: indirimli fiyatın boş olup olmadığını kontrol et
        if (empty($indirimliFiyat)) {
            $response['success'] = false;
            $response['message'] = "Der reduzierte Preis darf nicht leer sein.";
        } else {
            // Başlangıç ve bitiş tarihi POST edilmişse al, edilmemişse NULL olarak ata
            $baslangicTarihi = isset($_POST['baslangicTarihi']) ? $_POST['baslangicTarihi'] : null;
            $bitisTarihi = isset($_POST['bitisTarihi']) ? $_POST['bitisTarihi'] : null;

            // Veritabanına veriyi eklemek için SQL sorgusu hazırla
            $sql = "INSERT INTO indirimli_urunler (URUN_KODU, URUN_ADI, URUN_RESMI, URUN_FIYATI, INDIRIMLI_FIYAT, BITIS_TARIH, BASLANGIC_TARIH)
            VALUES (:urunKodu, :urunAdi, :urunResim, :mevcutFiyat, :indirimliFiyat, :bitisTarihi, :baslangicTarihi)";

            // SQL sorgusunu hazırla ve çalıştır
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':urunKodu', $urunKodu);
            $stmt->bindParam(':urunAdi', $urunAdi);
            $stmt->bindParam(':urunResim', $urunResim);
            $stmt->bindParam(':mevcutFiyat', $mevcutFiyat);
            $stmt->bindParam(':indirimliFiyat', $indirimliFiyat);

            // Eğer başlangıç tarihi boşsa null olarak ata, değilse değeri parametre olarak ata
            if (empty($baslangicTarihi)) {
                $stmt->bindValue(':baslangicTarihi', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':baslangicTarihi', $baslangicTarihi);
            }

            // Eğer bitiş tarihi boşsa null olarak ata, değilse değeri parametre olarak ata
            if (empty($bitisTarihi)) {
                $stmt->bindValue(':bitisTarihi', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':bitisTarihi', $bitisTarihi);
            }
            
            $stmt->execute();

            // Başarılı bir şekilde eklendiğini belirtmek için mesaj oluştur
            $response['message'] = "Die Daten wurden erfolgreich hinzugefügt.";
        }
    }
} catch (PDOException $e) {
    // Hata oluşursa hata mesajını doldur
    $response['success'] = false;
    $response['message'] = "Datenbankfehler: " . $e->getMessage();
}

// JSON formatında dönüş yap
header('Content-Type: application/json');
echo json_encode($response);
?>
