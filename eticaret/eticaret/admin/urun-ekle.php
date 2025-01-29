<?php
// Veritabanı bağlantısını dahil et
include 'assets/fonk/mysql.php';

// Veritabanından ana kategorileri çek
$stmt = $conn->prepare("SELECT * FROM ana_kategori");
$stmt->execute();
$anaKategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Veritabanından bedenleri çek
$stmt = $conn->prepare("SELECT * FROM bedenler");
$stmt->execute();
$bedenler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Veritabanından kumaşları çek
$stmt = $conn->prepare("SELECT * FROM kumas");
$stmt->execute();
$kumaslar = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div class="row">
              <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ürün Ekle</h5>
                  </div>
                  <div class="card-body">
                    <form id="productForm" enctype="multipart/form-data">
                      <!-- Ürün bilgileri -->
                      <div class="mb-3">
                        <label class="form-label" for="urunKodu">Ürün Kodu</label>
                        <input type="text" class="form-control" id="urunKodu" name="urunKodu" placeholder="Ürün Kodu">
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="urunAdi">Ürün Adı</label>
                        <input type="text" class="form-control" id="urunAdi" name="urunAdi" placeholder="Ürün Adı">
                      </div>
                      <div class="mb-3">
                        <label for="urunAciklama" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="urunAciklama" name="urunAciklama" rows="3" placeholder="Açıklama"></textarea>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="urunFiyat">Fiyat</label>
                        <input type="text" class="form-control" id="urunFiyat" name="urunFiyat" placeholder="Fiyat">
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="urunStok">Stok</label>
                        <input type="text" class="form-control" id="urunStok" name="urunStok" placeholder="Stok">
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="urunEtiket">Etiketler</label>
                        <input type="text" class="form-control" id="urunEtiket" name="urunEtiket" placeholder="Her etiketi (,) ile ayırın">
                      </div>
                      <div class="mb-3">
                        <label for="cinsiyetSec" class="form-label">Cinsiyet Seçin</label>
                        <select class="form-select" id="cinsiyetSec" name="cinsiyetSec" aria-label="Cinsiyet Seçin">
                          <option selected="">Cinsiyet seçin</option>
                          <option value="Erkek">Erkek</option>
                          <option value="Kadın">Kadın</option>
                          <option value="Unisex">Unisex</option>
                          <option value="Diger">Diğer</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xl-6">
                  <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="mb-0">Ürün Detayları</h5>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label for="kumasCinsi" class="form-label">Kumaş Türü Seçin</label>
                        <select class="form-select" id="kumasCinsi" name="kumasCinsi" aria-label="Kumaş Türü Seçin" required>
                          <option value="" selected disabled>Kumaş türü seçin</option>
                          <?php foreach ($kumaslar as $kumas): ?>
                            <option value="<?php echo $kumas['KUMAS_CINSI']; ?>"><?php echo $kumas['KUMAS_CINSI']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="anaKategori" class="form-label">Ana Kategori Seçin</label>
                        <select class="form-select" id="anaKategori" name="anaKategori" aria-label="Ana Kategori Seçin" required>
                          <option value="" selected disabled>Ana kategori seçin</option>
                          <?php foreach ($anaKategoriler as $anaKategori): ?>
                            <option value="<?php echo $anaKategori['ANA_KATEGORI_ADI']; ?>"><?php echo $anaKategori['ANA_KATEGORI_ADI']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="altKategori" class="form-label">Alt Kategori Seçin</label>
                        <select class="form-select" id="altKategori" name="altKategori" aria-label="Alt Kategori Seçin">
                          <option selected="">Alt kategori seçin</option>
                          <!-- Dinamik olarak doldurulacak -->
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="beden" class="form-label">Beden Seçin</label>
                        <select class="form-select" id="beden" name="beden[]" aria-label="Beden Seçin" multiple>
                          <?php foreach ($bedenler as $beden): ?>
                            <option value="<?php echo $beden['BEDEN_ADI']; ?>"><?php echo $beden['BEDEN_ADI']; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="kapakResim" class="form-label">Kapak Resmi</label>
                        <input class="form-control" type="file" id="kapakResim" name="kapakResim">
                      </div>
                      <div class="mb-3">
                        <label for="digerResimler" class="form-label">Diğer Ürün Resimleri</label>
                        <input class="form-control" type="file" id="digerResimler" name="digerResimler[]" multiple>
                      </div>
                      <button id="addProductButton" type="submit" class="btn btn-primary">Ürün Ekle</button>
                    </form>
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
              <small>1 Saniye Önce</small>
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
              <small>1 Saniye Önce</small>
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
</div>
<div class="layout-overlay layout-menu-toggle"></div>

<?php include 'view/script.php'; ?>
<script src="assets/js/addproduct.js"></script>
<script src="assets/js/get_subcategories.js"></script>
</body>
</html>
