<?php
include 'mysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kayit_id = $_POST['kayit_id'];

    // Resmi veritabanından sil
    $query = $conn->prepare("DELETE FROM varyant_resim WHERE KAYIT_ID = :kayit_id");
    $query->execute(['kayit_id' => $kayit_id]);

    // Silme işlemi başarılıysa başarı mesajı döndür
    if ($query->rowCount() > 0) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
