<?php
include 'mysql.php';

// Formdan gelen verileri işle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Eğer formdan bir dosya seçildiyse
    if(isset($_FILES['slider_resmi'])) {
        $target_dir = "../../../assets/images/upload/slider/";
        $target_file = $target_dir . basename($_FILES["slider_resmi"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Dosya türünü kontrol et
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo json_encode(array("success" => false, "message" => "Sadece JPG, JPEG, PNG dosyaları yükleyebilirsiniz."));
            return;
        }

        // Dosyayı yeniden adlandır ve hedef dizine kaydet
        $newFileName = uniqid() . '.' . $imageFileType;
        if (move_uploaded_file($_FILES["slider_resmi"]["tmp_name"], $target_dir . $newFileName)) {
            // Dosya yükleme başarılı oldu, veritabanında güncelle
            $sliderId = $_POST['slider_id'];
            $sliderYazi = $_POST['slider_yazi'];
            $butonYazi = $_POST['buton_yazi'];
            $butonUrl = $_POST['buton_url'];

            try {
                $stmt = $conn->prepare("UPDATE slider SET RESIM = ?, BASLIK = ?, BUTON_METNI = ?, BUTON_LINK = ? WHERE KAYIT_ID = ?");
                $stmt->execute([$newFileName, $sliderYazi, $butonYazi, $butonUrl, $sliderId]);
                echo json_encode(array("success" => true));
                return; // işlem tamamlandı, kodun devam etmemesi için çıkış yapılır
            } catch (PDOException $e) {
                echo json_encode(array("success" => false, "message" => "Veriyi güncellerken bir hata oluştu: " . $e->getMessage()));
                return; // hata olduğunda da çıkış yapılır
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Dosya yükleme başarısız."));
            return; // dosya yükleme başarısız olduğunda da çıkış yapılır
        }
    } else {
        // Dosya seçilmediyse sadece metin verilerini veritabanında güncelle
        $sliderId = $_POST['slider_id'];
        $sliderYazi = $_POST['slider_yazi'];
        $butonYazi = $_POST['buton_yazi'];
        $butonUrl = $_POST['buton_url'];

        try {
            $stmt = $conn->prepare("UPDATE slider SET BASLIK = ?, BUTON_METNI = ?, BUTON_LINK = ? WHERE KAYIT_ID = ?");
            $stmt->execute([$sliderYazi, $butonYazi, $butonUrl, $sliderId]);
            if($stmt->rowCount() > 0) {
                echo json_encode(array("success" => true));
                return; // işlem tamamlandı, kodun devam etmemesi için çıkış yapılır
            } else {
                echo json_encode(array("success" => false, "message" => "Güncelleme yapılamadı. Belirtilen kayıt bulunamadı."));
                return; // güncelleme yapılamadığında da çıkış yapılır
            }
        } catch (PDOException $e) {
            echo json_encode(array("success" => false, "message" => "Veriyi güncellerken bir hata oluştu: " . $e->getMessage()));
            return; // hata olduğunda da çıkış yapılır
        }
    }
}
?>
