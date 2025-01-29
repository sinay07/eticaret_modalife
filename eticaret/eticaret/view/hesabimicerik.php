<!-- Seitenabschnitt Start -->
<div class="page-section section section-padding">
    <div class="container">
        <div class="row mbn-30">

<!-- Hesabım Sekme-Menü Başlangıç -->
<div class="col-lg-3 col-12 mb-30">
    <div class="myaccount-tab-menu nav" role="tablist">
        <a href="#dashboad" class="active" data-bs-toggle="tab"><i class="fa fa-dashboard"></i> Gösterge Paneli</a>
        <a href="#orders" data-bs-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Siparişler</a>
        <a href="#address-edit" data-bs-toggle="tab"><i class="fa fa-map-marker"></i> Adres</a>
        <a href="#account-info" data-bs-toggle="tab"><i class="fa fa-user"></i> Hesap Detayları</a>
        <a href="logout.php"><i class="fa fa-sign-out"></i> Çıkış Yap</a>
    </div>
</div>
<!-- Hesabım Sekme-Menü Bitişi -->


<!-- Mein Konto Tab-Inhalt Start -->
<div class="col-lg-9 col-12 mb-30">
    <div class="tab-content" id="myaccountContent">
        <!-- Einzelner Tab-Inhalt Start -->
        <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
            <div class="myaccount-content">
                <h3>Dashboard</h3>

                <div class="welcome">
                    <p>Merhaba, <strong><?= $_SESSION['ADSOYAD'] ?></strong></p>
                </div>

                <p class="mb-0">Hesap gösterge panelinizden, son siparişlerinizi kolayca kontrol edebilir ve görüntüleyebilir, gönderim ve fatura adreslerinizi yönetebilir ve şifrenizi ve hesap detaylarınızı düzenleyebilirsiniz.</p>

            </div>
        </div>
        <!-- Einzelner Tab-Inhalt Ende -->

        <!-- Einzelner Tab-Inhalt Start -->
        <div class="tab-pane fade" id="orders" role="tabpanel">
            <div class="myaccount-content">
                <h3>Siparişler</h3>

                <div class="myaccount-table table-responsive text-center">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Tarih</th>
                                <th>Toplam</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            include 'assets/fonk/mysql.php';

                                        // Kullanıcının oturum e-posta adresini al
                            $session_email = $_SESSION['EPOSTA'];

                                        // SQL sorgusu
                            $sql = "SELECT ODEME_TARIH, TOPLAM_TUTAR, SIPARIS_NO FROM odeme WHERE EPOSTA = :session_email";

                                        // Sorguyu hazırla ve çalıştır
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':session_email', $session_email);
                            $stmt->execute();

                                        // Sorgudan dönen sonuçları işle
                            if ($stmt->rowCount() > 0) {
                                            // Veri bulunduğunda döngüyü başlat
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                // Verileri al
                                    $odeme_tarihi = $row["ODEME_TARIH"];
                                    $toplam_tutar = $row["TOPLAM_TUTAR"];
                                    $siparis_no = $row["SIPARIS_NO"];
                                    ?>
                                    <tr>
                                        <td><?= $siparis_no ?></td>
                                        <td><?= $odeme_tarihi ?></td>
                                        <td><?= $toplam_tutar ?>.-CHF</td>
                                        <td><a href="bestellungen.php?q=<?= $siparis_no ?>" class="btn btn-dark btn-round">Anzeigen</a></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<div style='text-align:center; margin-top: 20px;'><h2>Hiç sipariş bulunamadı</h2></div>";

                            }

                                        // PDO bağlantısını kapat
                            $stmt = null;
                            $conn = null;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Einzelner Tab-Inhalt Ende -->

<!-- Tekil Sekme-İçerik Başlangıç -->
<form method="post">
    <div class="tab-pane fade" id="address-edit" role="tabpanel">
        <div class="myaccount-content">
            <?php
            include 'assets/fonk/mysql.php';

            // Kullanıcının oturum e-posta adresini al
            $session_email = $_SESSION['EPOSTA'];

            // SQL sorgusu
            $sql = "SELECT * FROM adresler WHERE EPOSTA = :session_email";

            // Sorguyu hazırla ve çalıştır
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':session_email', $session_email);
            $stmt->execute();

            // Adres bulundu mu kontrol et
            if ($stmt->rowCount() > 0) {
                // Adres varsa, adres bilgilerini göster
                // Önce verileri al
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $adsoyad = $row["ADSOYAD"];
                $adres = $row["ADRES"];
                $sehir = $row["SEHIR"];
                $posta_kodu = $row["POSTA_KODU"];
                $ulke = $row["ULKE"];
                $telefon = $row["TELEFON"];

                // Adres bilgilerini görüntüle
                echo "<h3>Fatura Adresi</h3>";
                echo "<address>";
                echo "<p><strong>$adsoyad</strong></p>";
                echo "<p><strong>$adres</strong></p>";
                echo "<p>$sehir, $ulke <br>";
                echo "<p>$posta_kodu ";
                echo "Telefon: $telefon</p>";
                echo "</address>";
                // Adres bulunduysa, "Yeni Adres Ekle" butonunu göster
                echo "<div style='text-align:center; margin-top: 20px;'>";
                echo "<a href='#' class='sil btn btn-dark btn-round d-inline-block'><i class='fa fa-edit'></i> Yeni Adres Ekle</a>";
                echo "</div>";
            } else {
                // Adres bulunamadıysa, "Adres Ekle" butonunu göster
                //<!-- Dinamik olarak oluşturulacak input alanlarının ekleneceği div -->
                echo "<div id='address-fields'></div>";
                echo "<div style='text-align:center; margin-top: 20px;'>";
                echo "<button type='submit' class='edit-address-btn btn btn-dark btn-round d-inline-block'>Adres Ekle</button>";
                echo "</div>";
            }

            // PDO bağlantısını kapat
            $stmt = null;
            $conn = null;
            ?>
        </div>
    </div>
</form>

<!-- Einzelner Tab-Inhalt Ende -->

<div class="tab-pane fade" id="account-info" role="tabpanel">
    <div class="myaccount-content">
        <?php  
        include 'assets/fonk/mysql.php';
        $session_email = $_SESSION['EPOSTA'];

        $sql = "SELECT * FROM kullanicilar WHERE EPOSTA = :session_email";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':session_email', $session_email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $adsoyad = $row["ADSOYAD"];
            $eposta = $row["EPOSTA"];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_adsoyad = $_POST["first_name"];
            $new_email = $_POST["email"];
            $_SESSION['EPOSTA'] = $new_email;
            $current_password = $_POST["current_password"];
            $new_password = $_POST["new_password"];
            $confirm_password = $_POST["confirm_password"];

            // Şifre değiştirme işlemini sadece yeni şifre ve onaylanmış şifre varsa gerçekleştir
            $password_change = !empty($new_password) && ($new_password == $confirm_password);

            // Eğer şifre değiştirme işlemi gerçekleştirilecekse, eski şifreyi kontrol et
            if ($password_change) {
                if (!password_verify($current_password, $row['SIFRE'])) {
                    echo "<script>alert('Eski şifrenizi yanlış girdiniz.');</script>";
                    $password_change = false; // Şifre değiştirme işlemi iptal edildi
                }
            }

            // Şifre değiştirme işlemi gerçekleştirilecekse, yeni şifreyi hash'le
            if ($password_change) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            }

            // SQL sorgusu oluştur
            $sql_update = "UPDATE kullanicilar SET ADSOYAD = :adsoyad, EPOSTA = :eposta";
            if ($password_change) {
                $sql_update .= ", SIFRE = :sifre";
            }
            $sql_update .= " WHERE EPOSTA = :session_email";

            // Sorguyu hazırla ve çalıştır
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindParam(':adsoyad', $new_adsoyad);
            $stmt_update->bindParam(':eposta', $new_email);
            if ($password_change) {
                $stmt_update->bindParam(':sifre', $hashed_password);
            }
            $stmt_update->bindParam(':session_email', $session_email);
            $stmt_update->execute();

            // Başarı mesajını göster ve sayfayı yenile
            echo "<script>alert('Bilgileriniz başarıyla güncellendi.');</script>";
            echo "<script>window.location.href = window.location.href;</script>"; // Sayfayı yenile
        }
        ?>
        <h3>Hesap Ayarları</h3>

        <div class="account-details-form">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="row">
                    <div class="col-lg-6 col-12 mb-30">
                        <input name="first_name" id="first-name" placeholder="Ad Soyad" value="<?php echo $adsoyad; ?>" type="text">
                    </div>

                    <div class="col-12 mb-30">
                        <input name="email" id="email" placeholder="E-Posta Adresi" type="email" value="<?php echo $eposta; ?>">
                    </div>

                    <div class="col-12 mb-30"><h4>Şifreyi Değiştir</h4></div>

                    <div class="col-12 mb-30">
                        <input name="current_password" id="current-pwd" placeholder="Mevcut Şifre" type="password">
                    </div>

                    <div class="col-lg-6 col-12 mb-30">
                        <input name="new_password" id="new-pwd" placeholder="Yeni Şifre" type="password">
                    </div>

                    <div class="col-lg-6 col-12 mb-30">
                        <input name="confirm_password" id="confirm-pwd" placeholder="Şifreyi Onayla" type="password">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-dark btn-round btn-lg">Değişiklikleri Kaydet</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>



</div>
</div>
<!-- Mein Konto Tab-Inhalt Ende -->
</div>
</div>
</div><!-- Seitenabschnitt Ende -->

<!-- Modal -->
<div id="warningModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 class="modal-title">Warnung</h2>
    <p class="modal-body"><!--dinamik metin--></p>
    <button class="modal-button close-button">Schließen</button>
</div>
</div>

<!-- jQuery kütüphanesini sayfaya ekleyin -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- JavaScript kodu ve CSS stilini burada ekleyin -->
<script>
    $(document).ready(function() {
    // Placeholder değerlerini içeren bir dizi oluşturun
        var placeholders = ["Ad Soyad", "Telefon", "Firma", "Adres", "Ülke", "İl", "İlçe", "Posta Kodu"];
        var name = ["AdSoyad", "Telefon", "Firma", "Adres", "Ulke", "Sehir", "Eyalet", "PostaKodu"];

    // "Adresse bearbeiten" butonuna tıklandığında
        $(document).on('click', '.edit-address-btn', function(e) {
        e.preventDefault(); // Butona tıklanınca sayfanın yeniden yüklenmesini önler

        // 9 adet input alanı oluşturuluyor
        for (var i = 0; i < placeholders.length; i++) {
            var inputHtml = "<div class='col-12 mb-30'>";
            inputHtml += "<input name='" + name[i] + "' id='" + name[i] + "' placeholder='" + placeholders[i] + "' type='text'>";
            inputHtml += "</div>";

            $("#address-fields").append(inputHtml); // Yeni input alanı div'e ekleniyor
        }

        // Butonun class'ını değiştir
        $(this).removeClass('edit-address-btn').addClass('addAdress');
    });

    // "addAdress" butonuna tıklandığında
        $(document).on('click', '.addAdress', function(e) {
        e.preventDefault(); // Butona tıklanınca sayfanın yeniden yüklenmesini önler

        // AJAX ile verileri bir sayfaya post et
        var formData = {};
        for (var i = 0; i < name.length; i++) {
            formData[name[i]] = $('#' + name[i]).val();
        }

        $.ajax({
            type: "POST",
            url: "assets/fonk/addAdress.php", // Verilerin gönderileceği sayfanın URL'sini buraya ekleyin
            data: formData,
            success: function(response) {
                // Uyarı mesajını modal penceresinde göster
                $("#warningModal .modal-body").text(response);
                $("#warningModal").show();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Hata durumunda da uyarı mesajını modal penceresinde göster
                $("#warningModal .modal-body").text(response);
                $("#warningModal").show();
            }
        });
    });

    // "Sil" butonuna tıklandığında
        $(document).on('click', '.sil', function(e) {
        e.preventDefault(); // Sayfanın yeniden yüklenmesini engelliyoruz
        
        // Session'daki e-posta bilgisini alıyoruz
        var eposta = "<?php echo $_SESSION['EPOSTA']; ?>";
        
        // AJAX isteğiyle removeAdress.php dosyasına post ediyoruz
        $.ajax({
            type: "POST",
            url: "assets/fonk/removeAdress.php",
            data: { eposta: eposta }, // E-postayı POST ediyoruz
            success: function(response){
                // Uyarı mesajını modal penceresinde göster
                $("#warningModal .modal-body").text(response);
                $("#warningModal").show();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Hata durumunda konsola hata mesajını yazdırıyoruz
            }
        });
    });

    // Modal kapatma işlemi
        $(".close, .modal-button.close-button").click(function(){
            $("#warningModal").hide();
        location.reload(); // Sayfanın yenilenmesi
    });
    });
</script>

<!-- CSS stilini buraya ekleyin -->
<style>
    /* Dinamik olarak oluşturulan input alanlarının stili */
    #address-fields {
        margin-top: 20px;
    }

    #address-fields input {
        display: block;
        width: 100%;
        border: 1px solid #ebebeb;
        border-radius: 50px;
        line-height: 24px;
        padding: 11px 25px;
        color: #656565;
    }

    /* Modal stilini buraya ekleyin */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }
</style>
