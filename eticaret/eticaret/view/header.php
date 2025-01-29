<?php
session_start();
ob_start();
include 'assets/fonk/mysql.php';

try {
    // Kullanıcının oturum e-posta adresini al
    $session_email = isset($_SESSION['EPOSTA']) ? $_SESSION['EPOSTA'] : '';

    if ($session_email) {
        // Oturum açılmış kullanıcılar için wishlist ve sepet bilgilerini veritabanından al
        $stmt_wishlist = $conn->prepare("SELECT COUNT(*) AS wishlist_count FROM kaydedilenler WHERE EPOSTA = :session_email");
        $stmt_wishlist->bindParam(':session_email', $session_email);
        $stmt_wishlist->execute();
        $wishlist_row = $stmt_wishlist->fetch(PDO::FETCH_ASSOC);
        $wishlist_count = $wishlist_row['wishlist_count'];

        $stmt_cart = $conn->prepare("SELECT SUM(ADET) AS total_items, SUM(TOPLAM) AS total_price FROM sepet WHERE EPOSTA = :session_email");
        $stmt_cart->bindParam(':session_email', $session_email);
        $stmt_cart->execute();
        $cart_row = $stmt_cart->fetch(PDO::FETCH_ASSOC);

        if ($cart_row) {
            $total_items = $cart_row['total_items'];
            $total_price = $cart_row['total_price'];
        } else {
            $total_items = 0;
            $total_price = 0;
        }
    } else {
        // Oturum açılmamış kullanıcılar için varsayılan değerler
        $wishlist_count = 0;
        
        // Sepet verilerini $_SESSION['sepet'] üzerinden al
        $total_items = 0;
        $total_price = 0;
        
        if (isset($_SESSION['sepet']) && is_array($_SESSION['sepet'])) {
            foreach ($_SESSION['sepet'] as $item) {
                $total_items += $item['ADET'];
                $total_price += $item['TOPLAM'];
            }
        }
    }

    // Firma bilgileri tablosundaki tüm kayıtları seçme sorgusu
    $stmt = $conn->prepare("SELECT * FROM firma_bilgileri");
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $Slogan = $row["SLOGAN"];
        $Telefon1 = $row["FIRMA_TELEFON1"];
        $Telefon2 = $row["FIRMA_TELEFON2"];
        $Adres = $row["ADRES"];
        $Eposta1 = $row["FIRMA_EPOSTA1"];
        $Eposta2 = $row["FIRMA_EPOSTA2"];
        $Facebook = $row["FACEBOOK"];
        $Instagram = $row["INSTAGRAM"];
        $X = $row["X"];
    }
} catch(PDOException $e) {
    echo "Sorgu hatası: " . $e->getMessage();
    exit; // Hata durumunda kodun çalışmasını sonlandır
}
ob_clean();
?>

<!-- Kopfbereich Start -->
<div class="header-section section">
    <!-- Kopfoberteil Start -->
    <div class="header-top header-top-one bg-theme-two">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col mt-10 mb-10 d-none d-md-flex">
                    <!-- Kopfoberteil Links Start -->
                    <div class="header-top-left">
                        <?php  
                        echo (isset($_SESSION['ADSOYAD']) && isset($_SESSION['EPOSTA'])) ? '<p>Hoşgeldin ' . $_SESSION['ADSOYAD'] . '</p>' : '<p>' . $Slogan . '</p>';
                        ?>
                        <p>Müşteri Hizmetleri: <a href="tel:<?= $Telefon1 ?>"><?= $Telefon1 ?></a></p>
                    </div>
                </div>
                <div class="col mt-10 mb-10">
                                        <!-- Sepet Bilgileri Start -->
                    <div class="header-top-right">
                        <?php  
                        if (isset($_SESSION['ADSOYAD']) && isset($_SESSION['EPOSTA'])) {
                            echo '<p><a href="my-account.php">Hesabım</a></p>';
                            echo '<p><a href="mesajlar.php">Mesajlar</a></p>'; // Mesajlar linki ekledik
                        } else {
                            echo '<p><a href="login-register.php">Kayıt Ol</a><a href="login-register.php">Giriş Yap</a></p>';
                        }
                        ?>
                    </div><!-- Sepet Bilgileri Ende -->

                </div>
            </div>
        </div>
    </div><!-- Kopfoberteil Ende -->

    <!-- Kopfunterteil Start -->
    <div class="header-bottom header-bottom-one header-sticky">
        <div class="container-fluid">
            <div class="row menu-center align-items-center justify-content-between">
                <div class="col mt-15 mb-15">
                    <div class="header-logo">
                        <a href="index.php">
                            <img src="assets/images/logo.png?t=<?php echo time(); ?>" height="95" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="col order-2 order-lg-3">
                    <div class="header-shop-links">
                        <!-- Arama kutusu -->
                        <div class="header-search">
                            <button class="search-toggle"><img src="assets/images/icons/search.png" alt=""><img class="toggle-close" src="assets/images/icons/close.png" alt="Suche ein-/ausschalten"></button>
                            <div class="header-search-wrap">
                                <form action="#">
                                    <input type="text" id="search-input" placeholder="Ne aramıştınız?">
                                    <button><img src="assets/images/icons/search.png" alt="Suche"></button>
                                </form>
                                <!-- Arama sonuçları için alan -->
                                <div class="search-results"></div>
                            </div>
                        </div>
                        <div class="header-wishlist">
                            <a href="wishlist.php"><img src="assets/images/icons/wishlist.png" alt="Wunschliste"> <span><?php echo $wishlist_count; ?></span></a>
                        </div>
                        <div class="header-mini-cart">
                            <a href="cart.php"><img src="assets/images/icons/cart.png" alt="Warenkorb"> 
                                <span>
                                    <?php 
                                    if ($total_items > 0) {
                                        echo $total_items . ' (' . number_format($total_price, 2) . ' ₺)';
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col order-3 order-lg-2">
                    <div class="main-menu">
                        <nav>
                            <ul>
                                <li class="active"><a href="index.php">Anasayfa</a></li>
                                <?php
                                try {
                                    // Ana kategorileri almak için sorgu
                                    $stmt_categories = $conn->prepare("SELECT * FROM ana_kategori");
                                    $stmt_categories->execute();

                                    while ($category_row = $stmt_categories->fetch(PDO::FETCH_ASSOC)) {
                                        $ana_kategori_adi = $category_row['ANA_KATEGORI_ADI'];
                                        $kategoriler[] = $ana_kategori_adi = $category_row['ANA_KATEGORI_ADI'];

                                        // Alt kategorileri almak için sorgu
                                        $stmt_subcategories = $conn->prepare("SELECT * FROM alt_kategori WHERE ANA_KATEGORI_ADI = :ana_kategori_adi AND GIZLE=0");
                                        $stmt_subcategories->bindParam(':ana_kategori_adi', $ana_kategori_adi);
                                        $stmt_subcategories->execute();

                                        if ($stmt_subcategories->rowCount() > 0) {
                                            echo '<li><a href="category.php?category=' . urlencode($ana_kategori_adi) . '">' . $ana_kategori_adi . '</a>';
                                            echo '<ul class="sub-menu">';
                                            while ($subcategory_row = $stmt_subcategories->fetch(PDO::FETCH_ASSOC)) {
                                                $alt_kategori_adi = $subcategory_row['ALT_KATEGORI_ADI'];
                                                echo '<li><a href="category.php?category=' . urlencode($ana_kategori_adi) . '&subcategory=' . urlencode($alt_kategori_adi) . '">' . $alt_kategori_adi . '</a></li>';
                                            }
                                            echo '</ul></li>';
                                        } else {
                                            echo '<li><a href="category.php?category=' . urlencode($ana_kategori_adi) . '">' . $ana_kategori_adi . '</a></li>';
                                        }
                                    }
                                } catch(PDOException $e) {
                                    echo "Sorgu hatası: " . $e->getMessage();
                                    exit;
                                }
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="mobile-menu order-4 d-block d-lg-none col"></div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery kütüphanesi -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        // Arama kutusuna her tuşa basıldığında
        $('#search-input').on('keyup', function(){
            var searchText = $(this).val(); // Arama kutusundaki metni al

            // Ajax isteği
            $.ajax({
                type: 'POST',
                url: 'assets/fonk/search.php', // Arama sonuçlarını getirecek PHP dosyasının yolu
                data: {search_text: searchText},
                success: function(response){
                    $('.search-results').html(response); // Arama sonuçlarını göster
                }
            });
        });
    });
</script>
