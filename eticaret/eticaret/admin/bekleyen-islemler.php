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

              <!-- Tablo -->
              <div class="card">
                <h5 class="card-header">Bekleyen Siparişler</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>SİPARİŞ ID</th>
                        <th>E-POSTA</th>
                        <th>GENEL TUTAR</th>
                        <th>SİPARİŞ TARİHİ</th>
                        <th>İŞLEM</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                      include 'assets/fonk/mysql.php';
                      // Veritabanından bekleyen siparişleri al
                      $sql = "SELECT 
                      SIPARIS_NO, 
                      EPOSTA, 
                      SUM(ADET * BIRIM_FIYAT) AS TOPLAM_TUTAR, 
                      MAX(SIPARIS_TARIH) AS SIPARIS_TARIHI 
                      FROM 
                      siparisler 
                      WHERE 
                      DURUM = 'BEKLİYOR' 
                      GROUP BY 
                      SIPARIS_NO";
                      $stmt = $conn->prepare($sql);
                      $stmt->execute();
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $siparisNo = $row['SIPARIS_NO'];
                        $eposta = $row['EPOSTA'];
                        $toplamTutar = $row['TOPLAM_TUTAR'];
                        $siparisTarihi = $row['SIPARIS_TARIHI'];
                        ?>
                        <tr>
                          <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php echo $siparisNo; ?></strong></td>
                          <td><?php echo $eposta; ?></td>
                          <td><?php echo $toplamTutar; ?> CHF</td>
                          <td><span class="badge bg-label-primary me-1"><?php echo $siparisTarihi; ?></span></td>
                          <td>
                            <div class="dropdown">
                              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" href="siparis-sorgula.php?q=<?php echo $siparisNo; ?>"><i class="bx bx-edit-alt me-1"></i> İncele</a>
                                <a class="dropdown-item iptal-et" href="javascript:void(0);" data-siparisno="<?php echo $siparisNo; ?>"><i class="bx bx-trash me-1"></i> İptal Et</a>
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
              <!-- Tablo -->

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
  <script src="assets/js/bestellungendelete.js"></script>
</body>
</html>
