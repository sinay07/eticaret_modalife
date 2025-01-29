<?php
session_start();
include 'mysql.php'; // Veritabanı bağlantı dosyanızın adını buraya ekleyin

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // JSON verisini al
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!empty($data)) {
        if (isset($data['login'])) {
            login($data);
        } elseif (isset($data['register'])) {
            register($data);
        }
    } else {
        // echo json_encode(['status' => 'error', 'message' => 'Veri bulunamadı!']);
    }
}

function login($data) {
    global $conn;

    $email = isset($data['email']) ? $data['email'] : '';
    $password = isset($data['password']) ? $data['password'] : '';

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'E-posta veya Şifre boş olamaz']);
        return;
    }

    $sql = "SELECT * FROM kullanicilar WHERE EPOSTA = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['SIFRE'])) {
        // Giriş başarılı, oturumda kullanıcı bilgilerini tut
        $_SESSION['ADSOYAD'] = $user['ADSOYAD'];
        $_SESSION['EPOSTA'] = $user['EPOSTA'];
        
        // Sepet verilerini veritabanına aktar
        transferSessionCartToDatabase($_SESSION['EPOSTA']);

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'E-posta veya şifre hatalı!']);
    }
}

function register($data) {
    global $conn;

    $name = isset($data['name']) ? $data['name'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $password = isset($data['password']) ? $data['password'] : '';
    $confirm_password = isset($data['confirm_password']) ? $data['confirm_password'] : '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo json_encode(['status' => 'error', 'message' => 'Tüm alanlar doldurulmalıdır']);
        return;
    }

    $sql_check_email = "SELECT * FROM kullanicilar WHERE EPOSTA = :email";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();
    $existing_user = $stmt_check_email->fetch(PDO::FETCH_ASSOC);

    if ($existing_user) {
        echo json_encode(['status' => 'error', 'message' => 'Bu e-posta adresi zaten kayıtlı.']);
    } else {
        if ($password !== $confirm_password) {
            echo json_encode(['status' => 'error', 'message' => 'Şifreler uyuşmuyor!']);
        } else {
            try {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO kullanicilar (ADSOYAD, EPOSTA, SIFRE) VALUES (:name, :email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Veri başarıyla eklendi.']);
            } catch (PDOException $e) {
                //echo json_encode(['status' => 'error', 'message' => 'Bağlantı hatası: ' . $e->getMessage()]);
            }
        }
    }
}

function transferSessionCartToDatabase($email) {
    global $conn;

    if (isset($_SESSION['sepet']) && is_array($_SESSION['sepet'])) {
        foreach ($_SESSION['sepet'] as $item) {
            $urun_kodu = $item['URUN_KODU'];
            $adet = $item['ADET'];
            $resim = $item['RESIM'];
            $urun_adi = $item['URUN_ADI'];
            $urun_link = $item['URUN_LINK'];
            $fiyat = $item['FIYAT'];
            $toplam = $item['TOPLAM'];

            // Sepette var mı kontrol et
            $sql_check = "SELECT * FROM sepet WHERE EPOSTA = :eposta AND URUN_KODU = :urun_kodu";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bindParam(':eposta', $email);
            $stmt_check->bindParam(':urun_kodu', $urun_kodu);
            $stmt_check->execute();
            $existing_product = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($existing_product) {
                // Ürün zaten sepette varsa, miktarı güncelle
                $new_quantity = $existing_product['ADET'] + $adet;
                $new_total = $new_quantity * $fiyat;

                $sql_update = "UPDATE sepet SET ADET = :adet, TOPLAM = :toplam WHERE EPOSTA = :eposta AND URUN_KODU = :urun_kodu";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bindParam(':adet', $new_quantity);
                $stmt_update->bindParam(':toplam', $new_total);
                $stmt_update->bindParam(':eposta', $email);
                $stmt_update->bindParam(':urun_kodu', $urun_kodu);
                $stmt_update->execute();
            } else {
                // Ürün sepette yoksa, yeni bir kayıt ekle
                $sql_insert = "INSERT INTO sepet (EPOSTA, URUN_KODU, RESIM, URUN_ADI, URUN_LINK, FIYAT, ADET, TOPLAM) VALUES (:eposta, :urun_kodu, :resim, :urun_adi, :urun_link, :fiyat, :adet, :toplam)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bindParam(':eposta', $email);
                $stmt_insert->bindParam(':urun_kodu', $urun_kodu);
                $stmt_insert->bindParam(':resim', $resim);
                $stmt_insert->bindParam(':urun_adi', $urun_adi);
                $stmt_insert->bindParam(':urun_link', $urun_link);
                $stmt_insert->bindParam(':fiyat', $fiyat);
                $stmt_insert->bindParam(':adet', $adet);
                $stmt_insert->bindParam(':toplam', $toplam);
                $stmt_insert->execute();
            }
        }

        // Oturum sepetini temizle
        unset($_SESSION['sepet']);
    }
}
?>
