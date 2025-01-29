<?php
// Veritabanı bağlantısı
include 'mysql.php';

$response = array('status' => '', 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST verilerini al
    $urun_kodu = $_POST['urun_kodu'];
    $urun_adi = $_POST['urun_adi'];
    $urun_aciklama = $_POST['urun_aciklama'];
    $urun_fiyat = $_POST['urun_fiyat'];
    $urun_stok = $_POST['urun_stok'];
    $urun_etiketler = $_POST['urun_etiketler'];
    $urun_cinsiyet = $_POST['urun_cinsiyet'];

    try {
        // Ürün bilgilerini güncelle (urunler tablosu)
        $query_urun = $conn->prepare("UPDATE urunler SET URUN_ADI = :urun_adi, ACIKLAMA = :urun_aciklama, FIYAT = :urun_fiyat,
        STOK = :urun_stok, ETIKETLER = :urun_etiketler, CINSIYET = :urun_cinsiyet WHERE URUN_KODU = :urun_kodu");
        $query_urun->execute([
            'urun_adi' => $urun_adi,
            'urun_aciklama' => $urun_aciklama,
            'urun_fiyat' => $urun_fiyat,
            'urun_stok' => $urun_stok,
            'urun_etiketler' => $urun_etiketler,
            'urun_cinsiyet' => $urun_cinsiyet,
            'urun_kodu' => $urun_kodu,
        ]);

        // Target directory tanımlaması
        $target_dir = "../../../assets/images/upload/product/";

        // Kapak resmi yükleme ve webp formatına dönüştürme
        if (isset($_FILES['urun_kapak_resmi']) && $_FILES['urun_kapak_resmi']['error'] == 0) {
            $kapak_resmi = $_FILES['urun_kapak_resmi'];
            $unique_id = uniqid();
            $kapak_resmi_webp = $unique_id . '_kapak.webp';
            $target_file = $target_dir . $kapak_resmi_webp;

            // Resmi webp formatına dönüştürme
            if ($image = @imagecreatefromstring(file_get_contents($kapak_resmi['tmp_name']))) {
                imagewebp($image, $target_file);
                imagedestroy($image);

                // Veritabanında kapak resmini güncelle
                $query_kapak = $conn->prepare("UPDATE urunler SET RESIM = :kapak_resmi WHERE URUN_KODU = :urun_kodu");
                $query_kapak->execute(['kapak_resmi' => $kapak_resmi_webp, 'urun_kodu' => $urun_kodu]);
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Kapak resmi yüklenemedi. Geçersiz dosya formatı.';
                echo json_encode($response);
                exit;
            }
        }

        // Diğer resimleri yükleme ve webp formatına dönüştürme
        if (isset($_FILES['urun_diger_resimler']) && !empty($_FILES['urun_diger_resimler']['name'][0])) {
            foreach ($_FILES['urun_diger_resimler']['tmp_name'] as $key => $tmp_name) {
                $unique_id = uniqid();
                $diger_resim_webp = $unique_id . '_diger_' . $key . '.webp';
                $target_file = $target_dir . $diger_resim_webp;

                // Resmi webp formatına dönüştürme
                if ($image = @imagecreatefromstring(file_get_contents($tmp_name))) {
                    imagewebp($image, $target_file);
                    imagedestroy($image);

                    // Veritabanına diğer resmi ekle
                    $query_diger = $conn->prepare("INSERT INTO varyant_resim (URUN_KODU, RESIM) VALUES (:urun_kodu, :diger_resim)");
                    $query_diger->execute(['urun_kodu' => $urun_kodu, 'diger_resim' => $diger_resim_webp]);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Geçersiz dosya formatı.';
                    echo json_encode($response);
                    exit;
                }
            }
        }

        $response['status'] = 'success';
        $response['message'] = 'Ürün güncellendi.';
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Hata: ' . $e->getMessage();
    }

    // JSON yanıtı konsola yazdır
    echo json_encode($response);
}
?>
