<?php
include 'mysql.php';

// Gelen verileri al
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Alanların boş olup olmadığını kontrol et
        if (empty($_FILES['slider_resmi']['name']) || empty($_POST['slider_yazi']) || empty($_POST['buton_yazi']) || empty($_POST['buton_url'])) {
            echo json_encode(['status' => 'error', 'message' => 'Alle Felder müssen ausgefüllt sein.']);
            exit;
        }

        $resim = $_FILES['slider_resmi']['name'];
        $yazi = $_POST['slider_yazi'];
        $buton_yazi = $_POST['buton_yazi'];
        $buton_url = $_POST['buton_url'];

        // Benzersiz bir dosya adı oluştur
        $unique_id = uniqid();

        // Dosya adını güncelle ve webp formatına dönüştür
        $resim_webp = $unique_id . ".webp";
        $target_dir = "../../../assets/images/upload/slider/";
        $target_file = $target_dir . $resim_webp;

        // Resmi webp formatına dönüştürme
        $image = imagecreatefromstring(file_get_contents($_FILES['slider_resmi']['tmp_name']));
        imagewebp($image, $target_file);
        imagedestroy($image);

        // Veritabanına ekle (sadece dosya adını kaydet)
        $stmt = $conn->prepare("INSERT INTO slider (RESIM, BASLIK, BUTON_METNI, BUTON_LINK) VALUES (?, ?, ?, ?)");
        $stmt->execute([$resim_webp, $yazi, $buton_yazi, $buton_url]);

        echo json_encode(['status' => 'success', 'message' => 'Slider erfolgreich hinzugefügt.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ungültiger Anforderungstyp']);
}
?>
