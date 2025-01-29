<?php
include 'assets/fonk/mysql.php';

if (isset($_GET['q'])) {
    $siparis_no = $_GET['q'];

    // Sorguyu hazırla
    $query = "
    SELECT s.*, 
           COALESCE(a.ADSOYAD, an.ADSOYAD) AS ADSOYAD, 
           COALESCE(a.ADRES, an.ADRES) AS ADRES, 
           COALESCE(a.TELEFON, an.TELEFON) AS TELEFON, 
           COALESCE(a.ULKE, an.ULKE) AS ULKE, 
           COALESCE(a.SEHIR, an.SEHIR) AS SEHIR, 
           COALESCE(a.EYALET, an.EYALET) AS EYALET, 
           COALESCE(a.POSTA_KODU, an.POSTA_KODU) AS POSTA_KODU, 
           o.TOPLAM_TUTAR
    FROM siparisler s 
    LEFT JOIN adresler a ON s.EPOSTA = a.EPOSTA 
    LEFT JOIN adreslerno an ON s.EPOSTA = an.EPOSTA 
    INNER JOIN odeme o ON s.SIPARIS_NO = o.SIPARIS_NO
    WHERE s.SIPARIS_NO = :siparis_no";
    
    // Sorguyu hazırla ve çalıştır
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':siparis_no', $siparis_no);
    $stmt->execute();

    // Sorgu sonuçlarını işle
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Her sütunu burada işleyebilirsiniz
            $siparis_no = $row['SIPARIS_NO'];
            $eposta = $row['EPOSTA'];
            $siparis_tarih = $row['SIPARIS_TARIH'];
            $ad_soyad = $row['ADSOYAD'];
            $adres = $row['ADRES'];
            $telefon = $row['TELEFON'];
            $ulke = $row['ULKE'];
            $sehir = $row['SEHIR'];
            $eyalet = $row['EYALET'];
            $postakodu = $row['POSTA_KODU'];
            $toplam_tutar = $row['TOPLAM_TUTAR'];
            // Diğer sütunlar için aynı şekilde devam edebilirsiniz
        }
    } else {
        echo "Sipariş bulunamadı.";
    }

    // Sorguyu hazırla
    $query = "SELECT * FROM siparisler WHERE SIPARIS_NO = :siparis_no";
    
    // Sorguyu hazırla ve çalıştır
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':siparis_no', $siparis_no);
    $stmt->execute();

    // Sorgu sonuçlarını işle
    if ($stmt->rowCount() > 0) {
        // Sipariş verilerini bir diziye al
        $siparis_verileri = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Sepetteki ürünleri al
        $urunler = array();
        foreach ($siparis_verileri as $siparis) {
            $urun_kodu = $siparis['URUN_KODU'];
            $urun_adi = $siparis['URUN_ADI'];
            $urun_resim = $siparis['URUN_RESIM'];
            $adet = $siparis['ADET'];
            $birim_fiyat = $siparis['BIRIM_FIYAT'];

            // Ürün bilgilerini diziye ekle
            $urunler[] = array(
                'urun_kodu' => $urun_kodu,
                'urun_adi' => $urun_adi,
                'urun_resim' => $urun_resim,
                'adet' => $adet,
                'birim_fiyat' => $birim_fiyat
            );
        }
    } else {
        echo "Sipariş bulunamadı.";
    }
}
?>


<!DOCTYPE html>
<html lang="tr" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
<?php include 'view/head.php'; ?>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <?php include 'view/solmenu.php'; ?>
      <div class="layout-page">
        <?php include 'view/ustmenu.php'; ?>
        <div class="content-wrapper">

          <!-- İçerik Başlangıcı -->
          <div class="container-fluid flex-grow-1 container-p-y">
            <div class="col-lg-12 col-md-4 order-1">

              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Sipariş Detayları</h5>
                    </div>
                    <div class="card-body">
                      <form>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">Sipariş Numarası</label>
                            <input type="text" readonly value="<?php echo $siparis_no; ?>" class="form-control" id="basic-default-fullname" placeholder="Sipariş Numarası">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">E-Mail</label>
                            <input type="text" readonly value="<?php echo $eposta; ?>" class="form-control" id="basic-default-fullname" placeholder="E-Mail">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">Sipariş Tarihi</label>
                            <input type="text" readonly value="<?php echo $siparis_tarih; ?>" class="form-control" id="basic-default-fullname" placeholder="Sipariş Tarihi">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">Ad ve Soyad</label>
                            <input type="text" readonly value="<?php echo $ad_soyad; ?>" class="form-control" id="basic-default-fullname" placeholder="Ad ve Soyad">
                          </div>
                        </div>
                        <div class="mb-3">
                          <label for="exampleFormControlTextarea1" class="form-label">Adres</label>
                          <textarea class="form-control" readonly id="exampleFormControlTextarea1" rows="3"><?php echo $adres; ?></textarea>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">Telefon</label>
                            <input type="text" readonly value="<?php echo $telefon; ?>" class="form-control" id="basic-default-fullname" placeholder="Telefon">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">Ülke</label>
                            <input type="text" readonly value="<?php echo $ulke; ?>" class="form-control" id="basic-default-fullname" placeholder="Ülke">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">Şehir</label>
                            <input type="text" readonly value="<?php echo $sehir; ?>" class="form-control" id="basic-default-fullname" placeholder="Şehir">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">İlçe</label>
                            <input type="text" readonly value="<?php echo $eyalet; ?>" class="form-control" id="basic-default-fullname" placeholder="İlçe">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">Posta Kodu</label>
                            <input type="text" readonly value="<?php echo $postakodu; ?>" class="form-control" id="basic-default-fullname" placeholder="Posta Kodu">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label" for="basic-default-fullname">Toplam Tutar</label>
                            <input type="text" value="<?php echo $toplam_tutar; ?>" readonly class="form-control" id="basic-default-fullname" placeholder="Toplam Tutar">
                          </div>
                        </div>
                        <input data-urun-kodu="<?php echo $siparis_no; ?>" type="button" id="teslimButon" class="btn btn-primary" value="Teslim Et">
                        <a target="_blank" href="drucken.php?q=<?= $siparis_no ?>" class="btn btn-danger btn-round">Yazdır</a>
                      </form>
                    </div>
                  </div>
                </div>

                <div class="col-xl">
                  <div class="card">
                    <h5 class="card-header">Ürün Listesi</h5>
                    <div class="table-responsive text-nowrap">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>ÜRÜN KODU</th>
                            <th>ÜRÜN ADI</th>
                            <th>ÜRÜN GÖRSELİ</th>
                            <th>ADET</th>
                            <th>BİRİM FİYAT</th>
                          </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                          <?php foreach ($urunler as $urun): ?>
                            <tr>
                              <td><?php echo $urun['urun_kodu']; ?></td>
                              <td><?php echo $urun['urun_adi']; ?></td>
                              <td><img class="product-image" src="../assets/images/upload/product/<?php echo $urun['urun_resim']; ?>" style="width: 50px;"></td>
                              <td><?php echo $urun['adet']; ?></td>
                              <td><?php echo $urun['birim_fiyat']; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- / İçerik Sonu -->

          <!-- Toast Bildirimi -->
          <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="successToast" class="toast bg-primary text-white" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <strong class="me-auto">Bildirimi</strong>
                <small>1 saniye önce</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Kapat"></button>
              </div>
              <div class="toast-body">
                İşlem başarılı.
              </div>
            </div>
          </div>

          <!-- Toast Bildirimi -->
          <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="errorToast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <strong class="me-auto">Bildirimi</strong>
                <small>1 saniye önce</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Kapat"></button>
              </div>
              <div class="toast-body">
                İşlem başarısız. Lütfen tekrar deneyin.
              </div>
            </div>
          </div>

          <?php include 'view/footer.php'; ?>
          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <?php include 'view/script.php'; ?>
  <script>
    $(document).ready(function(){
      $(".product-image").hover(function(){
        $(this).css("width", "100px"); // Resim boyutunu büyüt
      }, function(){
        $(this).css("width", "50px"); // Resim boyutunu küçült
      });
    });
  </script>
  <script src="assets/js/teslimet.js"></script>
  <script src="assets/js/yazdir.js"></script>
</body>
</html>

