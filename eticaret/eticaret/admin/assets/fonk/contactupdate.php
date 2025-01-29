<?php
include 'mysql.php';

// Başlangıçta bir boş dizi oluşturalım
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POST isteğiyle gelen verileri al
    $firma_adi = $_POST['firma_adi'];
    $telefon_1 = $_POST['telefon_1'];
    $telefon_2 = $_POST['telefon_2'];
    $eposta_1 = $_POST['eposta_1'];
    $eposta_2 = $_POST['eposta_2'];
    $adres = $_POST['adres'];
    $slogan = $_POST['slogan'];
    $facebook_url = $_POST['facebook_url'];
    $instagram_url = $_POST['instagram_url'];
    $twitter_url = $_POST['twitter_url'];

    try {
        // Veritabanında güncelleme işlemini gerçekleştir
        $stmt = $conn->prepare("UPDATE firma_bilgileri SET FIRMA_ADI = ?, FIRMA_TELEFON1 = ?, FIRMA_TELEFON2 = ?, FIRMA_EPOSTA1 = ?, FIRMA_EPOSTA2 = ?, ADRES = ?, SLOGAN = ?, FACEBOOK = ?, INSTAGRAM = ?, X = ?");
        $stmt->execute([$firma_adi, $telefon_1, $telefon_2, $eposta_1, $eposta_2, $adres, $slogan, $facebook_url, $instagram_url, $twitter_url]);

        // Başarılı yanıtı oluştur
        $response['success'] = true;
        $response['message'] = "Bilgiler başarıyla güncellendi.";
    } catch (PDOException $e) {
        // Başarısız yanıtı oluştur
        $response['success'] = false;
        $response['message'] = "Datenbankfehler: " . $e->getMessage();
    }
}

// JSON yanıtı döndür
header('Content-Type: application/json');
echo json_encode($response);
?>
