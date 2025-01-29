<?php
// "mysql.php" dosyasını içe aktar
include 'mysql.php';

// POST verilerini doğrudan PDO prepared statements ile al ve veritabanına ekle
try {
    // E-posta bilgisini session'dan al
    session_start();
    $eposta = $_SESSION['EPOSTA'];

    // Zorunlu alanlarını kontrol et
    $requiredFields = ['AdSoyad', 'Telefon', 'Adres', 'Ulke', 'Sehir'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            echo "Hata: '$field' alanı boş olamaz!";
            exit;
        }
    }

    // SQL sorgusu oluştur
    $sql = "INSERT INTO adresler (ADSOYAD, EPOSTA, TELEFON, FIRMA, ADRES, ULKE, SEHIR, EYALET, POSTA_KODU)
    VALUES (:adSoyad, :eposta, :telefon, :firma, :adres, :ulke, :sehir, :eyalet, :postaKodu)";

    // PDO prepared statements kullanarak sorguyu hazırla ve çalıştır
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':adSoyad' => $_POST['AdSoyad'],
        ':eposta' => $eposta,
        ':telefon' => $_POST['Telefon'],
        ':firma' => $_POST['Firma'] ?? null, // Firma bilgisi boşsa null olarak eklenir
        ':adres' => $_POST['Adres'],
        ':ulke' => $_POST['Ulke'],
        ':sehir' => $_POST['Sehir'],
        ':eyalet' => $_POST['Eyalet'] ?? null, // Eyalet bilgisi boşsa null olarak eklenir
        ':postaKodu' => $_POST['PostaKodu'] ?? null // Posta kodu bilgisi boşsa null olarak eklenir
    ]);

    echo "Adres başarıyla eklendi!";
} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage();
}

// Veritabanı bağlantısını kapat
$conn = null;
?>
