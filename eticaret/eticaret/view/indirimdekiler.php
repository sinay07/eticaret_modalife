<?php
// Veritabanı bağlantısını içe aktar
include 'assets/fonk/mysql.php';

// SQL sorgusunu hazırla
$sql = "SELECT i.URUN_ADI, i.URUN_RESMI, i.URUN_FIYATI, i.INDIRIMLI_FIYAT, i.BITIS_TARIH, u.URUN_LINK, u.PUAN
FROM indirimli_urunler i
INNER JOIN urunler u ON i.URUN_KODU = u.URUN_KODU
WHERE i.BITIS_TARIH >= CURDATE()";

// Sorguyu çalıştır
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Ürün Bölümü Başlangıcı -->
<div class="product-section section section-padding pt-0">
    <div class="container">
        <div class="row mbn-40">

            <div class="col-lg-4 col-md-6 col-12 mb-40">
                <div class="row">
                    <div class="section-title text-start col mb-30">
                        <h1>En İyi Fırsat</h1>
                        <p>Sizin için özel teklifler</p>
                    </div>
                </div>
                <div class="best-deal-slider w-100">
                    <?php foreach ($products as $product): ?>
                        <div class="slide-item">
                            <div class="best-deal-product">
                                <div class="image"><img src="assets/images/upload/product/<?php echo $product['URUN_RESMI']; ?>" alt="Resim"></div>
                                <div class="content-top">
                                    <div class="content-top-left">
                                        <h4 class="title"><a href="#"><?php echo $product['URUN_ADI']; ?></a></h4>
                                        <div class="ratting">
                                            <!-- PUAN değerine göre yıldızları oluştur -->
                                            <?php for ($i = 0; $i < $product['PUAN']; $i++): ?>
                                                <i class="fa fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="content-top-right">
                                        <span class="price">$<?php echo $product['INDIRIMLI_FIYAT']; ?> <span class="old">$<?php echo $product['URUN_FIYATI']; ?></span></span>
                                    </div>
                                </div>
                                <div class="content-bottom">
                                    <div class="countdown" data-countdown="<?php echo $product['BITIS_TARIH']; ?>"></div>
                                    <a href="<?php echo $product['URUN_LINK']; ?>" data-hover="ŞİMDİ SATIN AL">ŞİMDİ SATIN AL</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-8 col-md-6 col-12 ps-3 ps-lg-4 ps-xl-5 mb-40">
                <div class="row">
                    <div class="section-title text-start col mb-30">
                        <h1>Teklifteki Ürünler</h1>
                        <p>Tüm sunulan ürünleri burada bulabilirsiniz</p>
                    </div>
                </div>
                <div class="small-product-slider row row-7 mbn-40">
                    <?php foreach ($products as $product): ?>
                        <div class="col mb-40">
                            <div class="on-sale-product">
                                <a href="<?php echo $product['URUN_LINK']; ?>" class="image"><img src="assets/images/product/<?php echo $product['URUN_RESMI']; ?>" alt="Resim"></a>
                                <div class="content text-center">
                                    <h4 class="title"><a href="<?php echo $product['URUN_LINK']; ?>"><?php echo $product['URUN_ADI']; ?></a></h4>
                                    <span class="price">$<?php echo $product['INDIRIMLI_FIYAT']; ?> <span class="old">$<?php echo $product['URUN_FIYATI']; ?></span></span>
                                    <div class="ratting">
                                        <!-- PUAN değerine göre yıldızları oluştur -->
                                        <?php for ($i = 0; $i < $product['PUAN']; $i++): ?>
                                            <i class="fa fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div><!-- Ürün Bölümü Sonu -->
