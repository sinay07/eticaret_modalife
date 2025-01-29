<?php
// Eğer sipariş ID'si varsa, göster ve sonra oturumdan sil
if(isset($_SESSION['siparisID'])) {
    $siparisID = $_SESSION['siparisID'];
    unset($_SESSION['siparisID']);
    unset($_SESSION['odemeID']);
} else {
    header("Location: index.php");
}
?>

<!-- Sayfa Bölümü Başlangıcı -->
<div class="page-section section section-padding">
    <div class="container">
        <div class="row">

         <div class="col-lg-6 col-md-8 col-12 mx-auto">
            <div class="error-404">
                <h2>Ödeme Başarılı!</h2>
                <h2>TEŞEKKÜRLER! ÖDEMENİZ BAŞARIYLA TAMAMLANDI</h2>
                <p><b><?php echo $siparisID; ?></b> numaralı siparişiniz tamamlanmıştır. Alışverişiniz için teşekkür ederiz.</p>
                <a href="index.php" class="back-btn">Başka Ürünler Alışverişi Yap</a>
            </div>
        </div>

    </div>
</div>
</div><!-- Sayfa Bölümü Sonu -->
