<?php
// Veritabanı bağlantısını sağlayan dosyayı include edin
include 'assets/fonk/mysql.php';

// Son eklenen 8 ürün ve bu ürünlere ait varyantları almak için sorguyu hazırlayın
$query = "SELECT u.*, 
GROUP_CONCAT(DISTINCT vr.RENK_KODU SEPARATOR ', ') AS varyant_renk,
GROUP_CONCAT(DISTINCT vb.BEDEN SEPARATOR ', ') AS varyant_beden
FROM urunler u
LEFT JOIN varyant_renk vr ON u.URUN_KODU = vr.URUN_KODU
LEFT JOIN varyant_beden vb ON u.URUN_KODU = vb.URUN_KODU
GROUP BY u.URUN_KODU
LIMIT 8";

$stmt = $conn->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Produktabschnitt Start -->
<div class="product-section section section-padding">
    <div class="container">
        <div class="row">
            <div class="section-title text-center col mb-30">
                <h1>Popüler Ürünler</h1>
                <p>Tüm popüler ürünleri burada bulabilirsiniz</p>
            </div>
        </div>

        <div class="row mbn-40">
            <?php foreach ($products as $product): ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-40">
                    <div class="product-item">
                        <div class="product-inner">
                            <div class="image">
                                <img src="assets/images/upload/product/<?php echo $product['RESIM']; ?>" alt="<?php echo $product['URUN_ADI']; ?>">
                                <div class="image-overlay">
                                    <div class="action-buttons">
                                        <input type="hidden" name="urun_kodu" value="<?php echo $product['URUN_KODU']; ?>">
                                        <button class="addToWishlist">Listeye Ekle</button>
                                    </div>
                                </div>
                            </div>
                            <div class="content">
                                <div class="content-left">
                                    <h4 class="title text-wrap"><a href="product.php?id=<?php echo $product['KAYIT_ID']; ?>"><?php echo $product['URUN_ADI']; ?></a></h4>
                                    <div class="ratting">
                                        <?php
                                        $rating = $product['PUAN']; // Örnek olarak puan verisi
                                        $whole = floor($rating); // Tam kısmı al
                                        $fraction = $rating - $whole; // Ondalık kısmı al

                                        // Tam kısmı yıldıza dönüştür
                                        for ($i = 0; $i < $whole; $i++) {
                                            echo '<i class="fa fa-star"></i>';
                                        }

                                        // Ondalık kısmı değerlendir
                                        if ($fraction > 0) {
                                            if ($fraction >= 0.75) {
                                                echo '<i class="fa fa-star"></i>'; // Bir yıldız ekleyin
                                            } elseif ($fraction >= 0.25) {
                                                echo '<i class="fa fa-star-half-o"></i>'; // Yarım yıldız ekleyin
                                            } else {
                                                echo '<i class="fa fa-star-o"></i>'; // Boş yıldız ekle
                                            }
                                        }

                                        // Boş yıldızları tamamla
                                        $remainingStars = 5 - $whole - ceil($fraction);
                                        for ($i = 0; $i < $remainingStars; $i++) {
                                            echo '<i class="fa fa-star-o"></i>';
                                        }
                                        ?>
                                    </div>
                                    <?php if (!empty($product['varyant_beden'])): ?>
                                        <h5 class="size">Beden: <span><?php echo $product['varyant_beden']; ?></span></h5>
                                    <?php else: ?>
                                        <h5 class="size">Beden: <span>Mevcut Değil</span></h5>
                                    <?php endif; ?>
                                    <br>
                                </div>
                                <div class="content-right">
                                    <span class="price"><?php echo $product['FIYAT']; ?> ₺</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div><!-- Produktabschnitt Ende -->

<!-- Modal -->
<div id="warningModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 class="modal-title">Uyarı</h2>
    <p class="modal-body"><!--dinamik metin--></p>
    <a href="login-register.php"><input type="button" class="modal-button" value="Kaydol / Giriş Yap"></a>
    <button class="modal-button">Kapat</button>
</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        function showModal(message) {
            var modal = document.getElementById("warningModal");
            var modalBody = modal.querySelector(".modal-body");
            modalBody.textContent = message;
            modal.style.display = "block";
        }

        var closeModalElements = document.querySelectorAll(".close, .modal-button");
        closeModalElements.forEach(function(element) {
            element.onclick = function() {
                var modal = document.getElementById("warningModal");
                modal.style.display = "none";
            };
        });

        window.onclick = function(event) {
            var modal = document.getElementById("warningModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        document.querySelectorAll('.addToWishlist').forEach(function(button) {
            button.addEventListener('click', function() {
                var urunKodu = button.closest('.product-item').querySelector('input[name="urun_kodu"]').value;
                fetch('assets/fonk/addToWishlist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `urun_kodu=${urunKodu}`
                })
                .then(response => response.text())
                .then(response => {
                    if (response.trim() === "success") {
                        location.reload();
                    } else if (response.trim() === "error: Ürün bulunamadı.") {
                        showModal('Ürün bulunamadı.');
                    } else if (response.trim() === "error: Oturum açınız.") {
                        showModal('Lütfen kullanıcı adınızı girin!');
                    }
                })
                .catch(error => {
                    console.error(error);
                    showModal('Ürün dilek listesine eklenirken bir hata oluştu!');
                });
            });
        });
    });
</script>

