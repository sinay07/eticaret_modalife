<?php
include 'assets/fonk/mysql.php';

// Veritabanından ana kategorileri çek
try {
    $stmt = $conn->prepare("SELECT * FROM ana_kategori");
    $stmt->execute();
    $anaKategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Hata oluşursa burada işleme alınabilir
    echo "Veritabanı Hatası: " . $e->getMessage();
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
                                            <h5 class="mb-0">Ana Kategori Ekle</h5>
                                        </div>
                                        <div class="card-body">
                                            <form>
                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-default-fullname">Ana Kategori Adı</label>
                                                    <input type="text" class="form-control" id="mainCategoryName" placeholder="Kategori Adı">
                                                </div>
                                                <input type="button" id="addMainCategoryButton" class="btn btn-primary" value="Ekle">
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Alt Kategori Ekle</h5>
                                        </div>
                                        <div class="card-body">
                                            <form>
                                                <div class="mb-3">
                                                    <label for="mainCategorySelect" class="form-label">Ana Kategori Seç</label>
                                                    <select class="form-select" id="mainCategorySelect" aria-label="Default select example">
                                                        <option selected="">Bir Ana Kategori Seçin</option>
                                                        <?php foreach ($anaKategoriler as $anaKategori): ?>
                                                            <option value="<?php echo $anaKategori['ANA_KATEGORI_ADI']; ?>"><?php echo $anaKategori['ANA_KATEGORI_ADI']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="subCategoryName">Alt Kategori Adı</label>
                                                    <input type="text" class="form-control" id="subCategoryName" placeholder="Kategori Adı">
                                                </div>
                                                <input type="button" id="addSubCategoryButton" class="btn btn-primary" value="Ekle">
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <!-- İçerik Sonu -->

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
                                İşlem başarıyla tamamlandı.
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
    <script src="assets/js/addmaincategory.js"></script>
    <script src="assets/js/addsubcategory.js"></script>
</body>
</html>
