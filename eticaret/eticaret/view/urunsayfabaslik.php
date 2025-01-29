<?php
include 'assets/fonk/mysql.php';
// ID'yi güvenli bir şekilde al
$id = isset($_GET['id']) ? $_GET['id'] : null;
// ID parametresini kontrol et
if (!is_numeric($id)) {
    header("Location: 404.php");
    exit; // İşlemi sonlandır
}
try {
    // SQL sorgusunu hazırla
    $stmt = $conn->prepare("SELECT * FROM urunler WHERE KAYIT_ID = :id");
    // Sorguyu çalıştır
    $stmt->execute(array(':id' => $id));
    // Sonucu al
    $urun = $stmt->fetch(PDO::FETCH_ASSOC);
    // Eğer ürün bulunamadıysa
    if (!$urun) {
        header("Location: 404.php");
        exit; // İşlemi sonlandır
    }
    $SayfaBaslik = $urun['URUN_ADI'];
} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>


<div class="page-banner-section section" style="background-image: url(assets/images/hero/hero-1.jpg?v=<?php echo time(); ?>)">
    <div class="container">
        <div class="row">
            <div class="page-banner-content col">

                <h1><?= $SayfaBaslik ?></h1>
                <ul class="page-breadcrumb">
                    <li><a href="index.php">Anasayfa</a></li>
                    <li><a href="#"><?= $SayfaBaslik ?></a></li>
                </ul>

            </div>
        </div>
    </div>
</div>