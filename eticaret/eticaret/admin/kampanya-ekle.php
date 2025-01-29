<?php
include 'assets/fonk/mysql.php';

// Veritabanından indirimli olmayan ürünleri çek
try {
  $stmt = $conn->prepare("SELECT * FROM urunler WHERE URUN_KODU NOT IN (SELECT URUN_KODU FROM indirimli_urunler)");
  $stmt->execute();
  $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // Hata oluşursa burada işleme alınabilir
  echo "Datenbankfehler: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
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
                <h5 class="card-header">Ürün Listesi</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ÜRÜN ADI</th>
                        <th>ÜRÜN RESMİ</th>
                        <th>FİYAT</th>
                        <th>İŞLEM</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php foreach ($urunler as $urun): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($urun['URUN_ADI']); ?></td>
                          <td><img class="product-image" src="../assets/images/upload/product/<?php echo htmlspecialchars($urun['RESIM']); ?>" style="width: 50px;"></td>
                          <td><span class="badge bg-label-primary me-1"><?php echo htmlspecialchars($urun['FIYAT']); ?> CHF</span></td>
                          <td>
                            <div class="dropdown">
                              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCenter" data-uruntipi="sureli" data-urunadi="<?php echo htmlspecialchars($urun['URUN_ADI']); ?>" data-urunkodu="<?php echo htmlspecialchars($urun['URUN_KODU']); ?>" data-fiyat="<?php echo htmlspecialchars($urun['FIYAT']); ?>"><i class="bx bx-edit-alt me-1"></i> Süreli Kampanya</a>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCenter" data-uruntipi="suresiz" data-urunadi="<?php echo htmlspecialchars($urun['URUN_ADI']); ?>" data-urunkodu="<?php echo htmlspecialchars($urun['URUN_KODU']); ?>" data-fiyat="<?php echo htmlspecialchars($urun['FIYAT']); ?>"><i class="bx bx-edit-alt me-1"></i> Süresiz Kampanya</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!--Tablo-->
            </div>
          </div>
          <!-- / İçerik Bitiş -->

          <!-- Modal -->
          <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalCenterTitle">Kampanya</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="urunKodu" class="form-label">Ürün Kodu</label>
                      <input type="text" id="urunKodu" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                      <label for="urunAdi" class="form-label">Ürün Adı</label>
                      <input type="text" id="urunAdi" class="form-control" readonly>
                    </div>
                  </div>
                  <input type="hidden" id="urunResim">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="mevcutFiyat" class="form-label">Mevcut Fiyat</label>
                      <input type="text" id="mevcutFiyat" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                      <label for="indirimliFiyat" class="form-label">İndirimli Fiyat</label>
                      <input type="text" id="indirimliFiyat" class="form-control">
                    </div>
                  </div>
                  <div id="tarihFields" class="row g-3">
                    <div class="col-md-6">
                      <label for="baslangicTarihi" class="form-label">Başlangıç Tarihi</label>
                      <input type="date" id="baslangicTarihi" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label for="bitisTarihi" class="form-label">Bitiş Tarihi</label>
                      <input type="date" id="bitisTarihi" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
                </div>
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

  <!-- Toast Benachrichtigung -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast bg-primary text-white" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <strong class="me-auto">Benachrichtigung</strong>
        <small>Vor 1 Sekunde</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        Operation erfolgreich.
      </div>
    </div>
  </div>

  <!-- Toast Benachrichtigung -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="errorToast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <strong class="me-auto">Benachrichtigung</strong>
        <small>Vor 1 Sekunde</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        Operation fehlgeschlagen. Bitte versuchen Sie es erneut.
      </div>
    </div>
  </div>
  
  <?php include 'view/script.php'; ?>
  <script src="assets/js/companyadd.js"></script>
</body>
</html>
