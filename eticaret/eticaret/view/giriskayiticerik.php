<!-- Sayfa Bölümü Başlangıcı -->
<div class="page-section section section-padding">
    <div class="container">
        <div class="row mbn-40">

            <div class="col-lg-4 col-12 mb-40">
                <div class="login-register-form-wrap">
                    <h3>Giriş Yap</h3>
                    <!-- Giriş formu -->
                    <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mb-30">
                        <div class="row">
                            <div class="col-12 mb-15">
                                <input type="text" name="email" id="loginEmail" placeholder="E-Posta">
                            </div>
                            <div class="col-12 mb-15">
                                <input type="password" name="password" id="loginPassword" placeholder="Şifre">
                            </div>
                            <div class="col-12">
                                <input type="submit" name="login" value="Giriş Yap">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-2 col-12 mb-40 text-center d-none d-lg-block">
                <span class="login-register-separator"></span>
            </div>

            <div class="col-lg-6 col-12 mb-40 ms-auto">
                <div class="login-register-form-wrap">
                    <h3>Kayıt Ol</h3>
                    <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-15">
                                <input type="text" name="name" id="registerName" placeholder="Adınız">
                            </div>
                            <div class="col-md-6 col-12 mb-15">
                                <input type="email" name="email" id="registerEmail" placeholder="E-Posta">
                            </div>
                            <div class="col-md-6 col-12 mb-15">
                                <input type="password" name="password" id="registerPassword" placeholder="Şifre">
                            </div>
                            <div class="col-md-6 col-12 mb-15">
                                <input type="password" name="confirm_password" id="registerConfirmPassword" placeholder="Şifreyi Onayla">
                            </div>
                            <div class="col-md-6 col-12">
                                <input type="submit" name="register" value="Kayıt Ol">
                            </div>
                        </div>
                    </form>
                    <?php if (!empty($_SESSION['sepet'])): ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <input type="button" onclick="window.location.href='checkoutno.php';" value="Giriş Yapmadan Devam Et" style="background-color: #ff708a; color: black; border: none; padding: 10px 20px; cursor: pointer; font-size: 16px; border-radius: 50px; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#e06076';" onmouseout="this.style.backgroundColor='#ff708a';">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div><!-- Sayfa Bölümü Sonu -->

<!-- Modal -->
<div id="warningModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 class="modal-title">Uyarı</h2>
    <p class="modal-body"><!--dinamik metin--></p>
    <button class="modal-button">Kapat</button>
  </div>
</div>

<script>
    $(document).ready(function(){
    // Modal'ı kapatma fonksiyonu
        $('.close, .modal-button').click(function() {
            $('#warningModal').hide();
        });

    // Giriş işlemi AJAX ile yapılacak
        $('#loginForm').submit(function(event){
        event.preventDefault(); // Formun normal submit işlemini engelle
        var formData = {
            email: $('#loginEmail').val(),
            password: $('#loginPassword').val(),
            login: true
        };

        if (!formData.email || !formData.password) {
            showModal('Lütfen E-Posta ve Şifre alanlarını doldurun');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'assets/fonk/auth.php', // Giriş işlemi yapılacak sayfa
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response){
                if(response.status == 'success'){
                    window.location.href = 'index.php'; // Giriş başarılıysa ana sayfaya yönlendir
                } else {
                    showModal(response.message); // Giriş başarısız ise, hata mesajını modal pencerede göster
                }
            }
        });
    });

    // Kayıt işlemi AJAX ile yapılacak
        $('#registerForm').submit(function(event){
        event.preventDefault(); // Formun normal submit işlemini engelle
        var formData = {
            name: $('#registerName').val(),
            email: $('#registerEmail').val(),
            password: $('#registerPassword').val(),
            confirm_password: $('#registerConfirmPassword').val(),
            register: true
        };

        if (!formData.name || !formData.email || !formData.password || !formData.confirm_password) {
            showModal('Lütfen tüm alanları doldurun');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'assets/fonk/auth.php', // Kayıt işlemi yapılacak sayfa
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response){
                showModal(response.message); // Kayıt işlemi sonucu gelen mesajı modal pencerede göster
            }
        });
    });

    // Modal gösterme fonksiyonu
        function showModal(message) {
            $('.modal-body').text(message);
            $('#warningModal').show();
        }
    });
</script>
