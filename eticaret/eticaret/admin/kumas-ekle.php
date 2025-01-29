<?php
include 'assets/fonk/mysql.php';

// Veritabanından kumaşları çek
try {
    $stmt = $conn->prepare("SELECT * FROM kumas");
    $stmt->execute();
    $kumaslar = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

                            <div class="row">
                                <div class="col-xl">
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Kumaş Ekle</h5>
                                        </div>
                                        <div class="card-body">
                                            <form>
                                                <div class="mb-3">
                                                    <label for="kumasSelect" class="form-label">Mevcut Kumaşlar</label>
                                                    <select class="form-select" id="kumasSelect" aria-label="Default select example">
                                                        <?php foreach ($kumaslar as $kumas): ?>
                                                            <option value="<?php echo $kumas['KUMAS_CINSI']; ?>"><?php echo $kumas['KUMAS_CINSI']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="kumasAdi">Kumaş Adı</label>
                                                    <input type="text" class="form-control" id="kumasAdi" placeholder="Kumaş Adı">
                                                </div>
                                                <input type="button" id="addKumasButton" class="btn btn-primary" value="Ekle">
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <!-- İçerik Sonu -->

                    <!-- Başarı Bildirimi -->
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

                    <!-- Hata Bildirimi -->
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
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <?php include 'view/script.php'; ?>
    <script src="assets/js/addfabric.js"></script>
</body>
</html>
