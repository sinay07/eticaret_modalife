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

              <div class="card mb-4">
                <h5 class="card-header">Hesap Ayarları</h5>
                <!-- Hesap -->
                <hr class="my-0">
                <div class="card-body">
                  <form id="formAccountSettings" method="POST" onsubmit="return false">
                    <div class="row">
                      <div class="mb-3 col-md-6">
                        <label for="username" class="form-label">Kullanıcı Adı</label>
                        <input class="form-control" type="text" id="username" name="username" autofocus="">
                      </div>
                      <div class="mb-3 col-md-6">
                        <label for="oldPassword" class="form-label">Eski Şifre</label>
                        <input class="form-control" type="password" name="oldPassword" id="oldPassword">
                      </div>
                      <div class="mb-3 col-md-6">
                        <label for="newPassword" class="form-label">Yeni Şifre</label>
                        <input class="form-control" type="password" name="newPassword" id="newPassword">
                      </div>
                      <div class="mb-3 col-md-6">
                        <label for="confirmPassword" class="form-label">Yeni Şifreyi Onayla</label>
                        <input class="form-control" type="password" name="confirmPassword" id="confirmPassword">
                      </div>
                    </div>
                    <div class="mt-2">
                      <button type="submit" class="btn btn-primary me-2" id="updateButton">Güncelle</button>
                    </div>
                  </form>
                </div>
                <!-- /Hesap -->
              </div>

            </div>
          </div>
          <!-- / İçerik Sonu -->

          <?php include 'view/footer.php'; ?>
          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>

  <!-- Toast-Benzerliği -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast bg-primary text-white" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <strong class="me-auto">Bildirim</strong>
        <small>1 saniye önce</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Kapat"></button>
      </div>
      <div class="toast-body">
        Güncelleme işlemi başarıyla tamamlandı.
      </div>
    </div>
    <div id="errorToast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <strong class="me-auto">Bildirim</strong>
        <small>1 saniye önce</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Kapat"></button>
      </div>
      <div class="toast-body">
        Güncelleme işlemi başarısız oldu. Lütfen tekrar deneyin.
      </div>
    </div>
  </div>

  <?php include 'view/script.php'; ?>
  <script src="assets/js/accountsettings.js"></script>
</body>
</html>
