<?php  
include 'assets/fonk/mysql.php';

// Kullanıcının oturum e-posta adresini al
$session_email = isset($_SESSION['EPOSTA']) ? $_SESSION['EPOSTA'] : '';

// Veritabanından kullanıcının oturum e-posta adresine göre verileri çek
$sql = "SELECT * FROM kaydedilenler WHERE EPOSTA = :session_email";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':session_email', $session_email);
$stmt->execute();

// Sorgu sonucundaki satır sayısını kontrol et
if ($stmt->rowCount() > 0) {
    ?>
    <div class="page-section section section-padding">
        <div class="container">
            <form action="#">               
                <div class="row">
                    <div class="col-12">
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
                                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $urun_kodu = $row["URUN_KODU"];
                                        $urun_resim = $row["URUN_RESIM"];
                                        $urun_adi = $row["URUN_ADI"];
                                        $urun_link = $row["URUN_LINK"];
                                        $fiyat = $row["FIYAT"];
                                        ?>
                                        <tr>
                                            <input type="hidden" class="urun_kodu" value="<?php echo $urun_kodu; ?>">
                                            <td class="pro-thumbnail"><a href="<?php echo $urun_link; ?>"><img src="assets/images/upload/product/<?php echo $urun_resim; ?>" alt="" /></a></td>
                                            <td class="pro-title"><a href="<?php echo $urun_link; ?>"><?php echo $urun_adi; ?></a></td>
                                            <td class="pro-price"><span class="amount"><?php echo $fiyat; ?> ₺</span></td>
                                            <td class="pro-quantity"><div class="pro-qty"><input type="text" class="quantity-input" value="1" min="1"></div></td>
                                            <td class="pro-add-cart"><a href="#" class="add-to-cart" data-urun-kodu="<?php echo $urun_kodu; ?>">Sepete Ekle</a></td>
                                            <td class="pro-remove"><a href="#">×</a></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
} else {
    echo "<div class='text-center'><h3>Favori Ürün Bulunamadı</h3><p>Favori listeniz boş. Favori listenize ürün ekleyin ve burada görüntüleyin.</p></div>";
}

// PDO bağlantısını kapat
$stmt = null;
$conn = null;
?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
    // Ürünü sepete ekleme işlemi
        $('.add-to-cart').click(function(event){
        event.preventDefault(); // Sayfanın yeniden yüklenmesini engellemek için varsayılan tıklama davranışını engelle
        var $this = $(this); // 'this' bağlamını sakla
        var urun_kodu = $(this).data('urun-kodu'); // Tıklanan ürünün URUN_KODU'sunu al
        var adet = $(this).closest('tr').find('.quantity-input').val(); // Tıklanan ürünün adetini al
        
        // AJAX isteği gönder
        $.post('assets/fonk/wisherToCart.php', { urun_kodu: urun_kodu, adet: adet})
        .done(function(response) {
            // Başarılı bir şekilde cevap alındığında, ilgili işlemleri gerçekleştir
            $this.closest('tr').remove();
        })
        .fail(function(xhr, status, error) {
            // Hata durumunda konsola yazdır
            console.error('Hata oluştu: ' + error);
        });
    });

    // Ürünü kaldırma işlemi
        $('.pro-remove a').click(function(event){
        event.preventDefault(); // Sayfanın yeniden yüklenmesini engellemek için varsayılan tıklama davranışını engelle
        var $this = $(this); // 'this' bağlamını sakla
        var urun_kodu = $this.closest('tr').find('.urun_kodu').val(); // Tıklanan ürünün URUN_KODU'sunu al
        
        // AJAX isteği gönder
        $.post('assets/fonk/removeFromWisher.php', { urun_kodu: urun_kodu })
        .done(function(response) {
            // Başarılı bir şekilde cevap alındığında, ilgili satırı tablodan kaldır
            $this.closest('tr').remove();
        })
        .fail(function(xhr, status, error) {
            // Hata durumunda konsola yazdır
            console.error('Hata oluştu: ' + error);
        });
    });

    });
</script>
