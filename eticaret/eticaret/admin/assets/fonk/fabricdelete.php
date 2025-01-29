<?php
include 'mysql.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mainCategory'])) {
    $mainCategory = $_POST['mainCategory'];

    try {
        $stmt = $conn->prepare("DELETE FROM kumas WHERE KUMAS_CINSI = :mainCategory");
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
