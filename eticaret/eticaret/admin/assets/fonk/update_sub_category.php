<?php
include 'mysql.php';

header('Content-Type: application/json');

$response = array();

if(isset($_POST['subCategory']) && isset($_POST['newSubCategoryName'])){
    $subCategory = $_POST['subCategory'];
    $newSubCategoryName = $_POST['newSubCategoryName'];
    
    try {
        // Transaction başlat
        $conn->beginTransaction();

        // alt_kategori tablosunu güncelle
        $stmt1 = $conn->prepare("UPDATE alt_kategori SET ALT_KATEGORI_ADI = :newSubCategoryName WHERE ALT_KATEGORI_ADI = :subCategory");
        $stmt1->bindParam(':newSubCategoryName', $newSubCategoryName);
        $stmt1->bindParam(':subCategory', $subCategory);
        $stmt1->execute();

        // urunler tablosunu güncelle
        $stmt2 = $conn->prepare("UPDATE urunler SET ALT_KATEGORI = :newSubCategoryName WHERE ALT_KATEGORI = :subCategory");
        $stmt2->bindParam(':newSubCategoryName', $newSubCategoryName);
        $stmt2->bindParam(':subCategory', $subCategory);
        $stmt2->execute();

        // Transaction başarılı ise commit et
        $conn->commit();

        // Başarılı olduğunda
        $response['success'] = true;
        $response['message'] = "Alt kategori ve ürünler başarıyla güncellendi.";

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
