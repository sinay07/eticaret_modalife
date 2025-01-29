<?php
try {
    // Ana ürün bilgilerini almak için sorguyu hazırla
    $stmt = $conn->prepare("
        SELECT *
        FROM urunler
        WHERE KAYIT_ID = :id
        ");
    // Sorguyu çalıştır
    $stmt->execute(array(':id' => $id));
    // Sonucu al
    $urun = $stmt->fetch(PDO::FETCH_ASSOC);
    // Eğer ürün bulunamadıysa
    if (!$urun) {
        header("Location: 404.php");
        exit; // İşlemi sonlandır
    }
    $UrunKodu = $urun['URUN_KODU'];
    $puan = $urun['PUAN'];
    $yildiz_sayisi = floor($puan);
    $yarim_yildiz = ($puan - $yildiz_sayisi) > 0.5 ? 1 : 0;
    $SayfaBaslik = $urun['URUN_ADI'];
    $UrunAciklama = $urun['ACIKLAMA'];
    $Fiyat = $urun['FIYAT'];
    $Etiketler = $urun['ETIKETLER'];
    $KumasCinsi = $urun["KUMAS_CINSI"];
    $Cinsiyet = $urun["CINSIYET"];
    $AnaResim = $urun['RESIM']; // Ana resim

    // Varyant resimlerini çek
    $stmt_varyant_resim = $conn->prepare("
        SELECT RESIM
        FROM varyant_resim
        WHERE URUN_KODU = :urun_kodu
        ");
    $stmt_varyant_resim->execute(array(':urun_kodu' => $UrunKodu));
    $varyant_resimler = $stmt_varyant_resim->fetchAll(PDO::FETCH_COLUMN);

    // Tüm resimleri birleştir
    $TumResimler = array_merge([$AnaResim], $varyant_resimler);

    $stmt_beden = $conn->prepare("
        SELECT GROUP_CONCAT(DISTINCT BEDEN) AS TUM_BEDENLER
        FROM varyant_beden
        WHERE URUN_KODU = :urun_kodu
        ");
    $stmt_beden->execute(array(':urun_kodu' => $UrunKodu));
    $beden_verisi = $stmt_beden->fetch(PDO::FETCH_ASSOC);
    $TumBedenler = explode(",", $beden_verisi["TUM_BEDENLER"]); // Bedenler

    // Yorumları al ve ZAMAN'a göre sırala
    $stmt_yorumlar = $conn->prepare("
        SELECT * FROM yorumlar
        WHERE URUN_ID = :urun_id
        ORDER BY ZAMAN DESC
        ");
    $stmt_yorumlar->execute(array(':urun_id' => $id));
    $yorumlar = $stmt_yorumlar->fetchAll(PDO::FETCH_ASSOC); // Yorumları diziye al

} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>

<style>
    .watermarked {
        position: relative;
        display: inline-block;
    }

    .watermarked img {
        display: block;
    }

    .watermarked::after {
        content: "";
        position: absolute;
        bottom: 10px; /* Alt kenardan 10px yukarıda */
        right: 10px; /* Sağ kenardan 10px içeride */
        width: 25%; /* Filigranın genişliği (oranı ayarlayabilirsiniz) */
        height: 25%; /* Filigranın yüksekliği (oranı ayarlayabilirsiniz) */
        background: url('assets/images/logo.png?v=<?php echo time(); ?>') center center no-repeat;
        background-size: contain; /* Filigranın içeri sığması için */
        opacity: 1; /* Filigranın şeffaflığı */
        pointer-events: none;
    }
</style>



<input type="hidden" id="urun_id" value="<?php echo $_GET['id']; ?>">
<input type="hidden" id="email" value="<?php echo $_SESSION['EPOSTA']; ?>">
<input type="hidden" id="adsoyad" value="<?php echo $_SESSION['ADSOYAD']; ?>">
<!-- Seitenabschnitt Start -->
<div class="page-section section section-padding">
    <div class="container">
        <div class="row row-30 mbn-50">
            <div class="col-12">
                <div class="row row-20 mb-10">
                    <div class="col-lg-6 col-12 mb-40">
                        <div class="pro-large-img mb-10 easyzoom easyzoom--with-thumbnails watermarked">
                            <a href="assets/images/upload/product/<?= $AnaResim ?>">
                                <img src="assets/images/upload/product/<?= $AnaResim ?>" alt="Ürün Resmi"/>
                            </a>
                        </div>
                        <ul id="pro-thumb-img" class="pro-thumb-img">
                            <?php foreach ($TumResimler as $resim) { ?>
                                <li>
                                    <a href="assets/images/upload/product/<?= $resim ?>" data-standard="assets/images/upload/product/<?= $resim ?>" class="watermarked">
                                        <img src="assets/images/upload/product/<?= $resim ?>" alt="Ürün Küçük Resmi" />
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="col-lg-6 col-12 mb-40">
                        <div class="single-product-content">
                            <div class="head">
                                <div class="head-left">
                                    <h3 class="title"><?= $SayfaBaslik ?></h3>
                                    <div class="ratting">
                                        <?php
                                            $rating = $urun['PUAN']; // Ürünün puan verisi
                                            $whole = floor($rating); // Tam kısmı al
                                            $fraction = $rating - $whole; // Ondalık kısmı al
                                            for ($i = 0; $i < $whole; $i++) {
                                                echo '<i class="fa fa-star"></i>';
                                            }
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
                                    </div>
                                    <div class="head-right">
                                        <span class="price"><?= $Fiyat ?> ₺</span>
                                    </div>
                                </div>
                                <span class="availability">Mevcutluk: <span>Stokta Var</span></span>
                                <input type="hidden" name="urun_kodu" value="<?= $UrunKodu ?>">
                                <span class="availability">Ürün Kodu: <span><?= $UrunKodu ?></span></span>
                                <div class="quantity-colors">
                                    <div class="quantity">
                                        <h5>Miktar:</h5>
                                        <div class="pro-qty"><input type="text" value="1"></div>
                                        <br>
                                        <br>
                                        <h5>Bedeni Seçin:</h5>
                                        <select class="form-select">
                                            <option selected disabled>Bedeni Seçin:</option>
                                            <?php foreach ($TumBedenler as $beden) { ?>
                                                <option value="<?= $beden ?>"><?= $beden ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="actions">
                                    <button class="addToCart"><i class="ti-shopping-cart"></i><span>Sepete Ekle</span></button>

                                    <button class="box addToWishlist" data-tooltip="İstek Listesi"><i class="ti-heart"></i></button>
                                </div>
                                <div class="tags">
                                    <h5>Etiketler:</h5>
                                    <?php  
                                    $etiketDizisi = explode(", ", $Etiketler);
                                    foreach ($etiketDizisi as $etiket) {
                                        echo "<a href='#'><b>$etiket</b></a>";
                                    }
                                    ?>
                                </div>
                                <div class="share">
                                    <h5>Paylaş: </h5>
                                    <a href="whatsapp://send?text=<?= $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>" data-action="share/whatsapp/share" style="font-size: 24px;"><i class="fa fa-whatsapp"></i></a>
                                    <a href="https://t.me/share/url?url=<?= urlencode($url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" style="font-size: 24px;"><i class="fa fa-telegram"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-50">
                            <!-- Tab-Navigasyon -->
                            <div class="col-12">
                                <ul class="pro-info-tab-list section nav">
                                    <li><a class="active" href="#more-info" data-bs-toggle="tab">Açıklama</a></li>
                                    <li><a href="#data-sheet" data-bs-toggle="tab">Özellikler</a></li>
                                    <li><a href="#reviews" data-bs-toggle="tab">Yorum Yap</a></li>
                                </ul>
                            </div>
                            <!-- Tab İçerikleri -->
                            <div class="tab-content col-12">
                                <div class="pro-info-tab tab-pane active" id="more-info">
                                    <p><?= $UrunAciklama ?></p>
                                </div>
                                <div class="pro-info-tab tab-pane" id="data-sheet">
                                    <table class="table-data-sheet">
                                        <tbody>
                                            <tr class="odd">
                                                <td>İçerik</td>
                                                <td><?= $KumasCinsi ?></td>
                                            </tr>
                                            <tr class="even">
                                                <td>Cinsiyet</td>
                                                <td><?= $Cinsiyet ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pro-info-tab tab-pane" id="reviews" style="padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px;">
                                    <?php if (isset($_SESSION['EPOSTA']) && !empty($_SESSION['EPOSTA'])): ?>
                                    <a href="#" id="openYorumModal" style="color: #007bff; font-weight: bold; text-decoration: none;">Yorum Yap</a>
                                    <br><br>
                                <?php else: ?>
                                    <p style="color: #d9534f; font-weight: bold;">Yorum yapabilmek için giriş yapmalısınız.</p>
                                <?php endif; ?>

                                <div class="yorumlar">
                                    <?php if (!empty($yorumlar)): ?>
                                        <?php foreach ($yorumlar as $yorum): ?>
                                            <div class="yorum" style="border-bottom: 1px solid #ddd; padding: 10px 0; display: flex; justify-content: space-between;">
                                                <div>
                                                    <p style="font-size: 14px; font-weight: bold; color: #333;">
                                                        <?php echo htmlspecialchars($yorum['ADSOYAD']); ?> 
                                                        <span style="font-weight: normal; color: #777;">(<?php echo $yorum['ZAMAN']; ?>)</span>
                                                    </p>
                                                    <p style="font-size: 14px; color: #555;"><?php echo nl2br(htmlspecialchars($yorum['YORUM'])); ?></p>
                                                </div>
                                                <div style="display: flex; flex-direction: column; align-items: center;">
                                                    <?php
                                                    // Eğer session'daki e-posta ile yorum yapan kişinin e-posta adresi farklıysa butonu göster
                                                    if ($_SESSION['EPOSTA'] !== $yorum['EPOSTA']) {
                                                        ?>
                                                        <a href="#" class="openModalBtn" 
                                                        data-sender-email="<?php echo $_SESSION['EPOSTA']; ?>" 
                                                        data-receiver-email="<?php echo htmlspecialchars($yorum['EPOSTA']); ?>" 
                                                        data-product-id="<?php echo $id; ?>" 
                                                        style="color: #28a745; font-weight: bold; text-decoration: none; margin-bottom: 5px;">
                                                        Özel Mesaj
                                                    </a>
                                                    <?php
                                                }
                                                ?>
                                            </div>

                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="color: #777; font-style: italic;">Henüz bu ürünle ilgili yorum yapılmamış.</p>
                                <?php endif; ?>
                            </div>


                            <!-- Modal HTML -->
                            <div id="messageModal" style="display: none;">
                                <div style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000;">
                                    <div style="background-color: white; padding: 20px; width: 400px; margin: 10% auto; border-radius: 8px;">
                                        <h3>Özel Mesaj Gönder</h3>
                                        <form id="messageForm" action="mesajgonder.php" method="POST">
                                            <input type="hidden" name="GONDEREN_EPOSTA" id="modalSenderEmail">
                                            <input type="hidden" name="ALICI_EPOSTA" id="modalReceiverEmail">
                                            <input type="hidden" name="URUN_ID" id="modalProductId">
                                            <div style="margin-bottom: 10px;">
                                                <label for="message" style="font-weight: bold;">Mesajınız:</label>
                                                <textarea name="MESAJ" id="message" rows="4" style="width: 100%;"></textarea>
                                            </div>
                                            <button type="submit" style="background-color: #28a745; color: white; padding: 10px 15px; border: none; cursor: pointer;">Gönder</button>
                                            <button type="button" onclick="closeModal()" style="background-color: #ccc; padding: 10px 15px; border: none; cursor: pointer; margin-left: 10px;">Kapat</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div><!-- Sayfa Bölümü Sonu -->
    </div>
</div>
</div>

<!-- Yorum Modal -->
<div id="yorumModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close yorumModal">&times;</span>
    <h2 class="modal-title">Yorum Yap</h2>
    <p class="modal-body">Lütfen aşağıya yorumunuzu yazın:</p>

    <!-- Yorum formu -->
    <textarea id="yorum" rows="4" cols="50" placeholder="Yorumunuzu buraya yazın..."></textarea>

    <br>

    <!-- Yorum gönderme ve kapanma butonları -->
    <button class="modal-button yorumModal" id="yorumGonder">Yorum Gönder</button>
    <button class="modal-button yorumModal" id="kapat">Kapat</button> <!-- Kapanma butonu -->
</div>
</div>




<!-- Uyarı Modal -->
<div id="warningModal" class="modal">
  <div class="modal-content">
    <span class="close warningModal" style="display: none;">&times;</span> <!-- X ikonunu kaldırdık -->
    <h2 class="modal-title">Uyarı</h2>
    <p class="modal-body"><!--dinamik metin--></p>
    <button class="modal-button warningModal">Kapat</button> <!-- Kapanma butonu -->
</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    // Modalı açma fonksiyonu
    function openModal(senderEmail, receiverEmail, productId) {
        document.getElementById('modalSenderEmail').value = senderEmail;
        document.getElementById('modalReceiverEmail').value = receiverEmail;
        document.getElementById('modalProductId').value = productId;
        document.getElementById('messageModal').style.display = 'block';
    }

    // Modalı kapama fonksiyonu
    function closeModal() {
        document.getElementById('messageModal').style.display = 'none';
    }

    // Butona tıklandığında modalı açma
    const modalBtns = document.querySelectorAll('.openModalBtn');
    modalBtns.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const senderEmail = this.getAttribute('data-sender-email');
            const receiverEmail = this.getAttribute('data-receiver-email');
            const productId = this.getAttribute('data-product-id');
            openModal(senderEmail, receiverEmail, productId);
        });
    });
</script>

<script>
    $(document).ready(function() {
  // Yorum gönderme butonuna tıklama olayı
      $('#yorumGonder').click(function() {
    var senderEmail = $('#email').val();   // Hidden input'tan e-posta alınıyor
    var productId = $('#urun_id').val();   // Hidden input'tan ürün ID'si alınıyor
    var adsoyad = $('#adsoyad').val();     // Hidden input'tan ad soyad alınıyor
    var yorum = $('#yorum').val();         // Kullanıcıdan alınan yorum

    // Eğer yorum boşsa işlem yapılmasın
    if (yorum.trim() === "") {
      alert("Lütfen bir yorum yazın.");
      return;
  }

    // jQuery ile AJAX isteği
  $.ajax({
      url: 'yorum-gonder.php', // PHP endpoint
      type: 'POST', // HTTP metodu
      dataType: 'json', // Dönen veri tipi
      contentType: 'application/json', // JSON olarak veri gönderileceğini belirt
      data: JSON.stringify({
        comment: yorum,         // Yorum
        sender_email: senderEmail,  // Gönderen e-posta
        product_id: productId,  // Ürün ID
        adsoyad: adsoyad        // Ad soyad
    }),
      success: function(response) {
        if (response.status === 'success') {
          alert(response.message);
          closeModal(); // Modal'ı kapat
      } else {
          alert(response.message); // Hata mesajını göster
      }
  },
  error: function() {
    alert("Bir hata oluştu. Lütfen tekrar deneyin.");
}
});
});

  // Kapat butonuna tıklama olayı
      $('#kapat').click(function() {
        closeModal();
    });

  // Modal'ı kapatma fonksiyonu
      function closeModal() {
        $('#yorumModal').hide();
    }

  // Modal'ı açma fonksiyonu (Eğer ihtiyacınız varsa)
    function openModal() {
        $('#yorumModal').show();
    }
});
</script>

<script>
    // Modalı açma fonksiyonu
    function openModal(email) {
        document.getElementById('modalEmail').value = email;
        document.getElementById('messageModal').style.display = 'block';
    }

    // Modalı kapama fonksiyonu
    function closeModal() {
        document.getElementById('messageModal').style.display = 'none';
    }

    // Butona tıklandığında modalı açma
    const modalBtns = document.querySelectorAll('.openModalBtn');
    modalBtns.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const email = this.getAttribute('data-id');
            openModal(email);
        });
    });
</script>


<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {

        // Modal açma fonksiyonu
        function showModal(modalType, message, showCloseButton) {
            var modal;
            var titleText;
            
            // Modal türüne göre ilgili modalı seç ve başlık belirle
            if(modalType === "yorumModal") {
                modal = document.getElementById("yorumModal");
                titleText = "Yorum Yap";
            } else {
                modal = document.getElementById("warningModal");
                titleText = "Uyarı";
            }

            var modalBody = modal.querySelector(".modal-body");
            modalBody.textContent = message;

            // Modal başlığını güncelle
            var modalTitle = modal.querySelector(".modal-title");
            modalTitle.textContent = titleText;

            // Kapatma butonunu kontrol et ve gerekirse göster
            var closeButton = modal.querySelector(".modal-button");
            closeButton.style.display = showCloseButton ? "block" : "none";

            modal.style.display = "block";
        }

        // Yorum modalini Kapatma Fonksiyonu
        document.querySelectorAll(".modal-button.yorumModal").forEach(function(element) {
            element.addEventListener("click", function() {
                var modal = document.getElementById("yorumModal");
                modal.style.display = "none";
            });
        });

        // Uyarı modalini Kapatma Fonksiyonu
        document.querySelectorAll(".modal-button.warningModal").forEach(function(element) {
            element.addEventListener("click", function() {
                var modal = document.getElementById("warningModal");
                modal.style.display = "none";
            });
        });

        // Modal dışına tıklanarak kapama işlemi
        window.onclick = function(event) {
            var modal = event.target.closest(".modal");
            if (modal && event.target === modal) {
                modal.style.display = "none";
            }
        };

        // Yorum modalını açma (yorum yapma işlemi)
        document.getElementById("openYorumModal")?.addEventListener("click", function(event) {
            event.preventDefault();
            showModal("yorumModal", "Lütfen yorumunuzu yazın!", true);
        });

        // Küçük resimlere tıklama olayını dinle
        document.querySelectorAll('#pro-thumb-img a').forEach(function(element) {
            element.addEventListener('click', function(event) {
                event.preventDefault(); // Varsayılan tıklama davranışını engelle
                var newSrc = this.getAttribute('data-standard');
                var largeImg = document.querySelector('.pro-large-img img');
                largeImg.src = newSrc; // Ana resmin src'sini güncelle
            });
        });

        // Sepete ekle butonuna tıklama işlemi
        document.querySelector('.addToCart').addEventListener('click', function(event) {
            var selectedSize = document.querySelector('.form-select').value;
            if (!selectedSize || selectedSize === "Bedeni Seçin:") {
                // Beden seçilmediği durumda hata mesajı göster
                event.preventDefault();
                showModal('warningModal', 'Lütfen bir beden seçiniz.', false);
                return;
            }

            var urunKodu = document.querySelector('input[name="urun_kodu"]').value;
            var anaResim = document.querySelector('.pro-large-img img').src.split('/').pop();
            var adet = document.querySelector('.pro-qty input').value;

            // Sepete ekleme işlemi
            fetch('assets/fonk/addToCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `urun_kodu=${urunKodu}&ana_resim=${anaResim}&adet=${adet}`
            })
            .then(response => response.text())
            .then(response => {
                if (response.trim() === "success") {
                    location.reload(); // Başarılıysa sayfayı yenile
                } else {
                    handleCartError(response); // Hata durumunu işle
                }
            })
            .catch(error => {
                console.error(error);
                showModal('warningModal', 'Ürün sepete eklenirken bir hata oluştu!', true);
            });
        });

        // Sepete eklerken oluşan hata durumları
        function handleCartError(response) {
            if (response.trim() === "error: Ürün bulunamadı.") {
                showModal('warningModal', 'Ürün bulunamadı.', true);
            } else if (response.trim() === "error: Oturum açınız.") {
                showModal('warningModal', 'Lütfen kullanıcı adınızı giriniz!', true);
            } else {
                showModal('warningModal', 'Ürün sepete eklenirken bir hata oluştu!', true);
            }
        }

        // Dilek listesine ekle butonuna tıklama işlemi
        document.querySelector('.addToWishlist').addEventListener('click', function() {
            var urunKodu = document.querySelector('input[name="urun_kodu"]').value;
            var anaResim = document.querySelector('.pro-large-img img').src.split('/').pop();

            // Dilek listesine ekleme işlemi
            fetch('assets/fonk/addToWishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `urun_kodu=${urunKodu}&ana_resim=${anaResim}`
            })
            .then(response => response.text())
            .then(response => {
                if (response.trim() === "success") {
                    location.reload(); // Başarılıysa sayfayı yenile
                } else {
                    handleWishlistError(response); // Hata durumunu işle
                }
            })
            .catch(error => {
                console.error(error);
                showModal('warningModal', 'Ürün dilek listesine eklenirken bir hata oluştu!', true);
            });
        });

        // Dilek listesine eklerken oluşan hata durumları
        function handleWishlistError(response) {
            if (response.trim() === "error: Ürün bulunamadı.") {
                showModal('warningModal', 'Ürün bulunamadı.', true);
            } else if (response.trim() === "error: Oturum açınız.") {
                showModal('warningModal', 'Lütfen kullanıcı adınızı giriniz!', true);
            }
        }

    });
</script>