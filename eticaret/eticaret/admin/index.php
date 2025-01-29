<!DOCTYPE html>
<html lang="tr" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
  <title>Modalife - Yönetici Paneli</title>
  <meta name="description" content="" />
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />
  <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="assets/css/demo.css" />
  <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
  <script src="assets/vendor/js/helpers.js"></script>
  <script src="assets/js/config.js"></script>
</head>

<body>
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <div class="card">
          <div class="card-body">
            <div class="app-brand justify-content-center">
              <a href="index.php" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <img width="150" height="80" src="../assets/images/logo.png?v=<?php echo time(); ?>">
                </span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Yönetici Paneli 👋</h4>
            <p class="mb-4">Lütfen hesabınıza giriş yapın.</p>

            <form id="formAuthentication" class="mb-3" action="#" method="POST">
              <div class="mb-3">
                <label for="email" class="form-label">Kullanıcı Adı</label>
                <input
                  type="text" autocomplete="off"
                  class="form-control"
                  id="email"
                  name="username"
                  placeholder="Kullanıcı adınızı girin"
                  autofocus
                />
              </div>
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Parola</label>
                </div>
                <div class="input-group input-group-merge">
                  <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password"
                  />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Giriş Yap</button>
              </div>
            </form>

            <div id="loginError" style="display:none;" class="alert alert-danger" role="alert">
              Geçersiz kullanıcı adı veya parola.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>
  <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="assets/vendor/js/menu.js"></script>
  <script src="assets/js/main.js"></script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="assets/js/login.js"></script>
</body>
</html>
