<?php
include 'mysql.php';

session_start(); // Oturumu başlat

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    // Gelen verileri al
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Kullanıcı adı ve şifreyi veritabanında kontrol et
        $stmt = $conn->prepare("SELECT * FROM yonetici WHERE KULLANICI_ADI = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['SIFRE'])) {
            // Kullanıcı ve şifre doğruysa başarılı yanıt döner
            $_SESSION['username'] = $username; // Kullanıcı adını oturumda sakla
            echo json_encode(['success' => true]);
        } else {
            // Kullanıcı adı veya şifre yanlışsa başarısız yanıt döner
            echo json_encode(['success' => false]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Eksik veya geçersiz veri']);
}
?>
