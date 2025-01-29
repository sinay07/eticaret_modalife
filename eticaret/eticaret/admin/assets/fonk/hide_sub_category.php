<?php
include 'mysql.php';

header('Content-Type: application/json');

$response = array();

if(isset($_POST['subCategory'])){
    $subCategory = $_POST['subCategory'];
    
    // Gelen subCategory verisinden (Secret) kısmını temizle
    $cleanedSubCategory = str_replace(" (Versteckt)", "", $subCategory);
    
    try {
        // Transaction başlat
        $conn->beginTransaction();
        
        // alt_kategori tablosunu güncelle
        $stmt = $conn->prepare("UPDATE alt_kategori SET GIZLE = CASE WHEN GIZLE = 1 THEN 0 ELSE 1 END WHERE ALT_KATEGORI_ADI = :subCategory");
        $stmt->bindParam(':subCategory', $cleanedSubCategory);
        $stmt->execute();

        // Transaction başarılı ise commit et
        $conn->commit();

        // Başarılı olduğunda
        $response['success'] = true;
        $response['message'] = "Alt kategori başarıyla güncellendi.";

    } catch(PDOException $e) {
        // Hata olduğunda transaction geri al
        $conn->rollBack();

        $response['success'] = false;
        $response['message'] = "Veritabanı hatası: " . $e->getMessage();
    }
} else {
    $response['success'] = false;
    $response['message'] = "Veri alınamadı.";
}

echo json_encode($response);
?>
