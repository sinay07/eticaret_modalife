<?php
// Veritabanı bağlantısını include ediyoruz
include('assets/fonk/mysql.php'); // mysql.php dosyasını dahil ediyoruz

// Gelen JSON verisini alıyoruz
$data = json_decode(file_get_contents("php://input"), true); // JSON verisini alıyoruz ve diziye dönüştürüyoruz

// Verileri alıyoruz
$yorum = isset($data['comment']) ? $data['comment'] : '';
$urun_id = isset($data['product_id']) ? $data['product_id'] : '';
$eposta_gonderen = isset($data['sender_email']) ? $data['sender_email'] : '';
$adsoyad = isset($data['adsoyad']) ? $data['adsoyad'] : '';

// Yorum boşsa hata döndürüyoruz
if (empty($yorum) || empty($urun_id) || empty($eposta_gonderen) || empty($adsoyad)) {
    echo json_encode(["status" => "error", "message" => "Lütfen tüm alanları doldurun."]);
    exit;
}

// Kullanıcının bu ürüne daha önce yorum yapıp yapmadığını kontrol et
$sql_check = "SELECT COUNT(*) FROM yorumlar WHERE URUN_ID = :urun_id AND EPOSTA = :eposta_gonderen";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bindParam(':urun_id', $urun_id);
$stmt_check->bindParam(':eposta_gonderen', $eposta_gonderen);
$stmt_check->execute();

// Eğer kullanıcı zaten yorum yapmışsa
if ($stmt_check->fetchColumn() > 0) {
    echo json_encode(["status" => "error", "message" => "Bu ürüne zaten yorum yapmışsınız."]);
    exit;
}

// Yorumları veritabanına eklemek için SQL sorgusu
$sql = "INSERT INTO yorumlar (URUN_ID, EPOSTA, ADSOYAD, YORUM) VALUES (:urun_id, :eposta_gonderen, :adsoyad, :yorum)";

// Veritabanına ekleme işlemi
try {
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':urun_id', $urun_id);
    $stmt->bindParam(':eposta_gonderen', $eposta_gonderen);
    $stmt->bindParam(':adsoyad', $adsoyad);
    $stmt->bindParam(':yorum', $yorum);
    
    $stmt->execute();

    // Başarıyla ekleme işlemi tamamlandıysa JSON yanıtı gönderiyoruz
    echo json_encode(["status" => "success", "message" => "Yorum başarıyla gönderildi!"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Yorum eklenirken bir hata oluştu: " . $e->getMessage()]);
}
?>
