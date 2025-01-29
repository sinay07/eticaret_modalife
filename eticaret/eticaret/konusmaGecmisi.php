<?php
include 'assets/fonk/mysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gonderen_email = $_POST['gonderen_email'];
    $alici_email = $_POST['alici_email'];

    // Konuşma geçmişini alalım
    $sql = "SELECT * FROM mesajlar WHERE (GONDEREN_EPOSTA = :gonderen_email AND ALICI_EPOSTA = :alici_email) 
            OR (GONDEREN_EPOSTA = :alici_email AND ALICI_EPOSTA = :gonderen_email) ORDER BY ZAMAN ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':gonderen_email', $gonderen_email);
    $stmt->bindParam(':alici_email', $alici_email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $conversation = '';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $gonderen_email = htmlspecialchars($row['GONDEREN_EPOSTA']);
            $mesaj = htmlspecialchars($row['MESAJ']);
            $tarih = htmlspecialchars($row['ZAMAN']);

            // Gönderenin ad ve soyadını almak için kullanıcılar tablosuna sorgu yapalım
            $user_sql = "SELECT ADSOYAD FROM kullanicilar WHERE EPOSTA = :gonderen_email";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->bindParam(':gonderen_email', $gonderen_email);
            $user_stmt->execute();

            $gonderen_adsoyad = '';
            if ($user_stmt->rowCount() > 0) {
                $user_row = $user_stmt->fetch(PDO::FETCH_ASSOC);
                $gonderen_adsoyad = $user_row['ADSOYAD'];
            }

            // Mesajı eklerken e-posta yerine adı soyadı gösterelim
            $conversation .= "<p><strong>{$gonderen_adsoyad}</strong> ({$tarih}): {$mesaj}</p>";
        }

        echo json_encode([
            'status' => 'success',
            'conversation' => $conversation
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Konuşma geçmişi bulunamadı.'
        ]);
    }
}

$stmt = null;
$conn = null;
?>
