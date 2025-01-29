<?php
include 'mysql.php';

// Gelen veriyi al
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $sliderId = $_POST['id'];

        // Slideri veritabanından sil
        $stmt = $conn->prepare("DELETE FROM slider WHERE KAYIT_ID = ?");
        $stmt->execute([$sliderId]);

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ungültige Anfrage']);
}
?>
