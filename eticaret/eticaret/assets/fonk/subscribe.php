<?php
header('Content-Type: application/json');
require_once 'mysql.php'; // Veritabanı bağlantısını içerir

// E-posta adresini alın
$email = isset($_POST['EMAIL']) ? $_POST['EMAIL'] : '';

// E-posta doğrulama
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz e-posta adresi.']);
    exit;
}

try {
    // E-posta adresinin daha önce eklenip eklenmediğini kontrol et
    $stmt = $conn->prepare("SELECT COUNT(*) FROM aboneler WHERE EPOSTA = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // E-posta zaten eklenmiş
        echo json_encode(['success' => false, 'message' => 'Bu e-posta adresi zaten kayıtlı.']);
    } else {
        // E-posta adresini ekle
        $stmt = $conn->prepare("INSERT INTO aboneler (EPOSTA) VALUES (:email)");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Başarılıysa
        echo json_encode(['success' => true, 'message' => 'Başarıyla abone oldunuz.']);
    }
} catch (PDOException $e) {
    // Hata varsa
    echo json_encode(['success' => false, 'message' => 'Bir hata oluştu.']);
}
?>
