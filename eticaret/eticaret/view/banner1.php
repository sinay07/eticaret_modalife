<?php
// Veritabanı bağlantısını sağlayan dosyayı include edin
include 'assets/fonk/mysql.php';

// Duyuru banner verilerini almak için sorguyu hazırlayın
$query = "SELECT * FROM duyuru_banner";
$stmt = $conn->prepare($query);
$stmt->execute();
$bannerItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Banner Bölümü Başlangıcı -->
<div class="banner-section section mt-40">
    <div class="container-fluid">
        <div class="row row-10 mbn-20">

            <?php foreach ($bannerItems as $item): ?>
                <div class="col-lg-4 col-md-6 col-12 mb-20">
                    <div class="banner banner-1 content-left content-middle">

                        <a href="<?php echo $item['LINK']; ?>" class="image"><img src="assets/images/upload/banner/<?php echo $item['RESIM']; ?>" alt="<?php echo $item['BASLIK']; ?>"></a>

                        <div class="content">
                            <h1><?php echo $item['BASLIK']; ?></h1>
                            <a href="<?php echo $item['LINK']; ?>" data-hover="ŞİMDİ AL">ŞİMDİ AL</a>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div><!-- Banner Bölümü Sonu -->
