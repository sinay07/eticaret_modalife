<?php
// Veritabanı bağlantısını başlatıyoruz
include('assets/fonk/mysql.php'); // Veritabanı bağlantı dosyanızın yolu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Formdan gelen veriler
        $gonderen_email = $_POST['GONDEREN_EPOSTA'];  // Kullanıcı e-posta adresi
        $alici_email = $_POST['ALICI_EPOSTA'];        // Mesajı alacak kullanıcının e-posta adresi
        $urun_id = $_POST['URUN_ID'];                 // Ürün ID'si
        $mesaj = $_POST['MESAJ'];                     // Kullanıcı mesajı

        // Mesajlar tablosuna veri eklemek için SQL sorgusu
        $stmt = $conn->prepare("INSERT INTO mesajlar (GONDEREN_EPOSTA, ALICI_EPOSTA, URUN_ID, MESAJ, ZAMAN) 
                                VALUES (:gonderen_email, :alici_email, :urun_id, :mesaj, NOW())");

        // Parametreleri bağla
        $stmt->bindParam(':gonderen_email', $gonderen_email);
        $stmt->bindParam(':alici_email', $alici_email);
        $stmt->bindParam(':urun_id', $urun_id);
        $stmt->bindParam(':mesaj', $mesaj);

        // Sorguyu çalıştır ve veriyi ekle
        $stmt->execute();

        // Başarılı işlem sonrası, kullanıcıyı geldiği sayfaya yönlendir
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "");
        } else {
            // Eğer HTTP_REFERER bilgisi yoksa, ana sayfaya yönlendir
            header("Location: index.php?success=true");
        }
        exit; // İşlem bitti
    } catch(PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }
}
?>
