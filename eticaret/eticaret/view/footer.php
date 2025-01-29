<!-- Üst Footer Bölümü Başlangıcı -->
<div class="footer-top-section section bg-theme-two-light section-padding">
    <div class="container">
        <div class="row mbn-40">

            <div class="footer-widget col-lg-3 col-md-6 col-12 mb-40">
                <h4 class="title">İletişim</h4>
                <p><?= $Adres ?></p>
                <p><a href="tel:<?= $Telefon1 ?>"><?= $Telefon1 ?></a><a href="tel:<?= $Telefon2 ?>"><?= $Telefon2 ?></a></p>
                <p><a href="mailto:<?= $Eposta1 ?>"><?= $Eposta1 ?></a><a href="mailto:<?= $Eposta2 ?>"><?= $Eposta2 ?></a></p>
            </div>

            <div class="footer-widget col-lg-3 col-md-6 col-12 mb-40">
                <h4 class="title">Kategoriler</h4>
                <ul>
                    <?php  
                    foreach ($kategoriler as $kategori) {
                        echo "<li><a href='category.php?category=".$kategori."'>".$kategori."</a></li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="footer-widget col-lg-3 col-md-6 col-12 mb-40">
                <h4 class="title">Diğer Sayfalar</h4>
                <ul>
                    <li><a href="hakkimizda.php">Hakkımızda</a></li>
                    <li><a href="garanti.php">Ürün Garantisi</a></li>
                    <li><a href="iade.php">İade Süreci</a></li>
                    <li><a href="kullanicisozlesmesi.php">Kullanım Şartları</a></li>
                    <li><a href="mesafelisatis.php">Mesafeli Satış Sözleşmesi</a></li>
                </ul>
            </div>

            <div class="footer-widget col-lg-3 col-md-6 col-12 mb-40">
                <h4 class="title">Abone Ol</h4>
                <p>Bültenimize abone olun ve ürünümüzle ilgili tüm güncellemeleri alın</p>

                <form id="mc-form" class="mc-form footer-subscribe-form">
                    <input id="mc-email" autocomplete="off" placeholder="E-posta adresinizi buraya girin" name="EMAIL" type="email">
                    <button id="mc-submit" type="submit"><i class="fa fa-paper-plane-o"></i></button>
                </form>
                <!-- mailchimp-alerts Başlangıcı -->
                <div class="mailchimp-alerts">
                    <div class="mailchimp-submitting" style="display:none;">Gönderiliyor...</div><!-- mailchimp-submitting Sonu -->
                    <div class="mailchimp-success" style="display:none;">Başarıyla abone oldunuz!</div><!-- mailchimp-success Sonu -->
                    <div class="mailchimp-error" style="display:none;">Bir hata oluştu.</div><!-- mailchimp-error Sonu -->
                </div><!-- mailchimp-alerts Sonu -->

                <h5>Bizi Takip Edin</h5> 
                <p class="footer-social"><a href="<?= $Facebook ?>">Facebook</a> - <a href="<?= $X ?>">X</a> - <a href="<?= $Instagram ?>">Instagram</a></p>
            </div>

        </div>
    </div>
</div><!-- Üst Footer Bölümü Sonu -->

<!-- Alt Footer Bölümü Başlangıcı -->
<div class="footer-bottom-section section bg-theme-two pt-15 pb-15">
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <p class="footer-copyright">© 2025 <i class="fa fa-heart heart-icon"></i> Tüm hakları saklıdır.</p>
            </div>
        </div>
    </div>
</div><!-- Alt Footer Bölümü Sonu -->

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('mc-form').addEventListener('submit', function(e) {
            e.preventDefault();

            var email = document.getElementById('mc-email').value;
            var alerts = {
                submitting: document.querySelector('.mailchimp-submitting'),
                success: document.querySelector('.mailchimp-success'),
                error: document.querySelector('.mailchimp-error')
            };

        // Temizleme
            alerts.submitting.style.display = 'block';
            alerts.success.style.display = 'none';
            alerts.error.style.display = 'none';

            fetch('assets/fonk/subscribe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `EMAIL=${encodeURIComponent(email)}`
            })
            .then(response => response.json())
            .then(data => {
                alerts.submitting.style.display = 'none';
                if (data.success) {
                    alerts.success.style.display = 'block';
                } else {
                    alerts.error.style.display = 'block';
                    alerts.error.textContent = data.message || 'Bir hata oluştu.';
                }
            })
            .catch(error => {
                console.error(error);
                alerts.submitting.style.display = 'none';
                alerts.error.style.display = 'block';
                alerts.error.textContent = 'Bir hata oluştu.';
            });
        });
    });
</script>
