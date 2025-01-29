<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="#" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img width="150" height="80" src="../assets/images/logo.png?v=<?php echo time(); ?>">
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2"></span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item">
      <a href="kontrol.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics">Hızlı Görünüm</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">MENÜ</span>
    </li>
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle" onclick="toggleSubMenu(this)">
        <i class="menu-icon tf-icons bx bx-dock-top"></i>
        <div data-i18n="Account Settings">Kategori İşlemleri</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="kategori-ekle.php" class="menu-link">
            <div data-i18n="Account">Kategori Ekle</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="kategori-duzenle-sil.php" class="menu-link">
            <div data-i18n="Account">Kategori Düzenle, Sil</div>
          </a>
        </li>
      </ul>
    </li>
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
        <div data-i18n="Form Elements">Varyant İşlemleri</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="beden-ekle.php" class="menu-link" >
            <div data-i18n="Basic Inputs">Beden Ekle</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="beden-duzenle-sil.php" class="menu-link" >
            <div data-i18n="Basic Inputs">Beden Düzenle, Sil</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="kumas-ekle.php" class="menu-link" >
            <div data-i18n="Basic Inputs">Kumaş Ekle</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="kumas-duzenle-sil.php" class="menu-link" >
            <div data-i18n="Basic Inputs">Kumas Düzenle, Sil</div>
          </a>
        </li>
      </ul>
    </li>
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
        <div data-i18n="Authentications">Ürün İşlemleri</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="urun-ekle.php" class="menu-link" >
            <div data-i18n="Basic">Ürün Ekle</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="urun-duzenle-sil.php" class="menu-link" >
            <div data-i18n="Basic">Ürün Düzenle, Sil</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Components -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Sipariş İşlemleri</span></li>
    <!-- Cards -->
    <li class="menu-item">
      <a href="bekleyen-islemler.php" class="menu-link">
        <i class="menu-icon tf-icons bx bx-collection"></i>
        <div data-i18n="Basic">Bekleyen Siparişler</div>
      </a>
    </li>

  <!-- Forms & Tables -->
  <li class="menu-header small text-uppercase"><span class="menu-header-text">AYARLAR</span></li>
  <!-- Forms -->
  <li class="menu-item">
    <a href="iletisim-ayarlari.php" class="menu-link">
      <i class="menu-icon tf-icons bx bx-table"></i>
      <div data-i18n="Tables">İletişim Ayarları</div>
    </a>
  </li>
  <li class="menu-item">
    <a href="slider-ayarlari.php" class="menu-link">
      <i class="menu-icon tf-icons bx bx-table"></i>
      <div data-i18n="Tables">Slider Ayarları</div>
    </a>
  </li>
</ul>
</aside>
<!-- / Menu -->

<script>
  function toggleSubMenu(link) {
    // Üst menüyü açık hale getir
    var parentMenu = link.closest('.menu-item');
    if (parentMenu) {
      parentMenu.classList.add('active');
      
      // Üst menüyü aç
      var parentMenuTitle = parentMenu.querySelector('.menu-toggle');
      if (parentMenuTitle) {
        parentMenuTitle.classList.add('open');
      }
    }
    
    // Alt menüyü aç veya kapat
    var subMenu = parentMenu.querySelector('.menu-sub');
    if (subMenu) {
      subMenu.classList.toggle('open');
    }
  }

  // Sayfa yüklendiğinde çalışacak işlev
  window.onload = function() {
    // Mevcut URL'yi al
    var currentUrl = window.location.href;
    
    // Menüdeki her bağlantıyı kontrol et
    var menuLinks = document.querySelectorAll('.menu-item a');
    menuLinks.forEach(function(link) {
      // Bağlantının URL'sini al
      var linkUrl = link.href;
      
      // Eğer mevcut URL, bağlantı URL'siyle eşleşiyorsa
      if (currentUrl === linkUrl) {
        // Bağlantıya "active" sınıfını ekle
        link.classList.add('active');
        
        // Eğer bağlantı bir alt menü öğesiyse, üst menüyü de aktif yap ve aç
        var parentMenu = link.closest('.menu-item');
        if (parentMenu) {
          parentMenu.classList.add('active');
          
          // Üst menüyü aç
          var parentMenuTitle = parentMenu.querySelector('.menu-toggle');
          if (parentMenuTitle) {
            parentMenuTitle.classList.add('open');
          }
          
          // Eğer üst menü bir alt menü ise, ana menüyü de aç
          var mainMenu = parentMenu.closest('.menu-sub');
          if (mainMenu) {
            var mainMenuParent = mainMenu.closest('.menu-item');
            if (mainMenuParent) {
              mainMenuParent.classList.add('active');
              
              // Ana menüyü aç
              var mainMenuTitle = mainMenuParent.querySelector('.menu-toggle');
              if (mainMenuTitle) {
                mainMenuTitle.classList.add('open');
              }
            }
          }
        }
      }
    });
  };
</script>

