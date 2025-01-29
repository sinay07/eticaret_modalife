<?php
// Veritabanı bağlantısı
include 'assets/fonk/mysql.php';

// URL'den q parametresini al
$param = $_GET['q'];

// Veritabanından URUN_KODU veya URUN_ADI ile eşleşen kaydı getir
$query = $conn->prepare("SELECT * FROM urunler WHERE URUN_KODU = :param OR URUN_ADI LIKE :param_like");
$query->execute(['param' => $param, 'param_like' => '%' . $param . '%']);
$urun = $query->fetch(PDO::FETCH_ASSOC);

// Ürün bulunamadıysa hata mesajı göster
if (!$urun) {
  echo "Ürün bulunamadı.";
  exit;
}

// Diğer resimleri getir
$query_resimler = $conn->prepare("SELECT KAYIT_ID, RESIM FROM varyant_resim WHERE URUN_KODU = :urun_kodu");
$query_resimler->execute(['urun_kodu' => $urun['URUN_KODU']]);
$resimler = $query_resimler->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="tr" class="light-style layout-menu-fixed" dir="ltr"
data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
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
                      <h5 class="mb-0">Ürünü Düzenle</h5>
                    </div>
                    <div class="card-body">
                      <form id="urun-guncelle-form">
                        <div class="mb-3">
                          <label class="form-label" for="urun-kodu">Ürün Kodu</label>
                          <input type="text" class="form-control" id="urun-kodu" name="urun_kodu" value="<?php echo $urun['URUN_KODU']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="urun-adi">Ürün Adı</label>
                          <input type="text" class="form-control" id="urun-adi" name="urun_adi" value="<?php echo $urun['URUN_ADI']; ?>">
                        </div>
                        <div class="mb-3">
                          <label for="urun-aciklama" class="form-label">Açıklama</label>
                          <textarea class="form-control" id="urun-aciklama" name="urun_aciklama" rows="3"><?php echo $urun['ACIKLAMA']; ?></textarea>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="urun-fiyat">Fiyat</label>
                          <input type="text" class="form-control" id="urun-fiyat" name="urun_fiyat" value="<?php echo $urun['FIYAT']; ?>">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="urun-stok">Stok</label>
                          <input type="text" class="form-control" id="urun-stok" name="urun_stok" value="<?php echo $urun['STOK']; ?>">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="urun-etiketler">Etiketler</label>
                          <input type="text" class="form-control" id="urun-etiketler" name="urun_etiketler" value="<?php echo $urun['ETIKETLER']; ?>">
                        </div>
                        <div class="mb-3">
                          <label for="urun-cinsiyet" class="form-label">Cinsiyet Seçin</label>
                          <select class="form-select" id="urun-cinsiyet" name="urun_cinsiyet" aria-label="Default select example">
                            <option value="Erkek" <?php echo ($urun['CINSIYET'] == "Erkek") ? 'selected' : ''; ?>>Erkek</option>
                            <option value="Kadın" <?php echo ($urun['CINSIYET'] == "Kadın") ? 'selected' : ''; ?>>Kadın</option>
                            <option value="Unisex" <?php echo ($urun['CINSIYET'] == "Unisex") ? 'selected' : ''; ?>>Unisex</option>
                          </select>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Ürün Düzenleme</h5>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label for="urun-kapak-resmi" class="form-label">Kapak Resmi</label>
                        <img src="../assets/images/upload/product/<?php echo $urun['RESIM']; ?>" alt="Kapak Resmi" class="img-thumbnail mb-3">
                        <input class="form-control" type="file" id="urun-kapak-resmi" name="urun_kapak_resmi">
                      </div>
                      <div class="mb-3">
                        <label for="urun-diger-resimler" class="form-label">Diğer Ürün Resimleri</label>
                        <div class="row" id="resimler-alani">
                          <?php
                          // Diğer resimleri göster
                          $count = 0;
                          foreach ($resimler as $resim) {
                            echo '<div class="col-4">';
                            echo '<img src="../assets/images/upload/product/' . $resim['RESIM'] . '" alt="Diğer Resim" class="img-thumbnail mb-3">';
                            echo '<button type="button" class="btn btn-danger btn-sm sil-buton" data-kayit-id="' . $resim['KAYIT_ID'] . '">Sil</button>';
                            echo '</div>';
                            $count++;
                            if ($count % 3 == 0) {
                              echo '</div><div class="row">';
                            }
                          }
                          ?>
                        </div>
                        <br>
                        <input class="form-control" type="file" id="urun-diger-resimler" name="urun_diger_resimler[]" multiple="">
                      </div>
                      <button type="button" class="btn btn-primary" id="urun-guncelle-btn">Ürünü Güncelle</button>
                    </div>
                  </div>
                </div>
                
              </div>

            </div>
          </div>
          <!-- / İçerik Sonu -->

          <!-- Başarı Toast Bildirimi -->
          <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="successToast" class="toast bg-primary text-white" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <strong class="me-auto">Bildirim</strong>
                <small>1 saniye önce</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Kapat"></button>
              </div>
              <div class="toast-body">
                İşlem başarılı.
              </div>
            </div>
          </div>

          <!-- Hata Toast Bildirimi -->
          <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="errorToast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <strong class="me-auto">Bildirim</strong>
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
  <script src="assets/js/subimagedelete.js"></script>
  <script src="assets/js/productupdate.js"></script>
</body>
</html>
