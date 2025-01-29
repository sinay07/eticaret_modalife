<?php
include 'mysql.php';

header('Content-Type: application/json');

$response = array();

if(isset($_POST['subCategory'])){
    $subCategory = $_POST['subCategory'];
    
    try {
        // Sorguyu hazırla ve bağlı değişkenleri ata
        $stmt = $conn->prepare("DELETE FROM alt_kategori WHERE ALT_KATEGORI_ADI = :subCategory");
        $stmt->bindParam(':subCategory', $subCategory);

        // Sorguyu çalıştır
        $stmt->execute();

        // Başarılı olduğunda
        $response['success'] = true;
        $response['message'] = "Alt kategori başarıyla silindi.";

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
