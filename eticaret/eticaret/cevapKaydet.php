<?php
include 'assets/fonk/mysql.php';

// Gelen POST verilerini alıyoruz
$gonderen_email = $_POST['gonderen_email'];  // Bu aslında cevap yazan kişi
$alici_email = $_POST['alici_email'];        // Bu ise cevabı alacak kişi
$urun_id = $_POST['urun_id'];
$cevap = $_POST['cevap'];

// Veritabanına cevabı kaydedelim
$sql = "INSERT INTO mesajlar (GONDEREN_EPOSTA, ALICI_EPOSTA, URUN_ID, MESAJ) VALUES (:gonderen_email, :alici_email, :urun_id, :cevap)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':gonderen_email', $gonderen_email);
$stmt->bindParam(':alici_email', $alici_email);
$stmt->bindParam(':urun_id', $urun_id);
$stmt->bindParam(':cevap', $cevap);

// Cevabın başarılı bir şekilde kaydedilmesi durumunda başarı mesajı gönderelim
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Cevabınız başarıyla kaydedildi.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Cevap kaydedilemedi.']);
}

$stmt = null;
$conn = null;
?>
