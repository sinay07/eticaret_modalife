<?php  
include 'assets/fonk/mysql.php';

// Sepet verilerini depolamak için bir değişken
$sepet = [];

// Kullanıcının oturum e-posta adresini al
$session_email = isset($_SESSION['EPOSTA']) ? $_SESSION['EPOSTA'] : '';

// Eğer oturum açılmışsa veritabanından sepet verilerini al
if ($session_email) {
    $sql = "SELECT * FROM sepet WHERE EPOSTA = :session_email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':session_email', $session_email);
    $stmt->execute();

    // Sorgu sonucunda satır varsa, sepet verilerini al
    if ($stmt->rowCount() > 0) {
        $sepet = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Eğer oturum açılmamışsa veya veritabanında sepet verisi yoksa session'daki sepet verilerini kontrol et
if (empty($sepet) && isset($_SESSION['sepet'])) {
    $sepet = $_SESSION['sepet'];
}

// Toplam fiyatı hesaplamak için değişken
$total_price = 0;

?>
<div class="page-section section section-padding">
    <div class="container">
        <form action="#">               
            <div class="row mbn-40">
                <div class="col-12 mb-40">
                    <div class="cart-table table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="pro-thumbnail">Resim</th>
                                    <th class="pro-title">Ürün</th>
                                    <th class="pro-price">Fiyat</th>
                                    <th class="pro-quantity">Miktar</th>
                                    <th class="pro-subtotal">Toplam</th>
                                    <th class="pro-remove">Kaldır</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($sepet)) {
                                    foreach ($sepet as $item) {
                                        $urun_link = htmlspecialchars($item["URUN_LINK"]);
                                        $resim = htmlspecialchars($item["RESIM"]);
                                        $urun_adi = htmlspecialchars($item["URUN_ADI"]);
                                        $fiyat = number_format($item["FIYAT"], 2, ',', '.');
                                        $adet = htmlspecialchars($item["ADET"]);
                                        $toplam = number_format($item["TOPLAM"], 2, ',', '.');
                                        $urun_kodu = htmlspecialchars($item["URUN_KODU"]);

                                        // Toplam fiyatı güncelle
                                        $total_price += $item["TOPLAM"];
                                        ?>
                                        <tr>
                                            <input type="hidden" class="urun_kodu" value="<?php echo $urun_kodu; ?>">
                                            <td class="pro-thumbnail"><a href="<?php echo $urun_link; ?>"><img src="assets/images/upload/product/<?php echo $resim; ?>" alt="" /></a></td>
                                            <td class="pro-title"><a href="<?php echo $urun_link; ?>"><?php echo $urun_adi; ?></a></td>
                                            <td class="pro-price"><span class="amount"><?php echo $fiyat; ?> ₺</span></td>
                                            <td class="pro-quantity"><div class="pro-qty"><input type="text" value="<?php echo $adet; ?>"></div></td>
                                            <td class="pro-subtotal"><?php echo $toplam; ?> ₺</td>
                                            <td class="pro-remove"><a href="#">×</a></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Sepetinizde hiç ürün yok...</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-8 col-md-7 col-12 mb-40">
                    <!--
                    <div class="cart-coupon">
                        <h4>Gutschein</h4>
                        <p>Geben Sie Ihren Gutscheincode ein, wenn Sie einen haben.</p>
                        <div class="cuppon-form">
                            <input type="text" placeholder="Gutscheincode" />
                            <input type="submit" value="Gutschein anwenden" />
                        </div>
                    </div>
                    -->
                </div>
                <div class="col-lg-4 col-md-5 col-12 mb-40">
                    <div class="cart-total fix">
                        <h3>Sepet Toplamı</h3>
                        <table>
                            <tbody>
                                <tr class="cart-subtotal">
                                    <th>Ara Toplam</th>
                                    <td><span class="amount"><?php echo number_format($total_price, 2, ',', '.'); ?> ₺</span></td>
                                </tr>
                                <tr class="order-total">
                                    <th>Toplam</th>
                                    <td>
                                        <strong><span class="amount"><?php echo number_format($total_price, 2, ',', '.'); ?> ₺</span></strong>
                                    </td>
                                </tr>                                           
                            </tbody>
                        </table>
                        <div class="proceed-to-checkout section mt-30">
                            <a id="checkout-button">Ödeme Sayfasına Git</a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<?php
// PDO bağlantısını kapat
$stmt = null;
$conn = null;
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.inc.qtybtn').click(function() {
            var adetInput = $(this).closest('tr').find('.pro-quantity input');
            var adet = parseInt(adetInput.val());
            adetInput.val(adet);
            updatePrice(adetInput);
        });

        $('.dec.qtybtn').click(function() {
            var adetInput = $(this).closest('tr').find('.pro-quantity input');
            var adet = parseInt(adetInput.val());
            if (adet > 1) { // Adeti 1'in altına çekme
                adetInput.val(adet - 1);
            }
            updatePrice(adetInput);
        });

        // Fiyatı güncelleme fonksiyonu
        function updatePrice(adetInput) {
            var adet = parseInt(adetInput.val());
            var urun_kodu = adetInput.closest('tr').find('.urun_kodu').val();
            
            // Güncellenmiş adet değerlerini sunucuya gönder
            $.post('assets/fonk/updatePrice.php', { urun_kodu: urun_kodu, adet: adet })
            .done(function(response) {
                // Sunucudan gelen cevabı JSON olarak ayrıştır
                var data = JSON.parse(response);
                // Güncel fiyatı ve toplamı ekrana yazdır
                adetInput.closest('tr').find('.pro-subtotal').text(data.toplamFiyat);
                // Cart-total-body içindeki toplam fiyat alanlarını güncelle
                $('.cart-subtotal .amount').text(data.totalToplam);
                $('.order-total .amount').text(data.totalToplam);
            })
            .fail(function(xhr, status, error) {
                // Hata durumunda konsola yazdır
                console.error('Hata oluştu: ' + error);
            });
        }

        // Ürünü kaldırma işlemi
        $('.pro-remove a').click(function(event) {
            event.preventDefault(); // Sayfanın yeniden yüklenmesini engellemek için varsayılan tıklama davranışını engelle
            var $this = $(this); // 'this' bağlamını sakla
            var urun_kodu = $this.closest('tr').find('.urun_kodu').val(); // Tıklanan ürünün URUN_KODU'sunu al
            
            // AJAX isteği gönder
            $.post('assets/fonk/removeFromCart.php', { urun_kodu: urun_kodu })
            .done(function(response) {
                // Başarılı bir şekilde cevap alındığında, ilgili satırı tablodan kaldır
                $this.closest('tr').remove();
                // Ayrıca, toplam fiyatları güncelle
                $('.cart-subtotal .amount, .order-total .amount').text(response);
            })
            .fail(function(xhr, status, error) {
                // Hata durumunda konsola yazdır
                console.error('Hata oluştu: ' + error);
            });
        });

        // Checkout butonuna tıklama işlemi
        $('#checkout-button').click(function(e) {
            e.preventDefault(); // Butonun varsayılan davranışını engelle

            $.ajax({
                type: 'POST',
                url: 'assets/fonk/check_session.php',
                data: { check: true },
                success: function(response) {
                    window.location.href = response; // Gelen yanıt URL'ye yönlendir
                },
                error: function(xhr, status, error) {
                    console.error('Hata oluştu: ' + error);
                }
            });
        });
    });
</script>
