<?php
include 'mysql.php';

header('Content-Type: application/json');

$response = array();

if(isset($_POST['mainCategory'])){
    $mainCategory = $_POST['mainCategory'];
    
    try {
        // Sorguyu hazırla ve bağlı değişkenleri ata
        $stmt = $conn->prepare("DELETE FROM ana_kategori WHERE ANA_KATEGORI_ADI = :mainCategory");
        $stmt->bindParam(':mainCategory', $mainCategory);

        // Sorguyu çalıştır
        $stmt->execute();

        // Başarılı olduğunda
        $response['success'] = true;
        $response['message'] = "Ana kategori başarıyla silindi.";

    } catch(PDOException $e) {
        // Hata olduğunda
        $response['success'] = false;
        $response['message'] = "Veritabanı hatası: " . $e->getMessage();
    }
} else {
    $response['success'] = false;
    $response['message'] = "Veri alınamadı.";
}

echo json_encode($response);
?>
