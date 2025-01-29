<?php
// Veritabanı bağlantısını sağlayan dosyayı include edin
include 'assets/fonk/mysql.php';

// Slider verilerini almak için sorguyu hazırlayın
$query = "SELECT * FROM slider";
$stmt = $conn->prepare($query);
$stmt->execute();
$sliderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="hero-section section">
    <div class="hero-slider hero-slider-one fix">
        <?php foreach ($sliderItems as $item): ?>
            <div class="hero-item" style="background-image: url(assets/images/upload/slider/<?php echo $item['RESIM']; ?>)">
                <div class="hero-content">
                    <h1><?php echo $item['BASLIK']; ?></h1>
                    <a href="<?php echo $item['BUTON_LINK']; ?>"><?php echo $item['BUTON_METNI']; ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
