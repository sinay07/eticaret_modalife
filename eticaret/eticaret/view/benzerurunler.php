<?php
// Veritabanı bağlantısını içe aktar
include 'assets/fonk/mysql.php';

// Mevcut ürünün KAYIT_ID'sini URL'den al
$current_product_id = $_GET['id'];

// Mevcut ürünü veritabanından al
$sql_current_product = "SELECT * FROM urunler WHERE KAYIT_ID = :id";
$stmt_current_product = $conn->prepare($sql_current_product);
$stmt_current_product->bindParam(':id', $current_product_id);
$stmt_current_product->execute();
$current_product = $stmt_current_product->fetch(PDO::FETCH_ASSOC);

// Eğer ürün bulunamadıysa hata mesajı döndür
if (!$current_product) {
    echo "error: Ürün bulunamadı.";
    exit;
}

// Mevcut ürünün ALT_KATEGORI ID'sini al
$current_product_alt_kategori = $current_product['ALT_KATEGORI'];

// Benzer ürünleri almak için SQL sorgusu
$sql_related_products = "
SELECT u.*, GROUP_CONCAT(v.beden SEPARATOR ',') AS bedenler
FROM urunler u
LEFT JOIN varyant_beden v ON u.URUN_KODU = v.URUN_KODU
WHERE u.ALT_KATEGORI = :alt_kategori AND u.KAYIT_ID != :current_id
GROUP BY u.KAYIT_ID
LIMIT 5";
$stmt_related_products = $conn->prepare($sql_related_products);
$stmt_related_products->bindParam(':alt_kategori', $current_product_alt_kategori);
$stmt_related_products->bindParam(':current_id', $current_product_id);
$stmt_related_products->execute();
$related_products = $stmt_related_products->fetchAll(PDO::FETCH_ASSOC);
?>


<!-- İlgili Ürün Bölümü Başlangıç -->
<div class="section section-padding pt-0">
    <div class="container">

        <div class="section-title text-start mb-30">
            <h1>Benzer Ürünler</h1>
        </div>

        <div class="related-product-slider related-product-slider-1 slick-space p-0">
            <?php foreach ($related_products as $product): ?>
                <div class="slick-slide">

                    <div class="product-item">
                        <div class="product-inner">

                            <div class="image">
                                <img src="assets/images/upload/product/<?php echo $product['RESIM']; ?>" alt="">

                                <div class="image-overlay">
                                    <div class="action-buttons">
                                        <input type="hidden" name="urun_kodu" value="<?php echo $product['URUN_KODU']; ?>">
                                        <button class="benzeraddToWishlist">Dilek Listesine Ekle</button>
                                    </div>
                                </div>

                            </div>

                            <div class="content">

                                <div class="content-left">

                                    <h4 class="title"><a href="product.php?id=<?php echo $product['KAYIT_ID']; ?>"><?php echo $product['URUN_ADI']; ?></a></h4>

                                    <div class="ratting">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i class="fa fa-star<?php echo $i < $product['PUAN'] ? '' : '-o'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>

                                    <?php if (!empty($product['varyant_beden'])): ?>
                                        <h5 class="size">Beden: <span><?php echo $product['varyant_beden']; ?></span></h5>
                                    <?php else: ?>
                                        <h5 class="size">Beden: <span>Mevcut Değil</span></h5>
                                    <?php endif; ?>
                                    <br>
                                </div>

                                <div class="content-right">
                                    <span class="price"><?php echo $product['FIYAT']; ?>.-TL</span>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div><!-- İlgili Ürün Bölümü Bitişi -->



<!-- Modal -->
<div id="warningModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 class="modal-title">Uyarı</h2>
    <p class="modal-body"><!--dinamik metin--></p>
    <a href="login-register.php"><input type="button" class="modal-button" value="Giriş Yap / Kayıt Ol"></a>
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

        document.querySelectorAll('.benzeraddToWishlist').forEach(function(button) {
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
                        showModal('Oturum açınız.');
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

