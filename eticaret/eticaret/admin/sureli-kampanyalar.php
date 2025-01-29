<?php  
include 'assets/fonk/mysql.php';

try {
  $stmt = $conn->prepare("SELECT * FROM indirimli_urunler WHERE BASLANGIC_TARIH IS NOT NULL AND BITIS_TARIH IS NOT NULL");
  $stmt->execute();
  $indirimli_urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // Hata oluşursa burada işleme alınabilir
  echo "Veritabanı hatası: " . $e->getMessage();
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

          <!-- İçerik Başlangıç -->
          <div class="container-fluid flex-grow-1 container-p-y">
            <div class="col-lg-12 col-md-4 order-1">
              <!--Tablo-->
              <div class="card">
                <h5 class="card-header">İndirimli Ürün Listesi</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ÜRÜN KODU</th>
                        <th>ÜRÜN ADI</th>
                        <th>ÜRÜN RESMİ</th>
                        <th>BAŞLANGIÇ TARİHİ</th>
                        <th>BİTİŞ TARİHİ</th>
                        <th>NORMAL FİYAT</th>
                        <th>KAMPANYALI FİYAT</th>
                        <th>İŞLEM</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                      foreach ($indirimli_urunler as $urun) {
                        ?>
                        <tr>
                          <td><?php echo htmlspecialchars($urun['URUN_KODU']); ?></td>
                          <td><?php echo htmlspecialchars($urun['URUN_ADI']); ?></td>
                          <td><img src="../assets/images/upload/product/<?php echo htmlspecialchars($urun['URUN_RESMI']); ?>" style="width: 50px;"></td>
                          <td><?php echo htmlspecialchars($urun['BASLANGIC_TARIH']); ?></td>
                          <td><?php echo htmlspecialchars($urun['BITIS_TARIH']); ?></td>
                          <td><span class="badge bg-label-primary me-1"><?php echo htmlspecialchars($urun['URUN_FIYATI']); ?> ₺</span></td>
                          <td><span class="badge bg-label-danger me-1"><?php echo htmlspecialchars($urun['INDIRIMLI_FIYAT']); ?> ₺</span></td>
                          <td>
                            <div class="dropdown">
                              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu">
                                <a class="dropdown-item remove-btn" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Kaldır</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <?php
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!--Tablo-->
            </div>
          </div>
          <!-- / İçerik Bitiş -->

          <!-- Toast Bildirimi -->
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

          <!-- Toast Bildirimi -->
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

          <?php include 'view/footer.php'; ?>
          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <?php include 'view/script.php'; ?>
  <script src="assets/js/companydelete.js"></script>
</body>
</html>
