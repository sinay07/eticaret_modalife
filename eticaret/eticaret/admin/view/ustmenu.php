<!-- Navigationsleiste -->

<nav
class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
id="layout-navbar"
>
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
  <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
    <i class="bx bx-menu bx-sm"></i>
  </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
<!-- Arama -->
<form id="searchForm" action="siparis-sorgula.php" method="GET" class="navbar-nav align-items-center">
  <div class="nav-item d-flex align-items-center">
    <i class="bx bx-search fs-4 lh-0"></i>
    <input
    type="text"
    name="q"
    id="searchInput"
    class="form-control border-0 shadow-none"
    placeholder="Sipariş ara..."
    aria-label="Sipariş ara..."
    />
  </div>
</form>
<!-- /Arama -->


<ul class="navbar-nav flex-row align-items-center ms-auto">

  <!-- Benutzer -->
  <li class="nav-item navbar-dropdown dropdown-user dropdown">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
      <div class="avatar avatar-online">
        <img src="assets/img/avatars/1.png?v=<?php echo time(); ?>" alt class="w-px-40 h-auto rounded-circle" />
      </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <li>
        <a class="dropdown-item" href="#">
          <div class="d-flex">
            <div class="flex-shrink-0 me-3">
              <div class="avatar avatar-online">
                <img src="assets/img/avatars/1.png?v=<?php echo time(); ?>" alt class="w-px-40 h-auto rounded-circle" />
              </div>
            </div>
            <div class="flex-grow-1">
              <span class="fw-semibold d-block">Yönetim Paneli</span>
              <small class="text-muted">Admin</small>
            </div>
          </div>
        </a>
      </li>
      <li>
        <div class="dropdown-divider"></div>
      </li>
      <li>
        <a class="dropdown-item" href="ayarlar.php">
          <i class="bx bx-cog me-2"></i>
          <span class="align-middle">Hesap Ayarları</span>
        </a>
      </li>
      <li>
        <div class="dropdown-divider"></div>
      </li>
      <li>
        <a class="dropdown-item" href="cikis.php">
          <i class="bx bx-power-off me-2"></i>
          <span class="align-middle">Çıkış Yap</span>
        </a>
      </li>
    </ul>
  </li>
  <!--/ Benutzer -->
</ul>
</div>
</nav>

<!-- / Navigationsleiste -->

<script>
  // Bei Drücken der Eingabetaste Formular senden
  document.getElementById("searchInput").addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
      var inputValue = document.getElementById("searchInput").value.trim(); // Boşlukları temizle
      var currentUrl = window.location.pathname;
      var targetUrl;

      if (currentUrl.endsWith("urun-duzenle-sil.php")) {
        targetUrl = "urun-duzenle-detay.php?q=" + inputValue; // Buraya yönlendirilmesini istediğiniz sayfanın URL'sini yazın
      } else {
        targetUrl = "siparis-sorgula.php?q=" + inputValue;
      }

      window.location.href = targetUrl;
    }
  });
</script>
