<?php
include 'assets/fonk/mysql.php';

// Her sayfada gösterilecek ürün sayısı
$items_per_page = 9;

// Hangi sayfa olduğunu belirle
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Sıralama seçeneğini al
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : "";

// Sıralama seçeneği yoksa, varsayılan olarak ilk seçeneği seç
if (empty($sort_option)) {
    $sort_option = "Ad Artan";
}

try {
    // SQL sorgusunu oluştur
    $sql_total = "SELECT COUNT(*) as total FROM urunler WHERE ANA_KATEGORI = :category";
    if (!empty($subcategory)) {
        $sql_total .= " AND ALT_KATEGORI = :subcategory";
    }

    // Toplam ürün sayısını al
    $stmt = $conn->prepare($sql_total);
    $stmt->bindParam(':category', $category);
    if (!empty($subcategory)) {
        $stmt->bindParam(':subcategory', $subcategory);
    }
    $stmt->execute();
    $total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Toplam sayfa sayısını hesapla
    $total_pages = ceil($total_products / $items_per_page);

    // Hangi ürünlerin çekileceğini belirle
    $start = ($current_page - 1) * $items_per_page;

    // Sıralama ifadesini belirle
    $order_by = "";
    switch ($sort_option) {
        case "Ad Artan":
        $order_by = "URUN_ADI ASC";
        break;
        case "Ad Azalan":
        $order_by = "URUN_ADI DESC";
        break;
        case "Fiyat Artan":
        $order_by = "FIYAT ASC";
        break;
        case "Fiyat Azalan":
        $order_by = "FIYAT DESC";
        break;
        default:
        $order_by = "URUN_ADI ASC";
    }

    // Ürünleri veritabanından çek (sadece belirli bir aralıkta) ve sırala
    $sql_products = "SELECT u.*, 
    GROUP_CONCAT(DISTINCT vr.RENK_KODU SEPARATOR ', ') AS varyant_renk,
    GROUP_CONCAT(DISTINCT vb.BEDEN SEPARATOR ', ') AS varyant_beden
    FROM urunler u
    LEFT JOIN varyant_renk vr ON u.URUN_KODU = vr.URUN_KODU
    LEFT JOIN varyant_beden vb ON u.URUN_KODU = vb.URUN_KODU
    WHERE u.ANA_KATEGORI = :category";
    if (!empty($subcategory)) {
        $sql_products .= " AND u.ALT_KATEGORI = :subcategory";
    }
    $sql_products .= " GROUP BY u.URUN_KODU ORDER BY $order_by LIMIT :start, :items_per_page";

    $stmt = $conn->prepare($sql_products);
    $stmt->bindParam(':category', $category);
    if (!empty($subcategory)) {
        $stmt->bindParam(':subcategory', $subcategory);
    }
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Alt kategorileri ve ürün sayılarını al
    $stmt = $conn->prepare("
        SELECT urunler.ALT_KATEGORI, COUNT(*) AS product_count 
        FROM urunler 
        JOIN alt_kategori ON urunler.ALT_KATEGORI = alt_kategori.ALT_KATEGORI_ADI
        WHERE urunler.ANA_KATEGORI = :category AND alt_kategori.GIZLE = 0
        GROUP BY urunler.ALT_KATEGORI
        ");
    $stmt->bindParam(':category', $category);
    $stmt->execute();
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>


<!-- Seitenabschnitt Start -->
<div class="page-section section section-padding">
    <div class="container">
        <div class="row row-30 mbn-40">

            <div class="col-xl-9 col-lg-8 col-12 order-1 order-lg-2 mb-40">
                <div class="row">

                    <div class="col-12">
                        <div class="product-short">
                            <h4>Sıralama:</h4>
                            <form id="sortForm" method="GET" action="">
                                <select class="nice-select" name="sort" onchange="document.getElementById('sortForm').submit()">
                                    <option <?php echo ($sort_option == "Ad Artan") ? 'selected' : ''; ?>>Ad Artan</option>
                                    <option <?php echo ($sort_option == "Ad Azalan") ? 'selected' : ''; ?>>Ad Azalan</option>
                                    <option <?php echo ($sort_option == "Fiyat Artan") ? 'selected' : ''; ?>>Fiyat Artan</option>
                                    <option <?php echo ($sort_option == "Fiyat Azalan") ? 'selected' : ''; ?>>Fiyat Azalan</option>
                                </select>
                                <input type="hidden" name="category" value="<?php echo urlencode($category); ?>">
                                <input type="hidden" name="subcategory" value="<?php echo urlencode($subcategory); ?>">
                            </form>
                        </div>
                    </div>


                    <!-- Ürünlerin listelendiği bölüm -->
                    <?php
                    if (!empty($products)) {
                        foreach ($products as $product) {
                            ?>
                            <div class="col-xl-4 col-md-6 col-12 mb-40">
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
                                                <h5 class="size">Größe: <span><?php echo $product['varyant_beden']; ?></span></h5>
                                            <?php endif; ?>
                                        </div>
                                        <div class="content-right">
                                            <span class="price"><?php echo $product['FIYAT']; ?> ₺</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12"><p>Ürün bulunamadı.</p></div>';
                }
                ?>

                <!-- Sayfalama için butonlar -->
                <div class="col-12">
                    <ul class="page-pagination">
                        <?php if ($current_page > 1): ?>
                            <li><a href="?category=<?php echo urlencode($category); ?>&subcategory=<?php echo urlencode($subcategory); ?>&page=<?php echo $current_page - 1; ?>"><i class="fa fa-angle-left"></i></a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li <?php echo ($current_page == $i) ? 'class="active"' : ''; ?>><a href="?category=<?php echo urlencode($category); ?>&subcategory=<?php echo urlencode($subcategory); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <li><a href="?category=<?php echo urlencode($category); ?>&subcategory=<?php echo urlencode($subcategory); ?>&page=<?php echo $current_page + 1; ?>"><i class="fa fa-angle-right"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </div>

<!-- Yan menü -->
<div class="col-xl-3 col-lg-4 col-12 order-2 order-lg-1 mb-40">
    <div class="sidebar">
        <h4 class="sidebar-title">Alt Kategoriler</h4>
        <ul class="sidebar-list">
            <?php foreach ($subcategories as $subcat): ?>
                <li><a href="?category=<?php echo urlencode($category); ?>&subcategory=<?php echo urlencode($subcat['ALT_KATEGORI']); ?>"><?php echo $subcat['ALT_KATEGORI']; ?> <span class="num"><?php echo $subcat['product_count']; ?></span></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

</div>
</div><!-- Seitenabschnitt Ende -->
</div>

<!-- Modal -->
<div id="warningModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 class="modal-title">Uyarı</h2>
    <p class="modal-body"><!--dinamik metin--></p>
    <a href="login-register.php"><input type="button" class="modal-button" value="Kayıt Ol / Giriş Yap"></a>
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
                        showModal('Lütfen giriş yapınız!');
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
