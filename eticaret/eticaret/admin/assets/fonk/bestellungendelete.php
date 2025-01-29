<?php
include 'mysql.php';

if(isset($_POST['siparisNo'])){
    $siparisNo = $_POST['siparisNo'];

    // Veritabanında ilgili siparişin durumunu güncelle
    $sql = "UPDATE siparisler SET DURUM = 'İPTAL' WHERE SIPARIS_NO = :siparisNo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':siparisNo', $siparisNo);
    $stmt->execute();

    // İşlem başarılı ise true döndür
    echo json_encode(array('success' => true));
} else {
    // İşlem başarısız ise false döndür
    echo json_encode(array('success' => false));
}
?>
