<?php
include 'mysql.php';

header('Content-Type: application/json');

$response = array();

if(isset($_POST['mainCategory']) && isset($_POST['newMainCategoryName'])){
    $mainCategory = $_POST['mainCategory'];
    $newMainCategoryName = $_POST['newMainCategoryName'];
    
    try {
        // Transaction başlat
        $conn->beginTransaction();

        // ana_kategori tablosunu güncelle
        $stmt1 = $conn->prepare("UPDATE ana_kategori SET ANA_KATEGORI_ADI = :newMainCategoryName WHERE ANA_KATEGORI_ADI = :mainCategory");
        $stmt1->bindParam(':newMainCategoryName', $newMainCategoryName);
        $stmt1->bindParam(':mainCategory', $mainCategory);
        $stmt1->execute();

        // alt_kategori tablosunu güncelle
        $stmt2 = $conn->prepare("UPDATE alt_kategori SET ANA_KATEGORI_ADI = :newMainCategoryName WHERE ANA_KATEGORI_ADI = :mainCategory");
        $stmt2->bindParam(':newMainCategoryName', $newMainCategoryName);
        $stmt2->bindParam(':mainCategory', $mainCategory);
        $stmt2->execute();

        // urunler tablosunu güncelle
        $stmt3 = $conn->prepare("UPDATE urunler SET ANA_KATEGORI = :newMainCategoryName WHERE ANA_KATEGORI = :mainCategory");
        $stmt3->bindParam(':newMainCategoryName', $newMainCategoryName);
        $stmt3->bindParam(':mainCategory', $mainCategory);
        $stmt3->execute();

        // Transaction başarılı ise commit et
        $conn->commit();

        // Başarılı olduğunda
        $response['success'] = true;
        $response['message'] = "Ana kategori, alt kategori ve ürünler başarıyla güncellendi.";

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
