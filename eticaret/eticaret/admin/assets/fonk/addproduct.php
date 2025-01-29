<?php
// Veritabanı bağlantısını içe aktar
require_once('mysql.php');

// Form verilerinin mevcut olup olmadığını kontrol et
$requiredFields = [
    'urunKodu', 'urunAdi', 'urunAciklama', 'urunFiyat', 
    'urunStok', 'urunEtiket', 'cinsiyetSec', 'kumasCinsi', 'anaKategori', 
    'altKategori', 'beden'
];

foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        echo json_encode(array('success' => false, 'message' => "Eksik form verisi: $field"));
        exit;
    }
}

if (!isset($_FILES['kapakResim'])) {
    echo json_encode(array('success' => false, 'message' => 'Kapak resmi eksik.'));
    exit;
}

function correctImageOrientation($filename) {
    if (function_exists('exif_read_data')) {
        $exif = @exif_read_data($filename);
        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            $image = imagecreatefromstring(file_get_contents($filename));
            switch ($orientation) {
                case 3:
                $image = imagerotate($image, 180, 0);
                break;
                case 6:
                $image = imagerotate($image, -90, 0);
                break;
                case 8:
                $image = imagerotate($image, 90, 0);
                break;
            }
            imagejpeg($image, $filename, 90);
        }
    }
}

function convertToWebp($source, $destination) {
    correctImageOrientation($source); // Resmin yönünü düzelt

    $image = @imagecreatefromstring(file_get_contents($source));
    if ($image === false) {
        return false; // Resim geçerli değilse, hata olarak false döndür
    }
    
    // Resmi webp formatına dönüştür
    $converted = imagewebp($image, $destination, 85);

    // Bellekteki resim kaynağını temizle
    imagedestroy($image);

    if ($converted === false) {
        return false; // Dönüştürme başarısız ise, hata olarak false döndür
    }

    return true; // Dönüştürme başarılı ise, true döndür
}

// Form verilerini al
$urunKodu = $_POST['urunKodu'];
$urunAdi = $_POST['urunAdi'];
$urunLink = $urunAdi."/".$urunKodu;
$urunAciklama = $_POST['urunAciklama'];
$urunFiyat = $_POST['urunFiyat'];
$urunStok = $_POST['urunStok'];
$urunEtiket = $_POST['urunEtiket'];
$cinsiyetSec = $_POST['cinsiyetSec'];
$kumasCinsi = $_POST['kumasCinsi'];
$anaKategori = $_POST['anaKategori'];
$altKategori = $_POST['altKategori'];
$bedenler = is_array($_POST['beden']) ? $_POST['beden'] : explode(',', $_POST['beden']); // Bedenleri dizi olarak al

// Dosya yüklemesi için gereken işlemler
$targetDir = "../../../assets/images/upload/product/";
$allowTypes = array('jpg', 'png', 'jpeg', 'gif');

// Kapak resmini kontrol et ve yükle
if ($_FILES['kapakResim']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['kapakResim']['tmp_name'])) {
    $coverImage = $_FILES['kapakResim'];
    $coverImageName = uniqid('cover_') . '.webp';
    $coverImagePath = $targetDir . $coverImageName;

    $imageType = strtolower(pathinfo($coverImage['name'], PATHINFO_EXTENSION));

    if (!in_array($imageType, $allowTypes)) {
        echo json_encode(array('success' => false, 'message' => 'Geçersiz dosya türü.'));
        exit;
    }

    // Resmi webp formatına dönüştür
    $converted = convertToWebp($coverImage['tmp_name'], $coverImagePath);
    if (!$converted) {
        echo json_encode(array('success' => false, 'message' => 'Resim dönüştürülürken bir hata oluştu.'));
        exit;
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Kapak resmi eksik veya yüklenemedi.'));
    exit;
}

// Diğer ürün resimlerini kontrol et ve yükle
$digerResimler = array();
if (!empty($_FILES['digerResimler']['name'][0])) {
    foreach ($_FILES['digerResimler']['name'] as $key => $image) {
        $imageTmp = $_FILES['digerResimler']['tmp_name'][$key];
        $imageName = uniqid('product_') . '_' . $key . '.webp';
        $imagePath = $targetDir . $imageName;

        $imageType = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (!in_array($imageType, $allowTypes)) {
            echo json_encode(array('success' => false, 'message' => 'Geçersiz dosya türü.'));
            exit;
        }

        // Resmi webp formatına dönüştür
        $converted = convertToWebp($imageTmp, $imagePath);
        if (!$converted) {
            echo json_encode(array('success' => false, 'message' => 'Resim dönüştürülürken bir hata oluştu.'));
            exit;
        }

        $digerResimler[] = $imageName;
    }
}

// Veritabanına ürünü ekle
// URUN_KODU ve URUN_LINK benzersiz olmalıdır
$sqlCheck = "SELECT COUNT(*) AS count FROM urunler WHERE URUN_KODU = :urunKodu OR URUN_LINK = :urunLink";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bindParam(':urunKodu', $urunKodu);
$stmtCheck->bindParam(':urunLink', $urunLink);
$stmtCheck->execute();
$row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
if ($row['count'] > 0) {
    echo json_encode(array('success' => false, 'message' => 'Bu ürün kodu veya ürün linki zaten kullanılıyor.'));
    exit;
}

// Veritabanına ürünü ekle
$sql = "INSERT INTO urunler (URUN_KODU, RESIM, URUN_ADI, ACIKLAMA, FIYAT, STOK, ETIKETLER, KUMAS_CINSI, CINSIYET, URUN_LINK, ANA_KATEGORI, ALT_KATEGORI, PUAN)
VALUES (:urunKodu, :kapakResim, :urunAdi, :urunAciklama, :urunFiyat, :urunStok, :urunEtiket, :kumasCinsi, :cinsiyetSec, :urunLink, :anaKategori, :altKategori, 1.9)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':urunKodu', $urunKodu);
$stmt->bindValue(':kapakResim', $coverImageName);
$stmt->bindParam(':urunAdi', $urunAdi);
$stmt->bindParam(':urunAciklama', $urunAciklama);
$stmt->bindParam(':urunFiyat', $urunFiyat);
$stmt->bindParam(':urunStok', $urunStok);
$stmt->bindParam(':urunEtiket', $urunEtiket);
$stmt->bindParam(':kumasCinsi', $kumasCinsi);
$stmt->bindParam(':cinsiyetSec', $cinsiyetSec); 
$stmt->bindParam(':urunLink', $urunLink);
$stmt->bindParam(':anaKategori', $anaKategori);
$stmt->bindParam(':altKategori', $altKategori); 

if ($stmt->execute()) {
    // Diğer resimler varsa, varyant_resim tablosuna ekle
    if (!empty($digerResimler)) {
        $sqlVaryantResim = "INSERT INTO varyant_resim (URUN_KODU, RESIM) VALUES ";
        $values = array();
        $params = array();
        foreach ($digerResimler as $index => $resim) {
            $values[] = "(:urunKodu_{$index}, :resim_{$index})";
            $params[":urunKodu_{$index}"] = $urunKodu;
            $params[":resim_{$index}"] = $resim;
        }
        $sqlVaryantResim .= implode(',', $values);
        $stmtVaryantResim = $conn->prepare($sqlVaryantResim);
        foreach ($params as $param => $value) {
            $stmtVaryantResim->bindValue($param, $value);
        }
        if (!$stmtVaryantResim->execute()) {
            echo json_encode(array('success' => false, 'message' => 'Ürün resmi yüklenirken bir hata oluştu.'));
            exit;
        }
    }

    // Beden bilgilerini varyant_beden tablosuna ekle
    $sqlVaryantBeden = "INSERT INTO varyant_beden (URUN_KODU, BEDEN) VALUES (:urunKodu, :beden)";
    $stmtVaryantBeden = $conn->prepare($sqlVaryantBeden);

    foreach ($bedenler as $beden) {
        $stmtVaryantBeden->bindParam(':urunKodu', $urunKodu);
        $stmtVaryantBeden->bindParam(':beden', $beden);
        if (!$stmtVaryantBeden->execute()) {
            echo json_encode(array('success' => false, 'message' => 'Beden bilgisi eklenirken bir hata oluştu.'));
            exit;
        }
    }

    echo json_encode(array('success' => true, 'message' => 'Ürün başarıyla eklendi.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Ürün eklenirken bir hata oluştu.'));
}
?>
