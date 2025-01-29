<?php
include 'mysql.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mainCategory']) && isset($_POST['newMainCategoryName'])) {
    $mainCategory = $_POST['mainCategory'];
    $newMainCategoryName = $_POST['newMainCategoryName'];

    try {
        $stmt = $conn->prepare("UPDATE kumas SET KUMAS_CINSI = :newMainCategoryName WHERE KUMAS_CINSI = :mainCategory");
        $stmt->bindParam(':newMainCategoryName', $newMainCategoryName);
        $stmt->bindParam(':mainCategory', $mainCategory);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Eksik veya geçersiz veri']);
}
?>
