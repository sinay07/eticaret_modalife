<?php
include 'assets/fonk/mysql.php';

// Slider bilgilerini al
try {
  $stmt = $conn->prepare("SELECT * FROM slider");
  $stmt->execute();
  $sliders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
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

          <!-- İçerik Başlangıcı -->
          <div class="container-fluid flex-grow-1 container-p-y">
            <div class="col-lg-12 col-md-4 order-1">

              <div class="row">
                <div class="col-xl">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Slider Ayarları</h5>
                    </div>
                    <div class="card-body">
                      <form id="sliderForm" enctype="multipart/form-data">
                        <input type="hidden" name="slider_id" id="sliderId" value="">
                        <div class="mb-3">
                          <label for="formFile" class="form-label">Slider Görseli (1900x744px)</label>
                          <input class="form-control" type="file" id="formFile" name="slider_resmi">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="slider_yazi">Slider Metni</label>
                          <input type="text" class="form-control" id="slider_yazi" name="slider_yazi" placeholder="Slider Metni">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="buton_yazi">Buton Metni</label>
                          <input type="text" class="form-control" id="buton_yazi" name="buton_yazi" placeholder="Buton Metni">
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="buton_url">Buton URL</label>
                          <input type="text" class="form-control" id="buton_url" name="buton_url" placeholder="Buton URL">
                        </div>
                        <button type="submit" class="btn btn-primary">Ekle</button>
                      </form>
                    </div>
                  </div>
                </div> 

                <div class="col-xl">
                  <div class="card">
                    <h5 class="card-header">Slider Listesi</h5>
                    <div class="table-responsive text-nowrap">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>SLIDER GÖRSELİ</th>
                            <th>SLIDER METNİ</th>
                            <th>BUTON METNİ</th>
                            <th>BUTON URL</th>
                            <th>İŞLEMLER</th>
                          </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                          <?php foreach ($sliders as $slider): ?>
                            <tr>
                              <td><img class="product-image" src="../assets/images/upload/slider/<?php echo htmlspecialchars($slider['RESIM']); ?>" style="width: 50px;"></td>
                              <td>
                                <span class="d-inline-block text-truncate" style="max-width: 150px;" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<?php echo htmlspecialchars($slider['BASLIK']); ?>">
                                  <?php echo htmlspecialchars(strlen($slider['BASLIK']) > 30 ? substr($slider['BASLIK'], 0, 30) . '...' : $slider['BASLIK']); ?>
                                </span>
                              </td>
                              <td><?php echo htmlspecialchars($slider['BUTON_METNI']); ?></td>
                              <td>
                                <span class="d-inline-block text-truncate" style="max-width: 100px;" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<?php echo htmlspecialchars($slider['BUTON_LINK']); ?>">
                                  <?php echo htmlspecialchars(strlen($slider['BUTON_LINK']) > 10 ? substr($slider['BUTON_LINK'], 0, 10) . '...' : $slider['BUTON_LINK']); ?>
                                </span>
                              </td>
                              <td>
                                <div class="dropdown">
                                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow "data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                  </button>
                                  <div class="dropdown-menu">
                                    <a class="dropdown-item delete-slider" data-slider-id="<?php echo $slider['KAYIT_ID']; ?>" href="#"><i class="bx bx-trash me-1"></i> Sil</a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCenter" data-slider-id="<?php echo $slider['KAYIT_ID']; ?>" data-slider-resim="<?php echo htmlspecialchars($slider['RESIM']); ?>" data-slider-yazi="<?php echo htmlspecialchars($slider['BASLIK']); ?>" data-buton-yazi="<?php echo htmlspecialchars($slider['BUTON_METNI']); ?>" data-buton-link="<?php echo htmlspecialchars($slider['BUTON_LINK']); ?>"><i class="bx bx-edit-alt me-1"></i> Düzenle</a>
                                  </div>
                                </div>
                              </td>
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
        </div>
        <!-- İçerik Sonu -->

        <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Slider Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
              </div>
              <div class="modal-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="sliderResim" class="form-label">Slider Görseli</label>
                    <input type="file" id="sliderResim" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label for="sliderYazi" class="form-label">Slider Metni</label>
                    <input type="text" id="sliderYazi" class="form-control">
                  </div>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="butonYazi" class="form-label">Buton Metni</label>
                    <input type="text" id="butonYazi" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label for="butonUrl" class="form-label">Buton URL</label>
                    <input type="text" id="butonUrl" class="form-control">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Güncelle</button>
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

<?php include 'view/script.php'; ?>
<script src="assets/js/addslider.js"></script>
<script src="assets/js/deleteslider.js"></script>
<script src="assets/js/editslider.js"></script>
</body>
</html>
