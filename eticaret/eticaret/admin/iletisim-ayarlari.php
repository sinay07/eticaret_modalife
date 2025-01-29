<?php
include 'assets/fonk/mysql.php';

// Firma bilgilerini al
try {
  $stmt = $conn->prepare("SELECT * FROM firma_bilgileri");
  $stmt->execute();
  $firmaBilgileri = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // Hata oluşursa burada işleme alınabilir
  echo "Veritabanı hatası: " . $e->getMessage();
}
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
                      <h5 class="mb-0">İletişim Bilgileri</h5>
                    </div>
                    <div class="card-body">
                      <form>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">Firma Adı</label>
                          <input type="text" class="form-control" id="basic-default-fullname" name="firma_adi" placeholder="Firma Adı" value="<?php echo htmlspecialchars($firmaBilgileri['FIRMA_ADI']); ?>">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">Telefon 1</label>
                          <input type="text" class="form-control" id="basic-default-fullname" name="telefon_1" placeholder="Telefon 1" value="<?php echo htmlspecialchars($firmaBilgileri['FIRMA_TELEFON1']); ?>">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">Telefon 2</label>
                          <input type="text" class="form-control" id="basic-default-fullname" name="telefon_2" placeholder="Telefon 2" value="<?php echo htmlspecialchars($firmaBilgileri['FIRMA_TELEFON2']); ?>">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">E-Posta 1</label>
                          <input type="text" class="form-control" id="basic-default-fullname" name="eposta_1" placeholder="E-Posta 1" value="<?php echo htmlspecialchars($firmaBilgileri['FIRMA_EPOSTA1']); ?>">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">E-Posta 2</label>
                          <input type="text" class="form-control" id="basic-default-fullname" name="eposta_2" placeholder="E-Posta 2" value="<?php echo htmlspecialchars($firmaBilgileri['FIRMA_EPOSTA2']); ?>">
                        </div>
                        <div class="mb-3">
                          <label for="exampleFormControlTextarea1" class="form-label">Adres</label>
                          <textarea class="form-control" id="exampleFormControlTextarea1" name="adres" rows="2"><?php echo htmlspecialchars($firmaBilgileri['ADRES']); ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div> 
                  <div class="col-xl">
                    <div class="card mb-4">
                      <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">İletişim Bilgileri</h5>
                      </div>
                      <div class="card-body">
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">Slogan</label>
                          <input type="text" class="form-control" id="basic-default-fullname" name="slogan" placeholder="Slogan" value="<?php echo htmlspecialchars($firmaBilgileri['SLOGAN']); ?>">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">Facebook URL</label>
                          <input type="text" class="form-control" id="basic-default-fullname" name="facebook_url" placeholder="Facebook URL" value="<?php echo htmlspecialchars($firmaBilgileri['FACEBOOK']); ?>">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">Instagram URL</label>
                          <input type="text" class="form-control" id="basic-default-fullname" name="instagram_url" placeholder="Instagram URL" value="<?php echo htmlspecialchars($firmaBilgileri['INSTAGRAM']); ?>">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="basic-default-fullname">X (Twitter) URL</label>
                          <input type="text" class="form-control" id="basic-default-fullname" name="twitter_url" placeholder="X (Twitter) URL" value="<?php echo htmlspecialchars($firmaBilgileri['X']); ?>">
                        </div>
                        <!--
                        <div class="mb-3">
                          <label for="formFile" class="form-label">Logo</label>
                          <input class="form-control" type="file" id="formFile" name="logo">
                        </div>
                      -->
                      <button type="submit" class="btn btn-primary">Güncelle</button>
                    </form>
                  </div>
                </div>
              </div>                
            </div>

          </div>
        </div>
        <!-- / İçerik Bitişi -->

        <?php include 'view/footer.php'; ?>
        <div class="content-backdrop fade"></div>
      </div>
    </div>
  </div>
  <div class="layout-overlay layout-menu-toggle"></div>
</div>

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
      İşlem başarısız oldu. Lütfen tekrar deneyin.
    </div>
  </div>
</div>

<?php include 'view/script.php'; ?>
<script src="assets/js/contactupdate.js"></script>
</body>
</html>
