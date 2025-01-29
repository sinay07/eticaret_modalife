<?php include 'assets/fonk/mysql.php'; ?>
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
          <div class="container-fluid flex-grow-1 container-p-y">
            <div class="col-lg-12 col-md-4 order-1">
              <!-- İçerik Başlangıcı -->
              <!-- Tablo -->
              <div class="card">
                <h5 class="card-header">Bekleyen Siparişler</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ÜRÜN KODU</th>
                        <th>GÖRSEL</th>
                        <th>ÜRÜN ADI</th>
                        <th>FİYAT</th>
                        <th>STOK</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                      // Sayfalama parametreleri
                      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                      $limit = 20;
                      $offset = ($page - 1) * $limit;

                      // Toplam ürün sayısını al
                      $total_sql = "SELECT COUNT(*) FROM urunler";
                      $stmt = $conn->prepare($total_sql);
                      $stmt->execute();
                      $total_results = $stmt->fetchColumn();
                      $total_pages = ceil($total_results / $limit);

                      // Sayfaya ait ürünleri al
                      $sql = "SELECT * FROM urunler LIMIT :limit OFFSET :offset";
                      $stmt = $conn->prepare($sql);
                      $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                      $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                      $stmt->execute();
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $urunkodu = $row['URUN_KODU'];
                        $resim = $row['RESIM'];
                        $urunadi = $row['URUN_ADI'];
                        $fiyat = $row['FIYAT'];
                        $stok = $row['STOK'];
                      ?>
                        <tr>
                          <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php echo $urunkodu; ?></strong></td>
                          <td><img class="product-image" src="../assets/images/upload/product/<?php echo htmlspecialchars($resim); ?>" style="width: 50px;"></td>
                          <td><?php echo $urunadi; ?></td>
                          <td><?php echo $fiyat; ?> ₺</td>
                          <td><?php echo $stok; ?></td>
                          <td>
                            <div class="dropdown">
                              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" href="urun-duzenle-detay.php?q=<?php echo $urunkodu; ?>"><i class="bx bx-edit-alt me-1"></i> İncele</a>
                                <a class="dropdown-item iptal-et" href="javascript:void(0);" data-urunkodu="<?php echo $urunkodu; ?>"><i class="bx bx-trash me-1"></i> İptal Et</a>
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
              <!-- Sayfalama -->
              <nav aria-label="Sayfa navigasyonu" class="mt-3">
                <ul class="pagination justify-content-center">
                  <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>"><i class="tf-icon bx bx-chevrons-left"></i></a>
                  </li>
                  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                      <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                  <?php endfor; ?>
                  <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo min($total_pages, $page + 1); ?>"><i class="tf-icon bx bx-chevrons-right"></i></a>
                  </li>
                </ul>
              </nav>
              <!-- Sayfalama -->
              <!-- İçerik Sonu -->
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
  <script src="assets/js/productdelete.js"></script>
</body>
</html>
